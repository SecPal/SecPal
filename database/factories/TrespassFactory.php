<?php

namespace Database\Factories;

use App\Models\Journal;
use App\Models\Participant;
use App\Models\Trespass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TrespassFactory extends Factory
{
    protected $model = Trespass::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'journal_id' => Journal::factory(),
            'participant_id' => Participant::factory(),
            'charge_filed_by' => User::factory(),
        ];
    }
}
