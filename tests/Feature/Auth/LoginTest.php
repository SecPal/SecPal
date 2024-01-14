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

beforeEach(function () {
    $this->withoutVite();
    $company = Company::factory()->create();
    $this->user = User::factory()->for($company)->create();
});

it('validates live wire login page', function () {
    get(route('login'))
        ->assertOk()
        ->assertSeeLivewire(Login::class);
});

it('validates login page accessible only to guests', function () {
    get(route('login'))
        ->assertOk();
    $this->actingAs($this->user);
    get(route('login'))
        ->assertRedirectToRoute('dashboard');
});

it('validates login process using valid and invalid credentials', function () {
    $user = 'john.doe';
    $invalidPassword = 'wrong';

    $this->assertGuest();

    // Test scenarios with invalid parameters
    testLoginWithCredentials('', '')
        ->assertHasErrors(['username' => 'required', 'password' => 'required']);
    testLoginWithCredentials($user, $invalidPassword)
        ->assertHasErrors('username');

    $this->assertGuest();

    // Test scenarios with valid parameters
    $validPassword = 'password';
    $company = Company::factory()->create();
    $user = User::factory()->for($company)->create(['password' => Hash::make($validPassword)]);
    testLoginWithCredentials($user->username, $validPassword)
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
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
    $username = 'john.doe';
    $invalidPassword = 'wrong';

    // Test scenario with invalid parameters
    testLoginWithCredentials($username, $invalidPassword)
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
    // Create a new customer
    $customer = Customer::factory()->create();
    $company = Company::factory()->create();

    // Create a new user with a known password
    $user = User::factory()->create([
        'username' => 'john.doe',
        'password' => bcrypt('password'),
        'company_id' => $company->id,
    ]);

    // Create a location associated with the customer
    $location = Location::factory()->create(['customer_id' => $customer->id]);

    // Start and end the user shift, then start again
    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftStart,
        'location_id' => $location->id,
        'created_at' => Carbon::now()->subMinutes(2),
    ]);

    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftEnd,
        'location_id' => $location->id,
        'created_at' => Carbon::now()->subMinute(),
    ]);

    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftStart,
        'location_id' => $location->id,
    ]);

    // Act: Attempt to login user
    Livewire::test(Login::class, [
        'username' => 'john.doe',
        'password' => 'password',
    ])->call('login');

    // Assert: Check session values
    $this->assertEquals(session('on_duty'), true);
    $this->assertEquals(session('location_id'), $location->id);
});

it('returns on_duty even if location_id is null', function () {
    // Create a new customer
    $customer = Customer::factory()->create();
    $company = Company::factory()->create();

    // Create a new user with a known password
    $user = User::factory()->create([
        'username' => 'john.doe',
        'password' => bcrypt('password'),
        'company_id' => $company->id,
    ]);

    // Create a location associated with the customer
    $location = Location::factory()->create(['customer_id' => $customer->id]);

    // Start and end the user shift, then start again
    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftStart,
        'location_id' => null,
        'created_at' => Carbon::now()->subMinutes(2),
    ]);

    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftEnd,
        'location_id' => null,
        'created_at' => Carbon::now()->subMinute(),
    ]);

    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftStart,
        'location_id' => null,
    ]);

    // Act: Attempt to login user
    Livewire::test(Login::class, [
        'username' => 'john.doe',
        'password' => 'password',
    ])->call('login');

    // Assert: Check session values
    $this->assertEquals(session('on_duty'), true);
    $this->assertEquals(session('location_id'), null);
});

it('should not set on_duty after ShiftEnd', function () {
    // Create required instances
    $customer = Customer::factory()->create();
    $company = Company::factory()->create();

    // Create a new user with a known password
    $user = User::factory()->create([
        'username' => 'john.doe',
        'password' => bcrypt('password'),
        'company_id' => $company->id,
    ]);

    // Create a location associated with the customer
    $location = Location::factory()->create(['customer_id' => $customer->id]);

    // Start and end the user shift
    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftStart,
        'location_id' => $location->id,
        'created_at' => Carbon::now()->subMinute(),
    ]);

    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftEnd,
        'location_id' => $location->id,
    ]);

    // Act: Attempt to login user
    Livewire::test(Login::class, [
        'username' => 'john.doe',
        'password' => 'password',
    ])->call('login');

    // Assert: Check session values
    $this->assertEquals(session('on_duty'), null);
    $this->assertEquals(session('location_id'), null);
});

it('should not set on_duty after ShiftAbort', function () {
    // Create required instances
    $customer = Customer::factory()->create();
    $company = Company::factory()->create();

    // Create a new user with a known password
    $user = User::factory()->create([
        'username' => 'john.doe',
        'password' => bcrypt('password'),
        'company_id' => $company->id,
    ]);

    // Create a location associated with the customer
    $location = Location::factory()->create(['customer_id' => $customer->id]);

    // Start and abort the user shift
    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftStart,
        'location_id' => null,
        'created_at' => Carbon::now()->subMinute(),
    ]);

    TimeTracker::factory()->create([
        'user_id' => $user->id,
        'event' => ShiftStatus::ShiftAbort,
        'location_id' => null,
    ]);

    // Act: Attempt to login user
    Livewire::test(Login::class, [
        'username' => 'john.doe',
        'password' => 'password',
    ])->call('login');

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
