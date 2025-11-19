<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','type','os','os_version','app_version','ip_address',
        'user_agent','browser','fingerprint','is_trusted','last_used_at','registered_at'
    ];

    protected $casts = [
        'is_trusted'  => 'boolean',
        'last_used_at'=> 'datetime',
        'registered_at'=> 'datetime',
    ];

    /** RELATIONS */
    public function user() { return $this->belongsTo(User::class); }

    /** SCOPES */
    public function scopeTrusted($q) { return $q->where('is_trusted', true); }
    public function scopeType($q, ?string $type) { return $type ? $q->where('type',$type) : $q; }
}
