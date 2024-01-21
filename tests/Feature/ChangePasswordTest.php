<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Livewire\ChangePassword;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

const OLD_PASSWORD = 'password';

beforeEach(function () {
    session()->flush();
    $this->withoutVite();
    $this->customer = Customer::factory()->create();
    $this->company = Company::factory()->create();

    $this->user = User::factory()->create([
        'username' => 'john.doe',
        'password' => bcrypt(OLD_PASSWORD),
        'company_id' => $this->company->id,
    ]);

    $this->location = Location::factory()->create(['customer_id' => $this->customer->id]);
});

it('shows change password form', function () {
    $this->actingAs($this->user);
    $component = Livewire::test(ChangePassword::class);
    $component->assertSee('current_password')
        ->assertSee('password')
        ->assertSee('password_confirmation');
});

it('updates the password', function () {
    $this->actingAs($this->user);
    $component = Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', 'new_password')
        ->set('password_confirmation', 'new_password')
        ->call('changePassword');
    $this->assertTrue(auth()->user()->checkPassword('new_password'));
});

it('does not update password when current password is incorrect', function () {
    $this->actingAs($this->user);
    $component = Livewire::test(ChangePassword::class)
        ->set('current_password', 'wrong_password')
        ->set('password', 'new_password')
        ->set('password_confirmation', 'new_password')
        ->call('changePassword');
    $component->assertHasErrors(['current_password']);
    $this->assertTrue(auth()->user()->checkPassword(OLD_PASSWORD));
});

it('throws validation error when password is less than minimum length', function () {
    $this->actingAs($this->user);
    $component = Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', '1234')
        ->set('password_confirmation', '1234')
        ->call('changePassword');
    $component->assertHasErrors(['password']);
});

it('fires an event when the password is changed', function () {
    $this->actingAs($this->user);

    Livewire::test('change-password')
        ->set('current_password', OLD_PASSWORD)
        ->set('password', 'new_password')
        ->set('password_confirmation', 'new_password')
        ->call('changePassword')
        ->assertDispatched('password-changed');
});

it('does not update password when password confirmation does not match', function () {
    $this->actingAs($this->user);
    Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', 'new_password')
        ->set('password_confirmation', 'different_password')
        ->call('changePassword')
        ->assertHasErrors(['password' => 'confirmed']);
    $this->assertTrue(auth()->user()->checkPassword(OLD_PASSWORD));
});

it('does not update when new password is the same as the old password', function () {
    $this->actingAs($this->user);

    Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', OLD_PASSWORD)
        ->set('password_confirmation', OLD_PASSWORD)
        ->call('changePassword')
        ->assertHasErrors(['password' => 'different']);
});

it('handles sql injection attempts into the password field', function () {
    $this->actingAs($this->user);
    $injectedSQL = "'; DROP TABLE users; --";
    Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', $injectedSQL)
        ->set('password_confirmation', $injectedSQL)
        ->call('changePassword');
    $this->assertTrue(auth()->user()->checkPassword($injectedSQL));
});

it('fails when the password is empty', function () {
    $this->actingAs($this->user);

    Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', '')
        ->set('password_confirmation', '')
        ->call('changePassword')
        ->assertHasErrors(['password' => 'required']);
});

it('does not update password when user is unauthenticated', function () {
    // Here we don't authenticate the user using $this->actingAs($this->user)

    Livewire::test(ChangePassword::class)
        ->set('current_password', OLD_PASSWORD)
        ->set('password', 'new_password')
        ->set('password_confirmation', 'new_password')
        ->call('changePassword')
        ->assertStatus(403); // Assuming that unauthenticated users are handled with a 403 response
});

it('shows necessary input fields', function () {
    Livewire::test(ChangePassword::class)
        ->assertPropertyWired('current_password')
        ->assertPropertyWired('password')
        ->assertPropertyWired('password_confirmation');
});

it('can submit the change password form', function () {
    Livewire::test(ChangePassword::class)
        ->assertMethodWiredToForm('changePassword');
});
