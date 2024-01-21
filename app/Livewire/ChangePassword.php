<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChangePassword extends Component
{
    #[Validate('required|current_password', onUpdate: false)]
    public $current_password = '';

    #[Validate('required|min:5|confirmed', onUpdate: false)]
    public $password = '';

    public $password_confirmation = '';

    public function changePassword(): void
    {
        $this->validate();

        $user = Auth::user();
        if ($user->checkPassword($this->current_password)) {
            $user->changePassword($this->password);
            $this->dispatch('password-changed', message: __('Your password was successfully changed.'));
            $this->reset();
        }
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
