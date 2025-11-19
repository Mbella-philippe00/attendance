<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','type','start_date','end_date','working_days','reason',
        'attachment_url','status','approved_by','approved_at'
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'working_days' => 'integer',
        'approved_at'  => 'datetime',
    ];

    /** RELATIONS */
    public function user()     { return $this->belongsTo(User::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }

    /** SCOPES */
    public function scopePending($q)  { return $q->where('status','pending'); }
    public function scopeApproved($q) { return $q->where('status','approved'); }
    public function scopeOverlapping($q, string $start, string $end)
    {
        // (start <= end_date) AND (end >= start_date)
        return $q->where('start_date','<=',$end)->where('end_date','>=',$start);
    }
}
