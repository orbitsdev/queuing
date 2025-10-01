<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-6xl bg-white rounded-lg  overflow-hidden">
        <!-- Header with logo -->
        <div class="bg-blue-600 p-6 text-white flex items-center justify-center">
            <h1 class="text-3xl font-bold text-center w-full">Monitor Selection</h1>
        </div>

        <div class="p-6">
            @if(!$showMonitors)
                <!-- Branch Code Entry Form -->
                <div class="mb-8 max-w-md mx-auto">
                    <h2 class="text-2xl font-semibold mb-6 text-center">Enter Branch Code</h2>
                    <p class="text-gray-600 mb-6 text-center">
                        Enter your branch code to view available monitors for your branch.
                    </p>

                    <form wire:submit="findBranch" class="space-y-4">
                        <div>
                            <x-input
                                label="Branch Code"
                                placeholder="Enter branch code"
                                wire:model="branchCode"
                                class="text-center text-2xl h-16"
                                autofocus
                            />
                        </div>

                        <div class="flex justify-center">
                            <x-button
                                type="submit"
                                primary
                                lg
                                class="w-full"
                                spinner="findBranch"
                            >
                                Find Monitors
                            </x-button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Monitor Selection -->
                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">{{ $branch->name }} Monitors</h2>
                        <x-button
                            wire:click="$set('showMonitors', false)"
                            outline
                            icon="arrow-left"
                        >
                            Back
                        </x-button>
                    </div>

                    @if($monitors->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xl text-gray-600">No monitors found for this branch.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($monitors as $monitor)
                                <div class="border rounded-lg overflow-hidden shadow hover:shadow-md transition-shadow">
                                    <div class="bg-gray-50 p-4 border-b">
                                        <h3 class="font-semibold text-lg">{{ $monitor->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $monitor->location ?? 'Floor ' . $loop->iteration }}</p>
                                    </div>

                                    <div class="p-4">
                                        <div class="mb-3">
                                            <span class="text-sm font-medium text-gray-700">Services:</span>
                                            <div class="mt-2 flex flex-col gap-1">
                                                @foreach($monitor->services as $service)
                                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-50 text-blue-800 border-l-2 border-blue-500">
                                                        {{ $service->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex justify-between mt-6">
                                            <a
                                                href="{{ route('display.show', $monitor) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                                                target="_blank"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                View Display
                                            </a>
                                            <button
                                                onclick="openFullscreen('{{ route('display.show', $monitor) }}')"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 bg-white hover:bg-gray-50 transition"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 text-center text-gray-600">
        <p>© {{ date('Y') }} QUEWIE. All rights reserved.</p>
    </div>
</div>

