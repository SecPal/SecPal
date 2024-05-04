<?php
/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use App\Enums\ShiftStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Shift extends Component
{
    public $canWork = false;

    public $show = false;

    public $identifier;

    public $locations;

    public $shift_start;

    public $shift_end;

    public $shift_location;

    public function mount($identifier): void
    {
        $this->identifier = $identifier;
        $this->locations = $this->getAuthenticatedUser()->locations()->get();
        $this->shift_start = $this->getInitialShiftStart();
        $this->shift_end = $this->getInitialShiftEnd();
        $this->canWork = Gate::allows('work', $this->getAuthenticatedUser());
    }

    #[On('start-shift')]
    public function showStartShift(): void
    {
        $this->show = true;
    }

    private function getAuthenticatedUser(): User|Authenticatable|null
    {
        abort_if(! Auth::check(), 403);

        return Auth::user();
    }

    private function getInitialShiftStart(): string
    {
        return $this->getRoundedQuarterHour();
    }

    private function getInitialShiftEnd(): string
    {
        return $this->getRoundedQuarterHour();
    }

    private function getRoundedQuarterHour(): string
    {
        $currentTimeInMinutes = time() / 60;
        $roundedTimeInMinutes = round($currentTimeInMinutes / 15) * 15;

        return date('H:i', $roundedTimeInMinutes * 60);
    }

    public function startShift(): void
    {
        $this->validate([
            'shift_start' => 'required|string',
            'shift_location' => 'required|int',
        ]);
        abort_unless($this->locations->contains('id', $this->shift_location), 403);

        $shift_start = Carbon::createFromFormat('H:i', $this->shift_start);
        $this->getAuthenticatedUser()->createTimeTrackerForUser($this->shift_location, ShiftStatus::ShiftStart,
            $shift_start);
        $this->reset('show');

        $this->checkWorkStatus();
    }

    public function endShift(): void
    {
        $this->validate([
            'shift_end' => 'required|string',
        ]);

        $locationId = Auth::user()->getLocationId();
        $shift_end = Carbon::createFromFormat('H:i', $this->shift_end);
        $this->getAuthenticatedUser()->createTimeTrackerForUser($locationId, ShiftStatus::ShiftEnd, $shift_end);
        $this->reset('show');

        $this->checkWorkStatus();
    }

    public function checkWorkStatus(): void
    {
        $newCanWorkStatus = Gate::allows('work', $this->getAuthenticatedUser());
        if ($newCanWorkStatus !== $this->canWork) {
            $this->canWork = $newCanWorkStatus;
            $this->js('location.reload()'); // reload whole page
        } else {
            $this->dispatch('shift-changed'); // only refresh shift component
        }
    }

    public function render()
    {
        return view('livewire.shift');
    }
}
