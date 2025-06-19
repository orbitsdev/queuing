<div>
    @section('nav-title', 'Select Counter')
    <x-admin-layout>
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h1 class="text-2xl font-bold mb-4">Select a Counter</h1>
            <p class="text-sm text-gray-500 mb-8">
                To start serving, please select an available counter below.
            </p>

            <!-- ✅ Search input -->
            <div class="mb-6 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        wire:model.live="search"
                        type="text"
                        placeholder="Search counter or service..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-denim-500 focus:border-denim-500"
                    />
                </div>
            </div>

            @if ($counters->isEmpty())
                @if ($search)
                    <p class="text-center text-gray-500">No counters match your search.</p>
                @else
                    <p class="text-center text-gray-500">No counters available for your branch.</p>
                @endif
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($counters as $counter)
                        <div
                            wire:click="assign({{ $counter->id }})"
                            wire:loading.attr="disabled"
                            class="relative cursor-pointer transition transform hover:scale-[1.02] rounded-xl overflow-hidden {{ $counter->user_id ? 'pointer-events-none' : '' }}"
                        >
                            <!-- Card Background -->
                            <div class="{{ $counter->user_id ? 'bg-gray-300 text-gray-700' : 'bg-gradient-to-tr from-denim-700 via-denim-800 to-denim-900 text-white' }} p-6 md:p-8 h-full flex flex-col justify-between rounded-xl shadow-md">

                                <div>
                                    <h3 class="text-xl font-semibold mb-3">{{ $counter->name }}</h3>

                                    <!-- Services -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @forelse ($counter->services as $service)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $counter->user_id ? 'bg-gray-700 text-gray-200 border-gray-600' : 'bg-white text-denim-800 border-denim-300' }} border shadow-sm">
                                                {{ $service->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-200">No services assigned</span>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Status -->
                                <p class="text-sm">
                                    @if ($counter->user_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700">
                                            Occupied by {{ $counter->user->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Available
                                        </span>
                                    @endif
                                </p>

                                <!-- Overlay loading when assigning -->
                                <div wire:loading wire:target="assign" class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-xl">
                                    <span class="text-white text-sm">Assigning...</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-admin-layout>
</div>
