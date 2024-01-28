<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use App\Enums\ShiftStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Shift extends Component
{
    public $show = false;

    public $identifier;

    public $locations;

    public $shift_start;

    public $shift_end;

    public $shift_location;

    public function mount($identifier): void
    {
        $this->identifier = $identifier;
        $this->locations = $this->getCurrentUser()->locations()->get();
        $this->shift_start = $this->getRoundedQuarterHour();
        $this->shift_end = $this->getRoundedQuarterHour();
    }

    private function getCurrentUser()
    {
        abort_if(! Auth::check(), 403);

        return auth()->user();
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
        $this->getCurrentUser()->createTimeTracker($this->shift_location, ShiftStatus::ShiftStart, $shift_start);
        session(['on_duty' => true, 'location_id' => $this->shift_location]);
        $this->reset('show');
    }

    public function endShift(): void
    {
        $this->validate([
            'shift_end' => 'required|string',
        ]);
        $locationId = session()->get('location_id');
        $shift_end = Carbon::createFromFormat('H:i', $this->shift_end);
        $this->getCurrentUser()->createTimeTracker($locationId, ShiftStatus::ShiftEnd, $shift_end);
        session()->forget(['on_duty', 'location_id']);
        $this->reset('show');
    }

    public function render()
    {
        return view('livewire.shift');
    }
}
