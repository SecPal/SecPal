<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use App\Enums\ShiftStatus;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Shift extends Component
{
    #[On('start-shift')]
    public function startShift(): void
    {

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[On('end-shift')]
    public function endShift(): void
    {
        $locationId = session()->get('location_id');

        // add shiftEnd to the Database
        auth()->user()->createTimeTracker($locationId, ShiftStatus::ShiftEnd, Carbon::now());

        $this->dispatch('logout');
    }

    public function render(): string
    {
        return <<<'blade'
            <div>
                {{-- no cool stuff here, sry ;-) --}}
            </div>
        blade;
    }
}
