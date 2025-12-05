<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Performance: DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Performance: Theme color for browser UI -->
    <meta name="theme-color" content="#22c55e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Performance: Reduce render blocking -->
    <title>@yield('title', 'Campus Directory Kiosk')</title>
    
    <!-- Tailwind with async loading pattern -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('head')
    <style>
        /* Critical CSS - Inlined for fastest first paint */
        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
        
        html {
            /* Smooth scrolling */
            scroll-behavior: smooth;
            /* Prevent pull-to-refresh on touch devices */
            overscroll-behavior: none;
            /* GPU acceleration hint */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }
        
        body { 
            margin: 0; 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            touch-action: pan-y pan-x;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            /* Prevent overscroll bounce */
            overscroll-behavior: none;
            /* Text rendering optimization */
            text-rendering: optimizeLegibility;
        }
        
        /* GPU-accelerated animations */
        .gpu-accelerated {
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            will-change: transform, opacity;
        }
        
        /* Optimized transitions - use transform instead of left/right */
        .slide-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Reduce motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Header */
        .header {
            background-color: #28a745;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .map-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            will-change: transform;
        }
        
        .map-container.panel-open {
            transform: translateX(-175px);
        }

        .svg-map-wrapper {
            width: 850px;
            height: 600px;
            border: 2px solid #ccc;
            background-color: #fff;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
            /* GPU acceleration for smooth interactions */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        .svg-map {
            width: 100%;
            height: 100%;
        }

        .svg-map svg {
            width: 100%;
            height: 100%;
            display: block;
            pointer-events: all;
            touch-action: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }
        
        .svg-map svg * {
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        .legend-box {
            width: 330px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            padding: 1rem;
            font-size: 16px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            height: fit-content;
            flex-shrink: 0;
        }

            .legend-box h5 {
                font-weight: bold;
                font-size: 18px;
            }

            /* Side panel - GPU accelerated */
            .side-panel {
                position: fixed;
                top: 0;
                right: 0;
                width: 700px;
                height: 100%;
                background: #f9f9fa;
                box-shadow: -2px 0 10px rgba(0,0,0,0.3);
                transform: translateX(100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 2rem;
                box-sizing: border-box;
                z-index: 1050;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                will-change: transform;
                -webkit-transform: translateZ(0);
            }
            .side-panel.active { transform: translateX(0); }
            .side-panel header { display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem; }
            .close-btn { background:none; border:none; font-size:24px; cursor:pointer; }
            
            /* Loading skeleton animation */
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
            .skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: shimmer 1.5s infinite;
            }
            
            /* Image lazy loading fade-in */
            img[loading="lazy"] {
                opacity: 0;
                transition: opacity 0.3s ease-in-out;
            }
            img[loading="lazy"].loaded, img:not([loading="lazy"]) {
                opacity: 1;
            }
    </style>
    
    <!-- Performance: Register Service Worker for caching -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                // Service worker would be registered here for production
                // navigator.serviceWorker.register('/sw.js');
            });
        }
        
        // Performance: Lazy load images when they enter viewport
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.classList.add('loaded');
                            imageObserver.unobserve(img);
                        }
                    });
                }, { rootMargin: '50px' });
                
                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback for older browsers
                lazyImages.forEach(function(img) {
                    img.classList.add('loaded');
                });
            }
        });
        
        // Performance: Detect reduced motion preference
        window.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    </script>
</head>
<body class="@yield('body-class', 'bg-gray-100')">
    @yield('content')
    @yield('scripts')
</body>
</html>
