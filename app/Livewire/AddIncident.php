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
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddIncident extends Component
{
    public $show = false;

    public $location_data;

    public $categories;

    public Category $category;

    #[Validate('required', message: 'Please select a category.')]
    #[Validate('numeric', message: 'Please select a category.')]
    public $categoryId = '';

    #[Validate('boolean')]
    public bool $rescue_involved = false;

    #[Validate('boolean')]
    public bool $fire_involved = false;

    #[Validate('boolean')]
    public bool $police_involved = false;

    #[Validate('required', message: 'It\'s necessary to specify an area.')]
    #[Validate('string', message: 'Don\'t mess around!')]
    public string $incidentArea = '';

    #[Validate('required', message: 'The number of persons involved is required.')]
    #[Validate('numeric', message: 'The number must be given in figures.')]
    public $peopleInvolved = 1;

    #[Validate('required', message: 'A meaningful incident description is required.')]
    #[Validate('min:20', message: 'A meaningful incident description should be longer than 20 characters.')]
    public string $incidentDescription = '';

    #[Validate('required', message: 'A meaningful description of the measures taken is required.')]
    #[Validate('min:20', message: 'A meaningful description of the measures taken should be longer than 20 characters.')]
    public string $measures = '';

    #[Validate('required', message: 'The correct date of the incident is required.')]
    #[Validate('before_or_equal:today', message: 'Share your time machine or correct the date. ;-)')]
    #[Validate('date', message: 'Don\'t mess around!')]
    public $incidentDate;

    #[Validate('required', message: 'The correct time of the incident is required.')]
    #[Validate('date_format:H:i', message: 'Don\'t mess around!')]
    public $incidentTime;

    #[Validate('required', message: 'Don\'t mess around!')]
    #[Validate('numeric', message: 'Don\'t mess around!')]
    public int $reportedById;

    public $selectSearchData;

    public $categorySearch;

    public array $participants;

    public function mount(): void
    {
        abort_unless(Gate::allows('work', Auth::user()), 403);
        $this->reportedById = Auth::user()->id;
        $this->setDateTimeNow();
        $this->categories = $this->getCategories();
        $this->categorySearch = $this->getCategorySearchData();
        $this->selectSearchData = $this->getSelectSearchData();
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

        $this->validateOnly($field);
    }

    public function updatedCategoryId(): void
    {
        if (is_numeric($this->categoryId)) {
            $this->category = Category::find($this->categoryId);
            $this->peopleInvolved = $this->category->usually_involved;
        } else {
            $this->reset('category');
            $this->reset('rescue_involved');
            $this->reset('fire_involved');
            $this->reset('police_involved');
        }
    }

    public function updatedParticipants($propertyName): void
    {
        $this->validateOnly($propertyName, [
            'participants.*.lastname' => 'nullable|string|max:255',
            'participants.*.firstname' => 'nullable|string|max:255',
            'participants.*.date_of_birth' => 'nullable|date|before:today',
            'participants.*.ban_until' => 'nullable|date|after:today',
            'participants.*.street' => 'nullable|string',
            'participants.*.number' => 'nullable|string',
            'participants.*.zipcode' => 'nullable|numeric',
            'participants.*.city' => 'nullable|string',
        ]);
    }

    public function getSelectSearchData(): array
    {
        return collect($this->location_data->users)->map(function ($user) {
            return [
                'id' => $user->id,
                'label' => "{$user->lastname}, {$user->firstname}".($user->company->subcontractor ? " ({$user->company->shortname})" : ''),
                'disabled' => false, // Assuming all users are not disabled by default.
            ];
        })->all();
    }

    public function getCategorySearchData(): array
    {
        return collect($this->categories)->map(function ($category) {
            return [
                'id' => $category->id,
                'label' => $category->name,
                'disabled' => false, // Assuming all users are not disabled by default.
            ];
        })->all();
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
        if (! $newValue) {
            $newValue = $this->incidentDate;
        }

        // Parse the date
        $date = Carbon::createFromFormat('Y-m-d', $newValue);

        // Fix 2 digit year to 4
        if ($date->year < 100) {
            $date->addYears(2000);
        }

        // Fetch the participant's existing ban_until date if it exists and is later than the new date
        if (isset($this->participants[$participantIndex]['id'])) {
            $existingParticipant = Participant::find($this->participants[$participantIndex]['id']);
            if (Carbon::parse($existingParticipant->ban_until)->greaterThan($date)) {
                $date = Carbon::parse($existingParticipant->ban_until);
            }
        }

        // If the date is less than 6 months in the future, recalculate it
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
        if ($newValue === '') {
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
        if (count($this->participants) === 0) {
            return true;
        }

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
        $this->validate();
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
            if ($participant['lastname'] == '' || $this->peopleInvolved == 0) {
                continue;
            }

            if (empty($participant['journal_id'])) {
                $participant['ban_since'] = $this->incidentDate;
                $participant['journal_id'] = $journal->id;
                $participant['location_id'] = $this->location_data->id;

                if ($this->location_data->customer->customer_wide_ban) {
                    $participant['customer_id'] = $this->location_data->customer->id;
                } else {
                    $participant['customer_id'] = null;
                }
            }

            $part = Participant::where('lastname', $participant['lastname'])
                ->where('firstname', $participant['firstname'])
                ->where('date_of_birth', $participant['date_of_birth'])
                ->where('ban_since', $participant['ban_since'])
                ->when($participant['customer_id'] !== null, function ($query) use ($participant) {
                    return $query->where('customer_id', $participant['customer_id']);
                })
                ->when($participant['location_id'] !== null, function ($query) use ($participant) {
                    return $query->where('location_id', $participant['location_id']);
                })
                ->first();

            if ($part === null) {
                // Record not found via above conditions, so create a new record.
                Participant::create(
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
                    'participant_id' => $part->id,
                ]);
            }
        }

        $this->participants = [];
        $this->addNewParticipantRow();

        $this->reset('category');
        $this->reset('show');
        $this->reportedById = Auth::user()->id;
        $this->dispatch('added');
        $this->dispatch('reset-select-search');
    }
}
