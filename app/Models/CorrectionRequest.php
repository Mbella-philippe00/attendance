<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CorrectionRequest extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','attendance_record_id','field','old_value','new_value','reason',
        'status','reviewed_by','reviewed_at'
    ];

    protected $casts = [
        'old_value'  => 'array',
        'new_value'  => 'array',
        'reviewed_at'=> 'datetime',
    ];

    /** RELATIONS */
    public function user()      { return $this->belongsTo(User::class); }
    public function record()    { return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id'); }
    public function reviewer()  { return $this->belongsTo(User::class, 'reviewed_by'); }

    /** SCOPES */
    public function scopePending($q)  { return $q->where('status','pending'); }
    public function scopeApproved($q) { return $q->where('status','approved'); }
    public function scopeRejected($q) { return $q->where('status','rejected'); }
}
