<?php

namespace App\Policies;

use App\Models\Trespass;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrespassPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Trespass $trespass): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Trespass $trespass): bool
    {
    }

    public function delete(User $user, Trespass $trespass): bool
    {
    }

    public function restore(User $user, Trespass $trespass): bool
    {
    }

    public function forceDelete(User $user, Trespass $trespass): bool
    {
    }
}
