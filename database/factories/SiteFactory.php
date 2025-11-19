<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'name'        => 'Site '.$this->faker->unique()->city(),
            'code'        => strtoupper($this->faker->bothify('S-###')),
            'address'     => $this->faker->streetAddress(),
            'city'        => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country'     => 'Cameroon',
            'latitude'    => $this->faker->latitude(2.0, 7.0),
            'longitude'   => $this->faker->longitude(8.0, 16.0),
            'geofence'    => null,
            'opening_time'=> '08:00:00',
            'closing_time'=> '17:00:00',
            'is_active'   => true,
        ];
    }
}
