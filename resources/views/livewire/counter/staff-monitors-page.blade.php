<div>
    @section('nav-title', 'Monitors')
    <x-staff-layout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Branch Monitors</h1>
                <p class="mt-1 text-sm text-gray-500">View and access all display monitors for {{ $branch->name }}</p>
            </div>

            @if($monitors->isEmpty())
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No monitors found</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no monitors set up for this branch yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($monitors as $monitor)
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Monitor ID
                                            </dt>
                                            <dd>
                                                <div class="text-lg font-medium text-gray-900">
                                                    {{ $monitor->id }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Name:</span>
                                        <span class="text-sm text-gray-900">{{ $monitor->name }}</span>
                                    </div>
                                    <div class="flex justify-between mt-1">
                                        <span class="text-sm font-medium text-gray-500">Type:</span>
                                        <span class="text-sm text-gray-900">{{ ucfirst($monitor->type) }}</span>
                                    </div>
                                    <div class="flex justify-between mt-1">
                                        <span class="text-sm font-medium text-gray-500">Status:</span>
                                        <span class="text-sm text-gray-900">
                                            @if($monitor->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <a href="{{ route('display.show', $monitor) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Display
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-staff-layout>
</div>
