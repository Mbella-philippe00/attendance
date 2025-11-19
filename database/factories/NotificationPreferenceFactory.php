<?php

namespace Database\Factories;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationPreferenceFactory extends Factory
{
    protected $model = NotificationPreference::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::inRandomOrder()->value('id'),
            'channels'       => ['late'=>'email','news'=>'in_app'],
            'do_not_disturb' => ['start'=>'22:00:00','end'=>'07:00:00'],
            'mute_until'     => null,
        ];
    }
}
