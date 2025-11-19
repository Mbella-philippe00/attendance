<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests\Schedules\StoreScheduleRequest;
use App\Http\Requests\Schedules\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends BaseApiController
{
    public function index(Request $req)
    {
        $q = Schedule::query()
            ->with(['user:id,first_name,last_name','site:id,name','shiftTemplate:id,name'])
            ->select('id','user_id','site_id','shift_template_id','date','start_time','end_time','is_remote');

        if ($req->filled('user_id')) $q->forUser($req->user_id);
        if ($req->filled('site_id')) $q->forSite($req->site_id);
        if ($req->filled('month'))   $q->forMonth($req->month);

        return $this->paginated($q->latestFirst('date')->paginate(min($req->get('per_page',20),100)), ScheduleResource::class);
    }

    public function store(StoreScheduleRequest $req)
    {
        $schedule = Schedule::create($req->validated());
        return $this->ok(new ScheduleResource($schedule->load('user:id,first_name,last_name','site:id,name','shiftTemplate:id,name')), 'Created', 201);
    }

    public function show(string $id)
    {
        $schedule = Schedule::with(['user:id,first_name,last_name','site:id,name','shiftTemplate:id,name'])->findOrFail($id);
        return $this->ok(new ScheduleResource($schedule));
    }

    public function update(UpdateScheduleRequest $req, string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($req->validated());
        return $this->ok(new ScheduleResource($schedule->fresh('user:id,first_name,last_name','site:id,name','shiftTemplate:id,name')), 'Updated');
    }

    public function destroy(string $id)
    {
        Schedule::findOrFail($id)->delete();
        return $this->ok(null, 'Deleted', 204);
    }
}
