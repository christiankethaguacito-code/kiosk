@extends('Layouts.app')

@section('title', 'Campus Map')
@section('body-class', 'bg-gray-900')

@section('head')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .building-marker {
        display: none; /* Hide database-driven markers, use SVG buildings instead */
        position: absolute;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #248823 0%, #1a6619 100%);
        border: 3px solid white;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
    }
    .building-marker:hover {
        background: linear-gradient(135deg, #7CC4B5 0%, #4FB86A 100%);
        transform: translate(-50%, -50%) scale(1.2);
    }
    .building-marker.dragging {
        opacity: 0.7;
        cursor: move;
    }
    .map-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        background: #f5f5f5;
        border: 4px solid #374151;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        z-index: 50;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-overlay.active > div {
        animation: slideUp 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Subtle pulse animation for clickable buildings */
    @keyframes buildingPulse {
        0%, 100% {
            filter: brightness(1);
        }
        50% {
            filter: brightness(1.05);
        }
    }
    
    /* Loading spinner for modal content */
    .loading-spinner {
        display: inline-block;
        width: 40px;
        height: 40px;
        border: 4px solid rgba(36, 136, 35, 0.2);
        border-top-color: #248823;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Highlight effect for selected building */
    .building-selected {
        filter: brightness(1.3) drop-shadow(0 0 16px rgba(36, 136, 35, 1)) !important;
        animation: buildingPulse 2s ease-in-out infinite;
    }
    
    /* Green gradient hover for legend items */
    .legend-item:hover {
        color: #248823 !important;
        background-color: rgba(36, 136, 35, 0.1) !important;
    }
    
    svg {
        width: 100%;
        height: 100%;
    }
    
    /* Interactive building hover effects */
    svg [id]:hover:not(#Premises):not(#Outline):not(#Main_Road):not(#Side_Entrance):not(#Main_Entrance):not(#BuildingLabels):not(path):not(g) {
        filter: brightness(1.2) drop-shadow(0 0 8px rgba(16, 185, 129, 0.6));
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        transform-origin: center;
    }
    
    /* Building click animation */
    svg [id]:active:not(#Premises):not(#Outline):not(#Main_Road):not(#Side_Entrance):not(#Main_Entrance):not(#BuildingLabels):not(path):not(g) {
        filter: brightness(1.3) drop-shadow(0 0 12px rgba(16, 185, 129, 0.8));
        transform: scale(0.98);
        transition: all 0.1s ease;
    }
    
    /* Smooth cursor change for interactive elements */
    svg [id][style*="cursor: pointer"] {
        will-change: filter, transform;
    }
    
    /* Enhanced button interactions */
    button {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    button:active {
        transform: scale(0.96);
    }
    
    button:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* Tooltip styling */
    .building-tooltip {
        position: absolute;
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        pointer-events: none;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.2s ease;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    .building-tooltip.show {
        opacity: 1;
    }
    
    .building-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 6px solid transparent;
        border-top-color: rgba(0, 0, 0, 0.9);
    }
    
    #navPath {
        pointer-events: none;
        z-index: 100;
    }
    
    /* Road skeleton overlay */
    #roadSkeleton {
        pointer-events: none;
        z-index: 5;
    }
    
    .skeleton-road {
        fill: none;
        stroke: #3b82f6;
        stroke-width: 2;
        stroke-opacity: 0;
        stroke-dasharray: 5,3;
        display: none;
    }
    
    .skeleton-node {
        fill: #3b82f6;
        fill-opacity: 0;
        stroke: #1e40af;
        stroke-width: 1;
        display: none;
    }
    
    /* Endpoint edit mode */
    .endpoint-marker {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .endpoint-marker.editable {
        cursor: move;
        filter: brightness(1.2) drop-shadow(0 0 8px rgba(147, 51, 234, 0.8));
    }
    
    .endpoint-marker.dragging {
        filter: brightness(1.5) drop-shadow(0 0 12px rgba(147, 51, 234, 1));
        opacity: 0.8;
    }
    
    .endpoint-marker:hover {
        transform: scale(1.1);
    }
    
    .endpoint-label {
        pointer-events: none;
        user-select: none;
    }
    
    /* Edit mode overlay */
    .edit-mode-active {
        outline: 3px dashed #a855f7;
        outline-offset: 5px;
    }
    
    /* Interactive hint overlay */
    .hint-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(16, 185, 129, 0.95);
        color: white;
        padding: 20px 40px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 600;
        z-index: 200;
        opacity: 0;
        animation: hintFadeInOut 4s ease;
        pointer-events: none;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }
    
    @keyframes hintFadeInOut {
        0% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
        10%, 70% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        100% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col">
    <header class="text-white p-6 flex justify-between items-center" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
        <div class="flex items-center gap-4">
            <a href="{{ route('kiosk.idle') }}" class="flex items-center">
                <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-16 w-16 object-contain">
            </a>
            <h1 class="text-3xl font-bold">Access Map</h1>
        </div>
        <div class="flex-1 max-w-md mx-8">
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search buildings..." 
                    class="w-full px-4 py-2 rounded-lg text-gray-800 focus:outline-none focus:ring-2" style="--tw-ring-color: #248823;"
                />
                <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div id="clock" class="text-xl"></div>
            
            <!-- Admin Menu Toggle -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="p-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition duration-200"
                        title="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden"
                     style="display: none;">
                    
                    @auth
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Admin Dashboard</span>
                        </a>
                        
                        <button onclick="toggleEditMode()" 
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 text-left">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Admin Inline Edit</span>
                        </button>
                    @else
                        <button onclick="showAdminLogin()" 
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 text-left">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Admin Login</span>
                        </button>
                    @endauth
                    
                    <button onclick="showAbout()" 
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-left">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">About</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 flex gap-6 p-8">
        <!-- Map Section (65%) -->
        <div class="flex-1" style="flex: 0 0 65%;">
            <div class="map-wrapper" id="mapContainer" style="width: 100%; height: 100%;">
                <div class="hint-overlay" id="interactiveHint">👆 Click on any building to explore</div>
                <svg xmlns="http://www.w3.org/2000/svg" id="campusMap" viewBox="0 0 302.596 275.484" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <g id="layer1" transform="translate(43.417 59.938)">
                    <path id="Premises" d="m-33.024-7.685-1.12 176.012 156.418 1.031v36.09l129.52.001c-.035-88.47-.025-172.804 0-261.322l-177.22.568z" style="fill:#bfe4c5;fill-opacity:1;fill-rule:evenodd;stroke:#0a0a00;stroke-width:.275879;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
                </g>
                <g id="layer2">
                    <g id="Outline">
                        <path d="m203.778 52.886-6.001-2.111-5.226-.15-6.093 1.756-5.11 3.679-2.06 2.547-2.471 5.705-.137 5.858 2.042 6.826 4.047 4.801 7.877 3.882 3.893.465 5.976-.897 5.59-2.92 4.778-4.61 2.04-7.242-.936-8.01-3.138-5.399z" style="fill:none;stroke:#525252;stroke-width:6.72521;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M30.144 164.837c.032-18.264-.107-86.59-.078-105.4" style="fill:#999;stroke:#525252;stroke-width:6.85868;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(0 -.094)"/>
                        <path d="M14.176 203.086c19.089.004 91.381-.191 110.16.02m-100.455.017c19.089.004 91.382-.191 110.16.019" style="fill:none;stroke:#525252;stroke-width:5.83566;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M161.494 110.994c3.598.042 20.067 0 23.412 0" style="fill:none;stroke:#525252;stroke-width:6.30215;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M186.912 203.238h-1.246l-1.705-.027-1.587 1.004-1.646.977-.822.977-.458 1.954-.365 1.955.346 1.954.477 1.954 1.645 1.954 1.646.977h4.937v-13.679h-1.222" style="fill:none;stroke:#525252;stroke-width:2.16529;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M203.193 111.071c7.558.01 36.183-.522 43.618.058m-39.775.045c7.558.009 36.182-.523 43.618.057" style="fill:none;stroke:#525252;stroke-width:6.07206;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M232.525 222.715v-2.19h-7.906l-1.684 1.36-.801.908-.96 2.034-.054 2.089.534 1.819.988 1.646 1.976 1.095h7.907v-8.761" style="fill:none;stroke:#525252;stroke-width:2.50331;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M203.58 168.071c14.586.005 69.825-.269 84.174.03m-76.758.023c14.586.005 69.825-.269 84.173.03" style="fill:none;stroke:#525252;stroke-width:6.05204;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M188.396 267.2c-.006-31.38-.201-150.032-.142-181.092" style="fill:none;stroke:#525252;stroke-width:8.54622;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M14.874 167.535c29.502.003 141.233-.142 170.256.007" style="fill:#4d4d4d;stroke:#525252;stroke-width:6.25982;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M252.55 166.149c.02-23.23.07-119.64.088-143.565" style="fill:none;stroke:#525252;stroke-width:6.24513;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M202.189 268.023c.011-31.518-.041-150.698.047-181.895" style="fill:none;stroke:#525252;stroke-width:8.43353;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M168.53 210.138c1.48.062 8.256 0 9.632 0" style="fill:none;stroke:#525252;stroke-width:5.05933;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M203.37 226.14c2.1.047 14.659-.464 16.61-.464" style="fill:none;stroke:#525252;stroke-width:5.23941;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M134.143 202.22c.083-5.387-.28-25.538-.205-31.085" style="fill:#4d4d4d;stroke:#525252;stroke-width:6.01726;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M162.074 163.185c.026-23.143-.088-109.723-.064-133.558" style="fill:none;stroke:#525252;stroke-width:6.97482;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(0 -.094)"/>
                    </g>
                    <rect id="Pathwalk" width="32" height="5" x="217" y="114.561" rx="0" ry="0" style="fill:#0b6515;fill-rule:evenodd;stroke-width:.441557;fill-opacity:1"/>
                    <g id="Main_Road" transform="translate(0 -.094)">
                        <path d="M14.286 203.09c19.065.004 91.266-.118 110.02.012m-100.327.01c19.065.004 91.266-.118 110.021.012" style="fill:none;stroke:#fff;stroke-width:4.58696;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M164.583-4.938c1.764.01 30 0 30 0v-34h-18" style="fill:#f9f9f9;stroke:#887319;stroke-width:6.90363;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;fill-opacity:0" transform="translate(43.417 60.031)"/>
                        <path d="M183.54 159.062c0-2.507-.766-8.04 2.295-8.04h29.65v16.081h4.84" style="fill:none;stroke:#827330;stroke-width:5.92702;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(43.417 60.031)"/>
                        <path d="M200.776 168.003c14.826.005 70.977-.149 85.563.017m-78.025.013c14.827.005 70.978-.15 85.563.016" style="fill:none;stroke:#fff;stroke-width:4.53991;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M159.412 51.001c7.817.01 37.418-.271 45.107.03m-41.133.024c7.817.009 37.418-.272 45.107.03" style="fill:none;stroke:#fff;stroke-width:4.45169;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(43.417 59.938)"/>
                        <path d="M252.48 167.945c.01-23.502.148-121.156.158-145.36" style="fill:none;stroke:#fff;stroke-width:4.67061;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="m203.295 53.462-5.664-2.002-4.932-.143-5.751 1.666-4.823 3.488-1.945 2.416-2.332 5.41-.13 5.555 1.928 6.472 3.82 4.554 7.434 3.68c3.169.765 6.159.067 9.315-.409l5.275-2.77 4.51-4.37 1.925-6.868-.883-7.595-2.962-5.12z" style="fill:none;stroke:#fff;stroke-width:6.36228;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M188.583 163.062v-2h-7.272l-1.55 1.242-.736.83-.883 1.857-.05 1.907.491 1.661.91 1.503 1.818 1h7.272v-8" style="fill:none;stroke:#fff;stroke-width:2.29434;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(43.417 59.938)"/>
                        <path d="M200.864 225.943c2.448.018 17.093-.18 19.368-.18" style="fill:none;stroke:#fff;stroke-width:3.52587;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M113.583 51.062c4.303.016 24 0 28 0" style="fill:none;stroke:#fff;stroke-width:4.31404;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(43.417 59.938)"/>
                        <path d="M202.28 267.912c-.044-31.322-.267-149.755-.266-180.758" style="fill:none;stroke:#fff;stroke-width:6.37675;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M168.447 210.104c1.553.03 8.663 0 10.107 0" style="fill:none;stroke:#fff;stroke-width:3.62771;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M187.238 203.696h-1.21l-1.655-.025-1.54.934-1.597.91-.799.908-.444 1.818-.355 1.819.337 1.818.462 1.818 1.598 1.818 1.597.909h4.792v-12.727h-1.186" style="fill:none;stroke:#fff;stroke-width:2.0576;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M188.32 267.166c-.005-31.559-.112-150.887-.08-182.124" style="fill:none;stroke:#fff;stroke-width:6.41371;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M15.007 167.47c29.373.003 140.615-.079 169.51.008" style="fill:none;stroke:#fff;stroke-width:4.606;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M118.539 105.627c.011-23.552-.038-111.661-.028-135.917" style="fill:none;stroke:#fff;stroke-width:4.61525;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1" transform="translate(43.417 59.938)"/>
                        <path d="M134.048 202.24c.047-5.872-.158-27.838-.116-33.885" style="fill:none;stroke:#fff;stroke-width:4.71747;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                        <path d="M30.031 167.477c.014-18.733-.047-88.815-.035-108.108" style="fill:none;stroke:#fff;stroke-width:4.6075;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1"/>
                    </g>
                </g>
                <g>
                    <g id="Main_Entrance">
                        <rect width="18.5" height="10" x="165.5" y="255" ry=".117" style="fill:#0b660b;fill-opacity:1;fill-rule:evenodd;stroke-width:.184374"/>
                        <rect width="18.058" height="10" x="206.942" y="255" ry=".117" style="fill:#0b660b;fill-opacity:1;fill-rule:evenodd;stroke-width:.18216"/>
                        <rect width="23.5" height="12" x="183.442" y="254" ry=".14" style="fill:#0b650b;fill-opacity:1;fill-rule:evenodd;stroke-width:.227636"/>
                    </g>
                    <g id="Side_Entrance">
                        <rect width="8" height="8" x="291" y="157" ry="0" style="fill:#0b6515;fill-opacity:1;fill-rule:evenodd;stroke-width:.52832"/>
                        <rect width="6" height="14" x="292" y="165" ry="0" style="fill:#0b6515;fill-opacity:1;fill-rule:evenodd;stroke-width:.511471"/>
                    </g>
                    <rect id="CCJE" width="46.097" height="13.013" x="236.915" y="240.297" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.957872;stroke-dasharray:none;stroke-opacity:1;cursor:pointer;" transform="matrix(1 -.00284 -.01038 .99995 0 0)"/>
                    <rect id="BCSF" width="9.046" height="16.065" x="171.386" y="235.367" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.929139;stroke-dasharray:none;stroke-opacity:1;cursor:pointer;" transform="rotate(.033)skewX(-.188)"/>
                    <path id="ULRC" d="M139.5 192.49h26.028v9.728h3.003v16.537h-3.003v8.755H139.5v-13.619h8.009v-7.782H139.5z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.986819;stroke-dasharray:none;stroke-opacity:1;cursor:pointer;"/>
                    <path id="DOST" d="M60.499 214.021h19.002V225H60.499z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:1.02132;stroke-dasharray:none;stroke-opacity:1;cursor:pointer;"/>
                    <rect id="FC" width="54.857" height="14.959" x="232.682" y="192.935" ry=".623" style="fill:#0b6616;fill-rule:evenodd;stroke-width:.413595;fill-opacity:1"/>
                    <path id="CHS" d="M118.454 102.818h-6.735v14.843h6.735l.058 23.707 11.43.085.058-20.823h15.395l.058 20.738h11.546V80.016l-10.642.042v20.78H130V80h-11.546z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0a5c12;stroke-width:.975771;stroke-dasharray:none;stroke-opacity:1;cursor:pointer;"/>
                    <path id="CTE" d="M285.583 84.352v-9.857l-29.089-.003.226 68.016 27.675-.003v-9.857h-17.318V115.89l7.923.003V101.11l-7.923-.003V84.349c5.782.037 12.733.003 18.506.003z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.973814;stroke-dasharray:none;stroke-opacity:1;cursor:pointer;"/>
                    <rect id="Field" width="67.264" height="112.993" x="36" y="49" rx="33.632" ry="30.959" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:1.076;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Bleacher" width="9.508" height="28.112" x="106.176" y="69.999" rx="0" ry=".567" style="fill:#0b6515;fill-opacity:1;fill-rule:evenodd;stroke-width:.303066"/>
                    <g id="Parking_Space" transform="matrix(1.2623 0 0 1.51228 -30.436 -55.826)">
                        <rect width="25.351" height="22.17" x="195.227" y="85.5" ry=".446" style="fill:#0b6515;fill-opacity:1;fill-rule:evenodd;stroke-width:.264583"/>
                        <rect width="21.646" height="4.674" x="197.015" y="86.717" ry=".094" style="fill:#609f60;fill-opacity:1;fill-rule:evenodd;stroke-width:.112265"/>
                        <rect width="21.646" height="4.674" x="197.181" y="94.357" ry=".094" style="fill:#609f60;fill-opacity:1;fill-rule:evenodd;stroke-width:.112265"/>
                        <rect width="21.646" height="4.674" x="197.148" y="101.732" ry=".094" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:.112265"/>
                    </g>
                    <path id="LHS_ext" d="M271.217 19.998h7.685v17.009h-7.685z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6614;stroke-width:1;stroke-opacity:1;stroke-dasharray:none"/>
                    <rect id="LHS" width="11.988" height="45.349" x="257" y="25.452" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6611;stroke-width:1;stroke-dasharray:none;stroke-opacity:1"/>
                    <path id="CoM" d="M275.674 4.977h18.774v61.03H286V51h-1.877v-3.002h-1.878v-8.004h1.878v-3.001H286v-20.01s-2.843-.113-2.816-3.002h-7.51z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6614;stroke-width:.969114;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Restroom" width="7" height="6" x="267" y="7" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:.353521;stroke:#0b6615;stroke-opacity:1"/>
                    <path id="SKSU-MPC" d="M256.5 12.62a4.5 4.38 0 0 1-4.486 4.38 4.5 4.38 0 0 1-4.514-4.353 4.5 4.38 0 0 1 4.457-4.408 4.5 4.38 0 0 1 4.543 4.324" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:1.12;stroke:#0b6615;stroke-opacity:1;stroke-dasharray:none"/>
                    <rect id="MPC-Dorm" width="8" height="8.001" x="257.998" y="7.037" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:.88900001;stroke-dasharray:none;stroke:#0b6615;stroke-opacity:1" transform="rotate(-.01)skewY(.001)"/>
                    <path id="ULD" d="M230.481 16.518h4.684v1.006h6.645v-1.006h4.702V7.48h-16.03z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6516;stroke-width:.977153;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="QMS" width="14.067" height="12.067" x="214.466" y="5.467" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6516;stroke-width:.933003;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Function" width="35.945" height="5.778" x="117.514" y="58.493" ry="0" style="fill:#0b6515;fill-opacity:1;fill-rule:evenodd;stroke-width:.473359"/>
                    <path id="UG" d="M118.19 24.217h39.819v33.706S119 58 118 58v-1l-9.18-.2V28.71h9.37z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.986189;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Reg_Office" width="46" height="4" x="167" y="21" ry="0" style="fill:#0b6515;fill-opacity:1;fill-rule:evenodd;stroke-width:.322645"/>
                    <path id="Administration" d="M188 50v-4h-21V25h55l.005 20.953-20.967-.052L201 50z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:1;stroke:#0b6614;stroke-opacity:1;stroke-dasharray:none;cursor:pointer;"/>
                    <rect id="CCJE_ext" width="69.085" height="9.094" x="225.474" y="255.395" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.947361;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="BCSF" width="9.046" height="16.065" x="171.386" y="235.367" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.929139;stroke-dasharray:none;stroke-opacity:1" transform="rotate(.033)skewX(-.188)"/>
                    <path id="UPP" d="m266.739 230.387 2.421-.002.034 6.564 19.612.102-.008-20.171-19.643-.005.008 6.381-2.423.005z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:1;stroke-dasharray:none;stroke:#0b6615;stroke-opacity:1"/>
                    <path id="AMTC" d="M234.987 228.671 235 231l-4.653.005.006 6.314h21.96L252.3 231l-3.71.005v-16.511l-18.241.012.006 6.32 4.624-.005.013 1.853h-2.44l-.006 5.984z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:1;stroke-dasharray:none;stroke:#0b6615;stroke-opacity:1"/>
                    <path id="TCL" d="M115.062 208.476h4.793v2.842h7.669v14.206h-20.13v-4.736h-1.918v-4.735h1.918v-4.735h7.668z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6516;stroke-width:.952803;stroke-dasharray:none;stroke-opacity:1"/>
                    <g id="Motorpool">
                        <path d="M35 208h21.5v17H35z" style="fill:#0b6516;fill-rule:evenodd;stroke-width:.197316;fill-opacity:1"/>
                        <path d="M36 219h16v5H36z" style="fill:#53ac53;fill-rule:evenodd;stroke-width:.0923133;fill-opacity:1"/>
                        <path d="M50.316 209H55v14.975h-4.684z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke-width:.0619257"/>
                    </g>
                    <rect id="mosque" width="4.956" height="11.33" x="-6.247" y="230.701" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.577552;stroke-dasharray:none;stroke-opacity:1" transform="rotate(-39.394)skewX(-.212)"/>
                    <rect id="TIP_center" width="16.061" height="13.061" x="113.469" y="172.469" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.938838;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Climate" width="19.079" height="14.079" x="90.46" y="172.46" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.920669;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Agri_bldg_1" width="25.023" height="12.048" x="52.486" y="172.483" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.966673;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Agri_bldg_2" width="25.069" height="11.977" x="24.466" y="172.4" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.931364;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="ROTC" width="13.066" height="14.066" x="12.467" y="145.467" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6516;stroke-width:.93382;stroke-dasharray:none;stroke-opacity:1"/>
                    <path id="OSAS" d="M116.158 153.119h15.367v9.406h-23.05v-15.05h7.683z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0a5c12;stroke-width:.950446;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="UC" width="17.065" height="11.065" x="140.467" y="151.467" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6113;stroke-width:.93498;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="GS-SBO" width="16.045" height="8.069" x="269.067" y="155.58" rx="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.920099;stroke-dasharray:none;stroke-opacity:1" transform="rotate(.211)skewY(-.235)"/>
                    <rect id="Alumni_Office" width="9.161" height="8.133" x="257.405" y="155.434" rx="0" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.867483;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="Univesity_AVR" width="24.542" height="9.014" x="258.421" y="144.994" rx="0" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.938589;stroke-dasharray:none;stroke-opacity:1" transform="matrix(1 -.00185 -.00299 1 0 0)"/>
                    <rect id="GS-ext" width="12.06" height="10.479" x="236.47" y="153.077" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:.940078;stroke-dasharray:none;stroke-opacity:1"/>
                    <path id="GS" d="m211.143 132.964-1.745.009-.077 3.893c-2.172 2.1-1.816 5.54-1.816 5.54.025-1.169-.401 2.59 1.77 5.023v4.814l1.81.08.025 11.203h23.54v-10.513h-11.3v-21.025h16.007v-10.489l-28.129-.024z" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:1;stroke-dasharray:none;stroke-opacity:1"/>
                    <rect id="CHS_Labs" width="15.561" height="46.321" x="166.969" y="116.832" rx="0" ry="0" style="fill:#53ac53;fill-opacity:1;fill-rule:evenodd;stroke:#0b6615;stroke-width:1;stroke-dasharray:none;stroke-opacity:1" transform="rotate(.018)skewY(-.229)"/>
                    <path id="MD_2" d="M13.022 83.001h11.957V93H13.022z" style="fill:#53ac53;fill-rule:evenodd;stroke:#0b6113;stroke-width:1;stroke-opacity:1;stroke-dasharray:none;fill-opacity:1"/>
                    <path id="MD_1" d="M13.002 71.001h11.957V81H13.002z" style="fill:#53ac53;fill-rule:evenodd;stroke:#0b6614;stroke-width:1;stroke-opacity:1;stroke-dasharray:none;fill-opacity:1"/>
                </g>
                <g id="BuildingLabels">
                    <!-- Administration -->
                    <rect x="180" y="32" width="60" height="8" rx="1" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="210" y="38" text-anchor="middle" font-size="4.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Administration</text>
                    
                    <!-- CTE -->
                    <rect x="250" y="110" width="40" height="8" rx="1" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="270" y="116" text-anchor="middle" font-size="5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">CTE</text>
                    
                    <!-- College of Nursing -->
                    <rect x="118" y="104" width="54" height="8" rx="1" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="145" y="110" text-anchor="middle" font-size="4.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">College of Nursing</text>
                    
                    <!-- CCJE -->
                    <rect x="240" y="242" width="40" height="8" rx="1" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="260" y="248" text-anchor="middle" font-size="5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">CCJE</text>
                    
                    <!-- CCJE Extension -->
                    <rect x="242" y="256" width="36" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="260" y="260" text-anchor="middle" font-size="3" font-weight="normal" fill="#0d4710" style="pointer-events:none;">Extension</text>
                    
                    <!-- BCSF -->
                    <rect x="161" y="239" width="30" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="176" y="243" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">BCSF</text>
                    
                    <!-- UPP Building -->
                    <rect x="258" y="228" width="40" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="278" y="232" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">UPP Building</text>
                    
                    <!-- Ang Magsasaka -->
                    <rect x="218" y="226" width="48" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="242" y="230" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Ang Magsasaka</text>
                    
                    <!-- ULRC -->
                    <rect x="137" y="204" width="30" height="8" rx="1" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="152" y="210" text-anchor="middle" font-size="5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">ULRC</text>
                    
                    <!-- TCL -->
                    <rect x="107" y="213" width="26" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="120" y="217" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">TCL</text>
                    
                    <!-- DOST -->
                    <rect x="56" y="215" width="28" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="70" y="219" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">DOST</text>
                    
                    <!-- Motorpool -->
                    <rect x="30" y="211" width="32" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="46" y="215" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Motorpool</text>
                    
                    <!-- Food Center -->
                    <rect x="242" y="196" width="36" height="7" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="260" y="201" text-anchor="middle" font-size="4" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Food Center</text>
                    
                    <!-- Mosque -->
                    <rect x="0" y="232" width="22" height="5" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));" transform="rotate(-40 2 236)"/>
                    <text x="2" y="236" text-anchor="start" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;" transform="rotate(-40 2 236)">Mosque</text>
                    
                    <!-- TIP -->
                    <rect x="110" y="174" width="22" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="121" y="178" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">TIP</text>
                    
                    <!-- Climate -->
                    <rect x="84" y="174" width="32" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="100" y="178" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Climate</text>
                    
                    <!-- Agri 1 -->
                    <rect x="52" y="174" width="26" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="65" y="178" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Agri 1</text>
                    
                    <!-- Agri 2 -->
                    <rect x="24" y="174" width="26" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="37" y="178" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Agri 2</text>
                    
                    <!-- ROTC Office -->
                    <rect x="5" y="148" width="28" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="19" y="152" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">ROTC Office</text>
                    
                    <!-- OSAS -->
                    <rect x="109" y="152" width="28" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="123" y="156" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">OSAS</text>
                    
                    <!-- UC -->
                    <rect x="139" y="152" width="20" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="149" y="156" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">UC</text>
                    
                    <!-- GS-SBO -->
                    <rect x="262" y="155" width="30" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="277" y="159" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">GS-SBO</text>
                    
                    <!-- Alumni Relations -->
                    <rect x="248" y="155" width="28" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="262" y="159" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Alumni</text>
                    
                    <!-- Univ AVR -->
                    <rect x="252" y="145" width="36" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="270" y="149" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Univ AVR</text>
                    
                    <!-- GS Ext -->
                    <rect x="230" y="153" width="24" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="242" y="157" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">GS Ext</text>
                    
                    <!-- Graduate School -->
                    <rect x="200" y="141" width="46" height="8" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="223" y="147" text-anchor="middle" font-size="5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Graduate School</text>
                    
                    <!-- College of Health Sciences -->
                    <rect x="148" y="135" width="54" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="175" y="139" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">College of Health Sciences</text>
                    
                    <!-- Field -->
                    <rect x="48" y="98" width="44" height="10" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="70" y="105" text-anchor="middle" font-size="8" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Field</text>
                    
                    <!-- Bleacher -->
                    <rect x="97" y="79" width="26" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="110" y="83" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Bleacher</text>
                    
                    <!-- Parking Area -->
                    <rect x="197" y="82" width="38" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="216" y="86" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Parking Area</text>
                    
                    <!-- LHS Ext -->
                    <rect x="260" y="23" width="30" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="275" y="27" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">LHS Ext</text>
                    
                    <!-- Laboratory Highschool -->
                    <rect x="238" y="43" width="50" height="7" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="263" y="48" text-anchor="middle" font-size="4" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Laboratory Highschool</text>
                    
                    <!-- College of Medicine -->
                    <rect x="258" y="30" width="54" height="7" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="285" y="35" text-anchor="middle" font-size="4" font-weight="bold" fill="#0d4710" style="pointer-events:none;">College of Medicine</text>
                    
                    <!-- Restroom -->
                    <rect x="257" y="6" width="26" height="5" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="270" y="9.5" text-anchor="middle" font-size="2.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Restroom</text>
                    
                    <!-- SKSU-MPC -->
                    <rect x="236" y="7" width="32" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="252" y="11" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">SKSU-MPC</text>
                    
                    <!-- MPC Dorm -->
                    <rect x="247" y="6" width="30" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="262" y="10" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">MPC Dorm</text>
                    
                    <!-- Ladies Dormitory -->
                    <rect x="218" y="7" width="38" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="237" y="11" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Ladies Dormitory</text>
                    
                    <!-- QMS -->
                    <rect x="209" y="6" width="24" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="221" y="10" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">QMS</text>
                    
                    <!-- Function Hall -->
                    <rect x="113" y="56" width="44" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="135" y="60" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Function Hall</text>
                    
                    <!-- University Gymnasium -->
                    <rect x="118" y="36" width="40" height="8" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="138" y="42" text-anchor="middle" font-size="5" font-weight="bold" fill="#0d4710" style="pointer-events:none;">University Gymnasium</text>
                    
                    <!-- Registrar -->
                    <rect x="172" y="18" width="36" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="190" y="22" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Registrar</text>
                    
                    <!-- Men's Dormitory 1 -->
                    <rect x="5" y="72" width="28" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="19" y="76" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Men's Dorm 1</text>
                    
                    <!-- Men's Dormitory 2 -->
                    <rect x="5" y="83" width="28" height="6" rx="0.8" fill="white" fill-opacity="0.95" stroke="#0d4710" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));"/>
                    <text x="19" y="87" text-anchor="middle" font-size="3" font-weight="bold" fill="#0d4710" style="pointer-events:none;">Men's Dorm 2</text>
                </g>
                <g id="NavigationPoints">
                    <circle cx="195" cy="50" r="2" fill="#f44336" data-building="Administration"/>
                    <circle cx="252" cy="111" r="2" fill="#f44336" data-building="CTE"/>
                    <circle cx="162" cy="111" r="2" fill="#f44336" data-building="CHS"/>
                    <circle cx="202" cy="203" r="2" fill="#f44336" data-building="CCJE"/>
                    <circle cx="134" cy="171" r="2" fill="#f44336" data-building="ULRC"/>
                    <circle cx="120" cy="203" r="2" fill="#f44336" data-building="TCL"/>
                    <circle cx="70" cy="203" r="2" fill="#f44336" data-building="DOST"/>
                    <circle cx="260" cy="168" r="2" fill="#f44336" data-building="FC"/>
                    <circle cx="223" cy="168" r="2" fill="#f44336" data-building="GS"/>
                    <circle cx="162" cy="111" r="2" fill="#f44336" data-building="CHS_Labs"/>
                    <circle cx="162" cy="111" r="2" fill="#f44336" data-building="UG"/>
                    <circle cx="30" cy="167" r="2" fill="#f44336" data-building="Field"/>
                </g>
            </svg>
            </div>
        </div>
        
        <!-- Legend Section (35%) -->
        <div class="bg-white rounded-xl shadow-lg p-6 overflow-y-auto" style="flex: 0 0 35%; max-height: calc(100vh - 200px);">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 pb-3" style="border-color: #248823;">Map Legend</h2>
            
            <!-- Campus Buildings Directory -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="text-2xl">🏛️</span> Campus Buildings
                </h3>
                <div class="space-y-1 text-base max-h-96 overflow-y-auto" style="scrollbar-width: thin;" id="buildingList">
                    <!-- Academic Colleges -->
                    <div class="font-semibold text-gray-800 mt-2 mb-1 text-lg">Academic Colleges</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Administration')">• Administration</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('CTE')">• College of Education</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('CHS')">• College of Nursing</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('CHS_Labs')">• College of Health Sciences</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('CCJE')">• College of Criminal Justice</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('CCJE_ext')">• CCJE Extension</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('CoM')">• College of Medicine</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('GS')">• Graduate School</div>
                    
                    <!-- Facilities & Services -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Facilities & Services</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('ULRC')">• University Library (ULRC)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('UG')">• University Gymnasium</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('UC')">• University Canteen (UC)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Function')">• Function Hall</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('UPP')">• UPP Building</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Motorpool')">• University Motorpool</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('FC')">• Food Center</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Parking_Space')">• Parking Area</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Restroom')">• Public Restroom</div>
                    
                    <!-- Medical & Training -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Medical & Training Centers</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('BCSF')">• Basic & Clinical Sciences (BCSF)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('AMTC')">• Ang Magsasaka Training Center</div>
                    
                    <!-- Research & Development -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Research & Development</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('TIP_center')">• Technology Incubation Park (TIP)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('TCL')">• Technology & Computer Lab (TCL)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('DOST')">• DOST Innovation Center</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Climate')">• Climate Resillient and Adoptation Center</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Agri_bldg_1')">• Agriculture Building 1</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Agri_bldg_2')">• Agriculture Building 2</div>
                    
                    <!-- Administrative Offices -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Administrative Offices</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Reg_Office')">• Registrar's Office</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Alumni_Office')">• Alumni Relations Office</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('GS-SBO')">• Graduate School - SBO Office</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('GS-ext')">• GS Extension Office</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('OSAS')">• Student Affairs (OSAS)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('QMS')">• Quality Management (QMS)</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('ULD')">• Ladies Dormitory</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Univesity_AVR')">• Audio-Visual Room (AVR)</div>
                    
                    <!-- Student Services -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Student Services</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('LHS')">• Laboratory Highschool</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('LHS_ext')">• LHS Extension</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('ROTC')">• ROTC Office</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('SKSU-MPC')">• SKSU Multi-Purpose Center</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('MPC-Dorm')">• MPC Dormitory</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('MD_1')">• Men's Dormitory 1</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('MD_2')">• Men's Dormitory 2</div>
                    
                    <!-- Sports & Recreation -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Sports & Recreation</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Field')">• University Athletic Field</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('Bleacher')">• Field Bleachers</div>
                    
                    <!-- Religious -->
                    <div class="font-semibold text-gray-800 mt-3 mb-1 text-lg">Religious Facility</div>
                    <div class="text-gray-700 pl-2 py-1 cursor-pointer legend-item rounded transition-colors" onclick="navigateTo('mosque')">• University Mosque</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Building Tooltip -->
