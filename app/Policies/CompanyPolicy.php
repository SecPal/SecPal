<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Company $company): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Company $company): bool
    {
    }

    public function delete(User $user, Company $company): bool
    {
    }

    public function restore(User $user, Company $company): bool
    {
    }

    public function forceDelete(User $user, Company $company): bool
    {
    }
}
