<?php

namespace App\Http\Controllers\Api;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationPreferenceResource;

class NotificationPreferenceController extends BaseApiController
{
    public function showMine()
    {
        $pref = NotificationPreference::firstOrCreate(['user_id'=>auth()->id()]);
        return $this->ok(new NotificationPreferenceResource($pref));
    }

    public function updateMine(Request $req)
    {
        $pref = NotificationPreference::firstOrCreate(['user_id'=>auth()->id()]);
        $data = $req->validate([
            'channels'       => ['nullable','array'],
            'do_not_disturb' => ['nullable','array'],
            'mute_until'     => ['nullable','date'],
        ]);
        $pref->update($data);
        return $this->ok(new NotificationPreferenceResource($pref), 'Updated');
    }
}
