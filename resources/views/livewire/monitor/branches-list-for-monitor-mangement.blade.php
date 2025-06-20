<div>
    @section('nav-title', 'Branches List For Monitor Management')
    <x-admin-layout>
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    <x-icon name="tv" class="w-6 h-6 inline-block mr-1" />
                    Monitor Management
                </h2>
            </div>
            <p class="text-gray-600 mt-1">Select a branch to manage its monitors. Each monitor can display specific services.</p>
        </div>
        
        {{ $this->table }}
        <x-filament-actions::modals />
    </x-admin-layout>
</div>
