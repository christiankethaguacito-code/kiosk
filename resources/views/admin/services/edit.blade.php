@extends('admin.layout')

@section('title', 'Edit Service')
@section('header', 'Edit Service')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
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

