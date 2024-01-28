<?php
/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Livewire\Dashboard;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $company = Company::factory()->create();
    $this->user = User::factory()->for($company)->create();
    $this->dashboardRoute = route('dashboard');
});

it('allows logged in users to view dashboard', function () {
    actingAs($this->user);

    get($this->dashboardRoute)
        ->assertOk()
        ->assertSeeLivewire('dashboard');
});

it('redirects unauthenticated guests to login', function () {
    $this->assertGuest();

    get($this->dashboardRoute)
        ->assertRedirectToRoute('login');
});

it('has a logout button', function () {
    // actingAs($this->user);

    Livewire::test(Dashboard::class)
        ->assertMethodWired('$dispatchTo(\'auth.logout\', \'logout\')');
});
