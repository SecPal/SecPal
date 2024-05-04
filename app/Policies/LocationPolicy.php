<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    public function viewAnyJournal(User $user): bool
    {
        return $user->hasPermission(
            [
                'see-recent-journal-overview',
                'see-full-journal-overview',
            ]
        );
    }

    public function viewRecentJournal(User $user, Location $location): bool
    {
        if ($user->isAbleTo('see-recent-journal-overview')) {
            return true;
        }

        if ($user->isOnDuty() && $user->getLocationId() == $location->id) {
            return true;
        }

        return $user->isAbleTo('see-recent-journal-overview', $location);
    }

    public function viewFullJournal(User $user, Location $location): bool
    {
        if ($user->isAbleTo('see-full-journal-overview')) {
            return true;
        }

        return $user->isAbleTo('see-full-journal-overview', $location);
    }

    public function createJournal(User $user, Location $location): bool
    {
        if ($user->isAbleTo('create-journal')) {
            return true;
        }

        if ($user->isOnDuty() && $user->getLocationId() == $location->id) {
            return true;
        }

        return $user->isAbleTo('create-journal', $location);
    }

    //    public function create(User $user): bool
    //    {
    //    }
    //
    //    public function update(User $user, Location $location): bool
    //    {
    //    }
    //
    //    public function delete(User $user, Location $location): bool
    //    {
    //    }
    //
    //    public function restore(User $user, Location $location): bool
    //    {
    //    }
    //
    //    public function forceDelete(User $user, Location $location): bool
    //    {
    //    }
}
