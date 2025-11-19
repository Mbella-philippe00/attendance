<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $first = $this->faker->firstName();
        $last  = $this->faker->lastName();

        return [
            'employee_id'  => strtoupper($this->faker->bothify('EMP-####')),
            'email'        => strtolower($first.'.'.$last).$this->faker->numberBetween(1,999).'@example.com',
            'password_hash'=> Hash::make('Password!123'),
            'first_name'   => $first,
            'last_name'    => $last,
            'role'         => $this->faker->randomElement(['employee','employee','employee','manager']),
            'department'   => $this->faker->randomElement(['IT','HR','Finance','Ops','QA']),
            'position'     => $this->faker->jobTitle(),
            'site_id'      => Site::inRandomOrder()->value('id'),
            'manager_id'   => null, // on l’assigne dans le seeder pour cohérence
            'phone'        => $this->faker->e164PhoneNumber(),
            'avatar_url'   => null,
            'hire_date'    => $this->faker->dateTimeBetween('-3 years','-1 month')->format('Y-m-d'),
            'contract_type'=> $this->faker->randomElement(['CDI','CDD','Stage']),
            'work_schedule'=> ['start'=>'08:00','end'=>'17:00','break'=>60],
            'is_active'    => true,
            'last_login_at'=> $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function role(string $role)
    {
        return $this->state(fn() => ['role' => $role]);
    }

    public function inactive()
    {
        return $this->state(fn() => ['is_active' => false]);
    }
}
