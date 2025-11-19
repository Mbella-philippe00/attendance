<?php

namespace App\Http\Controllers\Api;

use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Requests\Sites\StoreSiteRequest;
use App\Http\Requests\Sites\UpdateSiteRequest;
use App\Http\Resources\SiteResource;

class SiteController extends BaseApiController
{
    public function index(Request $req)
    {
        $q = Site::query()->select('id','name','code','city','country','is_active');
        if ($req->boolean('active')) $q->active();
        if ($req->filled('code')) $q->code($req->code);

        return $this->paginated($q->orderBy('name')->paginate(min($req->get('per_page',50),100)), SiteResource::class);
    }

    public function store(StoreSiteRequest $req)
    {
        $site = Site::create($req->validated());
        return $this->ok(new SiteResource($site), 'Created', 201);
    }

    public function show(string $id)
    {
        $site = Site::findOrFail($id);
        return $this->ok(new SiteResource($site));
    }

    public function update(UpdateSiteRequest $req, string $id)
    {
        $site = Site::findOrFail($id);
        $site->update($req->validated());
        return $this->ok(new SiteResource($site), 'Updated');
    }

    public function destroy(string $id)
    {
        $site = Site::findOrFail($id);
        $site->delete();
        return $this->ok(null, 'Deleted', 204);
    }
}
