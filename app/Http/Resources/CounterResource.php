<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => (bool)($this->is_active ?? false),
            'is_priority' => (bool)($this->is_priority ?? false),
            'branch_id' => $this->branch_id,
            'status' => $this->status ?? 'inactive',
            'break_message' => $this->break_message,
        ];
    }
}
