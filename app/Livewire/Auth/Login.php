<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Livewire\Auth;

use App\Enums\ShiftStatus;
use App\Models\TimeTracker;
use App\Models\User;
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

            try {
                // Get location_id if user is on shift
                $locationId = $this->getLocationIdIfOnShift(auth()->user());

                // Store on_duty and location_id in the session
                session([
                    'on_duty' => true,
                    'location_id' => $locationId,
                ]);
                ray('session set');
            } catch (\Exception $e) {
                // User is not on shift, do not set on_duty in the session
            }

            $this->handleSuccessfulLogin();
        } else {
            $this->handleFailedLogin();
        }
    }

    /**
     * Check if the user is on shift (including break time) and has not ended or aborted the shift,
     * then return the corresponding location_id
     *
     * @throws \Exception If user is not on shift
     */
    private function getLocationIdIfOnShift(User $user): ?int
    {
        // Retrieve the last record for the given user
        $timeTracker = TimeTracker::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // If there's no record, then the user isn't on a shift
        if (! $timeTracker) {
            throw new \Exception('User is not on shift.');
        }

        // If the last 'event' is 'ShiftEnd' or 'ShiftAbort', the user isn't on a shift
        if ($timeTracker->event === ShiftStatus::ShiftEnd || $timeTracker->event === ShiftStatus::ShiftAbort) {
            throw new \Exception('User is not on shift.');
        }

        // For all other 'event' types ('ShiftStart', 'BreakStart', 'BreakEnd', 'BreakAbort'), it means the user is on a shift
        return $timeTracker->location_id;
    }

    private function handleSuccessfulLogin(): void
    {
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
