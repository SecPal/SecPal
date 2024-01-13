<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

/**
 * Class Login
 *
 * This class represents the login component for the Livewire authentication module.
 */

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required')]
    public $username = '';

    #[Validate('required')]
    public $password = '';

    public $showErrorIndicator = false;

    public $redirectUrl = '';

    public function mount(): void
    {
        $this->checkRedirect();
    }

    private function checkRedirect(): void
    {
        ray('check redirect');
        // check if we want to redirect after successful login
        if (url()->current() != url()->previous()) {
            ray('set redirect route');
            $this->redirectUrl = str_replace(url('/'), '', url()->previous());
        }
    }

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();

            // redirect to previous url or to the dashboard
            if ($this->redirectUrl) {
                ray('redirect');
                $this->redirect($this->redirectUrl, navigate: true);
            } else {
                ray('home route');
                $this->redirectRoute('dashboard', navigate: true);
            }
        } else {
            $this->addError('username', __('auth.failed'));
            $this->showErrorIndicator = true;
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.login');
    }
}
