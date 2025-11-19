<?php

namespace Database\Factories;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemSettingFactory extends Factory
{
    protected $model = SystemSetting::class;

    public function definition(): array
    {
        return [
            'key'        => $this->faker->unique()->slug(),
            'value'      => ['enabled'=>true],
            'description'=> $this->faker->sentence(),
            'category'   => $this->faker->randomElement(['hr','security','ui','report']),
            'is_public'  => $this->faker->boolean(20),
            'updated_by' => User::inRandomOrder()->value('id'),
            'updated_at' => now()->subDays(rand(0,30)),
        ];
    }
}
