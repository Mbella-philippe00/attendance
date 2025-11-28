<?php

namespace App\Http\Controllers\Api;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Resources\AttendanceRecordResource;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;

class AttendanceRecordController extends BaseApiController
{
    public function index(Request $req)
    {
        $this->authorize('viewAny', AttendanceRecord::class);

        $q = AttendanceRecord::query()
            ->with([
                'user:id,first_name,last_name,site_id',
                'site:id,name'
            ])
            ->select([
                'id','user_id','site_id','date','status','clock_in','clock_out',
                'work_duration','break_duration','is_validated','validated_at'
            ]);

        // Filtres perfs
        $q->forUser($req->user_id ?? null)
          ->forSite($req->site_id ?? null)
          ->status($req->status ?? null);

        if ($req->filled('start') || $req->filled('end')) {
            $q->betweenDates('date', $req->get('start'), $req->get('end'));
        }
        if ($req->boolean('validated')) {
            $q->validated(true);
        }

        $q->latestFirst('date');

        $perPage = min(max((int) $req->get('per_page', 20), 1), 100);
        $paginator = $q->paginate($perPage)->appends($req->query());

        return $this->paginated($paginator, AttendanceRecordResource::class);
    }

    public function store(StoreAttendanceRequest $req)
    {
        $this->authorize('create', AttendanceRecord::class);

        $data = $req->validated();
        $record = AttendanceRecord::create($data);

        // calc métier rapide si fourni
        if (is_null($record->work_duration)) {
            $record->work_duration = $record->computeWorkDurationMinutes();
            $record->save();
        }

        return $this->ok(new AttendanceRecordResource($record->load('user:id,first_name,last_name','site:id,name')), 'Created', 201);
    }

    public function show(string $id)
    {
        $record = AttendanceRecord::with(['user:id,first_name,last_name,site_id','site:id,name'])->findOrFail($id);
        $this->authorize('view', $record);

        return $this->ok(new AttendanceRecordResource($record));
    }

    public function update(UpdateAttendanceRequest $req, string $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        $this->authorize('update', $record);

        $record->fill($req->validated());

        // recalcul si nécessaire
        if ($record->isDirty(['clock_in','clock_out','break_duration'])) {
            $record->work_duration = $record->computeWorkDurationMinutes();
        }

        $record->save();

        return $this->ok(new AttendanceRecordResource($record->fresh('user:id,first_name,last_name','site:id,name')), 'Updated');
    }

    public function destroy(string $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        $this->authorize('delete', $record);
        $record->delete();

        return $this->ok(null, 'Deleted', 204);
    }

    
    /** Action métier: validation manager */
    public function validateRecord(Request $req, string $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        $this->authorize('validate', $record);

        $record->markValidated($req->user()->id);

        return $this->ok(new AttendanceRecordResource($record->fresh()), 'Validated');
    }

    /** Stats journalières groupées par statut */
    public function dailyStats(Request $req)
    {
        $this->authorize('viewAny', AttendanceRecord::class);

        $req->validate([
            'start' => ['required','date'],
            'end'   => ['required','date','after_or_equal:start'],
            'site_id' => ['nullable','uuid'],
        ]);

        $rows = AttendanceRecord::dailyCountByStatus($req->start, $req->end, $req->site_id);

        return $this->ok($rows);
    }
}
