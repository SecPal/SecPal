<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Policies;

use App\Models\TimeTracker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeTrackerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, TimeTracker $timeTracker): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, TimeTracker $timeTracker): bool
    {
    }

    public function delete(User $user, TimeTracker $timeTracker): bool
    {
    }

    public function restore(User $user, TimeTracker $timeTracker): bool
    {
    }

    public function forceDelete(User $user, TimeTracker $timeTracker): bool
    {
    }
}
