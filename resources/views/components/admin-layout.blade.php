<div x-data="{ sidebarOpen: true }" class="min-h-screen flex bg-kiosqueeing-background text-kiosqueeing-text">

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-kiosqueeing-sidebar border-r border-gray-200 flex flex-col transition-all duration-300">

        {{-- Logo / Brand --}}
        <div class="flex items-center px-4">
            <img src="{{ asset('images/queue_logo.png') }}" alt="Kiosqueeing Logo" class="h-16" />
            <span class="font-bold text-xl text-kiosqueeing-primar uppercase text-center"
                x-show="sidebarOpen">KioskQueuing</span>
        </div>

        {{-- Links --}}
        @can('superadmin')
            @livewire('super-admin.super-admin-nav')
        @endcan
        @can('admin')
            @livewire('admin-nav')
        @endcan
        @can('staff')
            @livewire('staff-nav')
        @endcan


        {{-- Sidebar footer --}}
        <div class="p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-3 py-2 rounded-md hover:bg-kiosqueeing-primary/5 text-sm text-kiosqueeing-primary font-medium transition">
                    @svg('heroicon-o-arrow-left-on-rectangle', ['class' => 'w-5 h-5 mr-3'])
                    <span x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col">

        {{-- Topbar --}}
        <header class="h-16 bg-kiosqueeing-sidebar border-b border-gray-200 px-6 flex items-center justify-between">

            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 hover:text-kiosqueeing-primary transition">
                    <template x-if="sidebarOpen">
                        @svg('heroicon-o-bars-3', 'w-6 h-6')
                    </template>
                    <template x-if="!sidebarOpen">
                        @svg('heroicon-o-bars-3', 'w-6 h-6')
                    </template>
                </button>

                <h1 class="text-lg font-semibold text-kiosqueeing-primary">
                    @yield('nav-title', 'Dashboard')
                </h1>

            </div>


            {{-- Profile dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center space-x-2 focus:outline-none">
                    <span class="font-medium text-sm text-kiosqueeing-text">Admin</span>
                    <div
                        class="w-8 h-8 rounded-full bg-kiosqueeing-primary text-white flex items-center justify-center">
                        A
                    </div>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50"
                    style="display: none;">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-kiosqueeing-primary hover:bg-kiosqueeing-primary/5">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        </header>

        {{-- Page Content --}}
        <main class="p-6">
            {{ $slot }}
        </main>

    </div>

</div>
