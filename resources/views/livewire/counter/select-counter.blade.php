<div>
    @section('nav-title', 'Select Counter')
    <x-admin-layout>
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h1 class="text-2xl font-bold mb-4">Select a Counter</h1>
            <p class="text-sm text-gray-500 mb-8">
                To start serving, please select an available counter below.
            </p>

            @if ($counters->isEmpty())
                <p class="text-center text-gray-500">No counters available for your branch.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($counters as $counter)
                        <div
                            class="bg-white shadow rounded p-6 md:p-8 space-y-4 transition transform hover:scale-[1.02] hover:shadow-md {{ $counter->user_id ? 'opacity-50 pointer-events-none' : '' }}">
                            <h3 class="text-xl font-semibold">{{ $counter->name }}</h3>

                            <p class="text-sm">
                                @if ($counter->user_id)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Occupied by {{ $counter->user->name }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Available
                                    </span>
                                @endif
                            </p>

                            <button wire:click="assign({{ $counter->id }})"
                                wire:loading.attr="disabled"
                                wire:target="assign"
                                @disabled($counter->user_id)
                                class="{{ $counter->user_id
                                    ? 'px-4 py-2 bg-gray-300 text-gray-600 rounded cursor-not-allowed'
                                    : 'px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 transition' }}">
                                <span wire:loading.remove wire:target="assign">Use this Counter</span>
                                <span wire:loading wire:target="assign">Assigning...</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-admin-layout>
</div>
