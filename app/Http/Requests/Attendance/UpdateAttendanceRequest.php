<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'site_id'   => ['nullable','uuid','exists:sites,id'],
            'clock_in'  => ['nullable','date'],
            'clock_out' => ['nullable','date','after_or_equal:clock_in'],
            'break_start' => ['nullable','date'],
            'break_end'   => ['nullable','date','after_or_equal:break_start'],
            'break_duration' => ['nullable','integer','min:0'],
            'status'    => ['nullable','in:present,absent,late,half_day,remote'],
            'location_clock_in'  => ['nullable','array'],
            'location_clock_out' => ['nullable','array'],
            'ip_address_clock_in'  => ['nullable','string','max:45'],
            'ip_address_clock_out' => ['nullable','string','max:45'],
            'device_id_clock_in'  => ['nullable','uuid','exists:devices,id'],
            'device_id_clock_out' => ['nullable','uuid','exists:devices,id'],
            'notes'     => ['nullable','string'],
        ];
    }
}
