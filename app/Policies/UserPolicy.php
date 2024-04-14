<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function work(User $user): bool
    {
        // If the user has global 'work-without-shift' permission, return true immediately.
        if ($user->isAbleTo('work-without-shift')) {
            return true;
        }

        // Check for 'work-without-shift' permission in each location and return true immediately if found.
        foreach ($user->locations as $location) {
            if ($user->isAbleTo('work-without-shift', $location)) {
                return true;
            }
        }

        // If the user does not have the 'work-without-shift' permission, check if they are on duty.
        return $user->isOnDuty();
    }

    //    public function viewAny(User $user): bool
    //    {
    //
    //    }
    //
    //    public function view(User $user, User $model): bool
    //    {
    //    }
    //
    //    public function create(User $user): bool
    //    {
    //    }
    //
    //    public function update(User $user, User $model): bool
    //    {
    //    }
    //
    //    public function delete(User $user, User $model): bool
    //    {
    //    }
    //
    //    public function restore(User $user, User $model): bool
    //    {
    //    }
    //
    //    public function forceDelete(User $user, User $model): bool
    //    {
    //    }
}
