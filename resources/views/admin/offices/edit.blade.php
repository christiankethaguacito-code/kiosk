@extends('admin.layout')

@section('title', 'Edit Office')
@section('header', 'Edit Office')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
    <form action="{{ route('admin.offices.update', $office) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">Building</label>
            <select name="building_id" id="building_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('building_id') border-red-500 @enderror">
                <option value="">Select Building</option>
                @foreach($buildings as $building)
                    <option value="{{ $building->id }}" {{ old('building_id', $office->building_id) == $building->id ? 'selected' : '' }}>
                        {{ $building->name }}
                    </option>
                @endforeach
            </select>
            @error('building_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Office Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $office->name) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="floor_number" class="block text-sm font-medium text-gray-700 mb-2">Floor Number</label>
            <input type="text" name="floor_number" id="floor_number" value="{{ old('floor_number', $office->floor_number) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('floor_number') border-red-500 @enderror">
            @error('floor_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="head_name" class="block text-sm font-medium text-gray-700 mb-2">Head Name</label>
            <input type="text" name="head_name" id="head_name" value="{{ old('head_name', $office->head_name) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('head_name') border-red-500 @enderror">
            @error('head_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="head_title" class="block text-sm font-medium text-gray-700 mb-2">Head Title</label>
            <input type="text" name="head_title" id="head_title" value="{{ old('head_title', $office->head_title) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('head_title') border-red-500 @enderror">
            @error('head_title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                Update Office
            </button>
            <a href="{{ route('admin.offices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
