@extends('layouts.kiosk')

@section('title', 'Welcome - SKSU Campus Directory')
@section('body-class', 'bg-black')

@section('content')
<div class="w-screen h-screen overflow-hidden relative cursor-pointer" id="welcomeScreen" style="cursor: pointer; position: relative; z-index: 1;">
    @if($announcements->isEmpty())
        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-green-700 via-green-600 to-blue-700">
            <div class="text-center animate-fadeIn">
                <svg class="w-32 h-32 mx-auto mb-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h1 class="text-white text-7xl font-black mb-4 tracking-tight">SKSU Access Campus</h1>
                <p class="text-white text-4xl font-medium opacity-90">Interactive Campus Directory</p>
            </div>
        </div>
    @else
        <div class="slider-container w-full h-full relative">
            @foreach($announcements as $index => $announcement)
                <div class="slide absolute w-full h-full transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-slide="{{ $index }}">
                    @if($announcement->image_path)
                        <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-700 via-green-600 to-blue-700 flex items-center justify-center">
                            <h2 class="text-white text-7xl font-black px-16 text-center drop-shadow-lg">{{ $announcement->title }}</h2>
                        </div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/80 to-transparent text-white p-12">
                        <h3 class="text-5xl font-bold mb-2">{{ $announcement->title }}</h3>
                        @if($announcement->description)
                            <p class="text-2xl opacity-90">{{ $announcement->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <div class="absolute top-8 left-8 right-8 flex items-center justify-between z-20">
                <div class="bg-black/40 backdrop-blur-lg px-10 py-5 rounded-2xl border-2 border-white/30 shadow-2xl">
                    <h2 class="text-white text-4xl font-bold drop-shadow-lg">SKSU Access Campus</h2>
                </div>
                <div class="bg-black/50 backdrop-blur-lg px-10 py-6 rounded-2xl border-2 border-white/30 shadow-2xl">
                    <p class="text-white text-5xl font-bold drop-shadow-lg" id="currentTime"></p>
                </div>
            </div>
        </div>
    @endif
    
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-30">
        <div class="bg-white/20 backdrop-blur-md px-16 py-10 rounded-3xl border-4 border-white/40 shadow-2xl">
            <div class="flex items-center gap-6 animate-pulse">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                </svg>
                <div class="text-white text-5xl font-bold drop-shadow-lg">Touch Screen to Start</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const buildings = @json($buildings);
    console.log('Idle screen script loaded');
    
    // Start preloading images immediately
    let loadedCount = 0;
    let totalImages = 0;
    let currentFile = '';
    
    // Function to update progress
    const updateProgress = () => {
        console.clear();
        console.log(`🖼️ Caching building images: ${currentFile}`);
        console.log(`📊 Progress: ${loadedCount}/${totalImages}`);
    };
    
    // Preload all building images in background
    function preloadAllBuildingImages() {
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
                        console.log(`✅ All ${totalImages}/${totalImages} images cached successfully`);
                    }
                };
                img.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`✅ All ${totalImages}/${totalImages} images cached successfully`);
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
                        console.log(`✅ All ${totalImages}/${totalImages} images cached successfully`);
                    }
                };
                dbImg.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`✅ All ${totalImages}/${totalImages} images cached successfully`);
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
                            console.log(`✅ All ${totalImages}/${totalImages} images cached successfully`);
                        }
                    };
                    galleryImg.onerror = () => {
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            console.clear();
                            console.log(`✅ All ${totalImages}/${totalImages} images cached successfully`);
                        }
                    };
                    galleryImg.src = gallerySrc;
                });
            }
        });
    }
    
    // Start preloading immediately
    preloadAllBuildingImages();
    
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;
    
    console.log('Total slides:', totalSlides);
    
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        const dateString = now.toLocaleDateString('en-US', { 
            weekday: 'long',
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        const timeElement = document.getElementById('currentTime');
        if (timeElement) {
            timeElement.innerHTML = `<span class="text-5xl font-black">${timeString}</span><br><span class="text-xl opacity-90 font-medium">${dateString}</span>`;
        }
    }
    
    updateTime();
    setInterval(updateTime, 1000);
    
    if (totalSlides > 1) {
        setInterval(() => {
            slides[currentSlide].classList.remove('opacity-100');
            slides[currentSlide].classList.add('opacity-0');
            currentSlide = (currentSlide + 1) % totalSlides;
            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('opacity-100');
        }, 5000);
    }
    
    const welcomeScreen = document.getElementById('welcomeScreen');
    console.log('Welcome screen element:', welcomeScreen);
    
    function navigateToMap() {
        console.log('Navigate to map triggered!');
        welcomeScreen.style.opacity = '0';
        welcomeScreen.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            console.log('Redirecting to map...');
            window.location.href = '{{ route('kiosk.map') }}';
        }, 500);
    }
    
    if (welcomeScreen) {
        welcomeScreen.addEventListener('click', function(e) {
            console.log('Click detected at:', e.clientX, e.clientY);
            navigateToMap();
        });
        
        welcomeScreen.addEventListener('touchstart', function(e) {
            console.log('Touch detected');
            e.preventDefault();
            navigateToMap();
        });
        
        console.log('Event listeners attached');
    } else {
        console.error('Welcome screen element not found!');
    }
</script>
@endsection
