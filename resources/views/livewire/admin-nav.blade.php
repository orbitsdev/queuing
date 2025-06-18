<nav class="flex-1 px-2 space-y-1 mt-4">
    @php
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Branches', 'route' => 'admin.branches', 'icon' => 'building-office'],
            ['label' => 'Users', 'route' => 'admin.users', 'icon' => 'users'],
            ['label' => 'Services', 'route' => 'admin.services', 'icon' => 'wrench-screwdriver'],
            ['label' => 'Counters', 'route' => 'admin.counters', 'icon' => 'computer-desktop'],
            ['label' => 'Queues', 'route' => 'admin.queues', 'icon' => 'ticket'],
            ['label' => 'Branch Settings', 'route' => 'admin.branch-settings', 'icon' => 'cog-6-tooth'],
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
