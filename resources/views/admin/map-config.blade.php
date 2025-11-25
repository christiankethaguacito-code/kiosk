@extends('admin.layout')

@section('title', 'Map Configuration')

@section('content')
<!-- Full Page Background -->
<div class="min-h-screen relative" style="background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat fixed;">
    <!-- Overlay for better readability -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 via-teal-800/70 to-green-900/80"></div>
    
    <div class="relative z-10 p-8 max-w-5xl mx-auto">
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
                                ⚙️ Map Configuration
                            </h1>
                            <p class="text-xl text-teal-100 mt-2 font-semibold">Upload & Configure Campus Map</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-8 border-green-600 text-green-800 p-6 mb-8 rounded-2xl shadow-xl">
                <p class="font-black text-lg mb-2">✓ Success!</p>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-8 border-red-600 text-red-800 p-6 mb-8 rounded-2xl shadow-xl">
                <p class="font-black text-lg mb-2">✗ Error!</p>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border-4 border-teal-600/30">
            @if($mapExists)
                <div class="mb-8 p-6 bg-gradient-to-br from-blue-50 to-teal-50 rounded-2xl border-4 border-teal-600/30 shadow-lg">
                    <h3 class="font-black text-xl text-teal-900 mb-4 uppercase">📍 Current Map</h3>
                    <img src="{{ asset('storage/' . $currentMap) }}" alt="Current Campus Map" class="max-w-full h-auto border-4 border-teal-600/30 rounded-xl shadow-xl">
                </div>
            @endif

            <form method="POST" action="{{ route('admin.map-config.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="map_name" class="block text-lg font-black text-teal-900 mb-3 uppercase">
                        📝 Map Name (Optional)
                    </label>
                    <input 
                        type="text" 
                        id="map_name" 
                        name="map_name" 
                        class="w-full px-6 py-4 border-4 border-teal-600/30 rounded-2xl focus:ring-4 focus:ring-teal-500 focus:border-teal-600 font-semibold shadow-lg"
                        placeholder="e.g., Campus Map 2024"
                    >
                </div>

                <div>
                    <label for="map_image" class="block text-lg font-black text-teal-900 mb-3 uppercase">
                        📤 Upload New Map Image *
                    </label>
                    <div class="mt-2 flex justify-center px-8 pt-8 pb-8 border-4 border-teal-600/30 border-dashed rounded-2xl hover:border-teal-600/50 transition-all duration-300 bg-gradient-to-br from-teal-50/50 to-green-50/50 shadow-lg">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-16 w-16 text-teal-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-base text-teal-900 font-semibold">
                                <label for="map_image" class="relative cursor-pointer bg-white px-4 py-2 rounded-xl font-black text-teal-600 hover:text-teal-700 border-2 border-teal-600/30 hover:border-teal-600/50 shadow-lg">
                                    <span>Upload a file</span>
                                    <input 
                                        id="map_image" 
                                        name="map_image" 
                                        type="file" 
                                        class="sr-only" 
                                        accept=".svg,.png,.jpg,.jpeg"
                                        required
                                    >
                                </label>
                                <p class="pl-2 pt-2">or drag and drop</p>
                            </div>
                            <p class="text-sm text-teal-700 font-bold">SVG, PNG, JPG up to 10MB</p>
                        </div>
                    </div>
                    @error('map_image')
                        <p class="mt-3 text-sm text-red-700 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-2xl font-black transition-all duration-300 shadow-xl transform hover:scale-105 uppercase border-2 border-white/30">
                        Cancel
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-8 py-4 rounded-2xl font-black transition-all duration-300 shadow-xl transform hover:scale-105 uppercase border-2 border-white/30">
                        Upload Map
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

