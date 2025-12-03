<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends BaseApiController
{
    public function index(Request $req)
    {
        $this->authorize('viewAny', User::class);

        $q = User::query()
            ->with('site:id,name')
            ->select('id','employee_id','first_name','last_name','email','role','department','site_id','is_active');

        $q->search($req->get('q'))
          ->inDepartment($req->get('department'));

        if ($req->filled('role')) $q->role($req->role);
        if ($req->filled('site_id')) $q->where('site_id', $req->site_id);

        $perPage = min((int) $req->get('per_page', 20), 100);
        return $this->paginated($q->orderBy('last_name')->paginate($perPage), UserResource::class);
    }

    // public function store(StoreUserRequest $req)
    // {
    //     $this->authorize('create', User::class);
    //     $user = User::create($req->validated());
    //     return $user;
    //     // return $this->ok(new UserResource($user->load('site:id,name')), 'Created', 201);
    // }

    public function store(StoreUserRequest $request)
    {
        try {
            // Get validated data
            $validated = $request->validated();
            
            // Generate a unique employee_id using UUID
            $validated['employee_id'] = (string) \Illuminate\Support\Str::uuid();
            $validated['password_hash'] = hash('sha256', $validated['password']);
            // Log the input for debugging
            \Log::info('Creating user with data:', $validated);
            
            // Create the user with the generated employee_id
            $user = User::create($validated);
            
            // Log the created user
            \Log::info('User created successfully:', $user->toArray());
            
            // Return the created user with proper resource
            return new UserResource($user->load('site:id,name'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $user = User::with('site:id,name')->findOrFail($id);
        $this->authorize('view', $user);
        return $this->ok(new UserResource($user));
    }

    public function update(UpdateUserRequest $req, string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $user->update($req->validated());
        return $this->ok(new UserResource($user->fresh('site:id,name')), 'Updated');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return $this->ok(null, 'Deleted', 204);
    }
}
