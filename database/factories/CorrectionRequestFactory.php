<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\CorrectionRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CorrectionRequestFactory extends Factory
{
    protected $model = CorrectionRequest::class;

    public function definition(): array
    {
        return [
            'user_id'               => User::inRandomOrder()->value('id'),
            'attendance_record_id'  => AttendanceRecord::inRandomOrder()->value('id'),
            'field'                 => $this->faker->randomElement(['clock_in','clock_out','device']),
            'old_value'             => ['value'=>$this->faker->time('H:i:s')],
            'new_value'             => ['value'=>$this->faker->time('H:i:s')],
            'reason'                => $this->faker->sentence(8),
            'status'                => $this->faker->randomElement(['pending','approved','rejected']),
            'reviewed_by'           => null,
            'reviewed_at'           => null,
        ];
    }
}
