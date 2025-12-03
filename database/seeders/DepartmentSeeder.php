<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all available managers (users with manager role)
        $managers = User::whereIn('role', ['manager', 'hr', 'super_admin'])->get();
        
        // If no managers found, get any available user
        if ($managers->isEmpty()) {
            $managers = User::all();
        }
        
        $departments = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'Human Resources',
                'description' => 'Responsible for hiring, training, and employee relations.',
                'budget' => 1500000.00,
                'cost_center' => 'HR-001',
                'is_active' => true,
                'manager_id' => $managers->isNotEmpty() ? $managers->random()->id : null,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Information Technology',
                'description' => 'Manages the company\'s technical infrastructure and systems.',
                'budget' => 2500000.00,
                'cost_center' => 'IT-001',
                'is_active' => true,
                'manager_id' => $managers->isNotEmpty() ? $managers->random()->id : null,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Finance',
                'description' => 'Handles financial planning, accounting, and financial reporting.',
                'budget' => 1200000.00,
                'cost_center' => 'FIN-001',
                'is_active' => true,
                'manager_id' => $managers->isNotEmpty() ? $managers->random()->id : null,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Marketing',
                'description' => 'Responsible for brand management, advertising, and customer acquisition.',
                'budget' => 1800000.00,
                'cost_center' => 'MKT-001',
                'is_active' => true,
                'manager_id' => $managers->isNotEmpty() ? $managers->random()->id : null,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Operations',
                'description' => 'Oversees daily business activities and operational processes.',
                'budget' => 2000000.00,
                'cost_center' => 'OPS-001',
                'is_active' => true,
                'manager_id' => $managers->isNotEmpty() ? $managers->random()->id : null,
            ],
        ];

        foreach ($departments as $departmentData) {
            $departmentData['slug'] = Str::slug($departmentData['name']);
            Department::create($departmentData);
        }
    }
}
