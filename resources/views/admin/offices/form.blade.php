@extends('admin.layout')

@section('title', isset($office) ? 'Edit Office' : 'Add Office')
@section('header', isset($office) ? 'Edit Office' : 'Add New Office')

@section('content')
<!-- Full Page Background -->
<div class="min-h-screen relative" style="background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat fixed;">
    <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 via-teal-800/70 to-green-900/80"></div>
    
    <div class="relative z-10 p-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 rounded-3xl p-8 shadow-2xl border-4 border-white/30">
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.offices.index') }}" 
                       class="group bg-white/20 hover:bg-white/30 text-white p-4 rounded-2xl transition-all duration-300 border-2 border-white/30 hover:border-white/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-20 w-20 drop-shadow-2xl">
                    <div>
                        <h1 class="text-4xl font-black text-white drop-shadow-lg tracking-tight uppercase">
                            {{ isset($office) ? '✏️ Edit Office' : '➕ Add Office' }}
                        </h1>
                        <p class="text-lg text-blue-100 mt-2 font-semibold">{{ isset($office) ? $office->name : 'Create New Office' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border-4 border-blue-600/30">
        <form action="{{ isset($office) ? route('admin.offices.update', $office) : route('admin.offices.store') }}" method="POST">
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

