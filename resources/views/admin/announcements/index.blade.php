@extends('admin.layout')

@section('content')
<!-- Full Page Background -->
<div class="min-h-screen relative" style="background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat fixed;">
    <!-- Overlay for better readability -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 via-teal-800/70 to-green-900/80"></div>
    
    <div class="relative z-10 p-8 max-w-7xl mx-auto">
        <!-- Header with SKSU Branding Style -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-teal-700 to-teal-800 rounded-3xl p-8 shadow-2xl border-4 border-white/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-24 w-24 drop-shadow-2xl">
                        <div>
                            <h1 class="text-5xl font-black text-white drop-shadow-lg tracking-tight uppercase">
                                📢 Announcements
                            </h1>
                            <p class="text-xl text-teal-100 mt-2 font-semibold">Sultan Kudarat State University</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.announcements.create') }}" 
                       class="group bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-4 rounded-2xl text-xl font-black transition-all duration-300 shadow-2xl hover:shadow-red-500/50 transform hover:scale-105 flex items-center gap-3 uppercase border-4 border-white/30">
                        <svg class="w-7 h-7 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards with SKSU Style -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-teal-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-teal-700 font-bold uppercase">Total</p>
                        <p class="text-5xl font-black text-teal-900 mt-2">{{ $announcements->count() }}</p>
                    </div>
                    <div class="text-6xl">📊</div>
                </div>
            </div>
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-green-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-green-700 font-bold uppercase">Active</p>
                        <p class="text-5xl font-black text-green-900 mt-2">{{ $announcements->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="text-6xl">✅</div>
                </div>
            </div>
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl border-4 border-yellow-600/30 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg text-yellow-700 font-bold uppercase">Scheduled</p>
                        <p class="text-5xl font-black text-yellow-900 mt-2">{{ $announcements->where('starts_at', '>', now())->count() }}</p>
                    </div>
                    <div class="text-6xl">📅</div>
                </div>
            </div>
        </div>
    </div>

        <!-- SKSU-Styled Table -->
        <div class="relative z-10">
            <div class="bg-white/95 backdrop-blur-sm shadow-2xl rounded-3xl overflow-hidden border-4 border-teal-600/30">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-teal-700 to-teal-800">
                        <tr>
                            <th class="px-6 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Image</th>
                            <th class="px-6 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Title & Content</th>
                            <th class="px-6 py-5 text-center text-sm font-black text-white uppercase tracking-wider">Order</th>
                            <th class="px-6 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Schedule</th>
                            <th class="px-6 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-5 text-center text-sm font-black text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/90 divide-y-4 divide-teal-100">
                        @forelse($announcements as $announcement)
                            <tr class="hover:bg-teal-50/50 transition-all duration-300 transform hover:scale-[1.01]">
                                <td class="px-6 py-6">
                                    @if($announcement->image_path)
                                        <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                                             alt="{{ $announcement->title }}" 
                                             class="w-32 h-32 object-cover rounded-2xl shadow-lg ring-4 ring-teal-300 hover:ring-yellow-400 transition-all duration-300 transform hover:scale-110">
                                    @else
                                        <div class="w-32 h-32 bg-gradient-to-br from-teal-200 via-teal-300 to-green-300 rounded-2xl flex items-center justify-center text-5xl shadow-lg ring-4 ring-teal-300">
                                            📢
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-6">
                                    <div class="font-black text-teal-900 text-2xl mb-2 uppercase">{{ $announcement->title }}</div>
                                    <div class="text-base text-gray-700 leading-relaxed font-medium">{{ Str::limit($announcement->content, 120) }}</div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-500 text-yellow-900 font-black text-2xl shadow-lg ring-4 ring-yellow-300">
                                        {{ $announcement->display_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    @if($announcement->starts_at || $announcement->ends_at)
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2 text-sm">
                                                <span class="text-teal-700 font-black uppercase">📅 Start:</span>
                                                <span class="text-teal-900 font-bold">{{ $announcement->starts_at?->format('M d, Y') ?? 'No start' }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <span class="text-teal-700 font-black uppercase">📅 End:</span>
                                                <span class="text-teal-900 font-bold">{{ $announcement->ends_at?->format('M d, Y') ?? 'No end' }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl text-sm font-black uppercase shadow-lg ring-4 ring-blue-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Always Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center space-x-3">
                                        <span class="relative flex h-4 w-4">
                                            @if($announcement->is_active)
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 ring-2 ring-white"></span>
                                            @else
                                                <span class="relative inline-flex rounded-full h-4 w-4 bg-gray-500 ring-2 ring-white"></span>
                                            @endif
                                        </span>
                                        <span class="px-5 py-2.5 rounded-xl text-sm font-black uppercase tracking-wider shadow-lg {{ $announcement->is_active ? 'bg-gradient-to-r from-green-500 to-green-600 text-white ring-4 ring-green-300' : 'bg-gradient-to-r from-gray-400 to-gray-500 text-white ring-4 ring-gray-300' }}">
                                            {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white hover:from-teal-700 hover:to-teal-800 rounded-xl font-black text-base uppercase transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 border-4 border-white/30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 rounded-xl font-black text-base uppercase transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 border-4 border-white/30" 
                                                    onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                    </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20">
                                    <div class="text-center">
                                        <div class="text-9xl mb-6 animate-bounce">📢</div>
                                        <h3 class="text-4xl font-black text-teal-900 mb-4 uppercase">No Announcements Yet</h3>
                                        <p class="text-xl text-gray-700 mb-8 font-bold">Start engaging your campus community by creating your first announcement</p>
                                        <a href="{{ route('admin.announcements.create') }}" 
                                           class="inline-flex items-center gap-3 px-8 py-5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-2xl font-black text-xl uppercase transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:scale-105 border-4 border-white/30">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Create Your First Announcement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($announcements->hasPages())
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif
</div>

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endsection

