<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        
        // Ensure boolean fields are properly cast
        if (isset($data['is_active'])) {
            $data['is_active'] = (bool)$data['is_active'];
        }
        
        return array_merge($data, [
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'waiting_count' => $this->queues()->where('status', 'waiting')->todayQueues()->count(),
        ]);
    }
}
