<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    protected $listeners = ['logout'];

    public function logout(): void
    {
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
        $this->redirect('/', navigate: true);
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
