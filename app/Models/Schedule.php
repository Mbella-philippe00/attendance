<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Schedule extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','site_id','shift_template_id','date','start_time','end_time','is_remote','notes'
    ];

    protected $casts = [
        'date'       => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time'   => 'datetime:H:i:s',
        'is_remote'  => 'boolean',
    ];

    /** RELATIONS */
    public function user()         { return $this->belongsTo(User::class); }
    public function site()         { return $this->belongsTo(Site::class); }
    public function shiftTemplate() { return $this->belongsTo(ShiftTemplate::class); }

    /** SCOPES */
    public function scopeOnDate($q, $date) { return $q->whereDate('date', $date); }
    public function scopeForMonth($q, Carbon|string $month) // '2025-10'
    {
        $start = Carbon::parse($month.'-01')->startOfMonth();
        $end   = (clone $start)->endOfMonth();
        return $q->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
    }
}
