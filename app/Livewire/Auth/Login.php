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
    #[Validate]
    public $email = '';

    public $password = '';

    public function rules(): array
    {
        return [
            'email' => [
                'required',
            ],
            'password' => [
                'required',
            ],
        ];
    }

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            $this->redirectRoute('dashboard', navigate: true);
        } else {
            $this->addError('email', __('auth.failed'));
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
