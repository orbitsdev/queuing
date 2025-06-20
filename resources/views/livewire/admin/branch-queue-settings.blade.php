<div>
    @section('nav-title', 'Queue Settings - ' . $branch->name)
    <x-admin-layout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">
                    Queue Settings for {{ $branch->name }}
                </h1>
                <div class="text-right">
                    <div class="text-lg font-medium text-gray-600">Today's Date:</div>
                    <div class="text-2xl font-bold text-blue-700">{{ now()->format('F d, Y') }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Queue Number Configuration</h2>
                    <p class="text-gray-600 mb-4">
                        Configure how queue numbers are generated for this branch. The system uses a base number plus the count of tickets issued today.
                    </p>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-800 p-3 rounded-md mb-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input
                                label="Queue Number Base"
                                wire:model.live="base"
                                type="number"
                                min="0"
                                hint="Starting point for queue numbers"
                            />
                        </div>

                        <div>
                            <x-input
                                label="Today's Issued Tickets"
                                value="{{ $todayCount }}"
                                disabled
                                hint="Count of tickets issued today"
                            />
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium">Next Queue Number:</span>
                                    <span class="ml-2 text-lg font-bold text-blue-700">{{ $nextNumber }}</span>
                                </div>
                                <p class="text-sm text-blue-600 mt-1 ml-7">This is the number that will be assigned to the next ticket.</p>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="font-medium">Last Queue Number Today:</span>
                                    <span class="ml-2 text-lg font-bold text-green-700">{{ $lastQueueNumber ?? 'None issued' }}</span>
                                </div>
                                <p class="text-sm text-green-600 mt-1 ml-7">The most recently issued queue number for today.</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($todayCount > 0)
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="font-medium">Reset Option:</span>
                            <span class="ml-2 text-amber-700">{{ $todayCount }} queues have been issued today</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-amber-700">{{ $branch->name }} - {{ now()->format('F d, Y') }}</div>
                        </div>
                    </div>
                    <p class="text-sm text-amber-600 mt-1 ml-7">You can reset all of today's queues for this branch using the button below.</p>
                </div>
                @endif

                <div class="flex justify-between pt-4 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-50 transition"
                            wire:click="resetTodayQueues"
                            wire:confirm="Are you sure you want to delete all {{ $todayCount }} queues created today for this branch? This action cannot be undone."
                            @if($todayCount == 0) disabled @endif
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Reset Today's Queues
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-600 focus:ring focus:ring-gray-200 active:bg-gray-600 disabled:opacity-50 transition"
                            wire:click="resetBaseToOne"
                            wire:confirm="Are you sure you want to reset the queue base to 1? Next queue will start from {{ 1 + $todayCount }}."
                            @if($base == 1) disabled @endif
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Base to 1
                        </button>
                    </div>
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition"
                        wire:click="save"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    </x-admin-layout>
</div>
