<nav class="flex-1 px-2 space-y-1 mt-4">
    @php
        $navItems = [
            ['label' => 'Counter', 'route' => 'counter.transaction', 'icon' => 'computer-desktop'],
        ];
    @endphp

    @foreach ($navItems as $item)
        @php
            $isActive = request()->routeIs($item['route']);
        @endphp
        <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}" wire:navigate wire:ignore
            class="group nav-link {{ $isActive ? 'active' : 'inactive' }}">
            @svg('heroicon-o-' . $item['icon'], [
                'class' => 'nav-icon ' . ($isActive ? 'active' : 'inactive group-hover:text-kiosqueeing-primary'),
            ])
            <span x-show="sidebarOpen" class="text-sm font-medium">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
