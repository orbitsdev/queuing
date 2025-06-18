<x-admin-layout>
    <div class="bg-red-600">
        {{ $this->testAction }}
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <x-filament-actions::modals />
</x-admin-layout>
