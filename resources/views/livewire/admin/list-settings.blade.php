<div>
    @section('nav-title', 'Branch Settings')
    <x-admin-layout>
        <div class="max-w-8xl mx-auto">
            <div class="px-4 sm:px-6 lg:px-8 py-8">
                <div class="sm:flex sm:items-center mb-6">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-kiosqueeing-text">Branch Settings</h1>
                        <p class="mt-2 text-sm text-gray-700">Manage settings for each branch in your system.</p>
                    </div>

                </div>
                {{ $this->table }}
                <x-filament-actions::modals />
            </div>
        </div>
    </x-admin-layout>
</div>
