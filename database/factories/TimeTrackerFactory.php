<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace Database\Factories;

use App\Models\TimeTracker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TimeTrackerFactory extends Factory
{
    protected $model = TimeTracker::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'event' => $this->faker->word(),
            'real_time' => Carbon::now(),
            'plan_time' => Carbon::now(),
            'comment' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
