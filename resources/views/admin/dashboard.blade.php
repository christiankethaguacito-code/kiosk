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
                    
                    <a href="{{ route('kiosk.idle') }}" class="text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);" onmouseover="this.style.background='linear-gradient(135deg, #1f7320 0%, #165014 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #248823 0%, #1a6619 100%)'">
                        🏠 Back to Kiosk
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="rounded-xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-5xl font-bold">{{ $stats['buildings'] }}</div>
                        <div class="text-5xl">🏢</div>
                    </div>
                    <div class="text-xl mb-1">Buildings</div>
                    <a href="{{ route('admin.buildings.index') }}" class="inline-block mt-2 text-sm underline hover:opacity-80">
                        Manage →
                    </a>
                </div>

                <div class="rounded-xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #1a6619 0%, #165014 100%);">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-5xl font-bold">{{ $stats['offices'] }}</div>
                        <div class="text-5xl">🏛️</div>
                    </div>
                    <div class="text-xl mb-1">Offices</div>
                    <a href="{{ route('admin.offices.index') }}" class="inline-block mt-2 text-sm underline hover:opacity-80">
                        Manage →
                    </a>
                </div>

                <div class="rounded-xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #5AB89D 0%, #3FA07D 100%);">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-5xl font-bold">{{ $stats['services'] }}</div>
                        <div class="text-5xl">📋</div>
                    </div>
                    <div class="text-xl mb-1">Services</div>
                    <a href="{{ route('admin.services.index') }}" class="inline-block mt-2 text-sm underline hover:opacity-80">
                        Manage →
                    </a>
                </div>

                <div class="rounded-xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #1f7320 0%, #5AB89D 100%);">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-5xl font-bold">{{ $stats['announcements'] }}</div>
                        <div class="text-5xl">📢</div>
                    </div>
                    <div class="text-xl mb-1">Announcements</div>
                    <a href="{{ route('admin.announcements.index') }}" class="inline-block mt-2 text-sm underline hover:opacity-80">
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

            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">📢 Recent Announcements</h2>
                    <a href="{{ route('admin.announcements.create') }}" class="text-white px-4 py-2 rounded-lg text-sm font-semibold transition" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
                        + Add New
                    </a>
                </div>
                
                @if($recentAnnouncements->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAnnouncements as $announcement)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
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
                                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
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
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(36, 136, 35, 0.2); color: #1a6619;">Active</span>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">Inactive</span>
                                                @endif
                                                <div class="flex gap-2">
                                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Delete this announcement?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.announcements.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                            View All Announcements →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-5xl mb-3">📢</div>
                        <p>No announcements yet</p>
                        <a href="{{ route('admin.announcements.create') }}" class="inline-block mt-3 text-sm underline" style="color: #248823;">
                            Create your first announcement
                        </a>
                    </div>
                @endif
            </div>

            <div class="border-l-4 rounded p-4" style="background-color: rgba(36, 136, 35, 0.1); border-color: #248823;">
                <h3 class="font-bold mb-2" style="color: #1a6619;">💡 Inline Editing Mode</h3>
                <p style="color: #3d7a6f;">
                    Click the <strong>"Enable Edit Mode"</strong> button at the bottom-right of any public page to activate inline editing.
                    Drag buildings, click to edit text, and manage content visually without leaving the kiosk interface.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection


