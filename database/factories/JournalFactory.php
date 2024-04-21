<?php

namespace Database\Factories;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class JournalFactory extends Factory
{
    protected $model = Journal::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'text' => $this->faker->text(),
            'incident_time' => Carbon::now(),
            'actions' => $this->faker->word(),
            'involved' => $this->faker->randomNumber(),
            'reference' => $this->faker->word(),
        ];
    }
}
