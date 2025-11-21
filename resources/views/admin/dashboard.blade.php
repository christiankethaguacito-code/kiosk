@extends('layouts.dual-mode')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Dashboard</h1>
                
                <div class="flex items-center gap-4">
                    <span class="text-gray-700 font-mono text-xl bg-gray-100 px-4 py-2 rounded-lg" x-text="currentTime"></span>
                    
                    <div class="relative">
                        <button @click="menuOpen = !menuOpen" 
                                class="p-3 bg-blue-600 hover:bg-blue-700 rounded-lg transition shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path>
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
                             x-cloak>
                            
                            @auth
                            <div class="p-3 border-b border-gray-200">
                                <p class="text-sm text-gray-500">Signed in as</p>
                                <p class="font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                            </div>
                            @endauth
                            
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
                            
                            @auth
                            <div class="border-t border-gray-200 py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="border-t border-gray-200 py-2">
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-blue-600 hover:bg-blue-50 transition">
                                    🔐 Login
                                </a>
                            </div>
                            @endauth
                        </div>
                    </div>
                    
                    <a href="{{ route('kiosk.idle') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
                        🏠 Back to Kiosk
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="text-5xl font-bold mb-2">{{ $stats['buildings'] }}</div>
                    <div class="text-xl">Buildings</div>
                    <a href="{{ route('admin.buildings.index') }}" class="inline-block mt-4 text-sm underline hover:text-blue-200">
                        Manage →
                    </a>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="text-5xl font-bold mb-2">{{ $stats['offices'] }}</div>
                    <div class="text-xl">Offices</div>
                    <a href="{{ route('admin.offices.index') }}" class="inline-block mt-4 text-sm underline hover:text-green-200">
                        Manage →
                    </a>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="text-5xl font-bold mb-2">{{ $stats['services'] }}</div>
                    <div class="text-xl">Services</div>
                    <a href="{{ route('admin.services.index') }}" class="inline-block mt-4 text-sm underline hover:text-purple-200">
                        Manage →
                    </a>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="text-5xl font-bold mb-2">{{ $stats['announcements'] }}</div>
                    <div class="text-xl">Announcements</div>
                    <a href="{{ route('admin.announcements.index') }}" class="inline-block mt-4 text-sm underline hover:text-orange-200">
                        Manage →
                    </a>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('kiosk.map') }}" class="bg-white border-2 border-gray-300 hover:border-blue-500 rounded-lg p-4 text-center transition">
                        <div class="text-4xl mb-2">🗺️</div>
                        <div class="font-semibold">View Map</div>
                    </a>
                    
                    <a href="{{ route('admin.map-config') }}" class="bg-white border-2 border-gray-300 hover:border-blue-500 rounded-lg p-4 text-center transition">
                        <div class="text-4xl mb-2">⚙️</div>
                        <div class="font-semibold">Map Settings</div>
                    </a>
                    
                    <a href="{{ route('admin.buildings.create') }}" class="bg-white border-2 border-gray-300 hover:border-blue-500 rounded-lg p-4 text-center transition">
                        <div class="text-4xl mb-2">➕</div>
                        <div class="font-semibold">Add Building</div>
                    </a>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-4">
                <h3 class="font-bold text-blue-800 mb-2">💡 Inline Editing Mode</h3>
                <p class="text-blue-700">
                    Click the <strong>"Enable Edit Mode"</strong> button at the bottom-right of any public page to activate inline editing.
                    Drag buildings, click to edit text, and manage content visually without leaving the kiosk interface.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
