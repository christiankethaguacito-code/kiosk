@extends('layouts.dual-mode')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Full Page Background -->
<div class="min-h-screen relative" style="background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat fixed;">
    <!-- Overlay for better readability -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 via-teal-800/70 to-green-900/80"></div>
    
    <div class="relative z-10 p-8 max-w-7xl mx-auto">
        <!-- Header with SKSU Branding Style -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-teal-700 to-teal-800 rounded-3xl p-8 shadow-2xl border-4 border-white/30">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-6">
                        <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-24 w-24 drop-shadow-2xl">
                        <div>
                            <h1 class="text-5xl font-black text-white drop-shadow-lg tracking-tight uppercase">
                                📊 Admin Dashboard
                            </h1>
                            <p class="text-xl text-teal-100 mt-2 font-semibold">Sultan Kudarat State University</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-white font-mono text-xl bg-white/20 backdrop-blur-sm px-6 py-3 rounded-2xl font-bold border-2 border-white/30 shadow-lg" x-text="currentTime"></span>
                        
                        <div class="relative">
                            <button @click="menuOpen = !menuOpen" 
                                    class="p-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-2xl transition-all duration-300 shadow-2xl border-2 border-white/30 transform hover:scale-105">
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
                             class="absolute right-0 mt-2 w-64 bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border-4 border-teal-600/30 z-50"
                             x-cloak>
                            
                            @auth
                            <div class="p-4 border-b-2 border-teal-600/20">
                                <p class="text-sm text-teal-600 font-semibold">Signed in as</p>
                                <p class="font-bold text-gray-800">{{ auth()->user()->email }}</p>
                            </div>
                            @endauth
                            
                            <div class="py-2">
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    📊 Dashboard
                                </a>
                                <a href="{{ route('admin.buildings.index') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    🏢 Manage Buildings
                                </a>
                                <a href="{{ route('admin.offices.index') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    🏛️ Manage Offices
                                </a>
                                <a href="{{ route('admin.services.index') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    📋 Manage Services
                                </a>
                                <a href="{{ route('admin.announcements.index') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    📢 Manage Announcements
                                </a>
                                <a href="{{ route('admin.map-config') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    ⚙️ Map Settings
                                </a>
                                <a href="{{ route('dual.map') }}" class="block px-4 py-3 hover:bg-teal-50 transition font-semibold text-gray-700">
                                    🗺️ View Campus Map
                                </a>
                            </div>
                            
                            @auth
                            <div class="border-t-2 border-teal-600/20 py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 transition font-bold">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="border-t-2 border-teal-600/20 py-2">
                                <a href="{{ route('login') }}" class="block px-4 py-3 text-teal-600 hover:bg-teal-50 transition font-bold">
                                    🔐 Login
                                </a>
                            </div>
                            @endauth
                        </div>
                    </div>
                        
                        <a href="{{ route('kiosk.idle') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-2xl font-black shadow-2xl transition-all duration-300 transform hover:scale-105 uppercase border-2 border-white/30">
                            🏠 Back to Kiosk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards with SKSU Style -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-green-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-green-700 font-bold uppercase">Buildings</p>
                        <p class="text-5xl font-black text-green-900 mt-2">{{ $stats['buildings'] }}</p>
                        <a href="{{ route('admin.buildings.index') }}" class="inline-block mt-3 text-sm font-bold text-green-600 hover:text-green-800 underline">
                            Manage →
                        </a>
                    </div>
                    <div class="text-6xl">🏢</div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-teal-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-teal-700 font-bold uppercase">Offices</p>
                        <p class="text-5xl font-black text-teal-900 mt-2">{{ $stats['offices'] }}</p>
                        <a href="{{ route('admin.offices.index') }}" class="inline-block mt-3 text-sm font-bold text-teal-600 hover:text-teal-800 underline">
                            Manage →
                        </a>
                    </div>
                    <div class="text-6xl">🏛️</div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-blue-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-blue-700 font-bold uppercase">Services</p>
                        <p class="text-5xl font-black text-blue-900 mt-2">{{ $stats['services'] }}</p>
                        <a href="{{ route('admin.services.index') }}" class="inline-block mt-3 text-sm font-bold text-blue-600 hover:text-blue-800 underline">
                            Manage →
                        </a>
                    </div>
                    <div class="text-6xl">📋</div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-red-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-red-700 font-bold uppercase">Announcements</p>
                        <p class="text-5xl font-black text-red-900 mt-2">{{ $stats['announcements'] }}</p>
                        <a href="{{ route('admin.announcements.index') }}" class="inline-block mt-3 text-sm font-bold text-red-600 hover:text-red-800 underline">
                            Manage →
                        </a>
                    </div>
                    <div class="text-6xl">📢</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions with SKSU Style -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-8 mb-8 shadow-2xl border-4 border-teal-600/30">
            <h2 class="text-3xl font-black text-teal-900 mb-6 uppercase">⚡ Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('kiosk.map') }}" class="bg-gradient-to-br from-teal-50 to-teal-100 border-4 border-teal-600/30 hover:border-teal-600/50 rounded-2xl p-6 text-center transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <div class="text-5xl mb-3">🗺️</div>
                    <div class="font-black text-teal-900 text-lg">View Map</div>
                </a>
                
                <a href="{{ route('admin.map-config') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 border-4 border-blue-600/30 hover:border-blue-600/50 rounded-2xl p-6 text-center transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <div class="text-5xl mb-3">⚙️</div>
                    <div class="font-black text-blue-900 text-lg">Map Settings</div>
                </a>
                
                <a href="{{ route('admin.buildings.create') }}" class="bg-gradient-to-br from-green-50 to-green-100 border-4 border-green-600/30 hover:border-green-600/50 rounded-2xl p-6 text-center transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <div class="text-5xl mb-3">➕</div>
                    <div class="font-black text-green-900 text-lg">Add Building</div>
                </a>
            </div>
        </div>

        <!-- Recent Announcements with SKSU Style -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-8 mb-8 shadow-2xl border-4 border-red-600/30">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-black text-red-900 uppercase">📢 Recent Announcements</h2>
                <a href="{{ route('admin.announcements.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-2xl text-sm font-black transition-all duration-300 shadow-xl transform hover:scale-105 uppercase border-2 border-white/30">
                    + Add New
                </a>
            </div>
            
            @if($recentAnnouncements->count() > 0)
                <div class="space-y-4">
                    @foreach($recentAnnouncements as $announcement)
                        <div class="bg-gradient-to-br from-white to-red-50/30 border-4 border-red-600/20 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-102">
                                <div class="flex items-start gap-4">
                                    @if($announcement->image_path)
                                        <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-teal-100 to-green-100 rounded-lg flex items-center justify-center text-3xl">
                                            📢
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="font-semibold text-lg text-gray-800">{{ $announcement->title }}</h3>
                                                <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ Str::limit($announcement->content, 120) }}</p>
                                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-600 font-semibold">
                                                    @if($announcement->display_order)
                                                        <span>Order: {{ $announcement->display_order }}</span>
                                                    @endif
                                                    @if($announcement->starts_at)
                                                        <span>{{ $announcement->display_order ? '•' : '' }} From: {{ $announcement->starts_at->format('M d, Y') }}</span>
                                                    @endif
                                                    @if($announcement->ends_at)
                                                        <span>• To: {{ $announcement->ends_at->format('M d, Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end gap-2">
                                                @if($announcement->is_active)
                                                    <span class="px-4 py-2 rounded-full text-xs font-black bg-green-100 text-green-700 border-2 border-green-600/30">✓ Active</span>
                                                @else
                                                    <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-xs font-black border-2 border-gray-300">Inactive</span>
                                                @endif
                                                <div class="flex gap-2">
                                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-200 transition border-2 border-blue-600/30">Edit</a>
                                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-100 text-red-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-200 transition border-2 border-red-600/30" onclick="return confirm('Delete this announcement?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.announcements.index') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-2xl text-sm font-black transition-all duration-300 shadow-lg transform hover:scale-105 uppercase border-2 border-white/30">
                        View All Announcements →
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📢</div>
                    <p class="text-gray-600 font-bold text-lg mb-4">No announcements yet</p>
                    <a href="{{ route('admin.announcements.create') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-2xl text-sm font-black transition-all duration-300 shadow-lg transform hover:scale-105 uppercase border-2 border-white/30">
                        Create Your First Announcement
                    </a>
                </div>
            @endif
        </div>

        <!-- Info Card with SKSU Style -->
        <div class="bg-gradient-to-br from-green-50 to-teal-50 border-l-8 border-green-600 rounded-2xl p-6 shadow-xl">
            <h3 class="font-black text-xl mb-3 text-green-900">💡 Inline Editing Mode</h3>
            <p class="text-gray-700 font-semibold leading-relaxed">
                Click the <strong class="text-green-700">"Enable Edit Mode"</strong> button at the bottom-right of any public page to activate inline editing.
                Drag buildings, click to edit text, and manage content visually without leaving the kiosk interface.
            </p>
        </div>
    </div>
</div>
@endsection
