<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Campus Kiosk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <aside class="w-64 bg-green-600 text-white">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Campus Kiosk</h2>
                <p class="text-green-200 text-sm">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-3 px-6 hover:bg-green-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-700' : '' }}">
                    <span class="text-lg">📊 Dashboard</span>
                </a>
                <a href="{{ route('buildings.index') }}" class="block py-3 px-6 hover:bg-green-700 {{ request()->routeIs('buildings.*') ? 'bg-green-700' : '' }}">
                    <span class="text-lg">🏢 Buildings</span>
                </a>
                <a href="{{ route('offices.index') }}" class="block py-3 px-6 hover:bg-green-700 {{ request()->routeIs('offices.*') ? 'bg-green-700' : '' }}">
                    <span class="text-lg">🏛️ Offices</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm" x-data="{ menuOpen: false, currentTime: '' }" 
                    x-init="setInterval(() => { 
                        const now = new Date(); 
                        currentTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); 
                    }, 1000)">
                <div class="flex justify-between items-center px-8 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600 font-mono text-lg" x-text="currentTime"></span>
                        
                        <div class="relative">
                            <button @click="menuOpen = !menuOpen" 
                                    class="p-2 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            
                            <div x-show="menuOpen" 
                                 @click.away="menuOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                                 style="display: none;">
                                
                                <div class="p-3 border-b border-gray-200">
                                    <p class="text-sm text-gray-500">Signed in as</p>
                                    <p class="font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                                </div>
                                
                                <div class="py-2">
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        📊 Dashboard
                                    </a>
                                    <a href="{{ route('admin.buildings.index') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        🏢 Manage Buildings
                                    </a>
                                    <a href="{{ route('admin.offices.index') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        🏛️ Manage Offices
                                    </a>
                                    <a href="{{ route('admin.services.index') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        📋 Manage Services
                                    </a>
                                    <a href="{{ route('admin.announcements.index') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        📢 Manage Announcements
                                    </a>
                                    <a href="{{ route('admin.map-config') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        ⚙️ Map Settings
                                    </a>
                                    <a href="{{ route('dual.map') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                        🗺️ View Campus Map
                                    </a>
                                </div>
                                
                                <div class="border-t border-gray-200 py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                            🚪 Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
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
    </div>
</body>
</html>
