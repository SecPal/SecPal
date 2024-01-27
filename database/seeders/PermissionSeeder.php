<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // start user block
        $this->createPermission(
            'create-user',
            'Create user',
            'Create a new user'
        );

        $this->createPermission(
            'edit-user',
            'edit user',
            'edit a user'
        );

        $this->createPermission(
            'delete-user',
            'Delete user',
            'Delete a user'
        );
        $this->createPermission(
            'view-user',
            'View user',
            'View user details'
        );
        // end user block

        $this->createPermission(
            'need-no-shift',
            'Need no Shift',
            'Needs no shift to work'
        );

        // start journal block
        $this->createPermission(
            'create-journal',
            'Create journal entry',
            'Can create a new journal entry'
        );

        $this->createPermission(
            'edit-journal',
            'Edit journal entries',
            'Can edit journal entries'
        );

        $this->createPermission(
            'view-journal',
            'View journal entries ',
            'Can view journal entries'
        );

        $this->createPermission(
            'delete-journal',
            'Delete journal entries',
            'Can delete journal entries'
        );

        $this->createPermission(
            'approve-journal',
            'Approve journal entries',
            'Can approve journal entries'
        );

        $this->createPermission(
            'journal-overview',
            'See journal overview',
            'Can see a journal overview'
        );
        // end journal block

        // start management block
        $this->createPermission(
            'create-customer',
            'create customer',
            'Can create a new customer'
        );
        $this->createPermission(
            'edit-customer',
            'edit customer',
            'Can edit a customer'
        );
        $this->createPermission(
            'delete-customer',
            'delete customer',
            'Can delete a customer'
        );
        $this->createPermission(
            'view-customer',
            'view customer',
            'Can view a customer'
        );

        $this->createPermission(
            'create-location',
            'create location',
            'Can create a new location'
        );
        $this->createPermission(
            'edit-location',
            'edit location',
            'Can edit a location'
        );
        $this->createPermission(
            'delete-location',
            'delete location',
            'Can delete a location'
        );
        $this->createPermission(
            'view-location',
            'view location',
            'Can view a location'
        );
        // end management block

        $this->createPermission(
            'view-time-tracker',
            'view time tracker',
            'can view time trackers'
        );
    }

    private function createPermission(string $name, string $displayName, string $description): void
    {
        Permission::create([
            'name' => $name,
            'display_name' => $displayName, // optional
            'description' => $description, // optional
        ]);
    }
}
