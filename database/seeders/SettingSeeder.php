<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key'=>'workday.start','value'=>['08:00'],'category'=>'hr','is_public'=>true],
            ['key'=>'workday.end','value'=>['17:00'],'category'=>'hr','is_public'=>true],
            ['key'=>'break.minutes','value'=>['60'],'category'=>'hr','is_public'=>true],
            ['key'=>'notify.late','value'=>['enabled'=>true],'category'=>'security','is_public'=>false],
        ];

        foreach ($defaults as $d) {
            SystemSetting::updateOrCreate(['key'=>$d['key']], $d);
        }
    }
}
