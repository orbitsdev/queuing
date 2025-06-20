<div wire:poll.5s="loadQueue">
    @section('nav-title', 'Counter')
    <x-admin-layout>
        <div class="max-w-8xl mx-auto px-4">

            <div class="mb-6">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">
                    {{ $counter->name }}
                </h1>

                <!-- ✅ Service Badges -->
                <div class="flex flex-wrap gap-2 mb-4">
                    @forelse ($counter->services as $service)
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-medium">
                            {{ $service->name }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400">No services assigned</span>
                    @endforelse
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:gap-4 bg-gray-50 px-4 py-3 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium
                            {{ $status === 'active' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-gray-900' }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    @if ($status === 'break')
                        <div class="mt-2 md:mt-0 md:ml-2 px-4 py-2 bg-yellow-50 text-yellow-800 border border-yellow-300 rounded-md text-sm shadow-sm">
                            <strong>Break Notice:</strong> {{ $breakMessage ?? 'Not available' }}
                        </div>
                    @endif
                </div>
            </div>


            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" wire:poll.5s="loadQueue">

                <!-- LEFT: Now Serving & Actions -->
                <div class="bg-white shadow rounded-lg p-8 space-y-8">

                    <!-- Now Serving -->
                    <div class="relative w-fit mx-auto">
                        @if ($status === 'break')
                            <div
                                class="absolute inset-0 z-10 bg-black  flex items-center justify-center rounded-xl">
                                <div class="text-center text-white">
                                    <p class="text-lg font-bold">On Break</p>
                                    <p class="text-sm">{{ $breakMessage ?? 'Not available' }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="flex flex-col justify-between aspect-square w-80 h-80 mx-auto rounded-xl shadow-md border border-kiosqueeing-primary bg-white overflow-hidden">
                            <div class="flex-1 flex flex-col justify-center items-center space-y-1">
                                @if ($currentTicket)
                                    <!-- Big number -->
                                    <div class="text-7xl font-extrabold text-kiosqueeing-primary">
                                        {{ $currentTicket->number }}
                                    </div>

                                    <!-- Service name -->
                                    <div class="text-sm text-gray-600">
                                        {{ $currentTicket->service->name ?? 'No Service' }}
                                    </div>

                                    <!-- Created time -->
                                    <div class="text-sm text-gray-400">
                                        {{ $currentTicket->created_at->format('h:i A') }}
                                    </div>
                                @else
                                    <div class="text-4xl font-semibold text-gray-400">NONE</div>
                                    <p class="text-xs text-gray-400 mt-2">No ticket selected</p>
                                @endif
                            </div>

                            <!-- Bottom bar with full ticket number -->
                            <div class="bg-kiosqueeing-primary w-full text-center py-3 text-white font-semibold tracking-widest">
                                {{ $currentTicket?->ticket_number ?? '' }}
                            </div>
                        </div>





                        <h2 class="text-2xl mt-4 uppercase font-medium text-gray-600 mb-4 text-center">Now Serving</h2>

                        <div class="grid grid-cols-4 gap-4 mt-12">

                            <!-- ✅ Complete -->
                            <button wire:click="completeQueue"
                                wire:loading.attr="disabled"
                                @disabled(!$currentTicket)
                                class="{{ $currentTicket
                                    ? 'px-5 py-3 bg-gray-700 text-white hover:bg-gray-800 transition rounded-lg flex flex-col items-center justify-center'
                                    : 'px-5 py-3 bg-gray-200 text-gray-400 cursor-not-allowed rounded-lg flex flex-col items-center justify-center'
                                }}">
                                ✅ Complete
                            </button>

                            <!-- ✅ Hold -->
                            <button wire:click="holdQueue"
                                wire:loading.attr="disabled"
                                @disabled(!$currentTicket)
                                class="{{ $currentTicket
                                    ? 'px-5 py-3 bg-gray-700 text-white hover:bg-gray-800 transition rounded-lg flex flex-col items-center justify-center'
                                    : 'px-5 py-3 bg-gray-200 text-gray-400 cursor-not-allowed rounded-lg flex flex-col items-center justify-center'
                                }}">
                                ⏸️ Hold
                            </button>

                            <!-- ✅ Skip -->
                            <button wire:click="skipCurrent"
                                wire:loading.attr="disabled"
                                @disabled(!$currentTicket)
                                class="{{ $currentTicket
                                    ? 'px-5 py-3 bg-gray-700 text-white hover:bg-gray-800 transition rounded-lg flex flex-col items-center justify-center'
                                    : 'px-5 py-3 bg-gray-200 text-gray-400 cursor-not-allowed rounded-lg flex flex-col items-center justify-center'
                                }}">
                                ⏭️ Skip
                            </button>

                            <!-- ✅ Cancel -->
                            <button wire:click="cancelSelectedQueue"
                                wire:loading.attr="disabled"
                                @disabled(!$currentTicket)
                                class="{{ $currentTicket
                                    ? 'px-5 py-3 bg-gray-700 text-white hover:bg-gray-800 transition rounded-lg flex flex-col items-center justify-center'
                                    : 'px-5 py-3 bg-gray-200 text-gray-400 cursor-not-allowed rounded-lg flex flex-col items-center justify-center'
                                }}">
                                ❌ Cancel
                            </button>

                        </div>


                    </div>

                </div>

                <!-- RIGHT: Next, Hold, Others -->
                <div class="bg-white shadow rounded-lg p-8 space-y-8">

                    <!-- Next Tickets -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-4">
                                <h2 class="text-sm uppercase font-medium text-gray-500">Next Tickets</h2>

                                <span class="text-xs px-3 py-1 bg-gray-100 rounded-full border text-gray-700">
                                    Left Today: <strong>{{ $queueCountToday }}</strong>
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <button
                                wire:click="{{ $status === 'active' ? 'startBreak' : 'resumeWork' }}"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">
                                {{ $status === 'active' ? 'Start Break' : 'Resume Work' }}
                            </button>



                                <button wire:click="logoutCounter" wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-gray-100 text-gray-600 border border-gray-300 rounded hover:bg-gray-200 transition flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                    </svg>
                                    Logout
                                </button>

                            </div>
                        </div>


                        <div class="grid grid-cols-3 gap-4 mb-2">
                            @forelse ($nextTickets as $next)
                                <button
                                    type="button"
                                    wire:click="selectQueue({{ $next->id }})"
                                    wire:loading.attr="disabled"
                                    @disabled($status === 'break')
                                    class="relative flex flex-col items-center justify-center px-6 py-4 w-full rounded transition
                                        {{ ($status === 'break')
                                            ? 'bg-gray-200 cursor-not-allowed'
                                            : 'bg-gradient-to-tr from-denim-700 via-denim-800 to-denim-900 hover:bg-gradient-to-tr hover:from-denim-800 hover:via-denim-900 hover:to-denim-950 hover:shadow-md text-white'
                                        }}"
                                >
                                    <!-- Absolute created time -->
                                    <div class="absolute top-0 rounded-sm right-0 text-[10px] font-bold {{ $status === 'break' ? 'text-gray-500' : 'bg-black text-white px-1.5 py-0.5 rounded-sm' }}">
                                        {{ $next->created_at->format('h:i A') }}
                                    </div>

                                    <!-- Big number -->
                                    <div class="text-5xl font-bold {{ $status === 'break' ? 'text-gray-500' : 'text-white' }}">
                                        {{ $next->number }}
                                    </div>

                                    <!-- Ticket number -->
                                    <div class="text-xs mt-1 {{ $status === 'break' ? 'text-gray-500' : 'text-denim-200 font-bold' }} tracking-wide">
                                        {{ $next->ticket_number }}
                                    </div>

                                    <!-- Service name -->
                                    <div class="text-xs {{ $status === 'break' ? 'text-gray-500' : 'text-denim-100 font-bold' }} mt-1">
                                        {{ $next->service->name ?? 'No Service' }}
                                    </div>
                                </button>
                            @empty
                                <div class="col-span-3 flex flex-col items-center justify-center px-6 py-12 bg-gray-100 rounded text-gray-500 border border-gray-200 border-dashed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium">No waiting tickets available</p>
                                    <p class="text-xs mt-1">All tickets have been served or assigned</p>
                                </div>
                            @endforelse
                        </div>


                    </div>

                    <!-- Resume Hold -->
                    <div>
                        <h2 class="text-sm uppercase font-medium text-gray-500 mb-1">
                            Resume Hold
                            <span class="ml-2 inline-block px-2 py-0.5 text-xs bg-gray-200 text-gray-700 rounded-full">
                                {{ $holdTickets->count() }}
                            </span>
                        </h2>

                        @if($holdTickets->count() > 0)
                            <select
                                wire:model="selectedHoldTicket"
                                wire:change="triggerResumeSelectedHold"
                                @disabled($status === 'break')
                                class="w-full border border-gray-300 rounded px-4 py-3 mb-4"
                            >
                                <option value="">Select a Hold Ticket</option>
                                @foreach ($holdTickets as $hold)
                                    <option value="{{ $hold->id }}">
                                        {{ $hold->ticket_number }} - {{ $hold->service->name ?? 'No Service' }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="flex flex-col items-center justify-center px-6 py-8 bg-gray-100 rounded text-gray-500 border border-gray-200 border-dashed mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium">No tickets on hold</p>
                                <p class="text-xs mt-1">Use the Hold button when serving a ticket</p>
                            </div>
                        @endif

                    </div>



                    <!-- Currently Serving By Others -->
                    <div>
                        <h2 class="text-sm uppercase font-medium text-gray-500 mb-4">Currently Serving by Other Counters
                        </h2>
                        <div class="grid grid-cols-1 gap-2">
                            @forelse ($others as $other)
                                <div
                                    class="flex items-center bg-gray-50 border border-gray-200 rounded text-sm font-medium text-gray-800">
                                    <div
                                        class="flex items-center rounded-l-lg bg-kiosqueeing-primary text-white uppercase px-4 py-2">
                                        {{ $other->counter->name }}
                                    </div>
                                    <div class="px-4 py-2">
                                        {{ $other->ticket_number }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No other counters serving right now.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <x-modal-card title="Hold Ticket" wire:model.defer="showHoldModal" align="center">
            <div class="space-y-4">
                <x-input label="Hold Reason (optional)" placeholder="Explain why you are putting this on hold"
                    wire:model.defer="holdReason" />
            </div>

            <x-slot name="footer">
                <x-button flat label="Cancel" x-on:click="$wire.showHoldModal = false" />
                <x-button primary label="Confirm Hold" wire:click="confirmHoldQueueWithReason" />
            </x-slot>
        </x-modal-card>

        <x-modal-card title="Start Break" wire:model.defer="showBreakModal" align="center">
            <div class="space-y-4">
                <x-input label="Break Message" placeholder="Describe why you're taking a break"
                    wire:model.defer="breakInputMessage" />
            </div>

            <x-slot name="footer">
                <x-button flat label="Cancel" x-on:click="$wire.showBreakModal = false" />
                <x-button primary label="Start Break" wire:click="confirmStartBreak" />
            </x-slot>
        </x-modal-card>


    </x-admin-layout>
</div>
