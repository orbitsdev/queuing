<div>
    @section('nav-title', 'Counter')
    <x-admin-layout>
        <div class="max-w-8xl mx-auto px-4">

            <!-- Counter Name & Status -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $counter->name }}</h1>
                <div>
                    <span class="{{ $status === 'active' ? 'bg-green-500' : 'bg-yellow-500' }} px-4 py-1 text-white text-sm font-medium rounded-full">
                        {{ ucfirst($status) }}
                    </span>
                    @if ($status === 'break')
                        <p class="mt-1 text-gray-500 text-sm">{{ $breakMessage }}</p>
                    @endif
                </div>
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- LEFT: Now Serving & Actions -->
                <div class="bg-white shadow rounded-lg p-8 space-y-8">

                    <!-- Now Serving -->
                    <div>
                        <div class="flex flex-col justify-between aspect-square w-80 h-80 mx-auto rounded-xl shadow-md border border-kiosqueeing-primary bg-white overflow-hidden">
                            <div class="flex-1 flex flex-col justify-center items-center">
                                @if ($currentTicket)
                                    <div class="text-7xl font-extrabold text-kiosqueeing-primary">
                                        {{ $currentTicket->number }}
                                    </div>
                                @else
                                    <div class="text-4xl font-semibold text-gray-400">NONE</div>
                                    <p class="text-xs text-gray-400 mt-2">No ticket selected</p>
                                @endif
                            </div>
                            <div class="bg-kiosqueeing-primary w-full text-center py-3 text-white font-semibold tracking-widest">
                                {{ $currentTicket?->ticket_number ?? '' }}
                            </div>
                        </div>




                        <h2 class="text-2xl mt-4 uppercase font-medium text-gray-600 mb-4 text-center">Now Serving</h2>

                        <div class="grid grid-cols-4 gap-4 mt-12">
                            <button wire:click="serveCurrent" wire:loading.attr="disabled"
                                class="px-5 py-3 border border-gray-300 text-gray-800 hover:bg-kiosqueeing-primary-hover hover:text-white transition rounded-lg flex flex-col items-center justify-center">
                                ✅ Complete
                            </button>
                            <button wire:click="holdCurrent" wire:loading.attr="disabled"
                                class="px-5 py-3 border border-gray-300 text-gray-800 hover:bg-kiosqueeing-primary-hover hover:text-white transition rounded-lg flex flex-col items-center justify-center">
                                ⏸️ Hold
                            </button>
                            <button wire:click="skipCurrent" wire:loading.attr="disabled"
                                class="px-5 py-3 border border-gray-300 text-gray-800 hover:bg-kiosqueeing-primary-hover hover:text-white transition rounded-lg flex flex-col items-center justify-center">
                                ⏭️ Skip
                            </button>
                            @if ($currentTicket)
                                <button wire:click="cancelSelectedQueue" wire:loading.attr="disabled"
                                    class="px-5 py-3 border border-gray-300 text-red-600 hover:bg-red-100 transition rounded-lg flex flex-col items-center justify-center">
                                    ❌ Cancel
                                </button>
                            @endif
                        </div>

                    </div>

                </div>

                <!-- RIGHT: Next, Hold, Others -->
                <div class="bg-white shadow rounded-lg p-8 space-y-8">

                    <!-- Next Tickets -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-sm uppercase font-medium text-gray-500">Next Tickets</h2>
                            <div class="flex gap-2">
                                <button
                                    wire:click="toggleBreak"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">
                                    {{ $status === 'active' ? 'Start Break' : 'Resume Work' }}
                                </button>

                                <button
                                wire:click="logoutCounter"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gray-100 text-gray-600 border border-gray-300 rounded hover:bg-gray-200 transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
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
                                    class="flex flex-col items-center justify-center px-6 py-4 bg-gray-100 rounded hover:bg-gray-200 hover:shadow-md transition cursor-pointer w-full">
                                    <div class="text-5xl font-bold text-gray-900">{{ $next->number }}</div>
                                    <div class="text-xs mt-2 text-gray-500 tracking-wide">{{ $next->ticket_number }}</div>
                                </button>
                            @empty
                                <p class="text-gray-500 text-sm">No next tickets.</p>
                            @endforelse
                        </div>
                    </div>


                    <!-- Resume Hold -->
                    <div>
                        <h2 class="text-sm uppercase font-medium text-gray-500 mb-4">Resume Hold</h2>
                        <select wire:model="selectedHoldTicket"
                            class="w-full border border-gray-300 rounded px-4 py-3 mb-4">
                            <option value="">Select a Hold Ticket</option>
                            @foreach ($holdTickets as $hold)
                                <option value="{{ $hold->id }}">{{ $hold->ticket_number }}</option>
                            @endforeach
                        </select>
                        {{-- <button wire:click="resumeHold" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 transition">
                            Resume Selected Hold
                        </button> --}}
                    </div>

                    <!-- Currently Serving By Others -->
                    <div>
                        <h2 class="text-sm uppercase font-medium text-gray-500 mb-4">Currently Serving by Other Counters</h2>
                        <div class="grid grid-cols-1 gap-2">
                            @forelse ($others as $other)
                                <div class="flex items-center bg-gray-50 border border-gray-200 rounded text-sm font-medium text-gray-800">
                                    <div class="flex items-center rounded-l-lg bg-kiosqueeing-primary text-white uppercase px-4 py-2">
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
    </x-admin-layout>
</div>
