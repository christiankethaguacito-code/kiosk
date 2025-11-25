@extends('admin.layout')

@section('title', isset($building) ? 'Edit Building' : 'Add Building')
@section('header', isset($building) ? 'Edit Building' : 'Add New Building')

@section('content')
<!-- Full Page Background -->
<div class="min-h-screen relative" style="background: url('{{ asset('images/background.jpg') }}') center/cover no-repeat fixed;">
    <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 via-teal-800/70 to-green-900/80"></div>
    
    <div class="relative z-10 p-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="bg-gradient-to-r from-green-700 to-green-800 rounded-3xl p-8 shadow-2xl border-4 border-white/30">
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.buildings.index') }}" 
                       class="group bg-white/20 hover:bg-white/30 text-white p-4 rounded-2xl transition-all duration-300 border-2 border-white/30 hover:border-white/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-20 w-20 drop-shadow-2xl">
                    <div>
                        <h1 class="text-4xl font-black text-white drop-shadow-lg tracking-tight uppercase">
                            {{ isset($building) ? '✏️ Edit Building' : '➕ Add Building' }}
                        </h1>
                        <p class="text-lg text-green-100 mt-2 font-semibold">{{ isset($building) ? $building->name : 'Create New Building' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border-4 border-green-600/30">
        <form action="{{ isset($building) ? route('admin.buildings.update', $building) : route('admin.buildings.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="image_path" class="block text-gray-700 text-lg font-medium mb-2">Main Building Image</label>
                @if(isset($building) && $building->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $building->image_path) }}" alt="{{ $building->name }}" class="w-32 h-32 object-cover rounded border-2 border-teal-500">
                    </div>
                @endif
                <input 
                    type="file" 
                    id="image_path" 
                    name="image_path" 
                    accept="image/*"
                    onchange="previewMainImage(event)"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('image_path') border-red-500 @enderror"
                >
                <div id="mainImagePreview" class="mt-3 hidden">
                    <img src="" alt="Preview" class="w-32 h-32 object-cover rounded border-2 border-teal-500">
                </div>
                @error('image_path')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-lg font-medium mb-2">Image Gallery</label>
                <p class="text-gray-600 text-sm mb-3">Upload multiple images for this building (max 10 images, 2MB each)</p>
                
                @if(isset($building) && $building->image_gallery && count($building->image_gallery) > 0)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Current Gallery Images:</p>
                        <div id="currentGallery" class="grid grid-cols-4 gap-3">
                            @foreach($building->image_gallery as $index => $imagePath)
                                <div class="relative group" data-index="{{ $index }}">
                                    <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-24 object-cover rounded border border-gray-300">
                                    <button 
                                        type="button" 
                                        onclick="removeGalleryImage({{ $building->id }}, {{ $index }})"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600"
                                    >
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <input 
                    type="file" 
                    id="gallery_images" 
                    name="gallery_images[]" 
                    accept="image/*"
                    multiple
                    onchange="previewGalleryImages(event)"
                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('gallery_images') border-red-500 @enderror"
                >
                <div id="galleryPreview" class="grid grid-cols-4 gap-3 mt-3"></div>
                @error('gallery_images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('gallery_images.*')
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
                    class="text-white font-bold py-4 px-6 rounded-lg transition duration-200 text-lg"
                    style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);"
                    onmouseover="this.style.opacity='0.9'"
                    onmouseout="this.style.opacity='1'"
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

<script>
function previewMainImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('mainImagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function previewGalleryImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';
    
    if (files.length > 10) {
        alert('Maximum 10 images allowed');
        event.target.value = '';
        return;
    }
    
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded border-2 border-teal-300">
                <div class="absolute bottom-1 right-1 bg-teal-500 text-white text-xs px-2 py-1 rounded">${index + 1}</div>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeGalleryImage(buildingId, imageIndex) {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }
    
    fetch(`/admin/buildings/${buildingId}/gallery/${imageIndex}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the image element
            document.querySelector(`#currentGallery [data-index="${imageIndex}"]`).remove();
            
            // Check if gallery is empty
            if (document.querySelectorAll('#currentGallery > div').length === 0) {
                document.querySelector('#currentGallery').parentElement.remove();
            }
        } else {
            alert('Failed to delete image: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error deleting image');
        console.error(error);
    });
}
</script>
@endsection

