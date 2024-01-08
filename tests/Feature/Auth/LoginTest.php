<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
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
    $user = User::factory()->create(['password' => Hash::make($validPassword)]);
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

// Helper function to test login with provided credentials
function testLoginWithCredentials($username, $password)
{
    return Livewire::test('auth.login')
        ->set('username', $username)
        ->set('password', $password)
        ->call('login');
}
