@extends('admin.layout')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.announcements.index') }}" 
           class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white p-3 rounded-xl transition-all duration-300 shadow-lg hover:shadow-teal-500/50 transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Announcement</h1>
    </div>

    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;" required>
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="content" value="{{ old('content', $announcement->content) }}">

        <div class="mb-4">
            <label for="image_path" class="block text-gray-700 font-medium mb-2">Upload Announcement</label>
            @if($announcement->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-32 h-32 object-cover rounded" id="currentImage">
                    <p class="text-sm text-gray-600 mt-1">Current image</p>
                </div>
            @endif
            <input type="file" name="image_path" id="image_path" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;" onchange="previewImage(event)">
            @error('image_path')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <img id="imagePreview" class="mt-3 rounded-lg max-w-xs hidden">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="display_order" class="block text-gray-700 font-medium mb-2">Display Order</label>
                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $announcement->display_order) }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;">
                @error('display_order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="flex items-center h-full pt-9">
                    <input type="checkbox" name="is_active" value="1" {{ $announcement->is_active ? 'checked' : '' }} class="mr-2 w-5 h-5" style="accent-color: #248823;">
                    <span class="text-gray-700 font-medium">Active</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label for="starts_at" class="block text-gray-700 font-medium mb-2">Start Date (Optional)</label>
                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $announcement->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;">
                @error('starts_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="ends_at" class="block text-gray-700 font-medium mb-2">End Date (Optional)</label>
                <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $announcement->ends_at?->format('Y-m-d\TH:i')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;">
                @error('ends_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="text-white px-6 py-2 rounded-lg font-semibold transition" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">Update Announcement</button>
            <a href="{{ route('admin.announcements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">Cancel</a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const currentImage = document.getElementById('currentImage');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (currentImage) currentImage.style.opacity = '0.5';
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        if (currentImage) currentImage.style.opacity = '1';
    }
}
</script>
@endsection

