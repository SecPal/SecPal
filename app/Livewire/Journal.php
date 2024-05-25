<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use App\Models\Journal as JournalModel;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Journal extends Component
{
    public $actual_location;

    public $location_data;

    public $locations;

    public User $user;

    public $selectSearchLocation;

    public function mount(): void
    {
        $this->user = $this->getAuthenticatedUser();
        $this->loadLocations();
        $this->actual_location = $this->user->getLocationId();
        $this->setLocationData($this->actual_location);
        $this->selectSearchLocation = $this->getSelectSearchLocation();
    }

    public function render()
    {
        checkUserShift($this->user);

        $journals = JournalModel::where('location_id', $this->actual_location)
            ->with('category')
            ->with('reportedBy')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('livewire.journal',
            [
                'journals' => $journals,
            ]
        );
    }

    public function updatedActualLocation($location): void
    {
        $this->setLocationData($location);
    }

    private function setLocationData($location): void
    {
        if ($location) {
            $this->location_data = Location::where('id', $location)
                ->with('users')
                ->with('users.company')
                ->with('customer')
                ->first();
        } else {
            $this->location_data = null;
        }

    }

    private function loadLocations(): void
    {
        $this->locations = $this->user->can('viewAnyJournal', Location::class)
            ? Location::all() : $this->user->locations;
    }

    private function getAuthenticatedUser(): User
    {
        return Auth::user();
    }

    public function getSelectSearchLocation(): array
    {
        return collect($this->locations)->map(function ($location) {
            return [
                'id' => $location->id,
                'label' => $location->name.' - '.$location->location,
                'disabled' => false, // Assuming all locations are not disabled by default.
            ];
        })->all();
    }

    #[On('shift-changed')]
    public function shiftChanged(): void
    {
        if (! $this->actual_location || ! $this->user->canAny(['viewRecentJournal', 'viewFullJournal'], $this->location_data)) {
            $this->js('location.reload()');
        }
    }
}
