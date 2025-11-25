@extends('Layouts.app')

@section('title', $building->name . ' - Building Details')
@section('body-class', 'bg-gray-50')

@section('head')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .building-gallery-swiper .swiper-slide {
        height: 500px;
    }
    .building-gallery-swiper .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .swiper-button-next, .swiper-button-prev {
        color: #248823;
        background: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 24px;
    }
    .swiper-pagination-bullet-active {
        background: #248823;
    }
    @media print {
        .no-print { display: none; }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-white shadow-lg border-b-4 no-print" style="border-color: #248823;">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('kiosk.map') }}" class="text-4xl text-gray-600 hover:text-gray-800 transition p-3 rounded-lg hover:bg-gray-100">
                        ←
                    </a>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800">{{ $building->name }}</h1>
                        <p class="text-gray-500 mt-2 text-lg">Complete Building Information & Services</p>
                    </div>
                </div>
                <button onclick="window.print()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
        <!-- Building Image Gallery -->
        @php
            $galleryImages = $building->gallery_images ? json_decode($building->gallery_images, true) : [];
            $hasGallery = is_array($galleryImages) && count($galleryImages) > 0;
        @endphp
        
        @if($building->image_path || $hasGallery)
        <div class="mb-10 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="swiper building-gallery-swiper">
                <div class="swiper-wrapper">
                    @if($building->image_path)
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $building->image_path) }}" 
                             alt="{{ $building->name }}"
                             onerror="this.src='/images/placeholder-building.jpg'">
                    </div>
                    @endif
                    
                    @if($hasGallery)
                        @foreach($galleryImages as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $building->name }}"
                                 onerror="this.src='/images/placeholder-building.jpg'">
                        </div>
                        @endforeach
                    @endif
                </div>
                @if($hasGallery)
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
                @endif
            </div>
        </div>
        @endif

        <!-- Building Description -->
        @if($building->description)
        <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
            <h2 class="text-3xl font-bold mb-5 flex items-center gap-3">
                <span style="color: #248823;">📋</span> About This Building
            </h2>
            <p class="text-gray-700 leading-relaxed text-lg">{{ $building->description }}</p>
        </div>
        @endif

        <!-- Offices Section -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold mb-8 flex items-center gap-3">
                <span style="color: #248823;">🏛️</span> Offices & Services
                <span class="ml-3 text-xl font-normal text-gray-500">({{ $building->offices->count() }} {{ Str::plural('Office', $building->offices->count()) }})</span>
            </h2>

            @if($building->offices->count() > 0)
                <div class="space-y-6">
                    @foreach($building->offices as $index => $office)
                    <div class="bg-gradient-to-r from-gray-50 to-white border-l-4 rounded-lg p-6 hover:shadow-xl transition" style="border-color: #248823;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="font-bold text-2xl mb-2" style="color: #248823;">
                                    {{ $index + 1 }}. {{ $office->name }}
                                </h3>
                                
                                @if($office->floor_number)
                                <p class="text-gray-600 text-base mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="font-semibold">Location:</span> Floor {{ $office->floor_number }}
                                </p>
                                @endif

                                @if($office->head_name)
                                <div class="bg-white rounded-lg p-4 mb-4 border-2 border-gray-200">
                                    <p class="text-sm text-gray-500 mb-1">Office Head</p>
                                    <p class="font-bold text-lg text-gray-800">{{ $office->head_name }}</p>
                                    @if($office->head_title)
                                    <p class="text-base text-gray-600">{{ $office->head_title }}</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($office->services && $office->services->count() > 0)
                        <div class="mt-4 bg-white rounded-lg p-5 border border-gray-200">
                            <p class="font-bold text-base text-gray-700 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" style="color: #248823;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Services Offered ({{ $office->services->count() }})
                            </p>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($office->services as $service)
                                <li class="flex items-start gap-3 text-base text-gray-700 p-2 hover:bg-gray-50 rounded">
                                    <span class="text-green-600 font-bold mt-0.5">✓</span>
                                    <span class="flex-1">{{ $service->description }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                        <div class="mt-4 bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-gray-500 text-sm">No services listed for this office</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-lg">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-500 text-xl mb-2">No office information available</p>
                    <p class="text-gray-400">This building's details are being updated</p>
                </div>
            @endif
        </div>

        <!-- Back to Map Button -->
        <div class="mt-10 text-center no-print">
            <a href="{{ route('kiosk.map') }}" 
               class="inline-block px-10 py-4 text-white text-lg font-semibold rounded-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105"
               style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
                ← Back to Campus Map
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for gallery
    const gallerySwiper = new Swiper('.building-gallery-swiper', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
});
</script>
@endsection
