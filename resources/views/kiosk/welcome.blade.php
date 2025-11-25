@extends('Layouts.app')

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
    
    /* Admin Edit Controls */
    .edit-controls {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 50;
        display: flex;
        gap: 0.5rem;
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
            
            <!-- Content Overlay -->
            <div class="p-12">
                
                <!-- SKSU Header -->
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="w-20 h-20 drop-shadow-2xl">
                    <div class="text-white text-2xl font-bold uppercase tracking-widest" style="text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);">
                        Sultan Kudarat State University
                    </div>
                </div>
                
                <template x-for="(announcement, index) in [
                    @foreach($announcements as $announcement)
                    {
                        title: '{{ addslashes($announcement->title) }}',
                        content: '{{ addslashes($announcement->content) }}'
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ]" :key="index">
                    <div x-show="currentSlide === index" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        
                        <!-- Announcement Title (Red Badge) -->
                        <div class="inline-block bg-red-600 text-white px-10 py-3 rounded-full text-2xl font-bold mb-6 shadow-2xl">
                            <span x-text="announcement.title"></span>
                        </div>
                        
                        <!-- Announcement Content -->
                        <p class="text-3xl text-white italic" style="text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);">
                            <span x-text="announcement.content"></span>
                        </p>
                        
                    </div>
                </template>
                
                <!-- Admin Edit Controls -->
                @auth
                <div class="edit-controls">
                    <button class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full shadow-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                </div>
                @endauth
                
            </div>
            
            <!-- Slide Indicators -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-3">
                <template x-for="i in slides" :key="i">
                    <button @click="currentSlide = i - 1"
                            class="w-3 h-3 rounded-full transition"
                            :class="currentSlide === i - 1 ? 'bg-yellow-400 scale-125' : 'bg-white/50'"></button>
                </template>
            </div>
            
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
    
    <!-- Admin Add Button (Far Right) -->
    @auth
    <button class="fixed right-8 top-1/2 transform -translate-y-1/2 bg-green-600 hover:bg-green-700 text-white p-6 rounded-full shadow-2xl z-100 transition">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
        </svg>
    </button>
    @endauth
    
</div>

<script>
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
