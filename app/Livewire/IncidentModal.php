<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use App\Livewire\Forms\IncidentForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IncidentModal extends Component
{
    public IncidentForm $form;

    public $show = false;

    public $location_data;

    public $journal;

    public function mount(): void
    {
        $this->authorize('work', Auth::user());
        $this->form->setEnvironmentData($this->location_data);

        if ($this->journal) {
            $this->form->setJournalData($this->journal);
        }
    }

    public function render()
    {
        return view('livewire.incident-modal');
    }

    public function updated($field, $newValue): void
    {
        $this->form->updated($field, $newValue);
    }

    public function updatedFormCategoryId(): void
    {
        $this->form->updatedCategoryId();
    }

    public function save(): void
    {
        if ($this->form->edit) {
            $this->authorize('update', $this->form->journal);
        } else {
            $this->authorize('create-journal', $this->location_data);
        }

        $this->form->save();

        if ($this->form->edit) {
            $this->journal->refresh();
        } else {
            $this->dispatch('resetQuill');

        }

        $this->reset('show');
        $this->dispatch('added');
        $this->dispatch('reset-select-search');
    }
}
