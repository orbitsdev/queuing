<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 rounded-md bg-blue-50 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Ticket</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $transaction->ticket_number }}</p>
                    <p class="text-xs text-gray-500">Raw: {{ $transaction->raw_number }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 rounded-md bg-green-50 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Transaction Time</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $transaction->transaction_time->format('g:i A') }}</p>
                    <p class="text-xs text-gray-500">{{ $transaction->transaction_time->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 rounded-md
                    @if(in_array($transaction->action, ['serving', 'served']))
                        bg-green-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @elseif(in_array($transaction->action, ['called']))
                        bg-blue-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.06-7.072m-1.06 7.072a9 9 0 001.06-12.728M12 18v-3" /></svg>
                    @elseif(in_array($transaction->action, ['held']))
                        bg-yellow-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @elseif(in_array($transaction->action, ['skipped', 'cancelled']))
                        bg-red-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    @elseif(in_array($transaction->action, ['counter_active']))
                        bg-indigo-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    @elseif(in_array($transaction->action, ['counter_break']))
                        bg-orange-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @else
                        bg-gray-50 mr-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Action</p>
                    <p class="text-xl font-semibold text-gray-900">{{ ucfirst($transaction->action) }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst($transaction->status_before) }} → {{ ucfirst($transaction->status_after) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div>
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm font-medium text-gray-500">Service:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $transaction->service->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm font-medium text-gray-500">Branch:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $transaction->branch->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm font-medium text-gray-500">Status Before:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ ucfirst($transaction->status_before) }}</span>
                    </div>
                    <div class="flex justify-between pb-2">
                        <span class="text-sm font-medium text-gray-500">Status After:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ ucfirst($transaction->status_after) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Staff & Counter</h3>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm font-medium text-gray-500">Staff:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm font-medium text-gray-500">Staff Email:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $transaction->user->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between pb-2">
                        <span class="text-sm font-medium text-gray-500">Counter:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $transaction->counter->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            @if($transaction->metadata && (is_array($transaction->metadata) || is_object($transaction->metadata)))
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Details</h3>
                <div class="space-y-3">
                    @php
                        $metadata = is_array($transaction->metadata) ? $transaction->metadata : (array)$transaction->metadata;
                    @endphp
                    
                    @foreach($metadata as $key => $value)
                        @if($value)
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $value }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Related Transactions -->
            @if($transaction->queue_id)
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $relatedTransactions = \App\Models\TransactionHistory::where('queue_id', $transaction->queue_id)
                                    ->where('id', '!=', $transaction->id)
                                    ->orderBy('transaction_time', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($relatedTransactions as $related)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">{{ $related->transaction_time->format('M d, g:i A') }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if(in_array($related->action, ['serving', 'served']))
                                                bg-green-100 text-green-800
                                            @elseif(in_array($related->action, ['called']))
                                                bg-blue-100 text-blue-800
                                            @elseif(in_array($related->action, ['held']))
                                                bg-yellow-100 text-yellow-800
                                            @elseif(in_array($related->action, ['skipped', 'cancelled']))
                                                bg-red-100 text-red-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($related->action) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">{{ ucfirst($related->status_before) }} → {{ ucfirst($related->status_after) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-xs text-gray-500 text-center">No related transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
