<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use Livewire\Component;

class JournalRow extends Component
{
    public $journal;

    public $location_data;

    public function render()
    {
        return view('livewire.journal-row');
    }
}
