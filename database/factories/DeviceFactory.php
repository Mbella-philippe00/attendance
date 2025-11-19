<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::inRandomOrder()->value('id'),
            'name'        => $this->faker->randomElement(['iPhone','Android','Windows PC','Macbook']).' '.$this->faker->numberBetween(1,99),
            'type'        => $this->faker->randomElement(['mobile','desktop','tablet']),
            'os'          => $this->faker->randomElement(['iOS','Android','Windows','macOS','Linux']),
            'os_version'  => $this->faker->numerify('##.#'),
            'app_version' => $this->faker->numerify('1.#.#'),
            'ip_address'  => $this->faker->ipv4(),
            'user_agent'  => $this->faker->userAgent(),
            'browser'     => $this->faker->randomElement(['Chrome','Firefox','Edge','Safari']),
            'fingerprint' => $this->faker->sha256(),
            'is_trusted'  => $this->faker->boolean(70),
            'last_used_at'=> now()->subDays(rand(0,30)),
            'registered_at'=> now()->subDays(rand(10,300)),
        ];
    }
}
