<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name','code','address','city','postal_code','country',
        'latitude','longitude','geofence','opening_time','closing_time',
        'is_active','created_by','updated_by'
    ];

    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'geofence'   => 'array',
        'is_active'  => 'boolean',
        'opening_time' => 'datetime:H:i:s',
        'closing_time' => 'datetime:H:i:s',
    ];

    /** RELATIONS */
    public function users()   { return $this->hasMany(User::class); }
    public function shifts()  { return $this->hasMany(ShiftTemplate::class); }
    public function schedules() { return $this->hasMany(Schedule::class); }

    /** SCOPES */
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeCode($q, string $code) { return $q->where('code', $code); }
}
