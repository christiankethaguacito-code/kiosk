@extends('layouts.app')

@section('title', 'SKSU Access - Campus Kiosk')
@section('body-class', 'overflow-hidden font-sans')

@section('head')
<style>
    /* Full Screen Campus Background with Dark Overlay */
    .campus-bg {
        background: url('/images/background.jpg') center/cover no-repeat fixed;
        position: relative;
        min-height: 100vh;
        overflow: hidden;
    }
    
    .campus-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }
    
    /* All content above overlay */
    .content-layer {
        position: relative;
        z-index: 10;
    }
    
    /* Time & Date Widget - Top Right */
    .time-widget {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 100;
        text-align: right;
    }
    
    /* Announcement Card - Full Width in 75% Container */
    .announcement-card {
        position: relative;
        width: 90%;
        max-width: 1500px;
        height: 100%;
        max-height: 100%;
        margin: 0 auto;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7);
        background-size: cover;
        background-position: center;
        background-color: #1a1a1a;
        transition: background-image 0.3s ease-in-out;
        transition: opacity 0.5s ease;
    }
    
    /* CTA Button - Green Pill */
    .cta-button {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.85;
        }
    }
    
    /* Fade transition */
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="campus-bg flex items-center justify-center" x-data="kioskScreen()">
    
    <!-- Time & Date Widget - Top Right (15% Header) -->
    <div class="time-widget content-layer">
        <div class="time text-5xl font-black" x-text="currentTime"></div>
        <div class="date text-sm font-semibold" x-text="currentDate"></div>
    </div>
    
    <!-- Main Content Container with Proportional Layout -->
    <div class="content-layer w-full h-screen flex flex-col">
        
        <!-- Header Space (15%) -->
        <div class="h-[15vh]"></div>
        
        <!-- Announcement Card Container (75%) -->
        <div class="h-[75vh] flex items-center justify-center px-8">
            <!-- Announcement Card -->
            @if($announcements->count() > 0)
            <div class="announcement-card" 
                 x-data="{ currentSlide: 0, slides: {{ $announcements->count() }} }"
                 x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 5000)"
                 :style="'background-image: url(' + [
                    @foreach($announcements as $index => $announcement)
                    '{{ $announcement->image_path ? Storage::url($announcement->image_path) : "/images/background.jpg" }}'{{ !$loop->last ? ',' : '' }}
                    @endforeach
                 ][currentSlide] + ')'">
            </div>
            @endif
        </div>
        
        <!-- Footer Space (10%) - Touch to Continue -->
        <a href="{{ route('kiosk.map') }}" class="h-[10vh] flex items-center justify-center cursor-pointer group">
            <p class="text-white text-2xl font-bold animate-pulse" style="text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);">
                Touch the Screen to Continue
            </p>
        </a>
        
    </div>
    
</div>

<script>
const buildings = @json($buildings);

// Start preloading all building images immediately when page loads
(function preloadAllBuildingImages() {
    let loadedCount = 0;
    let totalImages = 0;
    let currentFile = '';
    
    // Function to update progress
    const updateProgress = () => {
        console.clear();
        console.log(`🖼️ Caching building images: ${currentFile}`);
        console.log(`📊 Progress: ${loadedCount}/${totalImages}`);
    };
    
    buildings.forEach(building => {
        // Try JPG first, then PNG from public folder
        const publicJpg = `/images/buildings/${building.code}.jpg`;
        const publicPng = `/images/buildings/${building.code}.png`;
        
        // Preload public images
        [publicJpg, publicPng].forEach(src => {
            totalImages++;
            const img = new Image();
            img.onload = () => {
                loadedCount++;
                currentFile = src.split('/').pop();
                updateProgress();
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            img.onerror = () => {
                loadedCount++;
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            img.src = src;
        });
        
        // Preload database images
        if (building.image_path) {
            totalImages++;
            const dbImg = new Image();
            const dbSrc = `/storage/${building.image_path}`;
            dbImg.onload = () => {
                loadedCount++;
                currentFile = dbSrc.split('/').pop();
                updateProgress();
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            dbImg.onerror = () => {
                loadedCount++;
                if (loadedCount === totalImages) {
                    console.clear();
                    console.log(`✅ All ${totalImages} building images cached successfully!`);
                    console.log(`🚀 Map will load instantly when you continue`);
                }
            };
            dbImg.src = dbSrc;
        }
        
        // Preload gallery images
        if (building.image_gallery && building.image_gallery.length > 0) {
            building.image_gallery.forEach(imgPath => {
                totalImages++;
                const galleryImg = new Image();
                const gallerySrc = `/storage/${imgPath}`;
                galleryImg.onload = () => {
                    loadedCount++;
                    currentFile = gallerySrc.split('/').pop();
                    updateProgress();
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`✅ All ${totalImages} building images cached successfully!`);
                        console.log(`🚀 Map will load instantly when you continue`);
                    }
                };
                galleryImg.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`✅ All ${totalImages} building images cached successfully!`);
                        console.log(`🚀 Map will load instantly when you continue`);
                    }
                };
                galleryImg.src = gallerySrc;
            });
        }
    });
    
    console.log(`🔄 Started caching ${totalImages} building images in background...`);
})();

function kioskScreen() {
    return {
        currentTime: '',
        currentDate: '',
        
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },
        
        updateClock() {
            const now = new Date();
            
            // Format: 10:00 AM
            this.currentTime = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            
            // Format: Wednesday, Nov 06, 2025
            this.currentDate = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            });
        }
    }
}
</script>
@endsection
