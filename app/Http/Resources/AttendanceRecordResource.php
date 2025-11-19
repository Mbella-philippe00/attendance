<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceRecordResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'user'          => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->fullName(),
                'site_id' => $this->user->site_id,
            ]),
            'site'          => $this->whenLoaded('site', fn() => [
                'id' => $this->site->id,
                'name' => $this->site->name,
            ]),
            'date'          => $this->date?->toDateString(),
            'status'        => $this->status,
            'clock_in'      => $this->clock_in?->toISOString(),
            'clock_out'     => $this->clock_out?->toISOString(),
            'break_start'   => $this->break_start?->toISOString(),
            'break_end'     => $this->break_end?->toISOString(),
            'work_duration' => $this->work_duration,  // minutes
            'break_duration'=> $this->break_duration, // minutes
            'is_validated'  => (bool) $this->is_validated,
            'validated_at'  => $this->validated_at?->toISOString(),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
        ];
    }
}
