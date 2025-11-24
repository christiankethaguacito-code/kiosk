@extends('admin.layout')

@section('title', isset($office) ? 'Edit Office' : 'Add Office')
@section('header', isset($office) ? 'Edit Office' : 'Add New Office')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ isset($office) ? route('offices.update', $office) : route('offices.store') }}" method="POST">
            @csrf
            @if(isset($office))
                @method('PUT')
            @endif

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-lg font-medium mb-2">Office Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $office->name ?? '') }}"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="building_id" class="block text-gray-700 text-lg font-medium mb-2">Building</label>
                <select 
                    id="building_id" 
                    name="building_id" 
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('building_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Select Building</option>
                    @foreach($buildings as $building)
                        <option value="{{ $building->id }}" {{ old('building_id', $office->building_id ?? '') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
                @error('building_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="floor_number" class="block text-gray-700 text-lg font-medium mb-2">Floor Number</label>
                <input 
                    type="text" 
                    id="floor_number" 
                    name="floor_number" 
                    value="{{ old('floor_number', $office->floor_number ?? '') }}"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('floor_number') border-red-500 @enderror"
                    placeholder="e.g., 1st Floor, Ground Floor"
                >
                @error('floor_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="head_name" class="block text-gray-700 text-lg font-medium mb-2">Head of Office Name</label>
                <input 
                    type="text" 
                    id="head_name" 
                    name="head_name" 
                    value="{{ old('head_name', $office->head_name ?? '') }}"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('head_name') border-red-500 @enderror"
                >
                @error('head_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="head_title" class="block text-gray-700 text-lg font-medium mb-2">Head of Office Title</label>
                <input 
                    type="text" 
                    id="head_title" 
                    name="head_title" 
                    value="{{ old('head_title', $office->head_title ?? '') }}"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('head_title') border-red-500 @enderror"
                    placeholder="e.g., Director, Dean"
                >
                @error('head_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 text-lg"
                >
                    {{ isset($office) ? 'Update Office' : 'Create Office' }}
                </button>
                <a 
                    href="{{ route('offices.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 text-lg"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

