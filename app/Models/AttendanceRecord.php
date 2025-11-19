<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRecord extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id','site_id','date','clock_in','clock_out','break_start','break_end',
        'total_hours','break_duration','work_duration','status',
        'location_clock_in','location_clock_out',
        'ip_address_clock_in','ip_address_clock_out',
        'device_id_clock_in','device_id_clock_out',
        'notes','is_validated','validated_by','validated_at'
    ];

    protected $casts = [
        'date'                 => 'date',
        'clock_in'             => 'datetime',
        'clock_out'            => 'datetime',
        'break_start'          => 'datetime',
        'break_end'            => 'datetime',
        'total_hours'          => 'decimal:2',
        'break_duration'       => 'integer',
        'work_duration'        => 'integer',
        'location_clock_in'    => 'array',
        'location_clock_out'   => 'array',
        'is_validated'         => 'boolean',
        'validated_at'         => 'datetime',
    ];

    /** RELATIONS */
    public function user()       { return $this->belongsTo(User::class); }
    public function site()       { return $this->belongsTo(Site::class); }
    public function deviceIn()   { return $this->belongsTo(Device::class, 'device_id_clock_in'); }
    public function deviceOut()  { return $this->belongsTo(Device::class, 'device_id_clock_out'); }
    public function validator()  { return $this->belongsTo(User::class, 'validated_by'); }

    /** SCOPES hyper-fréquents */
    public function scopeDay(Builder $q, $date)   { return $q->whereDate('date', $date); }
    public function scopeMonth(Builder $q, $ym)   // '2025-10'
    {
        [$y,$m] = explode('-', $ym);
        return $q->whereYear('date',$y)->whereMonth('date',$m);
    }
    public function scopeStatus(Builder $q, ?string $status)
    {
        return $status ? $q->where('status',$status) : $q;
    }
    public function scopeValidated(Builder $q, bool $yes = true)
    {
        return $q->where('is_validated', $yes);
    }
    public function scopeLate(Builder $q) { return $q->where('status','late'); }

    /** AGRÉGATIONS/KPIs PERFS */
    public static function sumWorkMinutesForUser(string $userId, string $start, string $end): int
    {
        return (int) static::query()
            ->forUser($userId)
            ->betweenDates('date', $start, $end)
            ->sum('work_duration');
    }

    public static function dailyCountByStatus(string $start, string $end, ?string $siteId = null)
    {
        return static::query()
            ->selectRaw('date, status, COUNT(*) as c')
            ->forSite($siteId)
            ->betweenDates('date', $start, $end)
            ->groupBy('date','status')
            ->orderBy('date')
            ->get();
    }

    /** HELPERS métier */
    public function computeWorkDurationMinutes(): ?int
    {
        if (!$this->clock_in || !$this->clock_out) return null;
        $clockIn  = Carbon::parse($this->clock_in);
        $clockOut = Carbon::parse($this->clock_out);
        $minutes  = $clockIn->diffInMinutes($clockOut) - (int)($this->break_duration ?? 0);
        return max(0, $minutes);
    }

    public function markValidated(string $managerId): void
    {
        $this->is_validated = true;
        $this->validated_by = $managerId;
        $this->validated_at = now();
        $this->save();
    }
}
