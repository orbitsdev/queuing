<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        
        // Ensure any boolean fields are properly cast
        if (isset($data['is_priority'])) {
            $data['is_priority'] = (bool)$data['is_priority'];
        }
        
        // Format created_at timestamp in a human-readable format
        if (isset($data['created_at'])) {
            $data['formatted_date'] = $this->created_at->format('M d, Y'); // Jun 20, 2025
            $data['formatted_time'] = $this->created_at->format('h:i A'); // 02:43 PM
            $data['formatted_datetime'] = $this->created_at->format('M d, Y h:i A'); // Jun 20, 2025 02:43 PM
        }
        
        return array_merge($data, [
            'service' => new ServiceResource($this->whenLoaded('service')),
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'counter' => new CounterResource($this->whenLoaded('counter')),
        ]);
    }
}
