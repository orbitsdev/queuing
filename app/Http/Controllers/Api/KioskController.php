<?php

namespace App\Http\Controllers\Api;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Setting;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Events\QueueStatusChanged;
use App\Http\Controllers\Controller;
use App\Http\Resources\QueueResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Validator;

class KioskController extends Controller
{
    /**
     * Validate branch code and return branch details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkBranch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation error', 422, $validator->errors());
        }

        $branch = Branch::where('code', $request->code)->first();

        if (!$branch) {
            return ApiResponse::error('Branch not found', 404);
        }

        return ApiResponse::success(new BranchResource($branch), 'Branch found');
    }

    /**
     * Get branch information by code
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranch($code)
    {
        $branch = Branch::where('code', $code)->first();

        if (!$branch) {
            return ApiResponse::error('Branch not found', 404);
        }

        return ApiResponse::success(new BranchResource($branch), 'Branch details retrieved');
    }

    /**
     * Get active services for a branch
     *
     * @param string $branchCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServices($branchCode)
    {
        $branch = Branch::where('code', $branchCode)->first();

        if (!$branch) {
            return ApiResponse::error('Branch not found', 404);
        }

        $services = Service::where('branch_id', $branch->id)
            ->orderBy('name')
            ->get();

        return ApiResponse::success(ServiceResource::collection($services), 'Services retrieved');
    }

    /**
     * Create a new queue ticket
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createQueue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_code' => 'required|string',
            'service_id' => 'required|integer|exists:services,id',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation error', 422, $validator->errors());
        }

        // Find branch by code
        $branch = Branch::where('code', $request->branch_code)->first();
        if (!$branch) {
            return ApiResponse::error('Branch not found', 404);
        }

        // Verify service belongs to branch
        $service = Service::where('id', $request->service_id)
            ->where('branch_id', $branch->id)
            ->first();

        if (!$service) {
            return ApiResponse::error('Service not found for this branch', 404);
        }

        // Get branch settings
        $settings = Setting::forBranch($branch);

        // Get today's queue count for this branch
        $todayCount = Queue::where('branch_id', $branch->id)
            ->todayQueues()
            ->count();

        // Calculate next number
        $nextNumber = ($settings->queue_number_base ?? 1) + $todayCount;

        // Format ticket number with prefix
        $prefix = $settings->ticket_prefix ?? 'QUE';
        $formattedTicketNumber = $prefix . $nextNumber;

        // Create queue
        $queue = new Queue();
        $queue->branch_id = $branch->id;
        $queue->service_id = $service->id;
        $queue->number = $nextNumber;
        $queue->ticket_number = $formattedTicketNumber;
        $queue->status = 'waiting';
        $queue->save();

        // Broadcast the queue status change event
        event(new QueueStatusChanged($queue));

        return ApiResponse::success(
            new QueueResource($queue),
            'Queue ticket created successfully'
        );
    }
}
