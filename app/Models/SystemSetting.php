<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemSetting extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'key','value','description','category','is_public','updated_by','updated_at'
    ];

    protected $casts = [
        'value'      => 'array',
        'is_public'  => 'boolean',
        'updated_at' => 'datetime',
    ];

    /** RELATIONS */
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }

    /** SCOPES */
    public function scopeKeyIs($q, string $key) { return $q->where('key', $key); }
    public function scopeCategory($q, ?string $cat)
    {
        return $cat ? $q->where('category', $cat) : $q;
    }

    /** HELPERS */
    public static function getValue(string $key, $default = null)
    {
        return optional(static::query()->keyIs($key)->first())->value ?? $default;
    }
}
