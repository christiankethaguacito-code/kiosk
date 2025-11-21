@extends('admin.layout')

@section('title', 'Map Settings')
@section('header', 'Update Map Data')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.map_settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Kiosk Starting Coordinates</h3>
            <p class="text-gray-600 text-sm mb-4">Set the fixed starting point for navigation paths on the map</p>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kiosk X Coordinate</label>
                    <input 
                        type="number" 
                        name="kiosk_x" 
                        value="{{ old('kiosk_x', $kioskX) }}" 
                        step="0.01"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kiosk_x') border-red-500 @enderror"
                        required
                    />
                    @error('kiosk_x')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kiosk Y Coordinate</label>
                    <input 
                        type="number" 
                        name="kiosk_y" 
                        value="{{ old('kiosk_y', $kioskY) }}" 
                        step="0.01"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kiosk_y') border-red-500 @enderror"
                        required
                    />
                    @error('kiosk_y')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Campus Map Image</h3>
            <p class="text-gray-600 text-sm mb-4">Upload a custom campus map image (SVG, PNG, or JPG)</p>
            
            @if($mapImagePath)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current Map:</p>
                    <img src="{{ Storage::url($mapImagePath) }}" alt="Current Map" class="max-w-md border rounded">
                </div>
            @endif
            
            <input 
                type="file" 
                name="map_image" 
                accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('map_image') border-red-500 @enderror"
            />
            @error('map_image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">Max size: 5MB. Formats: JPEG, PNG, JPG, SVG</p>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
