<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Policies;

use App\Models\HouseBan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HouseBanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, HouseBan $houseBan): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, HouseBan $houseBan): bool
    {
    }

    public function delete(User $user, HouseBan $houseBan): bool
    {
    }

    public function restore(User $user, HouseBan $houseBan): bool
    {
    }

    public function forceDelete(User $user, HouseBan $houseBan): bool
    {
    }
}
