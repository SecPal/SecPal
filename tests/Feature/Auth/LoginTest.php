<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Enums\ShiftStatus;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use App\Models\TimeTracker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const KNOWN_PASSWORD = 'password';

beforeEach(function () {
    session()->flush();
    $this->withoutVite();
    $this->customer = Customer::factory()->create();
    $this->company = Company::factory()->create();

    $this->user = User::factory()->create([
        'username' => 'john.doe',
        'password' => bcrypt(KNOWN_PASSWORD),
        'company_id' => $this->company->id,
    ]);

    $this->location = Location::factory()->create(['customer_id' => $this->customer->id]);
});

it('shows login page', function () {
    get(route('login'))->assertOk();
});

it('sees livewire on login page', function () {
    get(route('login'))->assertSeeLivewire(Login::class);
});

it('validates login page accessible only to guests', function () {
    get(route('login'))
        ->assertOk();
    $this->actingAs($this->user);
    get(route('login'))
        ->assertRedirectToRoute('dashboard');
});

it('validates login process using valid and invalid credentials', function () {
    $this->assertGuest();

    // Test scenarios with invalid parameters
    testLoginWithCredentials('', '')
        ->assertHasErrors(['username' => 'required', 'password' => 'required']);
    testLoginWithCredentials($this->user->username, 'invalid')
        ->assertHasErrors('username');

    $this->assertGuest();

    // Test scenarios with valid parameters
    testLoginWithCredentials($this->user->username, KNOWN_PASSWORD)
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($this->user);
});

it('has an username input field', function () {
    Livewire::test(Login::class)
        ->assertPropertyWired('username');
});

it('has a password input field', function () {
    Livewire::test(Login::class)
        ->assertPropertyWired('password');
});

it('can submit the login form', function () {
    Livewire::test(Login::class)
        ->assertMethodWiredToForm('login');
});

it('shows an error message if the login failed', function () {
    // Test scenario with invalid parameters
    testLoginWithCredentials($this->user->username, 'invalid')
        ->assertHasErrors('username')
        ->assertSee(__('auth.failed'));

    $this->assertGuest();
});

it('can logout an user', function () {
    actingAs($this->user);

    $this->assertAuthenticatedAs($this->user);

    Livewire::test(Logout::class)
        ->call('logout')
        ->assertRedirect('/');

    $this->assertGuest();
});

it('gets location id when user is on shift and sets session variables after login', function () {
    // Start and end the user shift, then start again
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftStart, $this->location->id, Carbon::now()->subMinutes(2));
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftEnd, $this->location->id, Carbon::now()->subMinute());
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftStart, $this->location->id);

    // Act: Attempt to login user
    testLoginWithCredentials($this->user->username, KNOWN_PASSWORD);

    // Assert: Check session values
    $this->assertEquals($this->user->isOnDuty(), true);
    $this->assertEquals($this->user->location_id, $this->location->id);
});

it('returns on_duty even if location_id is null', function () {
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftStart, null, Carbon::now()->subMinutes(2));
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftEnd, null, Carbon::now()->subMinute());
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftStart, null);

    // Act: Attempt to login user
    testLoginWithCredentials($this->user->username, KNOWN_PASSWORD);

    // Assert: Check session values
    $this->assertEquals($this->user->isOnDuty(), true);
    $this->assertEquals($this->user->location_id, null);
});

it('should not set on_duty after ShiftEnd', function () {
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftStart, $this->location->id, Carbon::now()->subMinute());
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftEnd, $this->location->id);

    // Act: Attempt to login user
    testLoginWithCredentials($this->user->username, KNOWN_PASSWORD);

    // Assert: Check session values
    $this->assertFalse(session()->has('on_duty'));
    $this->assertFalse(session()->has('location_id'));
});

it('should not set on_duty after ShiftAbort', function () {
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftStart, $this->location->id, Carbon::now()->subMinute());
    createTimetrackerForUser($this->user->id, ShiftStatus::ShiftAbort, $this->location->id);

    // Act: Attempt to login user
    testLoginWithCredentials($this->user->username, KNOWN_PASSWORD);

    // Assert: Check session values
    $this->assertFalse(session()->has('on_duty'));
    $this->assertFalse(session()->has('location_id'));
});

// Helper function to test login with provided credentials
function testLoginWithCredentials($username, $password)
{
    return Livewire::test('auth.login')
        ->set('username', $username)
        ->set('password', $password)
        ->call('login');
}

function createTimetrackerForUser($userId, $event, $locationId = null, $createdAt = null): void
{
    TimeTracker::factory()->create([
        'user_id' => $userId,
        'event' => $event,
        'location_id' => $locationId,
        'created_at' => $createdAt ?? now(),
    ]);
}
