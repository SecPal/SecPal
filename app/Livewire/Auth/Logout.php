<?php
/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Logout extends Component
{
    #[On('logout')]
    public function logout(): void
    {
        $this->dispatch('showLogoutModal');
        $this->performLogout();
        $this->invalidateSession();
        session()->flash('user-logout', true);
        $this->redirectToLogin();
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

    private function redirectToLogin(): void
    {
        $this->redirect(route('login'), navigate: false);
    }

    #[On('idle-timeout')]
    public function idleLogout(): void
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
