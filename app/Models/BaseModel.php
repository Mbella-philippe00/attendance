<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UsesUuid;

abstract class BaseModel extends Model
{
    use UsesUuid;

    protected static function boot(): void
    {
        parent::boot();
        static::bootUsesUuid();
    }

    /** Common, small building blocks for performance-friendly queries */
    public function scopeIdIn($query, array $ids)
    {
        if (empty($ids)) return $query;
        return $query->whereIn($this->getTable().'.id', $ids);
    }

    public function scopeSearchLike($query, string $column, ?string $term)
    {
        if (!$term) return $query;
        return $query->where($column, 'like', '%'.$term.'%');
    }

    public function scopeBetweenDates($query, string $column, ?string $start, ?string $end)
    {
        if ($start && $end) return $query->whereBetween($column, [$start, $end]);
        if ($start) return $query->where($column, '>=', $start);
        if ($end) return $query->where($column, '<=', $end);
        return $query;
    }

    public function scopeForSite($query, ?string $siteId)
    {
        return $siteId ? $query->where($this->getTable().'.site_id', $siteId) : $query;
    }

    public function scopeForUser($query, ?string $userId)
    {
        return $userId ? $query->where($this->getTable().'.user_id', $userId) : $query;
    }

    public function scopeLatestFirst($query, string $column = 'created_at')
    {
        return $query->orderByDesc($this->getTable().'.'.$column);
    }
}
