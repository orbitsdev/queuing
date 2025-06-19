<div class="h-screen w-screen bg-[#001a71] overflow-hidden" wire:poll.5s>
    <!-- Header with branch name and time -->
    {{-- <header class="bg-[#0058d5] text-white py-3 px-6 flex justify-between items-center"> --}}
        {{-- <h1 class="text-3xl font-bold">{{ $monitor->branch->name ?? 'Branch' }} - {{ $monitor->name }}</h1>
        <div class="text-3xl font-medium" x-data="{ time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }" x-init="setInterval(() => time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), 60000)" x-text="time"></div>
    </header> --}}

    <!-- Main content area with two panels side by side -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 h-screen">
        <!-- LEFT PANEL: Now Serving -->
        <div class="p-2 grid grid-rows-[auto_1fr] h-full col-span-1 bg-[#001a71]">
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
        <div class="p-2 grid grid-rows-[auto_1fr] h-full col-span-2 bg-[#cee1ff]">
            <!-- Waiting Queue Header -->
            <div class="bg-[#001a71] text-white p-8 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <h2 class="text-4xl md:text-5xl font-bold uppercase">WAITING QUEUE</h2>
            </div>

            <!-- Waiting Queue Content -->
            <div class="h-full pt-4 px-4 pb-4">
                <div class="grid {{ count($waitingQueues) > 6 ? 'grid-cols-3' : 'grid-cols-2' }} gap-4 auto-rows-fr">
                 @forelse ($waitingQueues as $queue)
                        @if(count($waitingQueues) == 1)
                        <div class="bg-[#001a71] text-white rounded-lg col-span-2 border-2 border-white flex items-center justify-center p-4 h-64">
                            <div class="text-[15rem] font-extrabold">
                                {{ $queue->number }}
                            </div>
                        </div>
                        @elseif(count($waitingQueues) == 2)
                        <div class="bg-[#001a71] text-white rounded-lg border-2 border-white flex items-center justify-center p-4 h-64">
                            <div class="text-[12rem] font-extrabold">
                                {{ $queue->number }}
                            </div>
                        </div>
                        @elseif(count($waitingQueues) == 3)
                        <div class="bg-[#001a71] text-white rounded-lg border-2 border-white flex items-center justify-center p-3 h-48">
                            <div class="text-[10rem] font-extrabold">
                                {{ $queue->number }}
                            </div>
                        </div>
                        @elseif(count($waitingQueues) <= 6)
                        <div class="bg-[#001a71] text-white rounded-lg border-2 border-white flex items-center justify-center p-2 h-40">
                            <div class="text-9xl font-bold">
                                {{ $queue->number }}
                            </div>
                        </div>
                        @else
                        <div class="bg-[#001a71] text-white rounded-lg border-2 border-white flex items-center justify-center p-2 h-32">
                            <div class="text-7xl font-bold">
                                {{ $queue->number }}
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center h-full bg-[#001a71] rounded-lg border-2 border-white p-8">
                            <p class="text-[8rem] font-extrabold leading-none uppercase text-white">No</p>
                            <p class="text-[4rem] font-extrabold leading-none uppercase mb-8 text-white">Number was called</p>
                            <p class="text-3xl text-center mx-10 text-white">Wait for your number to be displayed by an available counter</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
