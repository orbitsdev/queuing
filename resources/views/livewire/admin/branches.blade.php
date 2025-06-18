<div>
    @section('nav-title', 'Branch Management')
    <x-admin-layout>
           {{ $this->table }}
           <x-filament-actions::modals />
 </x-admin-layout>
</div>