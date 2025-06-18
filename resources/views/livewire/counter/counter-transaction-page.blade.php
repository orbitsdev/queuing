<div>
    @section('nav-title', 'Counter')
    <x-admin-layout>
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Two-column layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Left column: Current queue number -->
                <div class="space-y-6">
                    <h2 class="text-lg font-medium text-gray-500 uppercase">Current Queue Number</h2>
                    <div class="bg-white shadow-sm rounded-lg p-8 flex flex-col items-center hover:shadow-md transition-all duration-300">
                        <div class="text-9xl font-bold text-gray-800">
                            {{ $currentTicket?->ticket_number ?? '123' }}
                        </div>
                    </div>
                    
                    <!-- Search box -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300" placeholder="Search transactions">
                    </div>
                </div>
                
                <!-- Right column: Next numbers and actions -->
                <div class="space-y-6">
                    <h2 class="text-lg font-medium text-gray-500 uppercase">Next Numbers</h2>
                    
                    <!-- Next numbers grid -->
                    <div class="grid grid-cols-3 gap-4">
                        @forelse($nextTickets as $index => $ticket)
                            <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-center hover:shadow-md transition-all duration-200">
                                @if($index === 1)
                                    <div class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                @else
                                    <span class="text-3xl font-bold text-gray-800">{{ $ticket->ticket_number }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-4 text-gray-500">No waiting tickets</div>
                        @endforelse
                    </div>
                    
                    <!-- Action buttons in 2x2 grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <button wire:click="callNext" class="bg-white shadow-sm rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 text-gray-800 font-medium">
                            Call Next Person
                        </button>
                        <button wire:click="serveCurrent" class="bg-white shadow-sm rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 text-gray-800 font-medium">
                            Complete Transaction
                        </button>
                        <button wire:click="skipCurrent" class="bg-white shadow-sm rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 text-gray-800 font-medium">
                            Cancel Transaction
                        </button>
                        <button wire:click="holdCurrent" class="bg-white shadow-sm rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 text-gray-800 font-medium">
                            Hold Transaction
                        </button>
                    </div>
                    
                    <!-- Bottom call next button -->
                    <button wire:click="callNext" class="w-full bg-white shadow-sm rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 text-gray-800 font-medium">
                        Call Next Person
                    </button>
                    
                    <!-- Status toggle (hidden by default, can be shown if needed) -->
                    <div class="hidden">
                        <button wire:click="toggleBreak" class="w-full bg-white shadow-sm rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 text-gray-800 font-medium">
                            {{ $status === 'active' ? 'Start Break' : 'Resume Work' }}
                        </button>
                        @if($status === 'break')
                            <p class="text-sm text-gray-500 mt-1 text-center">{{ $breakMessage }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
</div>
