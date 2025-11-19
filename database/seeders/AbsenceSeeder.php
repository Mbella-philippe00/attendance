<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    public function run(): void
    {
        // 1 absence (courte) pour 25% des employés
        $users = User::where('role','employee')->inRandomOrder()->take(
            (int) ceil(User::where('role','employee')->count() * 0.25)
        )->get();

        foreach ($users as $u) {
            Absence::factory()->create(['user_id' => $u->id]);
        }
    }
}
