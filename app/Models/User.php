<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id','email','password_hash','first_name','last_name','role',
        'department','position','site_id','manager_id','phone','avatar_url',
        'hire_date','contract_type','work_schedule','is_active','last_login_at',
        'created_by','updated_by'
    ];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'hire_date'     => 'date',
        'work_schedule' => 'array',
        'is_active'     => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /** RELATIONS */
    public function site()     { return $this->belongsTo(Site::class); }
    public function manager()  { return $this->belongsTo(User::class, 'manager_id'); }
    public function team()     { return $this->hasMany(User::class, 'manager_id'); }
    public function devices()  { return $this->hasMany(Device::class); }
    public function attendance(){ return $this->hasMany(AttendanceRecord::class); }
    public function schedules(){ return $this->hasMany(Schedule::class); }
    public function absences() { return $this->hasMany(Absence::class); }
    public function notifications() { return $this->hasMany(Notification::class); }

    /** SCOPES */
    public function scopeActive($q)      { return $q->where('is_active', true); }
    public function scopeRole($q, $role) { return $q->where('role', $role); }
    public function scopeInDepartment($q, ?string $dept)
    {
        return $dept ? $q->where('department', $dept) : $q;
    }
    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function($qq) use ($term) {
            $qq->where('first_name','like',"%$term%")
               ->orWhere('last_name','like',"%$term%")
               ->orWhere('email','like',"%$term%")
               ->orWhere('employee_id','like',"%$term%");
        });
    }

    /** HELPERS (KPIs rapides) */
    public function isManager(): bool { return in_array($this->role, ['manager','hr','super_admin']); }
    public function fullName(): string { return "{$this->first_name} {$this->last_name}"; }
}
use Laravel\Sanctum\HasApiTokens;

