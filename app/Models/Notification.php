<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','type','channel','payload','read_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'read_at' => 'datetime',
    ];

    /** RELATIONS */
    public function user() { return $this->belongsTo(User::class); }

    /** SCOPES */
    public function scopeUnread($q)          { return $q->whereNull('read_at'); }
    public function scopeChannel($q, $c)     { return $c ? $q->where('channel',$c) : $q; }
    public function scopeTypeIs($q, $type)   { return $type ? $q->where('type',$type) : $q; }

    /** HELPERS */
    public function markRead(): void
    {
        if (!$this->read_at) {
            $this->read_at = now();
            $this->save();
        }
    }
}
