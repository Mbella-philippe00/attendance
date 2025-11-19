<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'type'    => $this->faker->randomElement(['late','absence','info','report_ready']),
            'channel' => $this->faker->randomElement(['in_app','email']),
            'payload' => ['title'=>$this->faker->sentence(),'body'=>$this->faker->sentence(10)],
            'read_at' => $this->faker->boolean(60) ? now()->subDays(rand(0,10)) : null,
        ];
    }
}
