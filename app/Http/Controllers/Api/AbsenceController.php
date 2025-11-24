<?php

namespace App\Http\Controllers\Api;

use App\Models\Absence;
use Illuminate\Http\Request;
use App\Http\Requests\Absences\StoreAbsenceRequest;
use App\Http\Requests\Absences\UpdateAbsenceRequest;
use App\Http\Resources\AbsenceResource;

class AbsenceController extends BaseApiController
{
    public function index(Request $req)
    {
        \Log::info('User role: ' . auth()->user()->role);
        $this->authorize('viewAny', Absence::class);
        return $this->ok(Absence::all());

        $q = Absence::query()
            ->with(['user:id,first_name,last_name'])
            ->select(['id','user_id','type','start_date','end_date','status','working_days']);

        if ($req->filled('user_id')) $q->forUser($req->user_id);
        if ($req->filled('status'))  $q->where('status', $req->status);
        if ($req->filled('start') || $req->filled('end')) {
            $q->betweenDates('start_date', $req->get('start'), $req->get('end'));
        }

        $paginator = $q->latestFirst('start_date')->paginate(min($req->get('per_page',20),100));
        return $this->paginated($paginator, AbsenceResource::class);
    }

    public function store(StoreAbsenceRequest $req)
    {
        // return $req;
        $this->authorize('create', Absence::class);

        // empêcher chevauchement sur la même période
        $overlap = Absence::query()
            ->forUser($req->user_id)
            ->overlapping($req->start_date, $req->end_date)
            ->exists();

        if ($overlap) {
            return response()->json(['message' => 'Overlap with existing absence.'], 422);
        }

        $absence = Absence::create($req->validated());
        return $this->ok(new AbsenceResource($absence->load('user:id,first_name,last_name')), 'Created', 201);
    }

    public function show(string $id)
    {
        $absence = Absence::with('user:id,first_name,last_name')->findOrFail($id);
        $this->authorize('view', $absence);
        return $this->ok(new AbsenceResource($absence));
    }

    public function update(UpdateAbsenceRequest $req, string $id)
    {
        $absence = Absence::findOrFail($id);
        $this->authorize('update', $absence);
        $absence->update($req->validated());

        return $this->ok(new AbsenceResource($absence->fresh('user:id,first_name,last_name')), 'Updated');
    }

    public function destroy(string $id)
    {
        $absence = Absence::findOrFail($id);
        $this->authorize('delete', $absence);
        $absence->delete();
        return $this->ok(null, 'Deleted', 204);
    }
}
