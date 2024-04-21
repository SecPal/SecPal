<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Journal extends Component
{
    public $location;

    public $locations;

    public User $user;

    public function mount(): void
    {
        $this->user = $this->getAuthenticatedUser();
        $this->loadLocations();
        $this->location = $this->user->getLocationId();
    }

    public function render()
    {
        $this->checkUserAuthorization();

        ray($this->location);

        return view('livewire.journal');
    }

    private function loadLocations(): void
    {
        $this->locations = $this->user->can('viewAny', Location::class)
            ? Location::all() : $this->user->locations;
    }

    private function checkUserAuthorization(): void
    {
        if (! Gate::allows('work', $this->user)) {
            view('livewire.no-shift');
            exit;
        }
    }

    private function getAuthenticatedUser(): User
    {
        return Auth::user();
    }
}
