<div class="min-h-screen bg-[#001a71]" wire:poll.5s>
    <!-- Header with branch name and time -->
    {{-- <header class="bg-[#0058d5] text-white py-3 px-6 flex justify-between items-center"> --}}
        {{-- <h1 class="text-3xl font-bold">{{ $monitor->branch->name ?? 'Branch' }} - {{ $monitor->name }}</h1>
        <div class="text-3xl font-medium" x-data="{ time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }" x-init="setInterval(() => time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), 60000)" x-text="time"></div>
    </header> --}}

    <!-- Main content area with two panels side by side -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-[calc(100vh-80px)]">
        <!-- LEFT PANEL: Now Serving -->
        <div class="p-4 grid grid-rows-[auto_1fr] h-full col-span-1 bg-[#001a71]">
            <!-- Now Serving Header -->
            <div class="bg-black text-white p-4  flex items-center border-t-4 border-[#cee1ff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h2 class="text-4xl md:text-5xl font-bold uppercase">NOW SERVING</h2>
            </div>

            <!-- Now Serving Content -->
            <div class=" overflow-y-auto mt-6">
                @forelse ($servingQueues as $queue)
                    <div class="animate-pulse grid grid-cols-5 border-2 rounded overflow-hidden border-b-2 border-white mb-4 last:mb-0">
                        <!-- Counter Name block -->
                        <div class="bg-[#cee1ff] text-black text-4xl md:text-4xl font-bold py-4 px-4 col-span-3 flex items-center justify-center text-center uppercase">
                            {{ $queue->counter->name ?? 'COUNTER' }}
                        </div>

                        <!-- Ticket Number block -->
                        <div class="col-span-2 flex items-center justify-center bg-black py-3 px-4">
                            <div class="text-white text-4xl md:text-6xl font-black">
                                {{ $queue->number }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-3xl text-gray-500 py-20">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto mb-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>NO ACTIVE TICKETS</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT PANEL: Waiting Queue -->
        <div class="p-4 grid grid-rows-[auto_1fr] h-full col-span-2 bg-[#cee1ff]">
            <!-- Waiting Queue Header -->
            <div class="bg-[#001a71] text-white p-4 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <h2 class="text-4xl md:text-5xl font-bold uppercase">WAITING QUEUE</h2>
            </div>

            <!-- Waiting Queue Content -->
            <div class=" p-6">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 h-full">
                    @forelse ($waitingQueues as $queue)
                        <div class="bg-[#001a71] text-white rounded-lg col-span-1 hover:bg-kiosqueeing-primary hover:text-white hover:border-kiosqueeing-primary  border-2 border-kiosqueeing-primary flex items-center justify-center p-2">
                            <div class=" text-5xl md:text-6xl lg:text-7xl font-bold py-2">
                                {{ $queue->number }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex items-center justify-center h-full text-3xl text-gray-500">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto mb-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>NO WAITING TICKETS</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
