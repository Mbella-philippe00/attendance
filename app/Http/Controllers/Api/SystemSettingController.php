<?php

namespace App\Http\Controllers\Api;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\UpdateSystemSettingRequest;
use App\Http\Resources\SystemSettingResource;

class SystemSettingController extends BaseApiController
{
    public function index(Request $req)
    {
        $q = SystemSetting::query()->select('id','key','category','is_public','updated_at');
        if ($req->filled('category')) $q->category($req->category);

        return $this->paginated($q->orderBy('key')->paginate(min($req->get('per_page',50),100)), SystemSettingResource::class);
    }

    public function showByKey(string $key)
    {
        $setting = SystemSetting::query()->where('key',$key)->firstOrFail();
        $this->authorize('view', $setting);
        return $this->ok(new SystemSettingResource($setting));
    }

    public function updateByKey(UpdateSystemSettingRequest $req, string $key)
    {
        $setting = SystemSetting::query()->where('key',$key)->firstOrFail();
        $this->authorize('update', $setting);
        $setting->fill($req->validated());
        $setting->updated_by = auth()->id();
        $setting->updated_at = now();
        $setting->save();

        return $this->ok(new SystemSettingResource($setting), 'Updated');
    }
}
