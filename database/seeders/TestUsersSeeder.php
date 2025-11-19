<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['email'=>'admin@example.com','first_name'=>'Admin','last_name'=>'Root','role'=>'super_admin','employee_id'=>'EMP-0001'],
            ['email'=>'manager@example.com','first_name'=>'Manny','last_name'=>'Ger','role'=>'manager','employee_id'=>'EMP-0002'],
            ['email'=>'user@example.com','first_name'=>'Eve','last_name'=>'Employee','role'=>'employee','employee_id'=>'EMP-0003'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                $u + ['password_hash'=>Hash::make('Password!123'),'is_active'=>true]
            );
        }
    }
}
