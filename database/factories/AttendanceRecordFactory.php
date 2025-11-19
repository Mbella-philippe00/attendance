<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\Device;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition(): array
    {
        $clockIn  = $this->faker->dateTimeBetween('08:00:00','09:30:00');
        $clockOut = (clone $clockIn);
        $clockOut->modify('+8 hours');
        $break    = $this->faker->randomElement([30,45,60]);

        $work = max(0, 8*60 - $break);

        return [
            'user_id'    => User::inRandomOrder()->value('id'),
            'site_id'    => Site::inRandomOrder()->value('id'),
            'date'       => $this->faker->dateTimeBetween('-90 days','-1 day')->format('Y-m-d'),
            'clock_in'   => $clockIn,
            'clock_out'  => $clockOut,
            'break_start'=> null,
            'break_end'  => null,
            'total_hours'=> round($work / 60, 2),
            'break_duration'=> $break,
            'work_duration' => $work,
            'status'     => $this->faker->randomElement(['present','present','present','late','half_day']),
            'location_clock_in'  => ['lat'=>$this->faker->latitude(),'lng'=>$this->faker->longitude()],
            'location_clock_out' => ['lat'=>$this->faker->latitude(),'lng'=>$this->faker->longitude()],
            'ip_address_clock_in' => $this->faker->ipv4(),
            'ip_address_clock_out'=> $this->faker->ipv4(),
            'device_id_clock_in'  => Device::inRandomOrder()->value('id'),
            'device_id_clock_out' => Device::inRandomOrder()->value('id'),
            'notes'       => null,
            'is_validated'=> $this->faker->boolean(70),
            'validated_by'=> null,
            'validated_at'=> null,
        ];
    }
}
