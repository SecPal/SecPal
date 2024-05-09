<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Journal;
use App\Models\Participant;
use App\Models\Trespass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AddIncident extends Component
{
    public $show = false;

    public $location_data;

    public $categories;

    public Category $category;

    public $categoryId = '';

    public bool $rescue_involved = false;

    public bool $fire_involved = false;

    public bool $police_involved = false;

    public string $incidentArea = '';

    public int $peopleInvolved = 1;

    public string $incidentDescription = '';

    public string $measures = '';

    public $incidentDate;

    public $incidentTime;

    public int $reportedById;

    public array $participants;

    public function mount(): void
    {
        abort_unless(Gate::allows('work', Auth::user()), 403);
        $this->reportedById = Auth::user()->id;
        $this->setDateTimeNow();
        $this->categories = $this->getCategories();
        $this->addNewParticipantRow();
    }

    private function setDateTimeNow(): void
    {
        $this->incidentDate = Carbon::now()->format('Y-m-d');
        $this->incidentTime = Carbon::now()->format('H:i');
    }

    public function getCategories(): Collection
    {
        return Category::where(function ($query) {
            $query->where('customer_id', $this->location_data->customer->id)
                ->orWhere('location_id', $this->location_data->id);
        })->orWhere(function ($query) {
            $query->whereNull('customer_id')
                ->whereNull('location_id');
        })->get();
    }

    public function render()
    {
        return view('livewire.add-incident');
    }

    public function updated($field, $newValue): void
    {
        $splitField = explode('.', $field);
        if (count($splitField) === 3) {
            $participantIndex = $splitField[1];
            $this->handleParticipantInformationUpdate($splitField, $participantIndex, $newValue);
        }
    }

    private function handleParticipantInformationUpdate($splitField, $participantIndex, $newValue): void
    {
        switch ($splitField[2]) {
            case 'date_of_birth':
                $this->processDateOfBirth($participantIndex, $newValue);
                break;
            case 'ban_until':
                $this->processBanUntil($participantIndex, $newValue);
                break;
            case 'lastname':
                $this->processLastName($participantIndex, $newValue);
        }
    }

    private function processDateOfBirth($participantIndex, $newValue): void
    {
        $dob = $this->validateAndCorrectDateOfBirth($newValue);
        $this->processDOBNullOrEmpty($participantIndex, $dob);

        if ($dob !== null) {
            $this->handleDOBProcessing($participantIndex, $dob);
        }
    }

    private function processDOBNullOrEmpty($participantIndex, $dob): void
    {
        if ($dob === null) {
            $this->participants[$participantIndex]['date_of_birth'] = '';
            $this->participants[$participantIndex]['ban_until'] = '';
        }
    }

    private function handleDOBProcessing($participantIndex, $dob): void
    {
        $dob = $this->setNewDOB($participantIndex, $dob);

        $age = $dob->diffInYears($this->incidentDate);

        if ($this->isParticipantIdentitySet($participantIndex)) {
            $this->updateParticipantInfo($participantIndex);

            if ($this->shouldRecalculateBanDate($participantIndex)) {
                $this->recalculateBanDate($participantIndex, $dob, $age);
            }
        }

        if ($this->canAddNewParticipantRow()) {
            $this->addNewParticipantRow();
        }
    }

    private function setNewDOB($participantIndex, $dob): Carbon
    {
        $this->participants[$participantIndex]['date_of_birth'] = $dob->format('Y-m-d');

        return Carbon::parse($this->participants[$participantIndex]['date_of_birth']);
    }

    private function isParticipantIdentitySet($participantIndex): bool
    {
        return $this->participants[$participantIndex]['lastname'] && $this->participants[$participantIndex]['firstname'];
    }

    private function updateParticipantInfo($participantIndex): void
    {
        $lastname = $this->participants[$participantIndex]['lastname'];
        $firstname = $this->participants[$participantIndex]['firstname'];
        $date_of_birth = $this->participants[$participantIndex]['date_of_birth'];

        $this->participants[$participantIndex] = $this->findOrCreateParticipant($lastname,
            $firstname, $date_of_birth);
    }

    private function findOrCreateParticipant($lastname, $firstname, $date_of_birth): array
    {
        return Participant::where(function ($query) {
            $query->where('customer_id', $this->location_data->customer_id)
                ->orWhere('location_id', $this->location_data->id);
        })
            ->where('lastname', $lastname)
            ->where('firstname', $firstname)
            ->where('date_of_birth', $date_of_birth)
            ->where('ban_until', '>=', $this->incidentDate)
            ->firstOrNew(
                [
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'date_of_birth' => $date_of_birth,
                ],
                [
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'date_of_birth' => $date_of_birth,
                    'street' => '',
                    'number' => '',
                    'zipcode' => '',
                    'city' => '',
                ]
            )->load('trespasses')
            ->toArray();
    }

    private function shouldRecalculateBanDate($participantIndex): bool
    {
        return empty($this->participants[$participantIndex]['ban_until']) ||
            $this->participants[$participantIndex]['ban_until'] < Carbon::parse($this->incidentDate)->addYears(2);
    }

    private function recalculateBanDate($participantIndex, $dob, $age): void
    {
        $this->participants[$participantIndex]['ban_until'] = $this->calculateBanUntil($dob, $age);
    }

    private function validateAndCorrectDateOfBirth($dobInput): ?Carbon
    {
        $dob = Carbon::parse($dobInput);

        if ($dob->year < 100) {
            $currentYearLastTwoDigits = Carbon::parse($this->incidentDate)->year % 100;

            if ($dob->year <= $currentYearLastTwoDigits) {
                $dob->year += 2000;
            } else {
                $dob->year += 1900;
            }
        }

        $centuryAgo = Carbon::parse($this->incidentDate)->subYears(100);
        if ($dob->isFuture() || $dob->lte($centuryAgo)) {
            return null;
        }

        return $dob;
    }

    private function processBanUntil($participantIndex, $newValue): void
    {
        // prevent error if empty
        if (! $newValue) {
            $newValue = $this->incidentDate;
        }

        // Parse the date
        $date = Carbon::createFromFormat('Y-m-d', $newValue);

        // Fix 2 digit year to 4
        if ($date->year < 100) {
            $date->addYears(2000);
        }

        // Now the checks, and if the date is less than 6 months in the future, recalculate it
        if ($date->lt($this->incidentDate) || $date->lt(Carbon::parse($this->incidentDate)->addMonths(6))) {
            $dob = Carbon::parse($this->participants[$participantIndex]['date_of_birth']);
            $age = $dob->diffInYears($this->incidentDate);
            $date = Carbon::parse($this->calculateBanUntil($dob, $age));
        }

        // Set the new banUntil
        $this->participants[$participantIndex]['ban_until'] = $date->format('Y-m-d');
    }

    private function processLastName($participantIndex, $newValue): void
    {
        if ($newValue === '' && count($this->participants) > 1) {
            $this->removeParticipantRow($participantIndex);
        }
    }

    private function calculateBanUntil($dob, $age): string
    {
        if ($age < 16) {
            return $dob->addYears(18)->subDay()->format('Y-m-d');
        } else {
            return Carbon::parse($this->incidentDate)->addYears(2)->subDay()->format('Y-m-d');
        }
    }

    private function canAddNewParticipantRow(): bool
    {
        $lastKey = array_key_last($this->participants);

        return count($this->participants) < $this->peopleInvolved &&
            $this->participants[$lastKey]['lastname'] !== '' &&
            $this->participants[$lastKey]['firstname'] !== '' &&
            $this->participants[$lastKey]['date_of_birth'] !== '';
    }

    private function addNewParticipantRow(): void
    {
        $this->participants[] = [
            'lastname' => '', 'firstname' => '', 'date_of_birth' => '', 'ban_until' => '',
        ];
    }

    private function removeParticipantRow($participantIndex): void
    {
        unset($this->participants[$participantIndex]);
        $this->participants = array_values($this->participants);

        // add a last Row again, if now missing
        if ($this->canAddNewParticipantRow()) {
            $this->addNewParticipantRow();
        }
    }

    public function save(): void
    {
        abort_unless(Auth::user()->can('create-journal', $this->location_data), 403);
        $dateTime = Carbon::parse($this->incidentDate.' '.$this->incidentTime.':00')->toDateTimeString();
        $journal = Journal::create([
            'location_id' => $this->location_data->id,
            'category_id' => $this->pull('categoryId'),
            'description' => $this->pull('incidentDescription'),
            'measures' => $this->pull('measures'),
            'reported_by' => $this->reportedById,
            'entry_by' => Auth::user()->id,
            'area' => $this->pull('incidentArea'),
            'involved' => $this->pull('peopleInvolved'),
            'rescue_involved' => $this->pull('rescue_involved'),
            'fire_involved' => $this->pull('fire_involved'),
            'police_involved' => $this->pull('police_involved'),
            'incident_time' => $dateTime,
        ]);

        foreach ($this->participants as $participant) {
            if ($participant['lastname'] == '') {
                continue;
            }

            if (empty($participant['journal_id'])) {
                $participant['ban_since'] = $this->incidentDate;
                $participant['journal_id'] = $journal->id;
                $participant['location_id'] = $this->location_data->id;

                // TODO check if a customer wide ban is possible / wanted
                $participant['customer_id'] = $this->location_data->customer->id;

            }

            $part = Participant::where('lastname', $participant['lastname'])
                ->where('firstname', $participant['firstname'])
                ->where('date_of_birth', $participant['date_of_birth'])
                ->where('ban_since', $participant['ban_since'])
                ->where(function ($query) use ($participant) {
                    $query->where('customer_id', $participant['customer_id'])
                        ->orWhere('location_id', $participant['location_id']);
                })
                ->first();

            if ($part === null) {
                // Record not found via above conditions, so create a new record.
                $part = Participant::create(
                    [
                        'lastname' => $participant['lastname'],
                        'firstname' => $participant['firstname'],
                        'date_of_birth' => $participant['date_of_birth'],
                        'street' => $participant['street'],
                        'number' => $participant['number'],
                        'zipcode' => $participant['zipcode'],
                        'city' => $participant['city'],
                        'ban_since' => $participant['ban_since'],
                        'ban_until' => $participant['ban_until'],
                        'journal_id' => $participant['journal_id'],
                        'customer_id' => $participant['customer_id'],
                        'location_id' => $participant['location_id'],
                    ]
                );
            } else {
                // Record found, update it
                $part->update($participant);

                // create a trespass
                Trespass::create([
                    'journal_id' => $journal->id,
                    'participant_id' => $participant['id'],
                ]);
            }
        }

        ray($this->participants);

        $this->participants = [];
        $this->addNewParticipantRow();

        $this->reset('category');
        $this->reset('show');
        $this->dispatch('added');
    }

    public function updatedCategoryId(): void
    {
        $this->category = Category::find($this->categoryId);
        $this->peopleInvolved = $this->category->usually_involved;
    }
}
