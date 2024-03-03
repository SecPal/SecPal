<?php
/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    // Update listeners to include 'idle-timeout' event
    protected $listeners = ['logout', 'idle-timeout' => 'idle_logout'];

    public function logout(): void
    {
        $this->dispatch('showLogoutModal');
        $this->performLogout();
        $this->invalidateSession();
        $this->redirectHome();
    }

    private function performLogout(): void
    {
        Auth::logout();
    }

    private function invalidateSession(): void
    {
        session()->invalidate();
        session()->regenerateToken();
    }

    private function redirectHome(): void
    {
        $this->redirect('/', navigate: false);
    }

    public function idle_logout(): void
    {
        $this->performLogout();
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
