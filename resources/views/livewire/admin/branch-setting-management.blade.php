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
                            Click this button to reset queue numbers to 1 right now. The system is scheduled to reset automatically at <span class="font-medium">{{ $this->setting->queue_reset_time ? date('h:i A', strtotime($this->setting->queue_reset_time)) : '05:00 AM' }}</span> daily.
                            <span class="font-medium">You only need this button if the automatic reset doesn't happen as scheduled.</span>
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
