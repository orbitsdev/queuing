<div>
    @section('nav-title', 'Monitor Management - ' . $branch->name)
    <x-admin-layout>
        <div class="mb-6">
            <!-- Back navigation -->
            {{-- <div class="mb-2">
                <a href="{{ route('admin.branches-for-monitor-management') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                    <x-icon name="arrow-left" class="w-4 h-4 mr-1" />
                    Back to Branches
                </a>
            </div> --}}

            <!-- Header with title and action button -->
            <div class="flex items-center mb-3">
                <div class="flex-grow">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <x-icon name="tv" class="w-6 h-6 inline-block mr-1" />
                        {{ $branch->name }} - Monitor Management
                    </h2>
                </div>
                <div class="ml-4">
                    {{ $this->createAction }}
                </div>
            </div>

            <!-- Description -->
            <p class="text-gray-600">Manage monitors and their associated services for this branch. Each monitor can display multiple services.</p>

            <!-- Info tip -->
            <div class="mt-2 flex items-center text-sm text-gray-500 bg-gray-50 p-2 rounded-md border border-gray-200">
                <x-icon name="information-circle" class="w-5 h-5 mr-2 text-blue-500" />
                <span>To view a monitor display, click on the monitor's name in the table.</span>
            </div>
        </div>

        {{ $this->table }}
        <x-filament-actions::modals />
    </x-admin-layout>
</div>
