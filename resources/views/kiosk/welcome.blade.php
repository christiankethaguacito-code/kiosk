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
