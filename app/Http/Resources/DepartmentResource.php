<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'manager' => $this->whenLoaded('manager', function () {
                return [
                    'id' => $this->manager_id,
                    'name' => $this->manager ? $this->manager->name : null,
                ];
            }),
            'budget' => $this->budget,
            'cost_center' => $this->cost_center,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employees_count' => $this->whenCounted('users'),
        ];
    }
}