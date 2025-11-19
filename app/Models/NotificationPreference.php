<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPreference extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id','channels','do_not_disturb','mute_until'];

    protected $casts = [
        'channels'       => 'array',
        'do_not_disturb' => 'array',
        'mute_until'     => 'datetime',
    ];

    /** RELATIONS */
    public function user() { return $this->belongsTo(User::class); }

    /** HELPERS */
    public function isMutedNow(): bool
    {
        if ($this->mute_until && now()->lessThan($this->mute_until)) return true;

        $dnd = $this->do_not_disturb ?? null;
        if (!$dnd || empty($dnd['start']) || empty($dnd['end'])) return false;

        $now  = now()->format('H:i:s');
        $from = $dnd['start'];
        $to   = $dnd['end'];

        // handle overnight windows (ex: 22:00 -> 07:00)
        return $from <= $to
            ? ($now >= $from && $now <= $to)
            : ($now >= $from || $now <= $to);
    }
}
