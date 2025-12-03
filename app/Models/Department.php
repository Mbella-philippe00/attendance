<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'is_active',
        'budget',
        'cost_center',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'budget' => 'decimal:2',
    ];

    /**
     * Get the users that belong to the department.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the manager of the department.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
