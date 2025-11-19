<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super admin + HR + managers + employees
        $super = User::factory()->role('super_admin')->create([
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'Root',
        ]);

        $hr = User::factory()->role('hr')->create([
            'email' => 'hr@example.com',
            'first_name' => 'Hector',
            'last_name'  => 'Ressources',
        ]);

        $managers = User::factory()->count(3)->role('manager')->create();

        // 30 employés
        $employees = User::factory()->count(30)->role('employee')->create();

        // Assigner un manager à chaque employé (répartition simple)
        $mIds = $managers->pluck('id')->all();
        $i = 0;
        foreach ($employees as $emp) {
            $emp->manager_id = $mIds[$i % count($mIds)];
            $emp->save();
            $i++;
        }
    }
}
