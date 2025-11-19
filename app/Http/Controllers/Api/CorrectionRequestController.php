<?php

namespace App\Http\Controllers\Api;

use App\Models\CorrectionRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Corrections\StoreCorrectionRequest;
use App\Http\Requests\Corrections\ReviewCorrectionRequest;
use App\Http\Resources\CorrectionRequestResource;

class CorrectionRequestController extends BaseApiController
{
    public function index(Request $req)
    {
        $q = CorrectionRequest::query()
            ->with(['user:id,first_name,last_name','record:id,user_id,date,status'])
            ->select('id','user_id','attendance_record_id','field','status','reviewed_by','reviewed_at','created_at');

        if ($req->filled('status')) $q->where('status',$req->status);
        if ($req->filled('user_id')) $q->forUser($req->user_id);

        return $this->paginated($q->latestFirst()->paginate(min($req->get('per_page',20),100)), CorrectionRequestResource::class);
    }

    public function show(string $id)
    {
        $cr = CorrectionRequest::with(['user:id,first_name,last_name','record'])->findOrFail($id);
        return $this->ok(new CorrectionRequestResource($cr));
    }

    public function store(StoreCorrectionRequest $req)
    {
        $cr = CorrectionRequest::create($req->validated());
        return $this->ok(new CorrectionRequestResource($cr->load('user:id,first_name,last_name','record')), 'Created', 201);
    }

    public function update(ReviewCorrectionRequest $req, string $id)
    {
        $cr = CorrectionRequest::findOrFail($id);
        $cr->fill($req->validated());
        if ($req->filled('status') && in_array($cr->status, ['approved','rejected'])) {
            $cr->reviewed_by = $req->user()->id;
            $cr->reviewed_at = now();
        }
        $cr->save();

        return $this->ok(new CorrectionRequestResource($cr->fresh('user:id,first_name,last_name','record')), 'Updated');
    }
}
