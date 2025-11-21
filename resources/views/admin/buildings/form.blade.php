@extends('admin.layout')

@section('title', isset($building) ? 'Edit Building' : 'Add Building')
@section('header', isset($building) ? 'Edit Building' : 'Add New Building')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ isset($building) ? route('buildings.update', $building) : route('buildings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($building))
                @method('PUT')
            @endif

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-lg font-medium mb-2">Building Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $building->name ?? '') }}"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="image_path" class="block text-gray-700 text-lg font-medium mb-2">Building Image</label>
                @if(isset($building) && $building->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $building->image_path) }}" alt="{{ $building->name }}" class="w-32 h-32 object-cover rounded">
                    </div>
                @endif
                <input 
                    type="file" 
                    id="image_path" 
                    name="image_path" 
                    accept="image/*"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('image_path') border-red-500 @enderror"
                >
                @error('image_path')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="map_x" class="block text-gray-700 text-lg font-medium mb-2">Map X Coordinate</label>
                    <input 
                        type="number" 
                        id="map_x" 
                        name="map_x" 
                        value="{{ old('map_x', $building->map_x ?? 100) }}"
                        class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('map_x') border-red-500 @enderror"
                        required
                    >
                    @error('map_x')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="map_y" class="block text-gray-700 text-lg font-medium mb-2">Map Y Coordinate</label>
                    <input 
                        type="number" 
                        id="map_y" 
                        name="map_y" 
                        value="{{ old('map_y', $building->map_y ?? 100) }}"
                        class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('map_y') border-red-500 @enderror"
                        required
                    >
                    @error('map_y')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 text-lg"
                >
                    {{ isset($building) ? 'Update Building' : 'Create Building' }}
                </button>
                <a 
                    href="{{ route('buildings.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 text-lg"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
