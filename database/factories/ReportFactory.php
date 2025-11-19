<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'type'         => $this->faker->randomElement(['attendance_summary','lateness','overtime','absences']),
            'params'       => ['period'=>'last_month','site'=>null],
            'generated_by' => User::inRandomOrder()->value('id'),
            'generated_at' => $this->faker->optional()->dateTimeBetween('-20 days','now'),
            'file_url'     => null,
            'status'       => $this->faker->randomElement(['queued','running','done','failed']),
            'error_message'=> null,
        ];
    }
}
