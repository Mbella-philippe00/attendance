<?php

namespace Database\Factories;

use App\Models\ShiftTemplate;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftTemplateFactory extends Factory
{
    protected $model = ShiftTemplate::class;

    public function definition(): array
    {
        return [
            'name'          => $this->faker->randomElement(['Jour','Matin','Après-midi','Nuit']),
            'site_id'       => Site::inRandomOrder()->value('id'),
            'start_time'    => $this->faker->randomElement(['08:00:00','07:00:00','14:00:00','22:00:00']),
            'end_time'      => $this->faker->randomElement(['17:00:00','15:00:00','22:00:00','06:00:00']),
            'break_minutes' => $this->faker->randomElement([30,45,60]),
            'days_of_week'  => [1,2,3,4,5],
            'is_active'     => true,
        ];
    }
}
