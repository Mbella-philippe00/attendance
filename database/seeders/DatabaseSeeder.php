<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteSeeder::class,
            DepartmentSeeder::class, // Add DepartmentSeeder here
            UserSeeder::class,       // UserSeeder should come after DepartmentSeeder
            DeviceSeeder::class,
            ShiftTemplateSeeder::class,
            ScheduleSeeder::class,
            AttendanceSeeder::class,
            AbsenceSeeder::class,
            CorrectionSeeder::class,
            NotificationSeeder::class,
            PreferenceSeeder::class,
            ReportSeeder::class,
            SettingSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
