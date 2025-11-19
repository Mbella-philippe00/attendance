<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::inRandomOrder()->value('id'),
            'action'      => $this->faker->randomElement(['create','update','delete','login']),
            'entity_type' => $this->faker->randomElement(['User','AttendanceRecord','Absence']),
            'entity_id'   => null,
            'old_values'  => null,
            'new_values'  => null,
            'ip_address'  => $this->faker->ipv4(),
            'user_agent'  => $this->faker->userAgent(),
        ];
    }
}
