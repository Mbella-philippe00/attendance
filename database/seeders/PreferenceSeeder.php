<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function ($u) {
            NotificationPreference::factory()->create(['user_id' => $u->id]);
        });
    }
}
