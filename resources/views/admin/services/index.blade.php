@extends('admin.layout')

@section('title', 'Services')
@section('header', 'Manage Services')

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
                        <a href="{{ route('admin.dashboard') }}" 
                           class="group bg-white/20 hover:bg-white/30 text-white p-4 rounded-2xl transition-all duration-300 border-2 border-white/30 hover:border-white/50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-24 w-24 drop-shadow-2xl">
                        <div>
                            <h1 class="text-5xl font-black text-white drop-shadow-lg tracking-tight uppercase">
                                📋 Services
                            </h1>
                            <p class="text-xl text-teal-100 mt-2 font-semibold">Manage Campus Services</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.services.create') }}" 
                       class="group bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-8 py-4 rounded-2xl text-xl font-black transition-all duration-300 shadow-2xl hover:shadow-purple-500/50 transform hover:scale-105 flex items-center gap-3 uppercase border-4 border-white/30">
                        <svg class="w-7 h-7 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-8 border-green-600 text-green-800 p-6 mb-8 rounded-2xl shadow-xl">
                <p class="font-black text-lg">✓ {{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border-4 border-purple-600/30">
            <table class="min-w-full divide-y-4 divide-purple-600/20">
                <thead class="bg-gradient-to-r from-purple-600 to-purple-700">
                    <tr>
                        <th class="px-8 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Description</th>
                        <th class="px-8 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Office</th>
                        <th class="px-8 py-5 text-left text-sm font-black text-white uppercase tracking-wider">Building</th>
                        <th class="px-8 py-5 text-right text-sm font-black text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y-2 divide-purple-600/10">
                    @forelse($services as $service)
                    <tr class="hover:bg-purple-50/50 transition-colors duration-200">
                        <td class="px-8 py-6 text-base font-bold text-gray-900">{{ $service->description }}</td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $service->office->name ?? 'N/A' }}</td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $service->office->building->name ?? 'N/A' }}</td>
                        <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-bold">
                            <a href="{{ route('admin.services.edit', $service) }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl hover:bg-blue-200 transition border-2 border-blue-600/30 mr-2">Edit</a>
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-700 px-4 py-2 rounded-xl hover:bg-red-200 transition border-2 border-red-600/30">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center">
                            <div class="text-6xl mb-4">📋</div>
                            <p class="text-gray-600 font-bold text-lg">No services found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection

