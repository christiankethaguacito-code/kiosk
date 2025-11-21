@extends('layouts.dual-mode')

@section('title', 'Campus Directory')
@section('body-class', 'bg-gray-900')

@section('content')
<div class="min-h-screen flex items-center justify-center p-8">
    <div class="w-full max-w-6xl">
        <div class="relative">
            <div class="overflow-hidden rounded-2xl shadow-2xl" 
                 x-data="{ currentSlide: 0, slides: {{ $announcements->count() }} }"
                 x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 5000)">
                
                @foreach($announcements as $index => $announcement)
                <div class="relative" 
                     x-show="currentSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-full"
                     x-transition:enter-end="opacity-100 transform translate-x-0">
                    
                    <img src="{{ Storage::url($announcement->image_path) }}" 
                         alt="{{ $announcement->title }}"
                         class="w-full h-[600px] object-cover">
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-8">
                        <h2 class="text-4xl font-bold text-white">{{ $announcement->title }}</h2>
                    </div>

                    @auth
                    <div x-show="editMode" x-cloak class="absolute top-4 right-4 flex gap-2">
                        <button @click="editImage({{ $announcement->id }})"
                                class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg shadow-lg">
                            📷 Change Image
                        </button>
                        
                        <button @click="deleteSlide({{ $announcement->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-lg shadow-lg">
                            🗑️ Delete
                        </button>
                    </div>
                    @endauth
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center gap-2">
                @foreach($announcements as $index => $announcement)
                <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-green-600' : 'bg-gray-600'"
                        class="w-3 h-3 rounded-full transition"></button>
                @endforeach
            </div>

            @auth
            <div x-show="editMode" x-cloak class="mt-8 text-center">
                <button @click="addNewSlide()"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-lg shadow-lg text-xl">
                    ➕ Add New Slide
                </button>
            </div>
            @endauth
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('kiosk.map') }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-12 rounded-2xl shadow-2xl text-2xl">
                👆 Touch to View Campus Map
            </a>
        </div>
    </div>
</div>

<input type="file" id="imageUpload" accept="image/*" class="hidden">
@endsection

@section('scripts')
<script>
    let currentEditId = null;

    function editImage(announcementId) {
        currentEditId = announcementId;
        document.getElementById('imageUpload').click();
    }

    document.getElementById('imageUpload').addEventListener('change', async function(e) {
        if (!e.target.files[0] || !currentEditId) return;

        try {
            const result = await ajaxPost(`/admin/inline/announcements/${currentEditId}/image`, {
                image: e.target.files[0]
            });
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            console.error('Image upload failed:', error);
        }
    });

    async function deleteSlide(announcementId) {
        if (!confirm('Delete this announcement?')) return;

        try {
            const result = await ajaxDelete(`/admin/inline/announcements/${announcementId}`);
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            console.error('Delete failed:', error);
        }
    }

    function addNewSlide() {
        const title = prompt('Enter slide title:');
        if (!title) return;

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = async function(e) {
            if (!e.target.files[0]) return;

            try {
                const result = await ajaxPost('/admin/inline/announcements', {
                    title: title,
                    image: e.target.files[0]
                });
                setTimeout(() => location.reload(), 1000);
            } catch (error) {
                console.error('Add slide failed:', error);
            }
        };
        input.click();
    }
</script>
@endsection
