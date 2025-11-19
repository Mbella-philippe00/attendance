<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use App\Models\ShiftTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $templates = ShiftTemplate::all();
        if ($templates->isEmpty()) return;

        // Génère des plannings sur les 30 prochains jours pour les employés
        $start = Carbon::now()->startOfDay();
        $end   = (clone $start)->addDays(30);

        $users = User::where('role','employee')->pluck('id');
        foreach ($users as $uid) {
            $date = (clone $start);
            while ($date <= $end) {
                if (!in_array($date->dayOfWeekIso, [6,7])) { // Lundi..Vendredi
                    $tpl = $templates->random();
                    Schedule::factory()->create([
                        'user_id' => $uid,
                        'site_id' => $tpl->site_id,
                        'shift_template_id' => $tpl->id,
                        'date' => $date->toDateString(),
                        'start_time' => $tpl->start_time,
                        'end_time'   => $tpl->end_time,
                        'is_remote'  => false,
                    ]);
                }
                $date->addDay();
            }
        }
    }
}
