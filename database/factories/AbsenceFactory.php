<?php

namespace Database\Factories;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    protected $model = Absence::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-60 days','+15 days');
        $end   = (clone $start)->modify('+'.rand(1,5).' days');

        return [
            'user_id'      => User::inRandomOrder()->value('id'),
            'type'         => $this->faker->randomElement(['conge_paye','conge_maladie','rtt','autre']),
            'start_date'   => $start->format('Y-m-d'),
            'end_date'     => $end->format('Y-m-d'),
            'working_days' => rand(1,5),
            'reason'       => $this->faker->sentence(),
            'attachment_url'=> null,
            'status'       => $this->faker->randomElement(['pending','approved','rejected']),
            'approved_by'  => null,
            'approved_at'  => null,
        ];
    }
}
