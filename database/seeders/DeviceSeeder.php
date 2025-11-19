<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        // 1-2 devices par user
        User::all()->each(function ($u) {
            Device::factory()->count(rand(1,2))->create(['user_id'=>$u->id]);
        });
    }
}
