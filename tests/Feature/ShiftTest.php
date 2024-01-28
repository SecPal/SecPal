<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Livewire\Shift;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
    $this->customer = Customer::factory()->create();
    $this->location = Location::factory()->for($this->customer)->create();
    $company = Company::factory()->create();
    $this->user = User::factory()->for($company)->create();
    $this->user->locations()->attach($this->location);
    $this->actingAs($this->user);
});

it('allows a user to set shift location', function () {
    Livewire::test(Shift::class, ['identifier' => 'your_identifier'])
        ->set('shift_location', $this->location->id)
        ->call('startShift')
        ->assertSet('shift_location', $this->location->id);
});

it('ensures shift location is required', function () {
    Livewire::test(Shift::class, ['identifier' => 'your_identifier'])
        ->set('shift_location', null)
        ->call('startShift')
        ->assertHasErrors('shift_location');
});

it('checks that a user can start a shift', function () {
    Livewire::test(Shift::class, ['identifier' => 'your_identifier'])
        ->set('shift_location', $this->location->id)
        ->call('startShift')
        ->assertSessionHas('on_duty')
        ->assertSessionHas('location_id', $this->location->id);
});

it('denies a wrong location id', function () {
    $other_location = Location::factory()->for($this->customer)->create();

    Livewire::test(Shift::class, ['identifier' => 'your_identifier'])
        ->set('shift_location', $other_location->id)
        ->call('startShift')
        ->assertForbidden();
});
