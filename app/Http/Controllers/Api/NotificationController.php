<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;

class NotificationController extends BaseApiController
{
    public function index(Request $req)
    {
        $q = Notification::query()->forUser($req->user()->id);
        if ($req->filled('channel')) $q->channel($req->channel);
        if ($req->boolean('unread')) $q->unread();

        return $this->paginated($q->latestFirst()->paginate(min($req->get('per_page',20),100)), NotificationResource::class);
    }

    public function markRead(string $id)
    {
        $n = Notification::where('user_id', auth()->id())->findOrFail($id);
        $n->markRead();
        return $this->ok(new NotificationResource($n), 'Marked as read');
    }
}