<div class="building-tooltip" id="buildingTooltip"></div>

<div class="modal-overlay" id="buildingModal">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-start mb-6">
            <h2 id="modalTitle" class="text-3xl font-bold text-gray-800"></h2>
            <button onclick="closeModal()" class="text-4xl text-gray-500 hover:text-gray-700">×</button>
        </div>
        <div id="modalContent"></div>
    </div>
</div>

<!-- Admin Login Modal -->
<div class="modal-overlay" id="adminLoginModal" style="backdrop-filter: blur(8px);">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl" style="animation: modalSlideIn 0.3s ease-out;">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Admin Login</h2>
                    <p class="text-sm text-gray-500">Access admin panel</p>
                </div>
            </div>
            <button onclick="closeAdminLogin()" class="text-3xl text-gray-400 hover:text-gray-600 transition">×</button>
        </div>
        
        <form action="{{ route('login') }}" method="POST" id="adminLoginForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" 
                       name="username" 
                       required
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-teal-500 transition"
                       placeholder="admin">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-teal-500 transition"
                       placeholder="admin123">
            </div>

            <div id="loginError" class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg hidden">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="loginErrorText"></span>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-lg"
                    style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(152, 216, 200, 0.5)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(152, 216, 200, 0.4)';">
                Login to Admin Panel
            </button>
        </form>
    </div>
