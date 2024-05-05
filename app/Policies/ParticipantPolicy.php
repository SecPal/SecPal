<?php

namespace App\Policies;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParticipantPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Participant $participant): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Participant $participant): bool
    {
    }

    public function delete(User $user, Participant $participant): bool
    {
    }

    public function restore(User $user, Participant $participant): bool
    {
    }

    public function forceDelete(User $user, Participant $participant): bool
    {
    }
}
