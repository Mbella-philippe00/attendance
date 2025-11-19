<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'type','params','generated_by','generated_at','file_url','status','error_message'
    ];

    protected $casts = [
        'params'       => 'array',
        'generated_at' => 'datetime',
    ];

    /** RELATIONS */
    public function author() { return $this->belongsTo(User::class, 'generated_by'); }

    /** SCOPES */
    public function scopeQueued($q)  { return $q->where('status','queued'); }
    public function scopeRunning($q) { return $q->where('status','running'); }
    public function scopeDone($q)    { return $q->where('status','done'); }
    public function scopeFailed($q)  { return $q->where('status','failed'); }
}