</div>

<!-- About Modal -->
<div class="modal-overlay" id="aboutModal" style="backdrop-filter: blur(8px);">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl" style="animation: modalSlideIn 0.3s ease-out;">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">About</h2>
                    <p class="text-sm text-gray-500">SKSU Access Campus Map</p>
                </div>
            </div>
            <button onclick="closeAbout()" class="text-3xl text-gray-400 hover:text-gray-600 transition">×</button>
        </div>
        
        <div class="space-y-4">
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Campus Navigation System</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    An interactive campus map designed to help students, faculty, and visitors navigate the 
                    Sultan Kudarat State University campus with ease.
                </p>
            </div>
            
            <div class="border-t pt-4">
                <h3 class="font-semibold text-gray-800 mb-2">Features</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Interactive building selection</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Office directory & services</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Real-time announcements</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Building image galleries</span>
                    </li>
                </ul>
            </div>
            
            <div class="border-t pt-4">
                <p class="text-xs text-gray-500 text-center">
                    © 2025 Sultan Kudarat State University<br>
                    Version 1.0.0
                </p>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showAdminLogin();
        const errorDiv = document.getElementById('loginError');
        const errorText = document.getElementById('loginErrorText');
        errorDiv.classList.remove('hidden');
        errorText.textContent = "{{ $errors->first() }}";
    });
