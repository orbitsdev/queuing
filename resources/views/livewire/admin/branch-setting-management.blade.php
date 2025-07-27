<div>
    <x-admin-layout>
        <div class="max-w-7xl mx-auto mb-6 mt-4">
            <h1 class="text-2xl font-bold">Branch Settings Management</h1>
            <p class="text-gray-600">
                Configure how queue numbers, daily resets, and break messages work for your branch.
                Changes take effect immediately.
            </p>
        </div>
        <div class="max-w-7xl mx-auto">
            <form wire:submit="save">
                {{ $this->form }}
                <div class="mt-4">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        Save
                    </x-filament::button>
                </div>
            </form>
        </div>
        <x-filament-actions::modals />
    </x-admin-layout>
</div>
