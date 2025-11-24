<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Campus Kiosk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" x-data="{ drawerOpen: false }">
    <!-- Hamburger Drawer -->
    <div>
        <!-- Overlay -->
        <div x-show="drawerOpen" 
             @click="drawerOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-40"
             style="display: none;">
        </div>

        <!-- Drawer -->
        <aside x-show="drawerOpen"
               @click.away="drawerOpen = false"
               x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed left-0 top-0 h-full w-80 text-white z-50 shadow-2xl"
               style="background: linear-gradient(180deg, #248823 0%, #1a6619 100%); display: none;">
            
            <div class="flex items-center justify-between p-6 border-b border-white border-opacity-20">
                <div>
                    <h2 class="text-2xl font-bold">Campus Kiosk</h2>
                    <p class="text-white text-sm opacity-80">Admin Panel</p>
                </div>
                <button @click="drawerOpen = false" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="mt-6 px-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 py-3 px-4 mb-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-2xl">📊</span>
                    <span class="text-lg font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.buildings.index') }}" 
                   class="flex items-center gap-3 py-3 px-4 mb-2 rounded-lg {{ request()->routeIs('admin.buildings.*') ? 'bg-white bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-2xl">🏢</span>
                    <span class="text-lg font-medium">Buildings</span>
                </a>
                <a href="{{ route('admin.offices.index') }}" 
                   class="flex items-center gap-3 py-3 px-4 mb-2 rounded-lg {{ request()->routeIs('admin.offices.*') ? 'bg-white bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-2xl">🏛️</span>
                    <span class="text-lg font-medium">Offices</span>
                </a>
                <a href="{{ route('admin.services.index') }}" 
                   class="flex items-center gap-3 py-3 px-4 mb-2 rounded-lg {{ request()->routeIs('admin.services.*') ? 'bg-white bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-2xl">📋</span>
                    <span class="text-lg font-medium">Services</span>
                </a>
                <a href="{{ route('admin.announcements.index') }}" 
                   class="flex items-center gap-3 py-3 px-4 mb-2 rounded-lg {{ request()->routeIs('admin.announcements.*') ? 'bg-white bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-2xl">📢</span>
                    <span class="text-lg font-medium">Announcements</span>
                </a>
                <a href="{{ route('admin.map-config') }}" 
                   class="flex items-center gap-3 py-3 px-4 mb-2 rounded-lg {{ request()->routeIs('admin.map-config') ? 'bg-white bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-2xl">⚙️</span>
                    <span class="text-lg font-medium">Map Settings</span>
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-white border-opacity-20">
                <div class="mb-4 pb-4 border-b border-white border-opacity-20">
                    <p class="text-sm opacity-80 mb-1">Signed in as</p>
                    <p class="font-semibold truncate">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('kiosk.map') }}" 
                   class="flex items-center gap-3 py-2 px-4 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-200">
                    <span class="text-xl">🗺️</span>
                    <span class="font-medium">View Campus Map</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 py-2 px-4 rounded-lg text-red-100 hover:bg-red-500 hover:bg-opacity-30 transition duration-200">
                        <span class="text-xl">🚪</span>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>
    </div>

    <div class="flex flex-col h-screen">
        <header class="bg-white shadow-sm" x-data="{ currentTime: '' }" 
                x-init="setInterval(() => { 
                    const now = new Date(); 
                    currentTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); 
                }, 1000)">
            <div class="flex justify-between items-center px-8 py-4">
                <div class="flex items-center gap-4">
                    <button @click="drawerOpen = true" 
                            class="p-2 rounded-lg transition duration-200"
                            style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);"
                            onmouseover="this.style.opacity='0.9'"
                            onmouseout="this.style.opacity='1'">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                </div>
                <span class="text-gray-600 font-mono text-lg" x-text="currentTime"></span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            @if(session('success'))
                <div class="px-4 py-3 rounded mb-4" style="background-color: rgba(36, 136, 35, 0.15); border: 1px solid #248823; color: #1a6619;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>

