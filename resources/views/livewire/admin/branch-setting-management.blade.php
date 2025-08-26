<div>
    <x-admin-layout>
        <div class="max-w-7xl mx-auto mb-6 mt-4">
            <h1 class="text-2xl font-bold">Branch Settings Management</h1>
            <p class="text-gray-600">
                Configure how queue numbers, daily resets, and break messages work for your branch.
                Changes take effect immediately.
            </p>

            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1 mr-4">
                        <h2 class="text-lg font-semibold text-amber-800">Branch Queue Reset</h2>
                        <p class="text-sm text-amber-700">
                            Need to reset this branch's queues immediately? This will clear today's active queues for this branch only and reset numbering to the base value.
                            <span class="font-medium">Use this if automatic reset failed or for emergency situations.</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <x-filament::button
                            color="warning"
                            size="md"
                            wire:click="confirmReset"
                            wire:loading.attr="disabled"
                        >
                            Reset Branch Queues
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto">
            <form wire:submit="save">
                {{ $this->form }}
                <div class="mt-4 flex items-center gap-4">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        Save Settings
                    </x-filament::button>
                </div>

            </form>
        </div>
        <x-filament-actions::modals />
    </x-admin-layout>
</div>
