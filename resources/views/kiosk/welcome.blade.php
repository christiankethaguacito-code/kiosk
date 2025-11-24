@extends('Layouts.app')

@section('title', 'Welcome - SKSU Access')
@section('body-class', 'overflow-hidden')

@section('head')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    /* Gradient Background */
    .gradient-bg {
        background: linear-gradient(135deg, #248823 0%, #1a6619 100%);
        position: relative;
        overflow: hidden;
    }
    
    /* Animated Background Pattern */
    .gradient-bg::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: movePattern 20s linear infinite;
    }
    
    @keyframes movePattern {
        from { transform: translate(0, 0); }
        to { transform: translate(50px, 50px); }
    }
    
    /* Blur Background Image */
    .blur-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/images/campus-bg.jpg');
        background-size: cover;
        background-position: center;
        filter: blur(8px);
        opacity: 0.3;
        z-index: 0;
    }
    
    /* Content Container */
    .content-container {
        position: relative;
        z-index: 1;
    }
    
    /* Clock Styles */
    .clock-display {
        font-size: 6rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        letter-spacing: 0.05em;
    }
    
    .date-display {
        font-size: 2rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    /* Announcement Card */
    .announcement-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        max-width: 800px;
        width: 90%;
    }
    
    .announcement-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 1rem;
    }
    
    .announcement-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    
    .announcement-content {
        font-size: 1.125rem;
        color: #64748b;
        line-height: 1.8;
    }
    
    /* Swiper Custom Styles */
    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: white;
        opacity: 0.5;
    }
    
    .swiper-pagination-bullet-active {
        opacity: 1;
        background: white;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.8);
    }
    
    /* CTA Button */
    .cta-button {
        background: white;
        color: #248823;
        padding: 1.5rem 4rem;
        border-radius: 16px;
        font-size: 1.5rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        animation: pulse 2s ease-in-out infinite;
    }
    
    .cta-button:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
    }
    
    .cta-button:active {
        transform: translateY(-2px) scale(1.02);
    }
    
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        50% {
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.4);
        }
    }
    
    /* Entrance Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .animate-fadeInDown {
        animation: fadeInDown 0.8s ease-out;
    }
    
    .animate-scaleIn {
        animation: scaleIn 0.6s ease-out;
        animation-delay: 0.3s;
        animation-fill-mode: both;
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
        animation-delay: 0.6s;
        animation-fill-mode: both;
    }
</style>
@endsection

@section('content')
<div class="gradient-bg min-h-screen flex flex-col items-center justify-center p-8" 
     x-data="welcomeScreen()">
    
    <!-- Optional Background Image with Blur -->
    <div class="blur-bg"></div>
    
    <div class="content-container flex flex-col items-center gap-12 w-full">
        
        <!-- Clock and Date Display -->
        <div class="text-center animate-fadeInDown">
            <div class="clock-display" x-text="currentTime"></div>
            <div class="date-display" x-text="currentDate"></div>
        </div>
        
        <!-- Announcement Carousel -->
        @if($announcements->count() > 0)
        <div class="w-full flex justify-center animate-scaleIn">
            <div class="announcement-card">
                <div class="swiper announcementSwiper">
                    <div class="swiper-wrapper">
                        @foreach($announcements as $announcement)
                        <div class="swiper-slide">
                            @if($announcement->image_path)
                            <img src="{{ Storage::url($announcement->image_path) }}" 
                                 alt="{{ $announcement->title }}"
                                 class="announcement-image"
                                 loading="lazy">
                            @endif
                            <h2 class="announcement-title">{{ $announcement->title }}</h2>
                            <p class="announcement-content">{{ $announcement->content }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        @else
        <!-- Fallback when no announcements -->
        <div class="announcement-card animate-scaleIn">
            <h2 class="announcement-title text-center">Welcome to SKSU Access</h2>
            <p class="announcement-content text-center">Your interactive campus navigation system</p>
        </div>
        @endif
        
        <!-- Call to Action Button -->
        <button class="cta-button animate-fadeInUp" onclick="window.location.href='{{ route('kiosk.map') }}'">
            👆 Tap to Explore Campus
        </button>
        
    </div>
    
</div>
@endsection

@section('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
function welcomeScreen() {
    return {
        currentTime: '',
        currentDate: '',
        
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            
            // Initialize Swiper
            new Swiper('.announcementSwiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: {{ $announcements->count() > 1 ? 'true' : 'false' }},
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        },
        
        updateClock() {
            const now = new Date();
            
            // Format time: 10:30 AM
            this.currentTime = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            
            // Format date: Wednesday, November 24, 2025
            this.currentDate = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        }
    }
}
</script>
@endsection
