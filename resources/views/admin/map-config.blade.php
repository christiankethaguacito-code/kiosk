@extends('layouts.admin')

@section('title', 'Map Configuration')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Update Map Data</h1>
        <p class="text-gray-600">Upload a new campus map image and configure navigation settings</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <p class="font-bold">Success!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-8">
        @if($mapExists)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">Current Map</h3>
                <img src="{{ asset('storage/' . $currentMap) }}" alt="Current Campus Map" class="max-w-full h-auto border rounded">
            </div>
        @endif

        <form method="POST" action="{{ route('admin.map-config.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="map_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Map Name (Optional)
                </label>
                <input 
                    type="text" 
                    id="map_name" 
                    name="map_name" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="e.g., Campus Map 2024"
                >
            </div>

            <div>
                <label for="map_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload New Map Image *
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="map_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
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
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">SVG, PNG, JPG up to 10MB</p>
                    </div>
                </div>
                @error('map_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Upload Map
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

