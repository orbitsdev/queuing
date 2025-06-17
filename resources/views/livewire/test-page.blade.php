<div>
    @include('partials.settings-heading')

    {{-- <div class="p-4">
        <x-button label="Open" x-on:click="$openModal('simpleModal')" primary />
    </div>
 
<x-modal name="simpleModal" style="z-index: 999 !important;">
    <div style="z-index: 1000 !important;">
        <x-card title="Consent Terms">
        <p>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
            industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
            and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
            leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s
            with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
            publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </p>
 
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
 
            <x-button primary label="I Agree" wire:click="agree" />
        </x-slot>
    </x-card>
</x-modal> --}}


<x-button label="Open" x-on:click="$openModal('cardModal')" primary />
 
<x-modal-card title="Edit Customer" name="cardModal">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="Name" placeholder="Your full name" />
 
        <x-input label="Phone" placeholder="USA phone" />
 
        <div class="col-span-1 sm:col-span-2">
            <x-input label="Email" placeholder="example@mail.com" />
        </div>
 
        <div
            class="flex items-center justify-center col-span-1 bg-gray-100 shadow-md cursor-pointer sm:col-span-2 dark:bg-secondary-700 rounded-xl h-64">
            <div class="flex flex-col items-center justify-center">
                <x-icon name="cloud-arrow-up" class="w-16 h-16 text-blue-600 dark:text-teal-600" />
 
                <p class="text-blue-600 dark:text-teal-600">Click or drop files here</p>
            </div>
        </div>
    </div>
 
    <x-slot name="footer" class="flex justify-between gap-x-4">
        <x-button flat negative label="Delete" x-on:click="close" />
 
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
 
            <x-button primary label="Save" wire:click="save" />
        </div>
    </x-slot>
</x-modal-card>
</div>