</script>
@endif

<style>
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
@endsection

@section('scripts')
<script>
    const buildings = @json($buildings);
    const isAdmin = @json($isAdmin);
    const dbEndpoints = @json($navigationEndpoints ?? []);
    
    // Main gate starting point (aligned with path start)
    const kioskX = 188.32;
    const kioskY = 267.166;
    let editMode = false;
    let draggedElement = null;
    let offsetX = 0;
    let offsetY = 0;
    
    // Endpoint edit mode variables (must be declared at top)
    let endpointEditMode = false;
    let draggedEndpoint = null;
    let endpointOffsetX = 0;
    let endpointOffsetY = 0;
    let modifiedEndpoints = {};
    
    // Navigation endpoints - UPDATED to use precise skeleton network
    let navigationPoints = {
        // Academic Buildings
        'Administration': {x: 193, y: 50, roadConnection: 'spine_roundabout'},
        'CTE': {x: 257, y: 108, roadConnection: 'east_north'},
        'CTE Building': {x: 257, y: 108, roadConnection: 'east_north'},
        'CHS': {x: 157, y: 110, roadConnection: 'west_north'},
        'CHS Building': {x: 157, y: 110, roadConnection: 'west_north'},
        'CHS_Labs': {x: 175, y: 140, roadConnection: 'west_140'},
        'CCJE': {x: 261, y: 240, roadConnection: 'spine_south'},
        'CCJE Building': {x: 261, y: 240, roadConnection: 'spine_south'},
        'CCJE_ext': {x: 261, y: 256, roadConnection: 'spine_south_lower'},
        'CoM': {x: 282, y: 43, roadConnection: 'east_top'},
        'GS': {x: 207, y: 142, roadConnection: 'spine_north_130'},
        
        // Facilities & Services
        'ULRC': {x: 168, y: 209, roadConnection: 'spine_south_210'},
        'ULRC Library': {x: 168, y: 209, roadConnection: 'spine_south_210'},
        'UG': {x: 158, y: 41, roadConnection: 'west_north'},
        'UC': {x: 148, y: 163, roadConnection: 'horiz_134'},
        'Function': {x: 135, y: 61, roadConnection: 'west_top'},
        'Function Hall': {x: 135, y: 61, roadConnection: 'west_top'},
        'UPP': {x: 278, y: 223, roadConnection: 'spine_south'},
        'Motorpool': {x: 46, y: 220, roadConnection: 'south_46'},
        'FC': {x: 259, y: 224, roadConnection: 'spine_south'},
        'Parking_Space': {x: 232, y: 96, roadConnection: 'north_220'},
        'Parking': {x: 232, y: 96, roadConnection: 'north_220'},
        'Restroom': {x: 272, y: 13, roadConnection: 'east_top'},
        
        // Medical & Training
        'BCSF': {x: 180, y: 243, roadConnection: 'spine_south_210'},
        'AMTC': {x: 233, y: 226, roadConnection: 'spine_south'},
        
        // Research & Development
        'TIP_center': {x: 122, y: 173, roadConnection: 'horiz_134'},
        'TIP': {x: 122, y: 173, roadConnection: 'horiz_134'},
        'TCL': {x: 117, y: 217, roadConnection: 'conn_134_south'},
        'DOST': {x: 70, y: 220, roadConnection: 'south_70'},
        'Climate': {x: 101, y: 173, roadConnection: 'horiz_100'},
        'Agri_bldg_1': {x: 67, y: 173, roadConnection: 'horiz_70'},
        'Agri 1': {x: 67, y: 173, roadConnection: 'horiz_70'},
        'Agri_bldg_2': {x: 37, y: 173, roadConnection: 'horiz_30'},
        'Agri 2': {x: 37, y: 173, roadConnection: 'horiz_30'},
        
        // Administrative Offices
        'Reg_Office': {x: 190, y: 23, roadConnection: 'dirt_north_208'},
        'Registrar': {x: 190, y: 23, roadConnection: 'dirt_north_208'},
        'Alumni_Office': {x: 262, y: 164, roadConnection: 'east_south'},
        'Alumni': {x: 262, y: 164, roadConnection: 'east_south'},
        'GS-SBO': {x: 276, y: 163, roadConnection: 'horiz_east_end'},
        'GS-ext': {x: 244, y: 163, roadConnection: 'horiz_220'},
        'GS Ext': {x: 244, y: 163, roadConnection: 'horiz_220'},
        'OSAS': {x: 119, y: 161, roadConnection: 'horiz_134'},
        'QMS': {x: 222, y: 17, roadConnection: 'dirt_north_end'},
        'ULD': {x: 239, y: 18, roadConnection: 'dirt_north_top'},
        'Univesity_AVR': {x: 258, y: 148, roadConnection: 'east_140'},
        'Univ AVR': {x: 258, y: 148, roadConnection: 'east_140'},
        
        // Student Services
        'LHS': {x: 257, y: 47, roadConnection: 'east_50'},
        'LHS_ext': {x: 271, y: 23, roadConnection: 'east_top'},
        'LHS Ext': {x: 271, y: 23, roadConnection: 'east_top'},
        'ROTC': {x: 25, y: 152, roadConnection: 'horiz_30'},
        'SKSU-MPC': {x: 253, y: 16, roadConnection: 'east_top'},
        'MPC': {x: 253, y: 16, roadConnection: 'east_top'},
        'MPC-Dorm': {x: 262, y: 15, roadConnection: 'east_top'},
        'MPC Dorm': {x: 262, y: 15, roadConnection: 'east_top'},
        'MD_1': {x: 20, y: 76, roadConnection: 'far_west_80'},
        'MD 1': {x: 20, y: 76, roadConnection: 'far_west_80'},
        'MD_2': {x: 20, y: 88, roadConnection: 'far_west_80'},
        'MD 2': {x: 20, y: 88, roadConnection: 'far_west_80'},
        
        // Sports & Recreation
        'Field': {x: 69, y: 160, roadConnection: 'horiz_100'},
        'Bleacher': {x: 115, y: 72, roadConnection: 'horiz_134'},
        
        // Religious
        'mosque': {x: 149, y: 184, roadConnection: 'conn_134_185'},
        'Mosque': {x: 149, y: 184, roadConnection: 'conn_134_185'}
    };
    
    // Merge database endpoints with defaults (database values take priority)
    if (dbEndpoints && Object.keys(dbEndpoints).length > 0) {
        Object.keys(dbEndpoints).forEach(buildingName => {
            if (dbEndpoints[buildingName].x && dbEndpoints[buildingName].y) {
                navigationPoints[buildingName] = {
                    x: parseFloat(dbEndpoints[buildingName].x),
                    y: parseFloat(dbEndpoints[buildingName].y),
                    roadConnection: dbEndpoints[buildingName].roadConnection || navigationPoints[buildingName]?.roadConnection || 'gate'
                };
            }
        });
        console.log('Loaded', Object.keys(dbEndpoints).length, 'endpoint(s) from database');
    }
    
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.toLocaleTimeString();
    }
    updateClock();
    setInterval(updateClock, 1000);
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const buildingList = document.getElementById('buildingList');
        const buildings = buildingList.querySelectorAll('[onclick^="navigateTo"]');
        const categories = buildingList.querySelectorAll('.font-semibold');
        
        buildings.forEach(building => {
            const buildingName = building.textContent.toLowerCase();
            if (buildingName.includes(searchTerm)) {
                building.style.display = '';
            } else {
                building.style.display = 'none';
            }
        });
        
        // Show/hide category headers based on visible buildings
        categories.forEach(category => {
            let nextElement = category.nextElementSibling;
            let hasVisibleBuildings = false;
            
            while (nextElement && !nextElement.classList.contains('font-semibold')) {
                if (nextElement.style.display !== 'none') {
                    hasVisibleBuildings = true;
                    break;
                }
                nextElement = nextElement.nextElementSibling;
            }
            
            category.style.display = hasVisibleBuildings ? '' : 'none';
        });
    });
    
    // Map SVG building IDs to database building names
    const buildingNameMap = {
        'Administration': 'Administration',
        'CTE': 'CTE Building',
        'CHS': 'CHS Building',
        'CCJE': 'CCJE Building',
        'ULRC': 'ULRC Library'
    };
    
    // Map SVG IDs to formal display names for navigation
    const svgToDisplayName = {
        'mosque': 'University Mosque',
        'TIP_center': 'Technology Incubation Park',
        'Agri_bldg_1': 'Agriculture Building 1',
        'Agri_bldg_2': 'Agriculture Building 2',
        'GS-SBO': 'Graduate School - SBO Office',
        'Alumni_Office': 'Alumni Affairs Office',
        'Univesity_AVR': 'University Audio-Visual Room',
        'GS-ext': 'Graduate School Extension',
        'CHS_Labs': 'College of Health Sciences Laboratory',
        'Parking_Space': 'Campus Parking Area',
        'LHS_ext': 'Laboratory High School Extension',
        'SKSU-MPC': 'SKSU Multi-Purpose Center',
        'MPC-Dorm': 'MPC Dormitory',
        'Function': 'University Function Hall',
        'Reg_Office': 'Registrar\'s Office',
        'MD_1': 'Mini Dorm 1',
        'MD_2': 'Mini Dorm 2',
        'BCSF': 'Basic & Clinical Sciences Facility',
        'UPP': 'University Printing Press',
        'AMTC': 'Advanced Medical Training Center',
        'TCL': 'Technology & Computer Laboratory',
        'DOST': 'DOST Innovation Center',
        'Motorpool': 'University Motorpool',
        'FC': 'Facilities Center',
        'Climate': 'Climate Research Center',
        'ROTC': 'ROTC Building',
        'OSAS': 'Office of Student Affairs & Services',
        'UC': 'University Canteen',
        'GS': 'Graduate School',
        'Field': 'University Athletic Field',
        'Bleacher': 'Field Bleachers',
        'LHS': 'Laboratory High School',
        'CoM': 'College of Medicine',
        'Restroom': 'Public Restroom Facility',
        'ULD': 'University Language Development Center',
        'QMS': 'Quality Management Services',
        'UG': 'University Gym'
    };
    
    // Add click handlers to SVG buildings
    document.addEventListener('DOMContentLoaded', function() {
        const clickableBuildings = ['Administration', 'CTE', 'CHS', 'CCJE', 'BCSF', 'UPP', 'AMTC', 'ULRC', 'TCL', 'DOST', 
                                   'Motorpool', 'FC', 'mosque', 'TIP_center', 'Climate', 'Agri_bldg_1', 'Agri_bldg_2', 
                                   'ROTC', 'OSAS', 'UC', 'GS-SBO', 'Alumni_Office', 'Univesity_AVR', 'GS-ext', 'GS', 
                                   'CHS_Labs', 'Field', 'Bleacher', 'Parking_Space', 'LHS_ext', 'LHS', 'CoM', 'Restroom', 
                                   'SKSU-MPC', 'MPC-Dorm', 'ULD', 'QMS', 'Function', 'UG', 'Reg_Office', 'MD_1', 'MD_2'];
        
        clickableBuildings.forEach(buildingId => {
            const element = document.getElementById(buildingId);
            if (element) {
                element.style.cursor = 'pointer';
                element.addEventListener('click', function(e) {
                    if (editMode) return;
                    e.stopPropagation();
                    
                    // Visual feedback: Add temporary highlight
                    element.classList.add('building-selected');
                    setTimeout(() => {
                        element.classList.remove('building-selected');
                    }, 1000);
                    
                    // Dismiss hint on first interaction
                    const hint = document.getElementById('interactiveHint');
                    if (hint) {
                        hint.style.display = 'none';
                    }
                    
                    // Get display name for navigation
                    const displayName = svgToDisplayName[buildingId] || buildingId;
                    
                    // Navigate directly without modal
                    navigateTo(displayName);
                });
                
                // Add hover tooltip functionality
                element.addEventListener('mouseenter', function(e) {
                    if (editMode) return;
                    
                    const displayName = svgToDisplayName[buildingId] || buildingId;
                    const tooltip = document.getElementById('buildingTooltip');
                    tooltip.textContent = displayName;
                    tooltip.classList.add('show');
                });
                
                element.addEventListener('mousemove', function(e) {
                    if (editMode) return;
                    
                    const tooltip = document.getElementById('buildingTooltip');
                    const offsetX = 15;
                    const offsetY = -30;
                    
                    // Position tooltip above and slightly to the right of cursor
                    tooltip.style.left = (e.clientX + offsetX) + 'px';
                    tooltip.style.top = (e.clientY + offsetY) + 'px';
                });
                
                element.addEventListener('mouseleave', function(e) {
                    const tooltip = document.getElementById('buildingTooltip');
                    tooltip.classList.remove('show');
                });
            }
        });
    });
    
    function showBuildingModal(buildingId) {
        if (editMode) return;
        
        const building = buildings.find(b => b.id === buildingId);
        if (!building) return;
        
        document.getElementById('modalTitle').textContent = building.name;
        
        let content = '';
        
        // Image Gallery with Swiper
        const gallery = building.image_gallery || [];
        const hasMainImage = building.image_path;
        const allImages = [];
        
        if (hasMainImage) {
            allImages.push(building.image_path);
        }
        if (gallery.length > 0) {
            allImages.push(...gallery);
        }
        
        if (allImages.length > 0) {
            content += `
                <div class="relative mb-6">
                    <div class="swiper buildingGallerySwiper">
                        <div class="swiper-wrapper">
                            ${allImages.map(img => `
                                <div class="swiper-slide">
                                    <img src="/storage/${img}" class="w-full h-80 object-cover rounded-lg">
                                </div>
                            `).join('')}
                        </div>
                        ${allImages.length > 1 ? `
                            <div class="swiper-button-next" style="color: #248823;"></div>
                            <div class="swiper-button-prev" style="color: #248823;"></div>
                            <div class="swiper-pagination"></div>
                        ` : ''}
                    </div>
                    ${allImages.length > 1 ? `
                        <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            <span class="swiper-current">1</span> / ${allImages.length}
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        // Building Description
        if (building.description) {
            content += `
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 mb-6 border-l-4" style="border-color: #248823;">
                    <p class="text-gray-700">${building.description}</p>
                </div>
            `;
        }
        
        // Offices Section
        if (building.offices && building.offices.length > 0) {
            content += '<h3 class="text-2xl font-bold mb-4 flex items-center gap-2"><span style="color: #248823;">🏛️</span> Offices in this Building</h3>';
            building.offices.forEach(office => {
                content += `
                    <div class="bg-white border border-gray-200 hover:border-teal-300 p-4 rounded-lg mb-3 transition shadow-sm hover:shadow-md">
                        <h4 class="text-xl font-semibold mb-2" style="color: #248823;">${office.name}</h4>
                        ${office.floor_number ? `<p class="text-gray-600 text-sm mb-2">📍 Floor: ${office.floor_number}</p>` : ''}
                        ${office.head_name ? `
                            <div class="mt-2 bg-gray-50 rounded p-3">
                                <p class="font-medium text-gray-800">${office.head_name}</p>
                                <p class="text-sm text-gray-600">${office.head_title || ''}</p>
                            </div>
                        ` : ''}
                        ${office.services && office.services.length > 0 ? `
                            <div class="mt-3">
                                <p class="font-semibold mb-2 text-gray-700">📋 Services:</p>
                                <ul class="space-y-1">
                                    ${office.services.map(s => `
                                        <li class="flex items-start gap-2 text-sm text-gray-700">
                                            <span class="text-teal-500 mt-1">•</span>
                                            <span>${s.description}</span>
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `;
            });
        }
        
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('buildingModal').classList.add('active');
        
        // Initialize Swiper after content is loaded
        if (allImages.length > 1) {
            setTimeout(() => {
                new Swiper('.buildingGallerySwiper', {
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    on: {
                        slideChange: function() {
                            const current = document.querySelector('.swiper-current');
                            if (current) {
                                current.textContent = this.realIndex + 1;
                            }
                        }
                    }
                });
            }, 100);
        }
    }
    
    function closeModal() {
        document.getElementById('buildingModal').classList.remove('active');
    }

    function showAdminLogin() {
        document.getElementById('adminLoginModal').classList.add('active');
    }

    function closeAdminLogin() {
        document.getElementById('adminLoginModal').classList.remove('active');
        // Clear error messages
        const errorDiv = document.getElementById('loginError');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }
    }

    function showAbout() {
        document.getElementById('aboutModal').classList.add('active');
    }

    function closeAbout() {
        document.getElementById('aboutModal').classList.remove('active');
    }

    function toggleEditMode() {
        editMode = !editMode;
        if (editMode) {
            alert('Edit Mode Activated!\n\nYou can now:\n• Drag building markers to reposition them\n• Click buildings to edit their information\n\nClick "Admin Inline Edit" again to deactivate.');
        } else {
            alert('Edit Mode Deactivated');
        }
    }

    // Close modals when clicking outside
    document.getElementById('adminLoginModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAdminLogin();
        }
    });

    document.getElementById('aboutModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAbout();
        }
    });
    
    function navigateTo(buildingName) {
        closeModal();
        
        // Map building name to navigation point key
        // Try the name as-is first, then check if it exists in navigationPoints
        let navKey = buildingName;
        
        // If not found, try to find by checking all keys
        if (!navigationPoints[navKey]) {
            // Check display names mapping
            const svgId = Object.keys(svgToDisplayName).find(key => svgToDisplayName[key] === buildingName);
            if (svgId && navigationPoints[svgId]) {
                navKey = svgId;
            } else {
                // Try common variations
                const variations = [
                    buildingName,
                    buildingName.replace(' Building', ''),
                    buildingName.replace(' Library', ''),
                    buildingName.replace('University ', ''),
                    buildingName.replace('Registrar\'s Office', 'Registrar'),
                    buildingName.replace('Technology Incubation Park', 'TIP'),
                    buildingName.replace('University Mosque', 'Mosque'),
                    buildingName.replace('Agriculture Building ', 'Agri '),
                    buildingName.replace('Alumni Affairs Office', 'Alumni'),
                    buildingName.replace('University Audio-Visual Room', 'Univ AVR'),
                    buildingName.replace('Graduate School Extension', 'GS Ext'),
                    buildingName.replace('College of Health Sciences Laboratory', 'CHS Labs'),
                    buildingName.replace('Campus Parking Area', 'Parking'),
                    buildingName.replace('Laboratory High School Extension', 'LHS Ext'),
                    buildingName.replace('SKSU Multi-Purpose Center', 'SKSU-MPC'),
                    buildingName.replace('MPC Dormitory', 'MPC Dorm'),
                    buildingName.replace('University Function Hall', 'Function Hall'),
                    buildingName.replace('DOST Innovation Center', 'DOST'),
                    buildingName.replace('University Motorpool', 'Motorpool'),
                    buildingName.replace('Facilities Center', 'FC'),
                    buildingName.replace('Climate Research Center', 'Climate'),
                    buildingName.replace('ROTC Building', 'ROTC'),
                    buildingName.replace('Office of Student Affairs & Services', 'OSAS'),
                    buildingName.replace('University Canteen', 'UC'),
                    buildingName.replace('Graduate School', 'GS'),
                    buildingName.replace('University Athletic Field', 'Field'),
                    buildingName.replace('Field Bleachers', 'Bleacher'),
                    buildingName.replace('Laboratory High School', 'LHS'),
                    buildingName.replace('College of Medicine', 'CoM'),
                    buildingName.replace('Public Restroom Facility', 'Restroom'),
                    buildingName.replace('University Language Development Center', 'ULD'),
                    buildingName.replace('Quality Management Services', 'QMS'),
                    buildingName.replace('University Gym', 'UG'),
                    buildingName.replace('Technology & Computer Laboratory', 'TCL'),
                    buildingName.replace('Basic & Clinical Sciences Facility', 'BCSF'),
                    buildingName.replace('University Printing Press', 'UPP'),
                    buildingName.replace('Advanced Medical Training Center', 'AMTC')
                ];
                
                for (const variant of variations) {
                    if (navigationPoints[variant]) {
                        navKey = variant;
                        break;
                    }
                }
            }
        }
        
        drawNavigationPath(navKey);
    }
    
    // Enhanced interactivity: Keyboard shortcuts and click-outside-to-close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    
    // Click outside modal to close
    document.getElementById('buildingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Draw road skeleton overlay (blue dashed lines showing navigation network)
    function drawRoadSkeleton() {
        const svg = document.getElementById('campusMap');
        if (!svg) return;
        
        // Remove existing skeleton if any
        const existingSkeleton = document.getElementById('roadSkeleton');
        if (existingSkeleton) existingSkeleton.remove();
        
        // Create skeleton group
        const skeletonGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        skeletonGroup.setAttribute('id', 'roadSkeleton');
        
        // Draw all road connections
        roadNetwork.roads.forEach(([start, end]) => {
            const startPoint = roadNetwork.intersections[start];
            const endPoint = roadNetwork.intersections[end];
            
            if (!startPoint || !endPoint) return;
            
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', startPoint.x);
            line.setAttribute('y1', startPoint.y);
            line.setAttribute('x2', endPoint.x);
            line.setAttribute('y2', endPoint.y);
            line.setAttribute('class', 'skeleton-road');
            skeletonGroup.appendChild(line);
        });
        
        // Draw intersection nodes
        for (const [name, point] of Object.entries(roadNetwork.intersections)) {
            const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            circle.setAttribute('cx', point.x);
            circle.setAttribute('cy', point.y);
            circle.setAttribute('r', '2');
            circle.setAttribute('class', 'skeleton-node');
            
            // Add title for debugging
            const title = document.createElementNS('http://www.w3.org/2000/svg', 'title');
            title.textContent = name;
            circle.appendChild(title);
            
            skeletonGroup.appendChild(circle);
        }
        
        // Safely append skeleton to SVG
        try {
            const firstBuilding = svg.querySelector('[id^="Admin"], [id^="CTE"], [id^="CHS"]');
            if (firstBuilding && firstBuilding.parentNode === svg) {
                svg.insertBefore(skeletonGroup, firstBuilding);
            } else {
                svg.appendChild(skeletonGroup);
            }
        } catch (e) {
            console.warn('Could not insert skeleton before building, appending instead:', e);
            svg.appendChild(skeletonGroup);
        }
    }
    
    // Interactive enhancements initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Draw the road skeleton overlay
        drawRoadSkeleton();
        
        // Auto-dismiss hint after 4 seconds
        const hint = document.getElementById('interactiveHint');
        if (hint) {
            setTimeout(() => {
                hint.style.display = 'none';
            }, 4000);
        }
        
        // Add subtle entrance animation to map
        const mapContainer = document.getElementById('mapContainer');
        if (mapContainer) {
            mapContainer.style.opacity = '0';
            mapContainer.style.transform = 'scale(0.95)';
            setTimeout(() => {
                mapContainer.style.transition = 'all 0.5s ease';
                mapContainer.style.opacity = '1';
                mapContainer.style.transform = 'scale(1)';
            }, 100);
        }
        
        // Dismiss hint on any map interaction
        const svg = document.getElementById('campusMap');
        if (svg) {
            svg.addEventListener('click', function() {
                if (hint) {
                    hint.style.display = 'none';
                }
            }, { once: true });
        }
    });
    
    // EXACT ROAD SKELETON - Coordinates extracted directly from mapresource.json SVG paths
    // All coordinates match the actual white road centerlines precisely
    const roadNetwork = {
        intersections: {
            // KIOSK / MAIN GATE (Starting point - actual position)
            'gate': {x: 195, y: 260},
            
            // === MAIN VERTICAL SPINE (Central north-south road) ===
            // path-2: M188.32 267.166 → ends at 85.042 (y: 267.166 - 182.124 = 85.042)
            // path-1: M202.28 267.912 → ends at 87.154 (y: 267.912 - 180.758 = 87.154)
            // Transform applied: translate(0 -.094) so subtract 0.094 from y values
            'spine_gate': {x: 195, y: 260},
            'spine_south_lower': {x: 202, y: 240},
            'spine_south': {x: 202.28, y: 226},
            'spine_south_210': {x: 188, y: 210},
            'spine_center': {x: 188.32, y: 167.47},
            'spine_north_130': {x: 188.32, y: 130},
            'spine_north': {x: 188.32, y: 111},
            'spine_roundabout': {x: 203.295, y: 53.462},
            
            // === CENTER HORIZONTAL ROAD (y≈167.47-167.95) - MAIN EAST-WEST ARTERY ===
            // path-4: M15.007 167.47 → 184.517 (15.007 + 169.51 = 184.517)
            'horiz_west_end': {x: 15.007, y: 167.47},
            'horiz_30': {x: 30.031, y: 167.477},
            'horiz_70': {x: 70, y: 167.47},
            'horiz_100': {x: 100, y: 167.47},
            'horiz_134': {x: 134.048, y: 167.47},
            'horiz_162': {x: 162.074, y: 167.47},
            'horiz_center': {x: 184.517, y: 167.478},
            // path-5: M200.776 168.003 → 286.339 (200.776 + 85.563 = 286.339)
            // path-6: M252.48 167.945 (eastern vertical intersects here)
            'horiz_202': {x: 200.776, y: 168.003},
            'horiz_220': {x: 220, y: 168.003},
            'horiz_252': {x: 252.48, y: 167.945},
            'horiz_east_end': {x: 286.339, y: 168.02},
            
            // === SOUTHERN HORIZONTAL ROAD (y≈202.996) ===
            // path-9: M14.286 203.09 → 124.306 (14.286 + 110.02 = 124.306)
            // Transform: translate(0 -.094) so actual y = 203.09 - 0.094 = 202.996
            'south_west_end': {x: 14.286, y: 202.996},
            'south_30': {x: 30, y: 202.996},
            'south_46': {x: 46, y: 202.996},
            'south_70': {x: 70, y: 202.996},
            'south_100': {x: 100, y: 202.996},
            'south_124': {x: 124.306, y: 203.002},
            'south_134': {x: 134.048, y: 202.24},
            
            // === WESTERN VERTICAL ROAD (x≈161.956) ===
            // path-7: M118.539 105.627 with transform(43.417, 59.938) = (161.956, 165.565)
            // path extends from y: 165.565 to -30.29 (105.627 - 135.917 = -30.29 + 59.938 = 29.648)
            'west_south': {x: 161.956, y: 167.091},
            'west_140': {x: 161.956, y: 140},
            'west_north': {x: 161.494, y: 110.994},
            'west_80': {x: 161.956, y: 80},
            'west_top': {x: 161.956, y: 29.648},
            
            // === EASTERN VERTICAL ROAD (x≈252.48-252.638) ===
            // path-6: M252.48 167.945 → 22.585 (167.945 - 145.36 = 22.585)
            'east_south': {x: 252.48, y: 167.945},
            'east_140': {x: 252.55, y: 140},
            'east_north': {x: 252.55, y: 111},
            'east_80': {x: 252.55, y: 80},
            'east_50': {x: 252.55, y: 50},
            'east_top': {x: 252.638, y: 22.585},
            
            // === FAR WEST VERTICAL ROAD (x≈30.031) ===
            // path-8: M30.031 167.477 → 59.369 (167.477 - 108.108 = 59.369)
            // Also: path M30.144 164.837 with transform(0, -0.094) → y: 164.743
            'far_west_south': {x: 30.031, y: 167.477},
            'far_west_130': {x: 30.144, y: 130},
            'far_west_field': {x: 30.144, y: 105},
            'far_west_80': {x: 30.144, y: 79.437},
            'far_west_north': {x: 30.144, y: 59.437},
            
            // === VERTICAL CONNECTOR (x≈134.048) ===
            // path-4-1: M134.048 202.24 → 168.355 (202.24 - 33.885 = 168.355)
            'conn_134_south': {x: 134.048, y: 202.24},
            'conn_134_185': {x: 134.048, y: 185},
            'conn_134_center': {x: 134.048, y: 168.355},
            
            // === NORTHERN HORIZONTAL CONNECTORS (y≈110.939-111.071) ===
            // path-1-3: M159.412 51.001 with transform(43.417, 59.938) → (202.829, 110.939)
            // path-101-5-2-3-6-8-7-6-2: M161.494 110.994 → 184.906
            // path-101-5-2-3-6-8-7-2: M203.193 111.071 → 246.811
            'north_162': {x: 161.494, y: 110.994},
            'north_180': {x: 180, y: 110.994},
            'north_188': {x: 184.906, y: 110.994},
            'north_203': {x: 203.193, y: 111.071},
            'north_220': {x: 220, y: 111.071},
            'north_246': {x: 246.811, y: 111.116},
            
            // === DIRT PATHS (brown/tan paths) ===
            // Dirt_path-1: M183.54 159.062 with transform(43.417, 60.031)
            // Start: (183.54 + 43.417, 159.062 + 60.031) = (226.957, 219.093)
            // Horizontal segment: 29.65 right → (256.607, 211.022)
            // Vertical down: 16.081 → (256.607, 227.103)
            // Final: 4.84 right → (261.447, 227.103)
            'dirt_start': {x: 226.957, y: 219.093},
            'dirt_corner_1': {x: 229.252, y: 211.022},
            'dirt_horiz_244': {x: 244, y: 211.022},
            'dirt_corner_2': {x: 256.607, y: 211.022},
            'dirt_vert': {x: 256.607, y: 227.103},
            'dirt_horiz_end': {x: 261.447, y: 227.103},
            // Dirt-Path-2: M164.583 -4.938 with transform(43.417, 60.031)
            // Start: (164.583 + 43.417, -4.938 + 60.031) = (208, 55.093)
            // 30 right: (238, 55.093), then 34 up: (238, 21.093), left 18: (220, 21.093)
            'dirt_north_208': {x: 208, y: 55.093},
            'dirt_north_corner': {x: 238, y: 55.093},
            'dirt_north_top': {x: 238, y: 21.093},
            'dirt_north_end': {x: 220, y: 21.093},
            
            // === ROUNDABOUT / CIRCLE (center point) ===
            // path-3: Circular path around x≈203, y≈53
            'roundabout_center': {x: 203, y: 53},
            
            // === SMALL CONNECTORS ===
            // path-1-2: M200.864 225.943 → 220.232 (200.864 + 19.368 = 220.232)
            'conn_201_226': {x: 200.864, y: 225.943},
            'conn_220_226': {x: 220.232, y: 225.763}
        },
        
        roads: [
            // === MAIN VERTICAL SPINE (Complete north-south) ===
            ['gate', 'spine_gate'],
            ['spine_gate', 'spine_south_lower'],
            ['spine_south_lower', 'spine_south'],
            ['spine_south', 'spine_south_210'],
            ['spine_south_210', 'spine_center'],
            ['spine_center', 'spine_north_130'],
            ['spine_north_130', 'spine_north'],
            ['spine_north', 'spine_roundabout'],
            
            // === CENTER HORIZONTAL (Complete east-west at y≈167) ===
            ['horiz_west_end', 'horiz_30'],
            ['horiz_30', 'horiz_70'],
            ['horiz_70', 'horiz_100'],
            ['horiz_100', 'horiz_134'],
            ['horiz_134', 'horiz_162'],
            ['horiz_162', 'horiz_center'],
            ['horiz_center', 'spine_center'],
            ['spine_center', 'horiz_202'],
            ['horiz_202', 'horiz_220'],
            ['horiz_220', 'horiz_252'],
            ['horiz_252', 'horiz_east_end'],
            
            // === SOUTHERN HORIZONTAL (Complete at y≈203) ===
            ['south_west_end', 'south_30'],
            ['south_30', 'south_46'],
            ['south_46', 'south_70'],
            ['south_70', 'south_100'],
            ['south_100', 'south_124'],
            ['south_124', 'south_134'],
            
            // === WESTERN VERTICAL (x≈162) ===
            ['west_south', 'west_140'],
            ['west_140', 'west_north'],
            ['west_north', 'west_80'],
            ['west_80', 'west_top'],
            
            // === EASTERN VERTICAL (x≈252) ===
            ['east_south', 'east_140'],
            ['east_140', 'east_north'],
            ['east_north', 'east_80'],
            ['east_80', 'east_50'],
            ['east_50', 'east_top'],
            
            // === FAR WEST VERTICAL (x≈30) ===
            ['far_west_south', 'far_west_130'],
            ['far_west_130', 'far_west_field'],
            ['far_west_field', 'far_west_80'],
            ['far_west_80', 'far_west_north'],
            
            // === VERTICAL CONNECTOR (x≈134) ===
            ['south_134', 'conn_134_south'],
            ['conn_134_south', 'conn_134_185'],
            ['conn_134_185', 'conn_134_center'],
            
            // === CROSS CONNECTIONS (Horizontal connectors) ===
            ['horiz_162', 'west_south'],
            ['horiz_30', 'far_west_south'],
            ['horiz_134', 'conn_134_center'],
            ['horiz_252', 'east_south'],
            ['south_30', 'far_west_south'],
            ['spine_south_210', 'spine_center'],
            
            // === NORTHERN HORIZONTAL CONNECTORS (y≈111) ===
            ['west_north', 'north_162'],
            ['north_162', 'north_180'],
            ['north_180', 'north_188'],
            ['north_188', 'spine_north'],
            ['spine_north', 'north_203'],
            ['north_203', 'north_220'],
            ['north_220', 'north_246'],
            ['north_246', 'east_north'],
            
            // === SMALL CONNECTORS ===
            ['spine_south', 'conn_201_226'],
            ['conn_201_226', 'conn_220_226'],
            
            // === DIRT PATHS ===
            ['dirt_start', 'dirt_corner_1'],
            ['dirt_corner_1', 'dirt_horiz_244'],
            ['dirt_horiz_244', 'dirt_corner_2'],
            ['dirt_corner_2', 'dirt_vert'],
            ['dirt_vert', 'dirt_horiz_end'],
            ['spine_roundabout', 'dirt_north_208'],
            ['dirt_north_208', 'dirt_north_corner'],
            ['dirt_north_corner', 'dirt_north_top'],
            ['dirt_north_top', 'dirt_north_end'],
            
            // === ROUNDABOUT CONNECTIONS ===
            ['spine_roundabout', 'roundabout_center'],
            ['roundabout_center', 'west_top'],
            ['roundabout_center', 'east_top']
        ]
    };

    // Find nearest intersection to a point
    function findNearestIntersection(x, y) {
        let nearest = 'center_main';
        let minDist = Infinity;
        
        for (const [name, point] of Object.entries(roadNetwork.intersections)) {
            const dist = Math.hypot(x - point.x, y - point.y);
            if (dist < minDist) {
                minDist = dist;
                nearest = name;
            }
        }
        return nearest;
    }

    function findPath(startIntersection, endIntersection) {
        // Dijkstra's algorithm to find shortest DISTANCE path (not just hop count)
        
        // Calculate actual distance between two intersections
        const getDistance = (int1, int2) => {
            const p1 = roadNetwork.intersections[int1];
            const p2 = roadNetwork.intersections[int2];
            return Math.hypot(p2.x - p1.x, p2.y - p1.y);
        };
        
        const distances = {};
        const previous = {};
        const unvisited = new Set();
        
        // Initialize all intersections
        for (const intersection in roadNetwork.intersections) {
            distances[intersection] = intersection === startIntersection ? 0 : Infinity;
            previous[intersection] = null;
            unvisited.add(intersection);
        }
        
        while (unvisited.size > 0) {
            // Find unvisited intersection with smallest distance
            let current = null;
            let minDist = Infinity;
            for (const node of unvisited) {
                if (distances[node] < minDist) {
                    minDist = distances[node];
                    current = node;
                }
            }
            
            if (current === null || current === endIntersection) break;
            
            unvisited.delete(current);
            
            // Check all neighbors connected by roads
            for (const [a, b] of roadNetwork.roads) {
                let neighbor = null;
                if (a === current && unvisited.has(b)) neighbor = b;
                else if (b === current && unvisited.has(a)) neighbor = a;
                
                if (neighbor) {
                    const alt = distances[current] + getDistance(current, neighbor);
                    if (alt < distances[neighbor]) {
                        distances[neighbor] = alt;
                        previous[neighbor] = current;
                    }
                }
            }
        }
        
        // Reconstruct shortest path
        const path = [];
        let current = endIntersection;
        while (current !== null) {
            path.unshift(current);
            current = previous[current];
        }
        
        return path.length > 0 ? path : [startIntersection, endIntersection];
    }

    // Simplify path by removing collinear points (points on same straight line)
    function simplifyPath(points) {
        if (points.length <= 2) return points;
        
        const simplified = [points[0]];
        
        for (let i = 1; i < points.length - 1; i++) {
            const prev = simplified[simplified.length - 1];
            const curr = points[i];
            const next = points[i + 1];
            
            // Check if current point is collinear with prev and next
            const onSameHorizontal = (prev.y === curr.y && curr.y === next.y);
            const onSameVertical = (prev.x === curr.x && curr.x === next.x);
            
            // Only add point if it changes direction
            if (!onSameHorizontal && !onSameVertical) {
                simplified.push(curr);
            }
        }
        
        simplified.push(points[points.length - 1]);
        return simplified;
    }
    
    // Enforce strict orthogonal routing - only horizontal and vertical lines
    function enforceOrthogonalPath(points) {
        if (points.length <= 1) return points;
        
        const orthoPoints = [points[0]];
        
        for (let i = 1; i < points.length; i++) {
            const prev = orthoPoints[orthoPoints.length - 1];
            const curr = points[i];
            
            // Skip if same point
            if (prev.x === curr.x && prev.y === curr.y) continue;
            
            // If diagonal, create intermediate point to make it orthogonal
            if (prev.x !== curr.x && prev.y !== curr.y) {
                // Decide whether to go horizontal first or vertical first
                // Use the longer distance to determine priority
                const dx = Math.abs(curr.x - prev.x);
                const dy = Math.abs(curr.y - prev.y);
                
                if (dx > dy) {
                    // Go horizontal first, then vertical
                    orthoPoints.push({x: curr.x, y: prev.y});
                } else {
                    // Go vertical first, then horizontal
                    orthoPoints.push({x: prev.x, y: curr.y});
                }
            }
            
            orthoPoints.push(curr);
        }
        
        // Remove collinear points
        return simplifyPath(orthoPoints);
    }

    function drawNavigationPath(buildingName) {
        const svg = document.getElementById('campusMap');
        
        // Remove existing navigation elements
        const existingPath = document.getElementById('navPath');
        if (existingPath) existingPath.remove();
        const existingMarkers = document.getElementById('navMarkers');
        if (existingMarkers) existingMarkers.remove();

        const point = navigationPoints[buildingName];
        if (!point) {
            console.error('Navigation point not found for:', buildingName);
            return;
        }
        
        // Validate the road connection exists
        if (!roadNetwork.intersections[point.roadConnection]) {
            console.error('Road connection not found:', point.roadConnection, 'for building:', buildingName);
            return;
        }
        
        // Use pre-defined road connection
        const startIntersection = 'gate';
        const endIntersection = point.roadConnection;
        
        // Get path through road network using Dijkstra's algorithm
        const intersectionPath = findPath(startIntersection, endIntersection);
        
        if (!intersectionPath || intersectionPath.length === 0) {
            console.error('No path found from', startIntersection, 'to', endIntersection);
            return;
        }
        
        // Build clean orthogonal path segments
        const segments = [];
        
        // Start from kiosk
        const kioskNode = roadNetwork.intersections['gate'];
        segments.push({x: kioskX, y: kioskY});
        
        // Only add kiosk node if it's different from actual kiosk position
        if (kioskNode && (kioskNode.x !== kioskX || kioskNode.y !== kioskY)) {
            segments.push({x: kioskNode.x, y: kioskNode.y});
        }
        
        // Add all intersection points from the path (skip first if it's gate)
        for (let i = 0; i < intersectionPath.length; i++) {
            const intersectionName = intersectionPath[i];
            if (intersectionName === 'gate' && i === 0) continue; // Skip gate as we already added it
            
            const intersection = roadNetwork.intersections[intersectionName];
            if (intersection) {
                segments.push({x: intersection.x, y: intersection.y});
            }
        }
        
        // Add final building destination
        segments.push({x: point.x, y: point.y});
        
        // Remove duplicate consecutive points
        const cleanedSegments = [segments[0]];
        for (let i = 1; i < segments.length; i++) {
            const prev = cleanedSegments[cleanedSegments.length - 1];
            const curr = segments[i];
            if (prev.x !== curr.x || prev.y !== curr.y) {
                cleanedSegments.push(curr);
            }
        }
        
        // Enforce strict orthogonal routing (only horizontal and vertical lines)
        const orthogonalSegments = enforceOrthogonalPath(cleanedSegments);
        
        // Build orthogonal path - only vertical and horizontal lines
        let pathData = `M ${orthogonalSegments[0].x} ${orthogonalSegments[0].y}`;
        
        // Draw only horizontal or vertical lines
        for (let i = 1; i < orthogonalSegments.length; i++) {
            pathData += ` L ${orthogonalSegments[i].x} ${orthogonalSegments[i].y}`;
        }
        
        // Create navigation path - polished red line
        const navPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        navPath.setAttribute('id', 'navPath');
        navPath.setAttribute('d', pathData);
        navPath.setAttribute('fill', 'none');
        navPath.setAttribute('stroke', '#ef4444');
        navPath.setAttribute('stroke-width', '5');
        navPath.setAttribute('stroke-dasharray', '10,5');
        navPath.setAttribute('stroke-linecap', 'round');
        navPath.setAttribute('stroke-linejoin', 'round');
        navPath.setAttribute('opacity', '1');
        navPath.setAttribute('filter', 'drop-shadow(0 2px 4px rgba(239, 68, 68, 0.4))');
        navPath.setAttribute('style', 'pointer-events: none;');
        svg.appendChild(navPath);
        
        // Create markers group
        const markersGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        markersGroup.setAttribute('id', 'navMarkers');
        
        // Start marker (main gate) - polished green
        const startMarker = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        startMarker.setAttribute('cx', kioskX);
        startMarker.setAttribute('cy', kioskY);
        startMarker.setAttribute('r', '7');
        startMarker.setAttribute('fill', '#10b981');
        startMarker.setAttribute('stroke', '#fff');
        startMarker.setAttribute('stroke-width', '3');
        startMarker.setAttribute('filter', 'drop-shadow(0 2px 4px rgba(16, 185, 129, 0.5))');
        markersGroup.appendChild(startMarker);
        
        // Destination marker - polished red circle with pulsing animation
        const destMarker = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        destMarker.setAttribute('cx', point.x);
        destMarker.setAttribute('cy', point.y);
        destMarker.setAttribute('r', '9');
        destMarker.setAttribute('fill', '#ef4444');
        destMarker.setAttribute('stroke', '#fff');
        destMarker.setAttribute('stroke-width', '3');
        destMarker.setAttribute('filter', 'drop-shadow(0 2px 4px rgba(239, 68, 68, 0.5))');
        destMarker.setAttribute('class', 'endpoint-marker');
        destMarker.dataset.buildingName = buildingName; // Store building name for drag functionality
        markersGroup.appendChild(destMarker);
        
        // Pulsing outer ring for destination
        const pulseRing = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        pulseRing.setAttribute('cx', point.x);
        pulseRing.setAttribute('cy', point.y);
        pulseRing.setAttribute('r', '8');
        pulseRing.setAttribute('fill', 'none');
        pulseRing.setAttribute('stroke', '#ef4444');
        pulseRing.setAttribute('stroke-width', '2');
        pulseRing.setAttribute('opacity', '0.6');
        markersGroup.appendChild(pulseRing);
        
        // Animate the pulse ring
        const animate = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
        animate.setAttribute('attributeName', 'r');
        animate.setAttribute('from', '8');
        animate.setAttribute('to', '16');
        animate.setAttribute('dur', '1.5s');
        animate.setAttribute('repeatCount', 'indefinite');
        pulseRing.appendChild(animate);
        
        const animateOpacity = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
        animateOpacity.setAttribute('attributeName', 'opacity');
        animateOpacity.setAttribute('from', '0.6');
        animateOpacity.setAttribute('to', '0');
        animateOpacity.setAttribute('dur', '1.5s');
        animateOpacity.setAttribute('repeatCount', 'indefinite');
        pulseRing.appendChild(animateOpacity);
        
        // Building name label near destination
        const labelBg = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        const labelText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        
        // Get display name
        const displayName = svgToDisplayName[buildingName] || buildingName;
        labelText.textContent = displayName;
        labelText.setAttribute('x', point.x);
        labelText.setAttribute('y', point.y - 15);
        labelText.setAttribute('text-anchor', 'middle');
        labelText.setAttribute('font-size', '12');
        labelText.setAttribute('font-weight', 'bold');
        labelText.setAttribute('fill', '#fff');
        labelText.setAttribute('class', 'endpoint-label');
        labelText.setAttribute('style', 'pointer-events: none;');
        
        // Measure text for background
        svg.appendChild(labelText);
        const bbox = labelText.getBBox();
        
        labelBg.setAttribute('x', bbox.x - 4);
        labelBg.setAttribute('y', bbox.y - 2);
        labelBg.setAttribute('width', bbox.width + 8);
        labelBg.setAttribute('height', bbox.height + 4);
        labelBg.setAttribute('fill', '#ef4444');
        labelBg.setAttribute('rx', '4');
        labelBg.setAttribute('opacity', '0.9');
        
        markersGroup.appendChild(labelBg);
        markersGroup.appendChild(labelText);
        
        svg.appendChild(markersGroup);
        
        // Attach endpoint drag listeners (always available now)
        attachEndpointDragListeners();
        
        // Navigation path will stay visible until user navigates to another building
    }
    
    // Old building marker drag code removed - now using endpoint edit mode
    
    function startDrag(e) {
        if (!editMode) return;
        e.preventDefault();
        
        draggedElement = e.target;
        draggedElement.classList.add('dragging');
        
        const rect = draggedElement.getBoundingClientRect();
        const containerRect = document.getElementById('mapContainer').getBoundingClientRect();
        
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        
        offsetX = clientX - rect.left - rect.width / 2;
        offsetY = clientY - rect.top - rect.height / 2;
    }
    
    function drag(e) {
        if (!draggedElement) return;
        e.preventDefault();
        
        const containerRect = document.getElementById('mapContainer').getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        
        let newX = clientX - containerRect.left - offsetX;
        let newY = clientY - containerRect.top - offsetY;
        
        newX = Math.max(0, Math.min(newX, containerRect.width));
        newY = Math.max(0, Math.min(newY, containerRect.height));
        
        draggedElement.style.left = newX + 'px';
        draggedElement.style.top = newY + 'px';
    }
    
    function endDrag(e) {
        if (!draggedElement) return;
        
        const buildingId = draggedElement.dataset.id;
        const newX = parseInt(draggedElement.style.left);
        const newY = parseInt(draggedElement.style.top);
        
        fetch(`/api/buildings/${buildingId}/coordinates`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({map_x: newX, map_y: newY})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Coordinates updated successfully');
            }
        })
        .catch(error => console.error('Error:', error));
        
        draggedElement.classList.remove('dragging');
        draggedElement = null;
    }

    // === ENDPOINT EDIT MODE INITIALIZATION ===
    console.log('Script loaded, isAdmin:', isAdmin);
    
    // Always wait for DOMContentLoaded to ensure buttons exist
    document.addEventListener('DOMContentLoaded', initEndpointEditMode);
    
    function initEndpointEditMode() {
        console.log('Initializing endpoint edit mode...');
        
        const editEndpointsBtn = document.getElementById('editEndpointsBtn');
        const saveEndpointsBtn = document.getElementById('saveEndpointsBtn');
        
        console.log('Edit button:', editEndpointsBtn);
        console.log('Save button:', saveEndpointsBtn);
        
        if (!editEndpointsBtn || !saveEndpointsBtn) {
            console.error('Endpoint edit buttons not found! isAdmin:', isAdmin);
            console.error('Available buttons:', document.querySelectorAll('button'));
            return;
        }
        
        console.log('✅ Endpoint edit mode initialized successfully');
        
        // Toggle endpoint edit mode
        editEndpointsBtn.addEventListener('click', () => {
            console.log('Edit button clicked!');
            endpointEditMode = !endpointEditMode;
            editEndpointsBtn.textContent = endpointEditMode ? 'Edit Endpoints: ON' : 'Edit Endpoints: OFF';
            editEndpointsBtn.className = endpointEditMode ? 
                'bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-bold' : 
                'bg-purple-500 hover:bg-purple-600 px-6 py-3 rounded-lg font-bold';
            
            // Show/hide save button
            if (endpointEditMode) {
                saveEndpointsBtn.classList.remove('hidden');
            } else {
                saveEndpointsBtn.classList.add('hidden');
            }
            
            // Update all endpoint markers
            const svg = document.getElementById('campusMap');
            const endpointMarkers = svg.querySelectorAll('.endpoint-marker');
            endpointMarkers.forEach(marker => {
                if (endpointEditMode) {
                    marker.classList.add('editable');
                } else {
                    marker.classList.remove('editable');
                }
            });
            
            // Add/remove visual indicator on map container
            const mapContainer = document.getElementById('mapContainer');
            if (endpointEditMode) {
                mapContainer.classList.add('edit-mode-active');
            } else {
                mapContainer.classList.remove('edit-mode-active');
            }
        });
        
        // Save endpoint changes
        saveEndpointsBtn.addEventListener('click', () => {
            if (Object.keys(modifiedEndpoints).length === 0) {
                alert('No endpoints have been modified.');
                return;
            }
            
            // Send to backend
            fetch('/api/navigation/endpoints', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({endpoints: modifiedEndpoints})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Successfully updated ${Object.keys(modifiedEndpoints).length} endpoint(s)!`);
                    modifiedEndpoints = {};
                    
                    // Update the navigationPoints object in memory
                    Object.keys(data.endpoints).forEach(buildingName => {
                        if (navigationPoints[buildingName]) {
                            navigationPoints[buildingName].x = data.endpoints[buildingName].x;
                            navigationPoints[buildingName].y = data.endpoints[buildingName].y;
                        }
                    });
                } else {
                    alert('Error saving endpoints: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save endpoints. Check console for details.');
            });
        });
    }
    
    // Add endpoint drag listeners after drawing navigation
    function attachEndpointDragListeners() {
        const svg = document.getElementById('campusMap');
        if (!svg) return;
        
        const endpointMarkers = svg.querySelectorAll('.endpoint-marker');
        
        endpointMarkers.forEach(marker => {
            // Remove existing listeners to avoid duplicates
            marker.removeEventListener('mousedown', startEndpointDrag);
            marker.removeEventListener('touchstart', startEndpointDrag);
            
            // Add drag listeners
            marker.addEventListener('mousedown', startEndpointDrag);
            marker.addEventListener('touchstart', startEndpointDrag, {passive: false});
            
            // Apply edit mode styling if currently active
            if (endpointEditMode) {
                marker.classList.add('editable');
            }
        });
    }
    
    function startEndpointDrag(e) {
        if (!endpointEditMode) return;
        e.preventDefault();
        e.stopPropagation();
        
        draggedEndpoint = e.target;
        draggedEndpoint.classList.add('dragging');
        
        const svg = document.getElementById('campusMap');
        const pt = svg.createSVGPoint();
        
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        
        pt.x = clientX;
        pt.y = clientY;
        
        const svgP = pt.matrixTransform(svg.getScreenCTM().inverse());
        
        const cx = parseFloat(draggedEndpoint.getAttribute('cx'));
        const cy = parseFloat(draggedEndpoint.getAttribute('cy'));
        
        endpointOffsetX = svgP.x - cx;
        endpointOffsetY = svgP.y - cy;
        
        // Attach move listeners
        document.addEventListener('mousemove', dragEndpoint);
        document.addEventListener('touchmove', dragEndpoint, {passive: false});
        
        document.addEventListener('mouseup', endEndpointDrag);
        document.addEventListener('touchend', endEndpointDrag);
    }
    
    function dragEndpoint(e) {
        if (!draggedEndpoint) return;
        e.preventDefault();
        
        const svg = document.getElementById('campusMap');
        const pt = svg.createSVGPoint();
        
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        
        pt.x = clientX;
        pt.y = clientY;
        
        const svgP = pt.matrixTransform(svg.getScreenCTM().inverse());
        
        let newX = svgP.x - endpointOffsetX;
        let newY = svgP.y - endpointOffsetY;
        
        // Constrain to map bounds (viewBox: 0 0 302.596 275.484)
        newX = Math.max(0, Math.min(newX, 302.596));
        newY = Math.max(0, Math.min(newY, 275.484));
        
        // Update marker position
        draggedEndpoint.setAttribute('cx', newX);
        draggedEndpoint.setAttribute('cy', newY);
        
        // Update pulsing animation circle if exists
        const nextSibling = draggedEndpoint.nextElementSibling;
        if (nextSibling && nextSibling.tagName === 'circle') {
            nextSibling.setAttribute('cx', newX);
            nextSibling.setAttribute('cy', newY);
        }
        
        // Update label position if exists
        const label = draggedEndpoint.parentElement.querySelector('.endpoint-label');
        if (label) {
            label.setAttribute('x', newX);
            label.setAttribute('y', newY - 15);
        }
        
        // Show coordinate tooltip
        showCoordinateTooltip(newX, newY);
    }
    
    function endEndpointDrag(e) {
        if (!draggedEndpoint) return;
        
        // Remove listeners
        document.removeEventListener('mousemove', dragEndpoint);
        document.removeEventListener('touchmove', dragEndpoint);
        document.removeEventListener('mouseup', endEndpointDrag);
        document.removeEventListener('touchend', endEndpointDrag);
        
        const buildingName = draggedEndpoint.dataset.buildingName;
        const newX = parseFloat(draggedEndpoint.getAttribute('cx'));
        const newY = parseFloat(draggedEndpoint.getAttribute('cy'));
        
        // Store modified endpoint
        modifiedEndpoints[buildingName] = {
            x: Math.round(newX * 100) / 100, // Round to 2 decimals
            y: Math.round(newY * 100) / 100
        };
        
        console.log(`Endpoint moved: ${buildingName} -> (${modifiedEndpoints[buildingName].x}, ${modifiedEndpoints[buildingName].y})`);
        
        draggedEndpoint.classList.remove('dragging');
        draggedEndpoint = null;
        
        hideCoordinateTooltip();
    }
    
    // Coordinate tooltip
    let coordinateTooltip = null;
    
    function showCoordinateTooltip(x, y) {
        if (!coordinateTooltip) {
            coordinateTooltip = document.createElement('div');
            coordinateTooltip.style.position = 'fixed';
            coordinateTooltip.style.background = 'rgba(0, 0, 0, 0.9)';
            coordinateTooltip.style.color = 'white';
            coordinateTooltip.style.padding = '8px 12px';
            coordinateTooltip.style.borderRadius = '6px';
            coordinateTooltip.style.fontSize = '14px';
            coordinateTooltip.style.fontFamily = 'monospace';
            coordinateTooltip.style.pointerEvents = 'none';
            coordinateTooltip.style.zIndex = '10000';
            coordinateTooltip.style.boxShadow = '0 4px 6px rgba(0,0,0,0.3)';
            document.body.appendChild(coordinateTooltip);
        }
        
        coordinateTooltip.textContent = `x: ${Math.round(x * 100) / 100}, y: ${Math.round(y * 100) / 100}`;
        coordinateTooltip.style.display = 'block';
        
        // Position near cursor
        const svg = document.getElementById('campusMap');
        const rect = svg.getBoundingClientRect();
        const pt = svg.createSVGPoint();
        pt.x = x;
        pt.y = y;
        const screenPt = pt.matrixTransform(svg.getScreenCTM());
        
        coordinateTooltip.style.left = (screenPt.x + 15) + 'px';
        coordinateTooltip.style.top = (screenPt.y - 30) + 'px';
    }
    
    function hideCoordinateTooltip() {
        if (coordinateTooltip) {
            coordinateTooltip.style.display = 'none';
        }
    }
</script>
@endsection


