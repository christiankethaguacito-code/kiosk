@extends('admin.layout')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Add New Announcement</h1>

    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;" required>
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="content" value="{{ old('content', '') }}">

        <div class="mb-4">
            <label for="image_path" class="block text-gray-700 font-medium mb-2">Upload Announcement</label>
            <input type="file" name="image_path" id="image_path" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;" onchange="previewImage(event)">
            @error('image_path')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <img id="imagePreview" class="mt-3 rounded-lg max-w-xs hidden">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="display_order" class="block text-gray-700 font-medium mb-2">Display Order</label>
                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', 0) }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;">
                @error('display_order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="flex items-center h-full pt-9">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2 w-5 h-5" style="accent-color: #248823;">
                    <span class="text-gray-700 font-medium">Active</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label for="starts_at" class="block text-gray-700 font-medium mb-2">Start Date (Optional)</label>
                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;">
                @error('starts_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="ends_at" class="block text-gray-700 font-medium mb-2">End Date (Optional)</label>
                <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;">
                @error('ends_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="text-white px-6 py-2 rounded-lg font-semibold transition" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">Create Announcement</button>
            <a href="{{ route('admin.announcements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">Cancel</a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection

