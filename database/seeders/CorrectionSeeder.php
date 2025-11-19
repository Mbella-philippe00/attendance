<?php

namespace Database\Seeders;

use App\Models\CorrectionRequest;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class CorrectionSeeder extends Seeder
{
    public function run(): void
    {
        // 50 corrections aléatoires
        if (AttendanceRecord::count() === 0) return;

        CorrectionRequest::factory()->count(50)->create([
            'user_id' => User::inRandomOrder()->value('id'),
            'attendance_record_id' => AttendanceRecord::inRandomOrder()->value('id'),
        ]);
    }
}
