<?php

namespace Database\Factories;

use App\Models\Journal;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'lastname' => $this->faker->lastName(),
            'firstname' => $this->faker->firstName(),
            'date_of_birth' => Carbon::now(),
            'street' => $this->faker->streetName(),
            'housenumer' => $this->faker->word(),
            'zipcode' => $this->faker->word(),
            'city' => $this->faker->city(),
            'ban_since' => Carbon::now(),
            'ban_until' => Carbon::now(),

            'journal_id' => Journal::factory(),
        ];
    }
}
