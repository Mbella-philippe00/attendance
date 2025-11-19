<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','action','entity_type','entity_id','old_values','new_values',
        'ip_address','user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /** RELATIONS */
    public function user() { return $this->belongsTo(User::class); }

    /** SCOPES */
    public function scopeAction($q, ?string $action)
    {
        return $action ? $q->where('action', $action) : $q;
    }

    public function scopeOnEntity($q, ?string $type, ?string $id = null)
    {
        if ($type) $q->where('entity_type', $type);
        if ($id)   $q->where('entity_id', $id);
        return $q;
    }
}
