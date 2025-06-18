<div>
    @section('nav-title', 'User Management')
    <x-admin-layout>
        <div class="flex justify-end mb-4">
            {{ $this->createAction }}
        </div>
        {{ $this->table }}
        <x-filament-actions::modals />
    </x-admin-layout>
</div>