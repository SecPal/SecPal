<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
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
        $this->checkForRedirect();
    }

    private function checkForRedirect(): void
    {
        // check if we want to redirect after successful login
        if (str_replace(url('/'), '', url()->previous()) != '/') {
            $this->redirectUrl = str_replace(url('/'), '', url()->previous());
        }
    }

    public function login(): void
    {
        $this->validateUserCredentials();
        $this->attemptLogin();
    }

    private function validateUserCredentials(): void
    {
        $this->validate();
    }

    private function attemptLogin(): void
    {
        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();

            $this->handleSuccessfulLogin();
        } else {
            $this->handleFailedLogin();
        }
    }

    private function handleSuccessfulLogin(): void
    {
        $this->dispatch('show-spinner');
        $this->dispatch('removeLastAction');

        // redirect to previous url or to the dashboard
        if ($this->redirectUrl) {
            $this->redirect($this->redirectUrl, navigate: true);
        } else {
            $this->redirectRoute('dashboard', navigate: true);
        }
    }

    private function handleFailedLogin(): void
    {
        $this->addError('username', __('auth.failed'));
        $this->showErrorIndicator = true;
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.login');
    }
}
