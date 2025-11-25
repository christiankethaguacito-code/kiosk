@extends('admin.layout')

@section('title', 'Edit Service')
@section('header', 'Edit Service')

@section('content')
<!-- Full Page Background -->
<div class="min-h-screen relative" style="background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat fixed;">
    <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 via-teal-800/70 to-green-900/80"></div>
    
    <div class="relative z-10 p-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-700 to-purple-800 rounded-3xl p-8 shadow-2xl border-4 border-white/30">
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.services.index') }}" 
                       class="group bg-white/20 hover:bg-white/30 text-white p-4 rounded-2xl transition-all duration-300 border-2 border-white/30 hover:border-white/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-20 w-20 drop-shadow-2xl">
                    <div>
                        <h1 class="text-4xl font-black text-white drop-shadow-lg tracking-tight uppercase">
                            ✏️ Edit Service
                        </h1>
                        <p class="text-lg text-purple-100 mt-2 font-semibold">{{ $service->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border-4 border-purple-600/30">
    <form action="{{ route('admin.services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label for="office_id" class="block text-sm font-medium text-gray-700 mb-2">Office</label>
            <select name="office_id" id="office_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('office_id') border-red-500 @enderror">
                <option value="">Select Office</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" {{ old('office_id', $service->office_id) == $office->id ? 'selected' : '' }}>
                        {{ $office->name }} ({{ $office->building->name }})
                    </option>
                @endforeach
            </select>
            @error('office_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Service Description</label>
            <textarea name="description" id="description" rows="4" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $service->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                Update Service
            </button>
            <a href="{{ route('admin.services.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

