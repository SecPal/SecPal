<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        if (! Gate::allows('work', Auth::user())) {
            return view('livewire.no-shift');
        }

        return view('livewire.dashboard');
    }
}
