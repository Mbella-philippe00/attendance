<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;

class ReportController extends BaseApiController
{
    public function index(Request $req)
    {
        $q = Report::query()->select('id','type','status','generated_at','file_url','created_at');
        if ($req->filled('status')) $q->where('status',$req->status);
        if ($req->filled('type'))   $q->where('type',$req->type);

        return $this->paginated($q->latestFirst('generated_at')->paginate(min($req->get('per_page',20),100)), ReportResource::class);
    }

    public function queue(Request $req)
    {
        $data = $req->validate([
            'type'   => ['required','in:attendance_summary,lateness,overtime,absences,custom'],
            'params' => ['nullable','array'],
        ]);
        $report = Report::create([
            'type' => $data['type'],
            'params' => $data['params'] ?? [],
            'generated_by' => auth()->id(),
            'status' => 'queued',
        ]);

        // Ici: dispatch(JobGenerateReport::class, $report->id);
        return $this->ok(new ReportResource($report), 'Queued', 202);
    }

    public function show(string $id)
    {
        return $this->ok(new ReportResource(Report::findOrFail($id)));
    }
}
