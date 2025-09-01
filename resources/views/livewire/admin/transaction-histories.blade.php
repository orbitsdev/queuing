<div>
    @section('nav-title', 'Transaction History')
    <x-admin-layout>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-kiosqueeing-primary">Transaction History</h1>
            </div>

            {{ $this->table }}
        </div>
        <x-filament-actions::modals />
    </x-admin-layout>
</div>
