<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('validates live wire login page', function () {
    // Keep the login page load test inline
    get(route('login'))
        ->assertOk()
        ->assertSeeLivewire(Login::class);
});

it('validates login page accessible only to guests', function () {
    get(route('login'))
        ->assertOk();
    $user = User::factory()->create();
    $this->actingAs($user);
    get(route('login'))
        ->assertRedirectToRoute('dashboard');
});

it('validates login process using valid and invalid credentials', function () {
    $email = 'test@example.com';
    $invalidPassword = 'wrong';

    // Test scenarios with invalid parameters
    testLoginWithCredentials('', '')
        ->assertHasErrors(['email' => 'required', 'password' => 'required']);
    testLoginWithCredentials($email, $invalidPassword)
        ->assertHasErrors('email');

    // Test scenarios with valid parameters
    $validPassword = 'password';
    $user = User::factory()->create(['password' => Hash::make($validPassword)]);
    testLoginWithCredentials($user->email, $validPassword)
        ->assertRedirect(route('dashboard'));
});

// Helper function to test login with provided credentials
function testLoginWithCredentials($email, $password)
{
    return Livewire::test('auth.login')
        ->set('email', $email)
        ->set('password', $password)
        ->call('login');
}
