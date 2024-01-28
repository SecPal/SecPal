<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use App\Enums\ShiftStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Shift extends Component
{
    public $show = false;

    public $identifier;

    public $locations;

    #[Validate('string')]
    public $shift_start = '';

    #[Validate('required|int')]
    public $shift_location;

    public $shift_end = '';

    public function mount($identifier): void
    {
        $this->identifier = $identifier;

        $roundedQuarterHour = $this->getRoundedQuarterHour();
        $this->shift_start = $roundedQuarterHour;
        $this->shift_end = $roundedQuarterHour;
        $this->locations = $this->getCurrentUser()->locations()->get();
    }

    private function getRoundedQuarterHour(): string
    {
        $currentTimeInMinutes = time() / 60;
        $roundedTimeInMinutes = round($currentTimeInMinutes / 15) * 15;

        return date('H:i', $roundedTimeInMinutes * 60);
    }

    private function getCurrentUser()
    {
        abort_if(! Auth::check(), 403);

        return auth()->user();
    }

    public function startShift(): void
    {
        $this->validate();

        abort_unless($this->locations->contains('id', $this->shift_location), 403);

        $shift_start = Carbon::createFromFormat('H:i', $this->shift_start);
        $this->getCurrentUser()->createTimeTracker($this->shift_location, ShiftStatus::ShiftStart, $shift_start);

        session(['on_duty' => true, 'location_id' => $this->shift_location]);

        $this->reset('show');
    }

    public function endShift(): void
    {
        $locationId = session()->get('location_id');

        $this->getCurrentUser()->createTimeTracker($locationId, ShiftStatus::ShiftEnd, Carbon::now());

        $this->dispatch('logout');
    }

    public function render()
    {
        return view('livewire.shift');
    }
}
