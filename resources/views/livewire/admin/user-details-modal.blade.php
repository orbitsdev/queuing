<div class="space-y-6">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- User Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-16 w-16 rounded-full bg-white text-indigo-600 flex items-center justify-center text-xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-4 text-white">
                    <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                    <p class="text-indigo-100">{{ $user->email }}</p>
                </div>
                <div class="ml-auto">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($user->role === 'superadmin') bg-red-100 text-red-800
                        @elseif($user->role === 'admin') bg-yellow-100 text-yellow-800
                        @elseif($user->role === 'staff') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Branch</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->branch ? $user->branch->name : 'Not Assigned' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Created</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Email</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Updated</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Summary -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Activity Summary</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-gray-50 rounded-lg p-4">
                    <span class="text-2xl font-bold text-indigo-600">{{ $ticketsProcessed }}</span>
                    <p class="mt-1 text-sm text-gray-500">Tickets Processed</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <span class="text-2xl font-bold text-indigo-600">{{ $ticketsToday }}</span>
                    <p class="mt-1 text-sm text-gray-500">Tickets Today</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <span class="text-2xl font-bold text-indigo-600">{{ $averageProcessingTime }}</span>
                    <p class="mt-1 text-sm text-gray-500">Avg. Processing Time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    @if(count($recentQueues) > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
        </div>
        <div class="px-6 py-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentQueues as $queue)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $queue->ticket_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $queue->service->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($queue->status === 'completed') bg-green-100 text-green-800
                                    @elseif($queue->status === 'serving') bg-blue-100 text-blue-800
                                    @elseif($queue->status === 'waiting') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($queue->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $queue->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
