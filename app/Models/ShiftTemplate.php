<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftTemplate extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name','site_id','start_time','end_time','break_minutes','days_of_week','is_active'
    ];

    protected $casts = [
        'start_time'   => 'datetime:H:i:s',
        'end_time'     => 'datetime:H:i:s',
        'break_minutes'=> 'integer',
        'days_of_week' => 'array',
        'is_active'    => 'boolean',
    ];

    /** RELATIONS */
    public function site()     { return $this->belongsTo(Site::class); }
    public function schedules(){ return $this->hasMany(Schedule::class); }

    /** SCOPES */
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeForWeekday($q, int $dow) // 1..7
    {
        return $q->whereJsonContains('days_of_week', $dow);
    }
}
