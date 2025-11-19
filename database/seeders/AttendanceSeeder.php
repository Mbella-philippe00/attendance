<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 90 derniers jours ouvrés par employé (évite chevauchement avec absences futures)
        $start = Carbon::now()->subDays(90)->startOfDay();
        $end   = Carbon::now()->subDay()->startOfDay();

        $users = User::where('role','employee')->pluck('id');

        foreach ($users as $uid) {
            $date = (clone $start);
            while ($date <= $end) {
                if (!in_array($date->dayOfWeekIso, [6,7])) {
                    // 10% de jours sans pointage (absent) ou demi-journée
                    $rand = rand(1,10);
                    $status = $rand <= 7 ? 'present' : ($rand <= 9 ? 'half_day' : 'absent');

                    AttendanceRecord::factory()->create([
                        'user_id' => $uid,
                        'date'    => $date->toDateString(),
                        'status'  => $status,
                    ]);
                }
                $date->addDay();
            }
        }
    }
}
