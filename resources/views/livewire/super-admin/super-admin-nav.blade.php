<nav class="flex-1 px-2 mt-4 space-y-1">
    @php
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'superadmin.dashboard', 'icon' => 'home'],
            ['label' => 'Branches', 'route' => 'superadmin.manage-branch', 'icon' => 'building-office'],
            ['label' => 'Users', 'route' => 'superadmin.manage-user', 'icon' => 'users'],
            // ['label' => 'Reports', 'route' => 'admin.reports', 'icon' => 'chart-bar'],
        ];
    @endphp

    @foreach ($navItems as $item)
        @php
            $isActive = request()->routeIs($item['route']);
        @endphp
        <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}" 
            class="group nav-link {{ $isActive ? 'active' : 'inactive' }}">
            @svg('heroicon-o-' . $item['icon'], [
                'class' => 'nav-icon ' . ($isActive ? 'active' : 'inactive group-hover:text-kiosqueeing-primary'),
            ])
            <span x-show="sidebarOpen" class="text-sm font-medium">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
