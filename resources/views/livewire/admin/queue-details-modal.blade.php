<div class="space-y-6">
    <!-- Queue Information -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Queue Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about the queue ticket.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->number }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Ticket Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->ticket_number }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($queue->status == 'waiting') bg-blue-100 text-blue-800
                            @elseif($queue->status == 'called') bg-yellow-100 text-yellow-800
                            @elseif($queue->status == 'serving') bg-green-100 text-green-800
                            @elseif($queue->status == 'held') bg-orange-100 text-orange-800
                            @elseif($queue->status == 'completed') bg-green-100 text-green-800
                            @elseif($queue->status == 'skipped') bg-red-100 text-red-800
                            @elseif($queue->status == 'cancelled') bg-gray-100 text-gray-800
                            @elseif($queue->status == 'expired') bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($queue->status) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Branch</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->branch->name ?? 'N/A' }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Service</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->service->name ?? 'N/A' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Counter</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->counter->name ?? 'Not assigned' }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Staff</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->user->name ?? 'Not assigned' }}</dd>
                </div>
                @if($queue->hold_reason)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Hold Reason</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->hold_reason }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Timeline</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Queue processing timeline.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->created_at->format('M d, Y H:i:s') }}</dd>
                </div>
                @if($queue->called_at)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Called</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($queue->called_at)->format('M d, Y H:i:s') }}</dd>
                </div>
                @endif
                @if($queue->served_at)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Serving Started</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($queue->served_at)->format('M d, Y H:i:s') }}</dd>
                </div>
                @endif
                @if($queue->hold_started_at)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Hold Started</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($queue->hold_started_at)->format('M d, Y H:i:s') }}</dd>
                </div>
                @endif
                @if($queue->skipped_at)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Skipped</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($queue->skipped_at)->format('M d, Y H:i:s') }}</dd>
                </div>
                @endif
                @if($queue->status == 'completed' && $queue->updated_at)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $queue->updated_at->format('M d, Y H:i:s') }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Processing Time -->
    @if($queue->status == 'completed' && $queue->served_at)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Processing Time</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Wait Time</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @php
                            $waitTime = \Carbon\Carbon::parse($queue->created_at)->diffInMinutes(\Carbon\Carbon::parse($queue->called_at));
                        @endphp
                        {{ $waitTime }} minutes
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Service Time</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @php
                            $serviceTime = \Carbon\Carbon::parse($queue->served_at)->diffInMinutes($queue->updated_at);
                        @endphp
                        {{ $serviceTime }} minutes
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total Time</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @php
                            $totalTime = \Carbon\Carbon::parse($queue->created_at)->diffInMinutes($queue->updated_at);
                        @endphp
                        {{ $totalTime }} minutes
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    @endif
</div>
