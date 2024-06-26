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

    #[Validate('required|min:5|confirmed|different:current_password', onUpdate: false)]
    public $password = '';

    public $password_confirmation = '';

    public function updated(): void
    {
        $this->resetErrorBag();
    }

    public function changePassword(): void
    {
        abort_unless(auth()->check(), 403);
        $this->validate();

        $user = Auth::user();
        if ($user->checkPassword($this->current_password)) {
            $user->changePassword($this->password);
            $this->dispatch('password-changed', message: t('You have successfully changed your password.'));
            $this->reset();
        }
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
