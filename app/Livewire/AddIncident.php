<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Journal;
use App\Models\Participant;
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
        //        $this->participants[0]['lastname'] = '';
        //        $this->participants[0]['firstname'] = '';
        //        $this->participants[0]['date_of_birth'] = '';
        //        $this->participants[0]['ban_until'] = '';
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
            if ($splitField[2] === 'date_of_birth') {
                $this->processDateOfBirth($participantIndex, $newValue);
            }
            if ($splitField[2] === 'ban_until') {
                $this->processBanUntil($participantIndex, $newValue);
            }
            if ($splitField[2] === 'lastname') {
                $this->processLastName($participantIndex, $newValue);
            }
        }
    }

    private function processDateOfBirth($participantIndex, $newValue): void
    {
        $dob = $this->validateAndCorrectDateOfBirth($newValue);

        if ($dob === null) {
            $this->participants[$participantIndex]['date_of_birth'] = '';
            $this->participants[$participantIndex]['ban_until'] = '';
        } else {
            $age = $dob->diffInYears(now());

            // set the new dateOfBirth
            $this->participants[$participantIndex]['date_of_birth'] = $dob->format('Y-m-d');

            if ($this->participants[$participantIndex]['lastname'] && $this->participants[$participantIndex]['firstname']) {
                $lastname = $this->participants[$participantIndex]['lastname'];
                $firstname = $this->participants[$participantIndex]['firstname'];
                $date_of_birth = $this->participants[$participantIndex]['date_of_birth'];
                $this->participants[$participantIndex] = Participant::firstOrNew([
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'date_of_birth' => $date_of_birth,
                ])->toArray();

                if (empty($this->participants[$participantIndex]['ban_until']) ||
                    $this->participants[$participantIndex]['ban_until'] < now()->addYears(2)
                ) {
                    // recalculate the banUntil date
                    $this->participants[$participantIndex]['ban_until'] = $this->calculateBanUntil($dob, $age);
                }
            }

            if ($this->canAddNewParticipantRow($participantIndex)) {
                $this->addNewParticipantRow();
            }
        }
    }

    private function validateAndCorrectDateOfBirth($dobInput): ?Carbon
    {
        $dob = Carbon::parse($dobInput);

        if ($dob->year < 100) {
            $now = Carbon::now();
            $currentYearLastTwoDigits = $now->year % 100;

            if ($dob->year <= $currentYearLastTwoDigits) {
                $dob->year += 2000;
            } else {
                $dob->year += 1900;
            }
        }

        $centuryAgo = Carbon::now()->subYears(100);
        if ($dob->isFuture() || $dob->lte($centuryAgo)) {
            return null;
        }

        return $dob;
    }

    private function processBanUntil($participantIndex, $newValue): void
    {
        // prevent error if empty
        if (! $newValue) {
            $newValue = now()->format('Y-m-d');
        }

        // Parse the date
        $date = Carbon::createFromFormat('Y-m-d', $newValue);

        // Fix 2 digit year to 4
        if ($date->year < 100) {
            $date->addYears(2000);
        }

        // Now the checks, and if the date is less than 6 months in the future, recalculate it
        if ($date->lt(Carbon::now()) || $date->lt(Carbon::now()->addMonths(6))) {
            $dob = Carbon::parse($this->participants[$participantIndex]['date_of_birth']);
            $age = $dob->diffInYears(now());
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
            return now()->addYears(2)->subDay()->format('Y-m-d');
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
        Journal::create([
            'location_id' => $this->location_data->id,
            'category_id' => $this->pull('categoryId'),
            'description' => $this->pull('incidentDescription'),
            'measures' => $this->pull('measures'),
            'reported_by' => $this->pull('reportedById'),
            'entry_by' => Auth::user()->id,
            'area' => $this->pull('incidentArea'),
            'involved' => $this->pull('peopleInvolved'),
            'rescue_involved' => $this->pull('rescue_involved'),
            'fire_involved' => $this->pull('fire_involved'),
            'police_involved' => $this->pull('police_involved'),
            'incident_time' => $dateTime,
        ]);

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
