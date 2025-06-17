<div>
    @include('partials.settings-heading')

    <div class="p-4">
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
</x-modal>
</div>
