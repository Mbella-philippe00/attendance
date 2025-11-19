<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Site;
use App\Models\User;
use App\Models\ShiftTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::inRandomOrder()->value('id'),
            'site_id'          => Site::inRandomOrder()->value('id'),
            'shift_template_id'=> ShiftTemplate::inRandomOrder()->value('id'),
            'date'             => $this->faker->dateTimeBetween('-2 months','+1 month')->format('Y-m-d'),
            'start_time'       => '08:00:00',
            'end_time'         => '17:00:00',
            'is_remote'        => $this->faker->boolean(10),
            'notes'            => null,
        ];
    }
}
