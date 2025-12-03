<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\Departments\StoreDepartmentRequest;
use App\Http\Requests\Departments\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $query = Department::query()
            ->withCount('users')
            ->with('manager:id,first_name,last_name,email');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $perPage = $request->input('per_page', 15);
        $departments = $query->latest()->paginate($perPage);

        return DepartmentResource::collection($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());

        return new DepartmentResource($department->load('manager'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $this->authorize('view', $department);

        return new DepartmentResource(
            $department->load('manager')->loadCount('users')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return new DepartmentResource(
            $department->fresh()->load('manager')->loadCount('users')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        // Prevent deletion if department has users
        if ($department->users()->exists()) {
            return response()->json([
                'message' => 'Cannot delete department with associated users. Please reassign users first.'
            ], 422);
        }

        $department->delete();

        return response()->noContent();
    }

    /**
     * Get all active departments for dropdown/select
     */
    public function dropdown()
    {
        $this->authorize('viewAny', Department::class);

        return Department::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}