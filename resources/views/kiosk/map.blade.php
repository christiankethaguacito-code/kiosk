@extends('layouts.app')

@section('title', 'Campus Map')
@section('body-class', 'bg-gray-900')

@section('head')
<!-- Google Fonts - Modern Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Viewport meta for touch devices -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<style>
    /* ============================================
       ENHANCED KIOSK UI - MODERN DESIGN SYSTEM
       ============================================ */
    
    :root {
        /* Brand Colors */
        --primary: #22c55e;
        --primary-dark: #16a34a;
        --primary-darker: #15803d;
        --primary-light: #4ade80;
        --primary-glow: rgba(34, 197, 94, 0.4);
        --accent: #10b981;
        --accent-light: #34d399;
        
        /* Surfaces */
        --surface: #ffffff;
        --surface-elevated: #f8fafc;
        --surface-hover: #f1f5f9;
        
        /* Text Colors */
        --text-primary: #0f172a;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --text-inverse: #ffffff;
        
        /* Borders & Dividers */
        --border: #e2e8f0;
        --border-light: #f1f5f9;
        --divider: rgba(0, 0, 0, 0.06);
        
        /* Shadows - More refined */
        --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.04);
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
        --shadow-xl: 0 16px 48px rgba(0, 0, 0, 0.16);
        --shadow-glow: 0 0 40px var(--primary-glow);
        --shadow-inner: inset 0 2px 4px rgba(0, 0, 0, 0.04);
        
        /* Border Radius */
        --radius-xs: 6px;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;
        --radius-2xl: 24px;
        --radius-full: 9999px;
        
        /* Transitions */
        --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-normal: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-slow: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-spring: 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        
        /* Typography */
        --font-display: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
        --font-body: 'Inter', system-ui, sans-serif;
    }
    
    /* Prevent text selection and callouts on touch - but allow scrolling */
    * {
        -webkit-touch-callout: none;
        -webkit-tap-highlight-color: transparent;
        box-sizing: border-box;
    }
    
    /* Disable text selection on body level, not globally */
    body {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        font-family: var(--font-body);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        letter-spacing: -0.01em;
    }
    
    /* Headings use display font */
    h1, h2, h3, h4, h5, h6, .font-display {
        font-family: var(--font-display);
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    
    /* Allow text selection in specific areas if needed */
    .allow-select {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }
    
    /* Minimum touch target size (44px recommended by Apple/Google) */
    .touch-target {
        min-height: 48px;
        min-width: 48px;
    }
    
    /* Smooth scrolling for touch and mouse */
    .touch-scroll {
        -webkit-overflow-scrolling: touch;
        overflow-y: auto !important;
        scroll-behavior: smooth;
    }
    
    /* Scrollable panel - ensure scrolling works */
    .scrollable-panel {
        overflow-y: auto !important;
        overflow-x: hidden;
        max-height: 100%;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Modern scrollbar styling */
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    
    /* Elegant scrollbar - Enhanced for touch */
    .show-scrollbar-on-hover {
        scrollbar-width: thin;
        scrollbar-color: rgba(36, 136, 35, 0.4) rgba(36, 136, 35, 0.1);
    }
    .show-scrollbar-on-hover::-webkit-scrollbar {
        width: 10px;
    }
    .show-scrollbar-on-hover::-webkit-scrollbar-track {
        background: linear-gradient(90deg, transparent 0%, rgba(36,136,35,0.08) 50%, transparent 100%);
        border-radius: 10px;
        margin: 4px 0;
    }
    .show-scrollbar-on-hover::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 10px;
        border: 2px solid rgba(255,255,255,0.8);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2);
    }
    .show-scrollbar-on-hover::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, var(--accent) 0%, var(--primary) 100%);
        box-shadow: 0 0 10px var(--primary-glow);
    }
    
    /* Larger fonts for touchscreen readability */
    html {
        font-size: 16px;
    }
    
    @media (min-width: 1024px) {
        html {
            font-size: 18px;
        }
    }
    
    /* ============================================
       ENHANCED BUTTON STYLES
       ============================================ */
    .kiosk-btn {
        min-height: 52px;
        padding: 14px 24px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: var(--radius-lg);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all var(--transition-fast);
        cursor: pointer;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .kiosk-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .kiosk-btn:hover::before {
        left: 100%;
    }
    
    .kiosk-btn:active {
        transform: scale(0.95);
    }
    
    .kiosk-btn:hover {
        box-shadow: var(--shadow-lg), var(--shadow-glow);
        transform: translateY(-2px);
    }
    
    /* Touch feedback for interactive elements */
    .touch-feedback {
        transition: all var(--transition-fast);
        position: relative;
    }
    
    .touch-feedback:active {
        transform: scale(0.97);
    }
    
    .touch-feedback:hover {
        transform: translateY(-1px);
    }
    
    /* ============================================
       ENHANCED LEGEND ITEMS
       ============================================ */
    .legend-item {
        min-height: 44px;
        padding: 12px 16px !important;
        margin: 6px 0;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        border-left: 3px solid transparent;
        background: transparent;
    }
    
    .legend-item:hover {
        color: var(--primary) !important;
        background: linear-gradient(90deg, rgba(36, 136, 35, 0.12) 0%, rgba(36, 136, 35, 0.05) 100%) !important;
        border-left-color: var(--primary);
        padding-left: 20px !important;
        box-shadow: var(--shadow-sm);
    }
    
    .legend-item:active {
        background: linear-gradient(90deg, rgba(36, 136, 35, 0.2) 0%, rgba(36, 136, 35, 0.1) 100%) !important;
    }
    
    /* ============================================
       ENHANCED MODAL STYLES
       ============================================ */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        z-index: 50;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-overlay.active > div {
        animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes modalSlideIn {
        from {
            transform: translateY(40px) scale(0.95);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
    
    @keyframes popupSlideIn {
        from {
            transform: translateY(20px) scale(0.98);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
    
    /* ============================================
       ENHANCED TAB STYLES
       ============================================ */
    .details-tab {
        color: var(--text-secondary);
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all var(--transition-fast);
        position: relative;
    }
    
    .details-tab:hover {
        color: var(--primary);
        background: rgba(36, 136, 35, 0.08);
    }
    
    .details-tab.active {
        color: var(--primary);
        background: white;
        border-bottom-color: var(--primary);
        box-shadow: 0 -2px 10px rgba(36, 136, 35, 0.1);
    }
    
    .details-tab.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: 3px 3px 0 0;
    }
    
    /* ============================================
       ENHANCED SWIPE DOTS
       ============================================ */
    .swipe-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d1d5db;
        cursor: pointer;
        transition: all var(--transition-normal);
        border: 2px solid transparent;
    }
    
    .swipe-dot:hover {
        background: var(--primary-light);
        transform: scale(1.2);
    }
    
    .swipe-dot.active {
        width: 32px;
        border-radius: 10px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        box-shadow: 0 2px 8px var(--primary-glow);
    }
    
    /* ============================================
       ENHANCED MAP & BUILDING STYLES
       ============================================ */
    .building-marker {
        display: none;
        position: absolute;
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: 3px solid white;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
        transition: all var(--transition-normal);
        z-index: 10;
        box-shadow: var(--shadow-md);
    }
    
    .building-marker:hover {
        background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: var(--shadow-lg), var(--shadow-glow);
    }
    
    .map-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        background: linear-gradient(145deg, #e8f5e9 0%, #f1f8e9 50%, #e8f5e9 100%);
        overflow: visible;
        touch-action: pan-x pan-y;
        border-radius: var(--radius-lg);
    }
    
    /* Subtle pulse animation for clickable buildings */
    @keyframes buildingPulse {
        0%, 100% { filter: brightness(1); }
        50% { filter: brightness(1.08); }
    }
    
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px var(--primary-glow); }
        50% { box-shadow: 0 0 35px var(--primary-glow); }
    }
    
    /* Loading spinner for modal content */
    .loading-spinner {
        display: inline-block;
        width: 48px;
        height: 48px;
        border: 4px solid rgba(36, 136, 35, 0.15);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Highlight effect for selected building */
    .building-selected {
        filter: brightness(1.3) drop-shadow(0 0 20px var(--primary)) !important;
        animation: buildingPulse 2s ease-in-out infinite;
    }
    
    svg {
        width: 100%;
        height: 100%;
        overflow: visible;
    }
    
    /* Navigation endpoint labels - allow overflow visibility */
    .endpoint-label {
        pointer-events: none;
        font-weight: bold;
    }
    
    /* Ensure navigation markers group is visible even outside SVG bounds */
    #navigationMarkers {
        overflow: visible;
    }
    
    /* Interactive building hover effects */
    svg [id]:hover:not(#Premises):not(#Outline):not(#Main_Road):not(#Side_Entrance):not(#Main_Entrance):not(#BuildingLabels):not(path):not(g) {
        filter: brightness(1.15) drop-shadow(0 0 12px rgba(16, 185, 129, 0.7));
        transition: all var(--transition-normal);
        cursor: pointer;
    }
    
    svg [id]:active:not(#Premises):not(#Outline):not(#Main_Road):not(#Side_Entrance):not(#Main_Entrance):not(#BuildingLabels):not(path):not(g) {
        filter: brightness(1.25) drop-shadow(0 0 16px rgba(16, 185, 129, 0.9));
        transform: scale(0.98);
    }
    
    /* Enhanced building tooltip */
    .building-tooltip {
        position: fixed;
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%);
        color: white;
        padding: 12px 18px;
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-weight: 600;
        pointer-events: none;
        z-index: 9999;
        opacity: 0;
        transition: all var(--transition-fast);
        white-space: nowrap;
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .building-tooltip::before {
        content: '🏛️';
        margin-right: 8px;
    }
    
    .building-tooltip.show {
        opacity: 1;
        transform: translateY(-5px);
    }
    
    /* ============================================
       ENHANCED LAYOUT ALIGNMENT
       ============================================ */
    .main-content-wrapper {
        display: flex;
        flex: 1;
        min-height: 0;
        padding: 1rem;
    }
    
    .content-container {
        display: flex;
        width: 100%;
        height: 100%;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.2), 
                    0 0 0 1px rgba(36,136,35,0.12);
    }
    
    /* ============================================
       ENHANCED LEGEND STYLES
       ============================================ */
    .legend-panel {
        background: linear-gradient(180deg, #ffffff 0%, #f8fdf8 100%);
        display: flex;
        flex-direction: column;
    }
    
    .legend-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        flex-shrink: 0;
    }
    
    .legend-scroll-content {
        flex: 1 1 0;
        min-height: 0;
        overflow-y: auto;
        padding: 0.75rem;
    }
    
    /* Map container alignment */
    .map-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #e8f5e9 0%, #c8e6c9 100%);
        position: relative;
    }
    
    .map-wrapper svg {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }

    .legend-category {
        margin-bottom: 0.75rem;
    }
    
    .legend-category-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 0.7rem;
        color: var(--primary-dark);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.5rem 0.75rem;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.12) 0%, rgba(34, 197, 94, 0.04) 100%);
        border-left: 3px solid var(--primary);
        margin-bottom: 0.4rem;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-xs);
    }
    
    .legend-category-icon {
        font-size: 1rem;
        line-height: 1;
    }
    
    .legend-item-enhanced {
        display: flex;
        align-items: center;
        padding: 0.6rem 0.75rem;
        margin: 0.2rem 0;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        background: transparent;
        border: 1px solid transparent;
        font-family: var(--font-body);
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-secondary);
        line-height: 1.4;
        position: relative;
    }
    
    .legend-item-enhanced::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: var(--primary);
        border-radius: 0 2px 2px 0;
        transition: height var(--transition-fast);
    }
    
    .legend-item-enhanced:hover {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.02) 100%);
        color: var(--primary-dark);
        padding-left: 1rem;
        border-color: rgba(34, 197, 94, 0.15);
    }
    
    .legend-item-enhanced:hover::before {
        height: 60%;
    }
    
    .legend-item-enhanced:active {
        background: rgba(34, 197, 94, 0.18);
        transform: scale(0.98);
    }
    
    .legend-item-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        margin-right: 0.65rem;
        flex-shrink: 0;
        opacity: 0.7;
        transition: all var(--transition-fast);
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.1);
    }
    
    .legend-item-enhanced:hover .legend-item-dot {
        opacity: 1;
        transform: scale(1.4);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2), 0 0 12px var(--primary-glow);
    }

    #navPath {
        pointer-events: none;
        z-index: 100;
    }
    
    /* Path animation styles */
    @keyframes drawPath {
        from {
            stroke-dashoffset: var(--path-length);
        }
        to {
            stroke-dashoffset: 0;
        }
    }
    
    .animated-path {
        animation: drawPath 1.5s ease-out forwards;
    }
    
    /* Search dropdown styles */
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        box-shadow: var(--shadow-xl), 0 20px 40px rgba(0,0,0,0.2);
        max-height: 320px;
        overflow-y: auto;
        z-index: 9999;
        display: none;
        border: 1px solid var(--border);
        border-top: none;
    }
    
    .search-dropdown.active {
        display: block;
    }
    
    .search-item {
        padding: 14px 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 14px;
        border-bottom: 1px solid var(--border-light);
        transition: all var(--transition-fast);
        position: relative;
    }
    
    .search-item:last-child {
        border-bottom: none;
    }
    
    .search-item:hover, .search-item.highlighted {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.02) 100%);
    }
    
    .search-item:hover::after {
        content: '→';
        position: absolute;
        right: 16px;
        color: var(--primary);
        font-weight: 600;
        opacity: 0.7;
    }
    
    .search-item-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.15rem;
        box-shadow: var(--shadow-sm), 0 0 0 2px rgba(34, 197, 94, 0.1);
        flex-shrink: 0;
    }
    
    .search-item-text {
        flex: 1;
        min-width: 0;
    }
    
    .search-item-name {
        font-family: var(--font-display);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.92rem;
        margin-bottom: 2px;
    }
    
    .search-item-category {
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .search-no-results {
        padding: 32px 24px;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    
    .search-no-results::before {
        content: '🔍';
        display: block;
        font-size: 2rem;
        margin-bottom: 8px;
        opacity: 0.5;
    }
    
    /* Walking time display - positioned at top-left corner */
    .walking-time-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 10px 16px;
        border-radius: var(--radius-lg);
        display: none;
        align-items: center;
        gap: 10px;
        box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        z-index: 100;
        animation: fadeSlideIn 0.3s ease-out;
        font-family: var(--font-display);
        font-size: 0.88rem;
    }
    
    .walking-time-badge.active {
        display: flex;
    }
    
    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .walking-time-icon {
        font-size: 1.1rem;
    }
    
    .walking-time-text {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .walking-time-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
    }
    
    .walking-time-distance {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        padding-left: 6px;
        border-left: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* Accessibility panel */
    .accessibility-panel {
        position: fixed;
        top: 85px;
        right: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-radius: var(--radius-xl);
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05);
        padding: 1.5rem;
        width: 340px;
        z-index: 1000;
        display: none;
        animation: slideInRight 0.3s ease-out;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    
    .accessibility-panel.active {
        display: block;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .accessibility-title {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(34, 197, 94, 0.15);
    }
    
    .accessibility-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid var(--border);
        border-radius: var(--radius-md);
        margin: 0.25rem -0.5rem;
        transition: background var(--transition-fast);
    }
    
    .accessibility-option:hover {
        background: rgba(34, 197, 94, 0.05);
    }
    
    .accessibility-option:last-child {
        border-bottom: none;
    }
    
    .accessibility-label {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
        font-weight: 500;
    }
    
    /* Toggle switch */
    .toggle-switch {
        position: relative;
        width: 52px;
        height: 28px;
        background: #e2e8f0;
        border-radius: 14px;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .toggle-switch.active {
        background: var(--primary);
    }
    
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        transition: all var(--transition-fast);
    }
    
    .toggle-switch.active::after {
        transform: translateX(24px);
    }
    
    /* High contrast mode */
    body.high-contrast {
        --primary: #006400;
        --text-primary: #000000;
        --text-secondary: #333333;
        --border: #000000;
    }
    
    body.high-contrast .legend-item-enhanced,
    body.high-contrast .search-item {
        border: 2px solid #000 !important;
    }
    
    body.high-contrast svg [id]:not(#Premises):not(#Outline) {
        stroke: #000 !important;
        stroke-width: 2 !important;
    }
    
    /* Large text mode */
    body.large-text {
        font-size: 20px !important;
    }
    
    body.large-text .legend-item-enhanced {
        font-size: 1.1rem !important;
        padding: 14px 16px !important;
    }
    
    body.large-text .search-item-name {
        font-size: 1.15rem !important;
    }
    
    body.large-text .walking-time-value {
        font-size: 1.5rem !important;
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
    
    /* Endpoint edit mode */
    .endpoint-marker {
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .endpoint-marker.editable {
        cursor: move;
        filter: brightness(1.2) drop-shadow(0 0 10px rgba(147, 51, 234, 0.8));
    }
    
    .endpoint-marker:hover {
        transform: scale(1.15);
    }
    
    /* Interactive hint overlay */
    .hint-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        color: white;
        padding: 24px 48px;
        border-radius: var(--radius-xl);
        font-size: 1.2rem;
        font-weight: 600;
        z-index: 200;
        opacity: 0;
        animation: hintFadeInOut 4s ease;
        pointer-events: none;
        box-shadow: var(--shadow-xl), 0 0 40px var(--primary-glow);
        border: 2px solid rgba(255,255,255,0.2);
    }
    
    @keyframes hintFadeInOut {
        0% { opacity: 0; transform: translate(-50%, -50%) scale(0.9); }
        10%, 70% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        100% { opacity: 0; transform: translate(-50%, -50%) scale(0.9); }
    }
    
    /* ============================================
       "YOU ARE HERE" MARKER
       ============================================ */
    .you-are-here-marker {
        pointer-events: none;
    }
    
    .you-are-here-pulse {
        animation: youAreHerePulse 2s ease-in-out infinite;
    }
    
    @keyframes youAreHerePulse {
        0%, 100% { 
            r: 8;
            opacity: 0.3;
        }
        50% { 
            r: 16;
            opacity: 0;
        }
    }
    
    .you-are-here-label {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 7px;
        letter-spacing: 0.5px;
    }
    
    /* ============================================
       3D MAP MODE - FIXED PERSPECTIVE
       ============================================ */
    
    /* 3D perspective container */
    .map-3d-container {
        perspective: 1200px;
        perspective-origin: 50% 50%;
        transform-style: preserve-3d;
    }
    
    /* 3D map transform - fixed angles */
    .map-3d-mode #campusMap {
        transform: rotateX(50deg) rotateZ(-10deg) scale(0.75);
        transform-style: preserve-3d;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        filter: drop-shadow(0 25px 35px rgba(0, 0, 0, 0.25));
    }
    
    /* Building 3D extrusion effect */
    .map-3d-mode #campusMap path[id] {
        transition: all 0.3s ease;
        transform-origin: center;
    }
    
    /* Major buildings - taller shadows */
    .map-3d-mode #campusMap path[id="Administration"],
    .map-3d-mode #campusMap path[id="CTE"],
    .map-3d-mode #campusMap path[id="ULRC"],
    .map-3d-mode #campusMap path[id="UG"] {
        filter: drop-shadow(0 6px 3px rgba(0, 0, 0, 0.2))
                drop-shadow(0 10px 6px rgba(0, 0, 0, 0.12));
        transform: translateY(-3px);
    }
    
    /* Medium buildings */
    .map-3d-mode #campusMap path[id="CHS"],
    .map-3d-mode #campusMap path[id="CCJE"],
    .map-3d-mode #campusMap path[id="CoM"],
    .map-3d-mode #campusMap path[id="GS"],
    .map-3d-mode #campusMap path[id="Function"] {
        filter: drop-shadow(0 4px 2px rgba(0, 0, 0, 0.18))
                drop-shadow(0 6px 4px rgba(0, 0, 0, 0.1));
        transform: translateY(-2px);
    }
    
    /* All other buildings - base shadow */
    .map-3d-mode #campusMap path[id]:not([id="Premises"]):not([id="Outline"]) {
        filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.15));
    }
    
    /* Hover effect in 3D */
    .map-3d-mode #campusMap path[id]:hover {
        filter: drop-shadow(0 12px 8px rgba(0, 0, 0, 0.3))
                brightness(1.15) !important;
        transform: translateY(-6px) scale(1.02) !important;
    }
    
    /* 3D Toggle Button */
    .toggle-3d-btn {
        position: absolute;
        bottom: 20px;
        left: 20px;
        z-index: 100;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        transition: all 0.3s ease;
        font-family: var(--font-display);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .toggle-3d-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }
    
    .toggle-3d-btn:active {
        transform: translateY(0);
    }
    
    .toggle-3d-btn.active {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
    }
    
    .toggle-3d-btn svg {
        width: 20px;
        height: 20px;
    }
    
    /* 3D mode label adjustments */
    .map-3d-mode .building-label-enhanced,
    .map-3d-mode .you-are-here-marker text {
        transform: rotateX(-50deg) rotateZ(15deg);
        transform-origin: center;
    }
    
    /* Smooth transition back to 2D */
    #campusMap {
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), 
                    filter 0.5s ease;
    }
    
    /* ============================================
       MICRO-ANIMATIONS
       ============================================ */
    
    /* Building SVG hover effects - applies to all building paths */
    #campusMap path[id] {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
    }
    
    #campusMap path[id]:hover {
        filter: brightness(1.15) drop-shadow(0 0 8px rgba(34, 197, 94, 0.6));
        transform: scale(1.02);
        cursor: pointer;
    }
    
    #campusMap path[id]:active {
        filter: brightness(1.25) drop-shadow(0 0 12px rgba(34, 197, 94, 0.8));
        transform: scale(0.98);
    }
    
    /* Building highlight on hover */
    @keyframes buildingGlow {
        0%, 100% { filter: brightness(1.1) drop-shadow(0 0 8px var(--primary-glow)); }
        50% { filter: brightness(1.2) drop-shadow(0 0 16px var(--primary-glow)); }
    }
    
    .building-hover-glow {
        animation: buildingGlow 1.5s ease-in-out infinite;
    }
    
    /* Selected building pulse effect */
    @keyframes selectedBuildingPulse {
        0%, 100% { 
            filter: brightness(1.2) drop-shadow(0 0 10px rgba(34, 197, 94, 0.7));
        }
        50% { 
            filter: brightness(1.3) drop-shadow(0 0 20px rgba(34, 197, 94, 0.9));
        }
    }
    
    .building-selected {
        animation: selectedBuildingPulse 1.5s ease-in-out infinite;
    }
    
    /* Smooth fade transitions */
    .fade-in {
        animation: smoothFadeIn 0.3s ease-out forwards;
    }
    
    @keyframes smoothFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeletonShimmer 1.5s infinite;
        border-radius: var(--radius-sm);
    }
    
    @keyframes skeletonShimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Button press effect */
    .press-effect:active {
        transform: scale(0.96);
        transition: transform 0.1s ease;
    }
    
    /* Ripple effect container */
    .ripple-container {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: scale(0);
        animation: rippleEffect 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes rippleEffect {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Legend item hover enhancement */
    .legend-item-enhanced {
        position: relative;
        overflow: hidden;
    }
    
    .legend-item-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .legend-item-enhanced:hover::before {
        left: 100%;
    }
    
    /* Card entrance animation */
    .card-enter {
        animation: cardEnter 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    
    @keyframes cardEnter {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* ============================================
       ENHANCED SCREENSAVER
       ============================================ */
    #kioskIdleOverlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.98) 100%);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
        overflow: hidden;
    }
    
    #kioskIdleOverlay::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 30%, rgba(34, 197, 94, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 70% 70%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
        animation: screensaverBg 15s ease-in-out infinite;
    }
    
    @keyframes screensaverBg {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(5%, 5%) rotate(5deg); }
        50% { transform: translate(0, 10%) rotate(0deg); }
        75% { transform: translate(-5%, 5%) rotate(-5deg); }
    }
    
    #kioskIdleOverlay.show {
        display: flex;
        animation: fadeIn 0.5s ease;
    }
    
    .screensaver-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    
    .idle-logo {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        animation: logoFloat 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 30px rgba(34, 197, 94, 0.3));
    }
    
    @keyframes logoFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-15px) scale(1.02); }
    }
    
    .idle-text {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: var(--font-display);
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .idle-subtext {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        font-weight: 500;
        margin-bottom: 3rem;
    }
    
    .idle-hint {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 16px 32px;
        border-radius: var(--radius-full);
        color: white;
        font-weight: 600;
        animation: hintPulse 2s ease-in-out infinite;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    @keyframes hintPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(0.98); }
    }
    
    .idle-icon {
        font-size: 1.5rem;
        animation: tapBounce 1s ease-in-out infinite;
    }
    
    @keyframes tapBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    /* Floating particles */
    .screensaver-particle {
        position: absolute;
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        opacity: 0.3;
        animation: particleFloat 10s ease-in-out infinite;
    }
    
    @keyframes particleFloat {
        0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
        50% { transform: translateY(-100px) translateX(50px); opacity: 0.6; }
    }
    
    /* ============================================
       ENHANCED CARD STYLES
       ============================================ */
    .info-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 20px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        transition: all var(--transition-normal);
    }
    
    .info-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }
    
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, rgba(36, 136, 35, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
        color: var(--primary);
        border: 1px solid rgba(36, 136, 35, 0.2);
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endsection

@section('content')
<!-- Enhanced Screensaver Overlay -->
<div id="kioskIdleOverlay" onclick="resetIdleTimer()">
    <!-- Floating particles -->
    <div class="screensaver-particle" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
    <div class="screensaver-particle" style="top: 60%; left: 80%; animation-delay: 2s;"></div>
    <div class="screensaver-particle" style="top: 40%; left: 30%; animation-delay: 4s;"></div>
    <div class="screensaver-particle" style="top: 80%; left: 60%; animation-delay: 6s;"></div>
    <div class="screensaver-particle" style="top: 30%; left: 70%; animation-delay: 8s;"></div>
    
    <div class="screensaver-content">
        <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="idle-logo">
        <div class="idle-text">Campus Navigation Kiosk</div>
        <div class="idle-subtext">Sultan Kudarat State University</div>
        <div class="idle-hint">
            <span class="idle-icon">👆</span>
            <span>Touch anywhere to start</span>
        </div>
    </div>
</div>

<div class="h-screen flex flex-col overflow-hidden" style="background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);">
    <!-- Enhanced Header -->
    <header class="text-white px-6 py-4 flex justify-between items-center relative" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%); box-shadow: 0 4px 24px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.1); z-index: 50;">
        <!-- Decorative background elements -->
        <div style="position: absolute; top: -50%; right: -10%; width: 300px; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -50%; left: 10%; width: 200px; height: 150%; background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%); pointer-events: none;"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <a href="{{ route('kiosk.idle') }}" class="touch-target touch-feedback flex items-center p-2.5 rounded-2xl bg-white bg-opacity-15 hover:bg-opacity-25 transition-all duration-300" style="box-shadow: 0 4px 20px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.1);">
                <img src="{{ asset('images/sksu.png') }}" alt="SKSU Logo" class="h-14 w-14 object-contain drop-shadow-lg">
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold tracking-tight" style="text-shadow: 0 2px 12px rgba(0,0,0,0.25); letter-spacing: -0.02em;">Acces Map</h1>
                <p class="text-green-100 font-medium opacity-90 text-sm tracking-wide">Sultan Kudarat State University</p>
            </div>
        </div>
        
        <div class="flex-1 max-w-lg mx-10 relative z-10">
            <div class="relative group" id="searchContainer">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search buildings, offices, services..." 
                    class="w-full px-5 py-3.5 pl-12 rounded-2xl text-gray-800 text-base font-medium focus:outline-none focus:ring-4 allow-select transition-all duration-300"
                    style="background: rgba(255,255,255,0.98); --tw-ring-color: rgba(74, 222, 128, 0.5); box-shadow: 0 4px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,1);"
                    autocomplete="off"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false"
                />
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <!-- Search Dropdown -->
                <div class="search-dropdown" id="searchDropdown"></div>
            </div>
        </div>
        
        <div class="flex items-center gap-4 relative z-10">
            <!-- Clock with enhanced styling -->
            <div id="clock" class="text-xl font-bold px-4 py-2.5 rounded-xl bg-white bg-opacity-15" style="font-variant-numeric: tabular-nums; text-shadow: 0 1px 4px rgba(0,0,0,0.2); font-family: var(--font-display); letter-spacing: 0.02em;"></div>
            
            <!-- Admin Menu Toggle -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="touch-target touch-feedback p-3.5 bg-white bg-opacity-15 hover:bg-opacity-25 rounded-xl transition-all duration-300"
                        title="Menu"
                        style="box-shadow: 0 2px 12px rgba(0,0,0,0.15);"
                        style="backdrop-filter: blur(10px);">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
                     style="display: none; z-index: 9999;">
                    
                    @auth
                        <a href="{{ route('admin.dashboard') }}" 
                           class="touch-target touch-feedback flex items-center gap-3 px-5 py-4 hover:bg-green-50 transition border-b border-gray-100">
                            <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">Admin Dashboard</span>
                        </a>
                        
                        <button onclick="toggleEditMode()" 
                                class="touch-target touch-feedback w-full flex items-center gap-3 px-5 py-4 hover:bg-green-50 transition border-b border-gray-100 text-left">
                            <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">Admin Inline Edit</span>
                        </button>
                    @else
                        <button onclick="showAdminLogin()" 
                                class="touch-target touch-feedback w-full flex items-center gap-3 px-5 py-4 hover:bg-green-50 transition border-b border-gray-100 text-left">
                            <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">Admin Login</span>
                        </button>
                    @endauth
                    
                    <button onclick="toggleAccessibilityPanel()" 
                            class="touch-target touch-feedback w-full flex items-center gap-3 px-5 py-4 hover:bg-green-50 transition border-b border-gray-100 text-left">
                        <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-600">Accessibility</span>
                    </button>
                    
                    <button onclick="showAbout()" 
                            class="touch-target touch-feedback w-full flex items-center gap-3 px-5 py-4 hover:bg-green-50 transition text-left">
                        <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-600">About</span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Accessibility Panel -->
    <div class="accessibility-panel" id="accessibilityPanel">
        <div class="accessibility-title">
            <svg class="w-6 h-6" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Accessibility Options
        </div>
        
        <div class="accessibility-option">
            <div class="accessibility-label">
                <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                </svg>
                Large Text
            </div>
            <div class="toggle-switch" id="toggleLargeText" onclick="toggleLargeText()"></div>
        </div>
        
        <div class="accessibility-option">
            <div class="accessibility-label">
                <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
                High Contrast
            </div>
            <div class="toggle-switch" id="toggleHighContrast" onclick="toggleHighContrast()"></div>
        </div>
        
        <div class="accessibility-option">
            <div class="accessibility-label">
                <svg class="w-5 h-5" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Reduced Motion
            </div>
            <div class="toggle-switch" id="toggleReducedMotion" onclick="toggleReducedMotion()"></div>
        </div>
        
        <button onclick="resetAccessibility()" 
                class="w-full mt-5 py-3 px-4 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 font-medium transition-all" style="font-family: var(--font-body);">
            Reset to Default
        </button>
    </div>

    <!-- Main Content Container - Unified 3-Panel Layout -->
    <div style="flex: 1; display: flex; padding: 1.25rem; min-height: 0;">
        <div style="display: flex; width: 100%; height: 100%; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.2), 0 4px 6px -1px rgba(0,0,0,0.1); border: 2px solid #22c55e; border-radius: 0.5rem;">
            
            <!-- Left Legend Section (20%) -->
            <div id="leftLegendContainer" class="legend-panel" style="flex: 0 0 20%; display: flex; flex-direction: column; overflow: hidden; background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);">
                <div class="legend-header" style="flex-shrink: 0; padding: 1rem 1.25rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -30px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -40px; left: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                    <h2 class="font-bold text-white text-center text-sm relative z-10" style="text-shadow: 0 2px 4px rgba(0,0,0,0.15); letter-spacing: 0.025em;">
                        <span class="flex items-center justify-center gap-2.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Buildings & Facilities
                        </span>
                    </h2>
                </div>
                <div class="px-3 py-3 touch-scroll show-scrollbar-on-hover" style="flex: 1 1 0; min-height: 0; overflow-y: auto; font-size: 0.85rem;">
                    <div class="legend-category">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">🎓</span>
                            Academic Colleges
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Administration')"><span class="legend-item-dot"></span>Administration</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('CTE')"><span class="legend-item-dot"></span>College of Education</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('CHS')"><span class="legend-item-dot"></span>College of Nursing</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('CHS_Labs')"><span class="legend-item-dot"></span>College of Health Sciences</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('CCJE')"><span class="legend-item-dot"></span>College of Criminal Justice</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('CCJE_ext')"><span class="legend-item-dot"></span>CCJE Extension</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('CoM')"><span class="legend-item-dot"></span>College of Medicine</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('GS')"><span class="legend-item-dot"></span>Graduate School</div>
                    
                    <div class="legend-category" style="margin-top: 1rem;">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">🏢</span>
                            Facilities & Services
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('ULRC')"><span class="legend-item-dot"></span>University Library</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('UG')"><span class="legend-item-dot"></span>University Gymnasium</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('UC')"><span class="legend-item-dot"></span>University Canteen</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Function')"><span class="legend-item-dot"></span>Function Hall</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('UPP')"><span class="legend-item-dot"></span>UPP Building</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Motorpool')"><span class="legend-item-dot"></span>University Motorpool</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('FC')"><span class="legend-item-dot"></span>Food Center</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Parking_Space')"><span class="legend-item-dot"></span>Parking Area</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Restroom')"><span class="legend-item-dot"></span>Public Restroom</div>
                    
                    <div class="legend-category" style="margin-top: 1rem;">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">🏥</span>
                            Medical & Training
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('BCSF')"><span class="legend-item-dot"></span>Basic & Clinical Sciences</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('AMTC')"><span class="legend-item-dot"></span>Ang Magsasaka Center</div>
                </div>
            </div>
            
            <!-- Divider Line -->
            <div style="width: 1px; background: linear-gradient(180deg, #248823 0%, #1a6619 50%, #248823 100%); flex-shrink: 0;"></div>
            
            <!-- Map Section (60%) - Centered & Aligned -->
            <div class="map-wrapper map-3d-container" id="mapContainer" style="flex: 1 1 60%; height: 100%; position: relative; overflow: visible; background: linear-gradient(180deg, #e8f5e9 0%, #c8e6c9 100%);">
                <div class="hint-overlay" id="interactiveHint">
                    <span class="flex items-center gap-3">
                        <span style="font-size: 1.5rem;">👆</span>
                        <span>Tap on any building to explore</span>
                    </span>
                </div>
                
                <!-- 3D Mode Toggle Button -->
                <button class="toggle-3d-btn" id="toggle3DBtn" onclick="toggle3DMode()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3L2 9l10 6 10-6-10-6z"/>
                        <path d="M2 17l10 6 10-6"/>
                        <path d="M2 13l10 6 10-6"/>
                    </svg>
                    <span id="toggle3DText">3D VIEW</span>
                </button>
                
                <!-- Walking Time Badge - Compact top-left position -->
                <div class="walking-time-badge" id="walkingTimeBadge">
                    <span class="walking-time-icon">🚶</span>
                    <div class="walking-time-text">
                        <span class="walking-time-value" id="walkingTimeValue">~2 min</span>
                        <span class="walking-time-distance" id="walkingDistance">~150m</span>
                    </div>
                </div>
                
                <svg xmlns="http://www.w3.org/2000/svg" id="campusMap" viewBox="0 0 302.596 275.484" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; max-width: 100%; max-height: 100%; overflow: visible;" preserveAspectRatio="xMidYMid meet">
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
                    <!-- ========================================
                         ENHANCED BUILDING LABELS
                         Organized by area, non-overlapping
                         ======================================== -->
                    
                    <!-- === TOP AREA (Administration & Offices) === -->
                    
                    <!-- Administration - Main Building -->
                    <rect x="178" y="28" width="52" height="9" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="204" y="34.5" text-anchor="middle" font-size="5" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Administration</text>
                    
                    <!-- Registrar -->
                    <rect x="175" y="16" width="30" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="190" y="20.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Registrar</text>
                    
                    <!-- QMS -->
                    <rect x="214" y="2" width="18" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="223" y="6.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">QMS</text>
                    
                    <!-- Ladies Dormitory -->
                    <rect x="230" y="10" width="28" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="244" y="14.5" text-anchor="middle" font-size="2.8" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Ladies Dorm</text>
                    
                    <!-- MPC -->
                    <rect x="247" y="2" width="18" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="256" y="6.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">MPC</text>
                    
                    <!-- Restroom -->
                    <rect x="266" y="2" width="22" height="6" rx="1.5" fill="#e8f5e9" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="277" y="6.5" text-anchor="middle" font-size="2.8" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Restroom</text>
                    
                    <!-- === MEDICAL & COLLEGE AREA (Right Side) === -->
                    
                    <!-- College of Medicine -->
                    <rect x="274" y="18" width="24" height="14" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="286" y="24" text-anchor="middle" font-size="2.8" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">College of</text>
                    <text x="286" y="28.5" text-anchor="middle" font-size="2.8" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Medicine</text>
                    
                    <!-- LHS Extension -->
                    <rect x="268" y="34" width="20" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="278" y="38" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">LHS Ext</text>
                    
                    <!-- Laboratory Highschool -->
                    <rect x="253" y="42" width="24" height="12" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="265" y="47" text-anchor="middle" font-size="2.6" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Laboratory</text>
                    <text x="265" y="51.5" text-anchor="middle" font-size="2.6" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Highschool</text>
                    
                    <!-- === GYMNASIUM AREA (Left-Center) === -->
                    
                    <!-- University Gymnasium -->
                    <rect x="112" y="32" width="50" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="137" y="39" text-anchor="middle" font-size="4" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Gymnasium</text>
                    
                    <!-- Function Hall -->
                    <rect x="117" y="56" width="36" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="135" y="60.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Function Hall</text>
                    
                    <!-- Field -->
                    <rect x="54" y="96" width="30" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="69" y="103" text-anchor="middle" font-size="5.5" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Field</text>
                    
                    <!-- Bleacher -->
                    <rect x="100" y="78" width="22" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="111" y="82" text-anchor="middle" font-size="2.8" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Bleacher</text>
                    
                    <!-- === DORMITORIES (Left Side) === -->
                    
                    <!-- Men's Dormitory 1 -->
                    <rect x="6" y="69" width="24" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="18" y="73" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Men's Dorm 1</text>
                    
                    <!-- Men's Dormitory 2 -->
                    <rect x="6" y="82" width="24" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="18" y="86" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Men's Dorm 2</text>
                    
                    <!-- === CTE AREA (Right Side) === -->
                    
                    <!-- CTE -->
                    <rect x="256" y="88" width="32" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="272" y="95" text-anchor="middle" font-size="5" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">CTE</text>
                    
                    <!-- Parking Area -->
                    <rect x="203" y="80" width="30" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="218" y="84.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Parking</text>
                    
                    <!-- === COLLEGE OF NURSING AREA (Center) === -->
                    
                    <!-- College of Nursing -->
                    <rect x="118" y="98" width="44" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="140" y="103" text-anchor="middle" font-size="3" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">College of</text>
                    <text x="140" y="107.5" text-anchor="middle" font-size="3" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Nursing</text>
                    
                    <!-- College of Health Sciences -->
                    <rect x="145" y="130" width="50" height="8" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="170" y="135.5" text-anchor="middle" font-size="3" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Health Sciences</text>
                    
                    <!-- === GRADUATE SCHOOL AREA === -->
                    
                    <!-- Graduate School -->
                    <rect x="206" y="120" width="40" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="226" y="125" text-anchor="middle" font-size="3" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Graduate</text>
                    <text x="226" y="129" text-anchor="middle" font-size="3" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">School</text>
                    
                    <!-- AVR -->
                    <rect x="256" y="122" width="18" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="265" y="126.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">AVR</text>
                    
                    <!-- GS Extension -->
                    <rect x="256" y="132" width="20" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="266" y="136" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">GS Ext</text>
                    
                    <!-- GS-SBO -->
                    <rect x="256" y="140" width="22" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="267" y="144" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">GS-SBO</text>
                    
                    <!-- Alumni -->
                    <rect x="280" y="140" width="18" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="289" y="144" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Alumni</text>
                    
                    <!-- === CANTEEN & OFFICES AREA === -->
                    
                    <!-- OSAS -->
                    <rect x="113" y="148" width="22" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="124" y="152.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">OSAS</text>
                    
                    <!-- UC (University Canteen) -->
                    <rect x="140" y="148" width="18" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="149" y="152.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">UC</text>
                    
                    <!-- ROTC Office -->
                    <rect x="6" y="145" width="22" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="17" y="149" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">ROTC</text>
                    
                    <!-- === RESEARCH & AGRICULTURE AREA === -->
                    
                    <!-- TIP -->
                    <rect x="113" y="172" width="18" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="122" y="176.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">TIP</text>
                    
                    <!-- Climate -->
                    <rect x="86" y="172" width="24" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="98" y="176.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Climate</text>
                    
                    <!-- Agri 1 -->
                    <rect x="54" y="172" width="20" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="64" y="176.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Agri 1</text>
                    
                    <!-- Agri 2 -->
                    <rect x="29" y="172" width="20" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="39" y="176.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Agri 2</text>
                    
                    <!-- === FOOD CENTER AREA === -->
                    
                    <!-- Food Center -->
                    <rect x="244" y="194" width="32" height="8" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="260" y="199.5" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">Food Center</text>
                    
                    <!-- === LIBRARY AREA === -->
                    
                    <!-- ULRC (University Library) -->
                    <rect x="140" y="200" width="26" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="153" y="207" text-anchor="middle" font-size="4.5" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">ULRC</text>
                    
                    <!-- TCL -->
                    <rect x="108" y="212" width="20" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="118" y="216.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">TCL</text>
                    
                    <!-- DOST -->
                    <rect x="58" y="214" width="22" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="69" y="218.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">DOST</text>
                    
                    <!-- Motorpool -->
                    <rect x="32" y="208" width="26" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="45" y="212.5" text-anchor="middle" font-size="2.8" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Motorpool</text>
                    
                    <!-- === BOTTOM AREA (CCJE & Medical) === -->
                    
                    <!-- Ang Magsasaka -->
                    <rect x="230" y="220" width="36" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="248" y="224.5" text-anchor="middle" font-size="2.8" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">Ang Magsasaka</text>
                    
                    <!-- UPP Building -->
                    <rect x="268" y="228" width="24" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="280" y="232.5" text-anchor="middle" font-size="3" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">UPP</text>
                    
                    <!-- BCSF -->
                    <rect x="165" y="238" width="22" height="6" rx="1.5" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.4" style="pointer-events:none;filter:drop-shadow(0 1px 3px rgba(0,0,0,0.12));"/>
                    <text x="176" y="242.5" text-anchor="middle" font-size="3.2" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">BCSF</text>
                    
                    <!-- CCJE -->
                    <rect x="248" y="238" width="28" height="10" rx="2" fill="white" fill-opacity="0.97" stroke="#248823" stroke-width="0.5" style="pointer-events:none;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));"/>
                    <text x="262" y="245" text-anchor="middle" font-size="4.5" font-weight="bold" fill="#1a5c1a" style="pointer-events:none;">CCJE</text>
                    
                    <!-- CCJE Extension -->
                    <rect x="240" y="254" width="28" height="5" rx="1" fill="white" fill-opacity="0.95" stroke="#248823" stroke-width="0.3" style="pointer-events:none;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.1));"/>
                    <text x="254" y="258" text-anchor="middle" font-size="2.5" font-weight="600" fill="#1a5c1a" style="pointer-events:none;">CCJE Ext</text>
                </g>
                
                <!-- ==================== YOU ARE HERE MARKER ==================== -->
                <g id="youAreHereMarker" class="you-are-here-marker" style="pointer-events: none;">
                    <!-- Outer pulsing ring -->
                    <circle cx="188.32" cy="267.166" r="8" fill="none" stroke="#22c55e" stroke-width="1" opacity="0.3">
                        <animate attributeName="r" values="6;12;6" dur="2s" repeatCount="indefinite"/>
                        <animate attributeName="opacity" values="0.5;0;0.5" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <!-- Middle ring -->
                    <circle cx="188.32" cy="267.166" r="5" fill="none" stroke="#22c55e" stroke-width="1.5" opacity="0.4">
                        <animate attributeName="r" values="4;8;4" dur="2s" repeatCount="indefinite" begin="0.3s"/>
                        <animate attributeName="opacity" values="0.6;0;0.6" dur="2s" repeatCount="indefinite" begin="0.3s"/>
                    </circle>
                    <!-- Main marker circle -->
                    <circle cx="188.32" cy="267.166" r="3.5" fill="#22c55e" stroke="white" stroke-width="1">
                        <animate attributeName="r" values="3.5;4;3.5" dur="1.5s" repeatCount="indefinite"/>
                    </circle>
                    <!-- Inner dot -->
                    <circle cx="188.32" cy="267.166" r="1.5" fill="white"/>
                    <!-- "You Are Here" Label with background -->
                    <rect x="192" y="258" width="30" height="8" rx="2" fill="white" fill-opacity="0.95" stroke="#22c55e" stroke-width="0.5" style="filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"/>
                    <text x="207" y="263.5" text-anchor="middle" font-size="3.5" font-weight="bold" fill="#16a34a" font-family="Inter, sans-serif">YOU ARE HERE</text>
                    <!-- Location pin icon -->
                    <g transform="translate(193.5, 259.5) scale(0.2)">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#22c55e"/>
                    </g>
                </g>
            </svg>
            </div>
            
            <!-- Divider Line -->
            <div style="width: 2px; background: linear-gradient(180deg, #22c55e 0%, #16a34a 50%, #22c55e 100%); flex-shrink: 0;"></div>
            
            <!-- Right Legend Section (20%) - Aligned with Left -->
            <div id="rightLegendContainer" class="legend-panel" style="flex: 0 0 20%; display: flex; flex-direction: column; overflow: hidden; background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);">
                <div class="legend-header" style="flex-shrink: 0; padding: 1rem 1.25rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -30px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -40px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                    <h2 class="font-bold text-white text-center text-sm relative z-10" style="text-shadow: 0 2px 4px rgba(0,0,0,0.15); letter-spacing: 0.025em;">
                        <span class="flex items-center justify-center gap-2.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Offices & Areas
                        </span>
                    </h2>
                </div>
                <div class="px-3 py-3 touch-scroll show-scrollbar-on-hover" style="flex: 1 1 0; min-height: 0; overflow-y: auto; font-size: 0.85rem;">
                    <div class="legend-category">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">🔬</span>
                            Research & Development
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('TIP_center')"><span class="legend-item-dot"></span>Technology Incubation Park</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('TCL')"><span class="legend-item-dot"></span>Tech & Computer Lab</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('DOST')"><span class="legend-item-dot"></span>DOST Innovation Center</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Climate')"><span class="legend-item-dot"></span>Climate Research Center</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Agri_bldg_1')"><span class="legend-item-dot"></span>Agriculture Building 1</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Agri_bldg_2')"><span class="legend-item-dot"></span>Agriculture Building 2</div>
                    
                    <div class="legend-category" style="margin-top: 1rem;">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">📋</span>
                            Administrative Offices
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Reg_Office')"><span class="legend-item-dot"></span>Registrar's Office</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Alumni_Office')"><span class="legend-item-dot"></span>Alumni Relations Office</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('GS-SBO')"><span class="legend-item-dot"></span>GS - SBO Office</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('GS-ext')"><span class="legend-item-dot"></span>GS Extension Office</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('OSAS')"><span class="legend-item-dot"></span>Student Affairs (OSAS)</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('QMS')"><span class="legend-item-dot"></span>Quality Management</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('ULD')"><span class="legend-item-dot"></span>Ladies Dormitory</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Univesity_AVR')"><span class="legend-item-dot"></span>Audio-Visual Room</div>
                    
                    <div class="legend-category" style="margin-top: 1rem;">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">👥</span>
                            Student Services
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('LHS')"><span class="legend-item-dot"></span>Laboratory Highschool</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('LHS_ext')"><span class="legend-item-dot"></span>LHS Extension</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('ROTC')"><span class="legend-item-dot"></span>ROTC Office</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('SKSU-MPC')"><span class="legend-item-dot"></span>Multi-Purpose Center</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('MPC-Dorm')"><span class="legend-item-dot"></span>MPC Dormitory</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('MD_1')"><span class="legend-item-dot"></span>Men's Dormitory 1</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('MD_2')"><span class="legend-item-dot"></span>Men's Dormitory 2</div>
                    
                    <div class="legend-category" style="margin-top: 1rem;">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">⚽</span>
                            Sports & Recreation
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Field')"><span class="legend-item-dot"></span>University Athletic Field</div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('Bleacher')"><span class="legend-item-dot"></span>Field Bleachers</div>
                    
                    <div class="legend-category" style="margin-top: 1rem;">
                        <div class="legend-category-title">
                            <span class="legend-category-icon">🕌</span>
                            Religious Facility
                        </div>
                    </div>
                    <div class="legend-item-enhanced touch-feedback" onclick="navigateTo('mosque')"><span class="legend-item-dot"></span>University Mosque</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Building Preview Popup (shows when clicking a building on the map) -->
<div id="buildingPreviewPopup" class="fixed inset-0 z-50 hidden flex items-center justify-center" onclick="closeBuildingPreview(event)" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 overflow-hidden relative" onclick="event.stopPropagation()" style="animation: popupSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.1);">
        <!-- Decorative top bar -->
        <div style="height: 5px; background: linear-gradient(90deg, #22c55e, #10b981, #059669);"></div>
        
        <div class="p-6">
            <!-- Building Image with enhanced styling -->
            <div id="previewImageContainer" class="relative mb-5 rounded-xl overflow-hidden" style="height: 200px; box-shadow: 0 8px 25px -5px rgba(34,197,94,0.25);">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/15 to-emerald-600/15"></div>
                <img id="previewBuildingImage" src="" alt="" class="w-full h-full object-cover" style="display: none;">
                <div id="previewImagePlaceholder" class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center">
                            <svg class="w-12 h-12" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-400 font-medium text-sm">No image available</p>
                    </div>
                </div>
            </div>
            
            <!-- Building Name with decorative element -->
            <div class="text-center mb-4">
                <h2 id="previewBuildingName" class="text-xl font-bold text-gray-800 mb-2" style="font-family: var(--font-display);"></h2>
                <div class="w-12 h-1 mx-auto rounded-full" style="background: linear-gradient(90deg, #22c55e, #10b981);"></div>
            </div>
            
            <!-- Building Summary with badge styling -->
            <div id="previewBuildingSummary" class="text-center mb-5">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-full border border-green-100">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p id="previewOfficeCount" class="text-base font-medium text-green-700"></p>
                </div>
            </div>
            
            <!-- Action Buttons - Enhanced styling -->
            <div class="flex gap-3">
                <button onclick="closeBuildingPreview()" class="touch-target touch-feedback flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-3.5 px-5 rounded-xl transition-all text-base border-2 border-transparent hover:border-gray-300" style="box-shadow: inset 0 -2px 0 rgba(0,0,0,0.06);">
                    Close
                </button>
                <button id="viewBuildingDetailsBtn" onclick="openBuildingDetailsModal()" class="touch-target touch-feedback flex-1 text-white font-semibold py-3.5 px-5 rounded-xl transition-all text-base relative overflow-hidden group" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 4px 15px rgba(34,197,94,0.35);">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Details
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Building Details Modal (full details view with tabs) -->
<div id="buildingDetailsModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center" style="background: rgba(0,0,0,0.65); backdrop-filter: blur(12px);" onclick="closeBuildingDetailsModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col relative" onclick="event.stopPropagation()" style="animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4), 0 0 60px rgba(34,197,94,0.12);">
        <!-- Decorative gradient bar -->
        <div style="height: 4px; background: linear-gradient(90deg, #22c55e, #10b981, #059669, #10b981, #22c55e);"></div>
        
        <!-- Modal Header with enhanced styling -->
        <div class="flex items-center justify-between p-5 border-b relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdf4 100%);">
            <!-- Decorative circles -->
            <div style="position: absolute; top: -40px; right: -40px; width: 100px; height: 100px; background: rgba(34,197,94,0.08); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; right: 60px; width: 50px; height: 50px; background: rgba(16,185,129,0.08); border-radius: 50%;"></div>
            
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3.5 rounded-xl relative overflow-hidden" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 6px 16px rgba(34,197,94,0.35);">
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/0 to-white/20"></div>
                    <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h2 id="detailsModalTitle" class="text-2xl font-bold text-gray-800">Building Details</h2>
                    <p id="detailsModalSubtitle" class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Complete building information
                    </p>
                </div>
            </div>
            <button onclick="closeBuildingDetailsModal()" class="touch-target touch-feedback text-gray-400 hover:text-gray-600 transition-all p-3 hover:bg-white/80 rounded-xl relative z-10 group" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <svg class="w-8 h-8 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Tab Navigation - Enhanced styling -->
        <div class="border-b px-4 py-2" style="background: linear-gradient(180deg, #f9fafb 0%, #f3f4f6 100%);">
            <div class="flex gap-2 overflow-x-auto hide-scrollbar" id="detailsTabNav">
                <button onclick="switchDetailsTab('overview')" id="tab-overview" class="details-tab active touch-target px-5 py-4 font-semibold text-base rounded-xl transition-all whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Overview
                </button>
                <button onclick="switchDetailsTab('offices')" id="tab-offices" class="details-tab touch-target px-5 py-4 font-semibold text-base rounded-xl transition-all whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    Offices <span id="officeCountBadge" class="ml-1 px-2.5 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200">0</span>
                </button>
                <button onclick="switchDetailsTab('services')" id="tab-services" class="details-tab touch-target px-5 py-4 font-semibold text-base rounded-xl transition-all whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Services <span id="serviceCountBadge" class="ml-1 px-2.5 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 border border-blue-200">0</span>
                </button>
                <button onclick="switchDetailsTab('heads')" id="tab-heads" class="details-tab touch-target px-5 py-4 font-semibold text-base rounded-xl transition-all whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Heads <span id="headsCountBadge" class="ml-1 px-2.5 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 border border-purple-200">0</span>
                </button>
            </div>
        </div>
        
        <!-- Swipe Indicator - Enhanced styling -->
        <div class="flex justify-center py-4" style="background: linear-gradient(180deg, #f3f4f6 0%, #ffffff 100%);">
            <div class="flex gap-3 p-2 rounded-full bg-gray-100/80" id="swipeIndicators">
                <div class="swipe-dot active" data-tab="overview"></div>
                <div class="swipe-dot" data-tab="offices"></div>
                <div class="swipe-dot" data-tab="services"></div>
                <div class="swipe-dot" data-tab="heads"></div>
            </div>
        </div>
        
        <!-- Tab Content Container - Simple show/hide for reliable scrolling -->
        <div id="detailsTabContent" style="flex: 1; overflow-y: auto; min-height: 0; background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);">
            <!-- Overview Tab -->
            <div class="tab-panel p-6" id="panel-overview">
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="loading-spinner mx-auto mb-4"></div>
                        <p class="text-gray-500 font-medium">Loading building information...</p>
                    </div>
                </div>
            </div>
            <!-- Offices Tab -->
            <div class="tab-panel p-6 hidden" id="panel-offices">
                <!-- Content loaded dynamically -->
            </div>
            <!-- Services Tab -->
            <div class="tab-panel p-6 hidden" id="panel-services">
                <!-- Content loaded dynamically -->
            </div>
            <!-- Heads Tab -->
            <div class="tab-panel p-6 hidden" id="panel-heads">
                <!-- Content loaded dynamically -->
            </div>
        </div>
        
        <!-- Modal Footer with Navigation - Touch optimized -->
        <div class="p-4 border-t bg-gray-50 flex items-center justify-between gap-4">
            <button onclick="prevDetailsTab()" id="prevTabBtn" class="touch-target touch-feedback kiosk-btn bg-gray-200 hover:bg-gray-300 text-gray-700 opacity-50 cursor-not-allowed" disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Previous
            </button>
            <div class="text-base text-gray-500 text-center flex-1">
                <span id="currentTabLabel" class="font-semibold">Overview</span>
                <br><span class="text-sm">Swipe or tap arrows</span>
            </div>
            <button onclick="nextDetailsTab()" id="nextTabBtn" class="touch-target touch-feedback kiosk-btn text-white" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>
</div>

<style>
@keyframes popupSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.details-tab {
    color: #6b7280;
    background: transparent;
    border-bottom: 3px solid transparent;
    margin-bottom: -1px;
}

.details-tab:hover, .details-tab:active {
    color: #248823;
    background: rgba(36, 136, 35, 0.05);
}

.details-tab.active {
    color: #248823;
    background: white;
    border-bottom: 3px solid #248823;
}

.swipe-dot {
    width: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #d1d5db;
    transition: all 0.3s ease;
    cursor: pointer;
    /* Larger touch target via padding */
    padding: 6px;
    box-sizing: content-box;
    background-clip: content-box;
}

.swipe-dot:hover, .swipe-dot:active {
    background: #9ca3af;
    background-clip: content-box;
}

.swipe-dot.active {
    width: 32px;
    border-radius: 6px;
    background: #248823;
    background-clip: content-box;
}

.tab-panel {
    flex-shrink: 0;
}

.office-card {
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border-left: 4px solid #248823;
    transition: all 0.2s ease;
}

.office-card:hover, .office-card:active {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateX(4px);
}

.service-card {
    background: white;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.service-card:hover, .service-card:active {
    border-color: #248823;
    box-shadow: 0 2px 8px rgba(36, 136, 35, 0.15);
}

.head-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
    border: 2px solid #bbf7d0;
    transition: all 0.2s ease;
}

.head-card:hover, .head-card:active {
    border-color: #248823;
    box-shadow: 0 4px 12px rgba(36, 136, 35, 0.2);
}
</style>

<div class="modal-overlay" id="buildingModal">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto touch-scroll hide-scrollbar">
        <div class="flex justify-between items-start mb-6">
            <h2 id="modalTitle" class="text-3xl font-bold text-gray-800"></h2>
            <button onclick="closeModal()" class="touch-target touch-feedback text-3xl text-gray-400 hover:text-gray-600 p-2 transition">×</button>
        </div>
        <div id="modalContent"></div>
    </div>
</div>

<!-- Admin Login Modal -->
<div class="modal-overlay" id="adminLoginModal" style="backdrop-filter: blur(10px);">
    <div class="bg-white rounded-xl p-6 max-w-md w-full shadow-2xl" style="animation: modalSlideIn 0.3s ease-out;">
        <div class="flex justify-between items-start mb-5">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-lg" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 4px 12px rgba(34,197,94,0.3);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800" style="font-family: var(--font-display);">Admin Login</h2>
                    <p class="text-xs text-gray-400">Access admin panel</p>
                </div>
            </div>
            <button onclick="closeAdminLogin()" class="text-2xl text-gray-400 hover:text-gray-600 transition">×</button>
        </div>
        
        <form action="{{ route('login') }}" method="POST" id="adminLoginForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Username</label>
                <input type="text" 
                       name="username" 
                       required
                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-400 transition text-sm"
                       placeholder="admin">
            </div>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Password</label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-400 transition text-sm"
                       placeholder="admin123">
            </div>

            <div id="loginError" class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg hidden text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="loginErrorText"></span>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full text-white font-semibold py-2.5 px-5 rounded-lg transition duration-200"
                    style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 4px 12px rgba(34,197,94,0.3);"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(34,197,94,0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(34,197,94,0.3)';">
                Login to Admin Panel
            </button>
        </form>
    </div>
</div>

<!-- About Modal -->
<div class="modal-overlay" id="aboutModal" style="backdrop-filter: blur(10px);">
    <div class="bg-white rounded-xl p-6 max-w-4xl w-full shadow-2xl overflow-y-auto" style="animation: modalSlideIn 0.3s ease-out; max-height: 90vh;">
        <div class="flex justify-between items-start mb-5">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-lg" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 4px 12px rgba(34,197,94,0.3);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800" style="font-family: var(--font-display);">About This Research</h2>
                    <p class="text-xs text-gray-400">SKSU Access Campus Map System</p>
                </div>
            </div>
            <button onclick="closeAbout()" class="text-2xl text-gray-400 hover:text-gray-600 transition">×</button>
        </div>
        
        <div class="space-y-5">
            <!-- Research Overview -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-5 border-l-4" style="border-color: #22c55e;">
                <h3 class="font-bold text-base text-gray-800 mb-2" style="font-family: var(--font-display);">Research Overview</h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-2">
                    This study presents the development and implementation of an <strong>Interactive Digital Campus Navigation System</strong> 
                    for Sultan Kudarat State University (SKSU). The research addresses the increasing need for efficient wayfinding 
                    solutions in modern educational institutions, particularly for new students, visitors, and faculty members 
                    navigating the extensive SKSU campus.
                </p>
                <p class="text-sm text-gray-600 leading-relaxed">
                    The system leverages web-based technologies to provide real-time, accessible campus information through an 
                    intuitive interface, facilitating seamless navigation and information retrieval across 44 campus buildings, 
                    116 offices, and 345 services.
                </p>
            </div>

            <!-- Research Purpose -->
            <div>
                <h3 class="font-bold text-lg text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: #248823;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Research Purpose & Objectives
                </h3>
                <div class="bg-white border rounded-lg p-4">
                    <p class="text-sm text-gray-700 leading-relaxed mb-3">
                        <strong>Primary Purpose:</strong> To design, develop, and deploy a comprehensive digital kiosk system 
                        that enhances campus accessibility, reduces navigation barriers, and improves overall user experience 
                        within the university environment.
                    </p>
                    <p class="text-sm text-gray-700 font-semibold mb-2">Specific Objectives:</p>
                    <ul class="text-sm text-gray-600 space-y-2 ml-4">
                        <li class="flex items-start gap-2">
                            <span class="font-bold" style="color: #248823;">•</span>
                            <span>Develop an interactive SVG-based campus map with real-time building information</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold" style="color: #248823;">•</span>
                            <span>Create a centralized database of campus facilities, offices, and services</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold" style="color: #248823;">•</span>
                            <span>Implement an administrative system for content management and announcements</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold" style="color: #248823;">•</span>
                            <span>Ensure responsive design and accessibility across multiple devices</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold" style="color: #248823;">•</span>
                            <span>Evaluate system usability and effectiveness in improving campus navigation</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Key Features -->
            <div>
                <h3 class="font-bold text-lg text-gray-800 mb-3">System Features & Capabilities</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5" style="color: #248823;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            <span class="font-semibold text-sm text-gray-800">Interactive Map</span>
                        </div>
                        <p class="text-xs text-gray-600">SVG-based clickable buildings with visual feedback and detailed information</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-semibold text-sm text-gray-800">Office Directory</span>
                        </div>
                        <p class="text-xs text-gray-600">Comprehensive database with office heads and service descriptions</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-white border border-purple-100 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            <span class="font-semibold text-sm text-gray-800">Announcements</span>
                        </div>
                        <p class="text-xs text-gray-600">Real-time campus announcements and important notices</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-100 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold text-sm text-gray-800">Image Galleries</span>
                        </div>
                        <p class="text-xs text-gray-600">Visual building documentation with image caching system</p>
                    </div>
                </div>
            </div>

            <!-- Research Team -->
            <div class="border-t pt-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4">Research Team</h3>
                
                <!-- Researchers -->
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Researchers</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-4 bg-gradient-to-r from-gray-50 to-white p-4 rounded-lg border">
                            <img src="{{ asset('images/researcher1.jpg') }}" alt="Hannah Mae V. Magallosa" class="w-20 h-20 rounded-full object-cover border-2" style="border-color: #248823;">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Hannah Mae V. Magallosa</p>
                                <p class="text-xs text-gray-600">Researcher</p>
                                <p class="text-xs text-gray-500 mt-1">BS Computer Engineering</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 bg-gradient-to-r from-gray-50 to-white p-4 rounded-lg border">
                            <img src="{{ asset('images/researcher2.jpg') }}" alt="Sam Jones L. Cedana" class="w-20 h-20 rounded-full object-cover border-2" style="border-color: #248823;">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Sam Jones L. Cedana</p>
                                <p class="text-xs text-gray-600">Researcher</p>
                                <p class="text-xs text-gray-500 mt-1">BS Computer Engineering</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adviser -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-3">Research Adviser</p>
                    <div class="flex items-center gap-4 bg-gradient-to-r from-green-50 to-white p-4 rounded-lg border-2" style="border-color: #248823;">
                        <img src="{{ asset('images/adviser.jpg') }}" alt="Charity L. Oria, DEng" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        <div>
                            <p class="font-bold text-gray-800">Charity L. Oria, DEng</p>
                            <p class="text-sm text-gray-600">Research Adviser</p>
                            <p class="text-xs text-gray-500 mt-1">College of Engineering</p>
                            <p class="text-xs text-gray-500">Sultan Kudarat State University</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Specifications -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <h3 class="font-semibold text-gray-800 mb-2 text-sm">Technical Implementation</h3>
                <div class="grid grid-cols-3 gap-3 text-xs">
                    <div>
                        <p class="font-semibold text-gray-700">Frontend</p>
                        <p class="text-gray-600">Laravel Blade, Alpine.js, Tailwind CSS</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Backend</p>
                        <p class="text-gray-600">Laravel 11, PHP 8.2, MySQL</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Database</p>
                        <p class="text-gray-600">44 Buildings, 116 Offices, 345 Services</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="border-t pt-4">
                <p class="text-xs text-gray-500 text-center">
                    © 2025 Sultan Kudarat State University<br>
                    Interactive Campus Navigation System • Version 1.0.0<br>
                    Research Project - College of Engineering
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

<script>
    // Handle admin login form submission with CSRF refresh
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('adminLoginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Refresh CSRF token before submission
                try {
                    const response = await fetch('/refresh-csrf');
                    const data = await response.json();
                    
                    if (data.csrf_token) {
                        // Update the CSRF token in the form
                        const csrfInput = loginForm.querySelector('input[name="_token"]');
                        if (csrfInput) {
                            csrfInput.value = data.csrf_token;
                        }
                    }
                } catch (error) {
                    console.error('Error refreshing CSRF token:', error);
                }
                
                // Submit the form
                loginForm.submit();
            });
        }
    });
</script>

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

<!-- Building Hover Tooltip -->
<div id="buildingTooltip" class="building-tooltip"></div>

@endsection

@section('scripts')
<script>
    // ============================================
    // KIOSK IDLE TIMEOUT SYSTEM
    // ============================================
    let idleTimer = null;
    const IDLE_TIMEOUT = 120000; // 2 minutes of inactivity (adjust as needed)
    
    function resetIdleTimer() {
        // Hide idle overlay if visible
        const idleOverlay = document.getElementById('kioskIdleOverlay');
        if (idleOverlay) {
            idleOverlay.classList.remove('show');
        }
        
        // Clear existing timer
        if (idleTimer) {
            clearTimeout(idleTimer);
        }
        
        // Set new timer
        idleTimer = setTimeout(showIdleScreen, IDLE_TIMEOUT);
    }
    
    function showIdleScreen() {
        const idleOverlay = document.getElementById('kioskIdleOverlay');
        if (idleOverlay) {
            // Close any open modals first
            closeBuildingPreview();
            closeBuildingDetailsModal();
            
            // Show idle screen
            idleOverlay.classList.add('show');
        }
    }
    
    // Start idle timer and listen for user activity
    document.addEventListener('DOMContentLoaded', function() {
        // Events that reset idle timer
        const resetEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'touchmove', 'click'];
        
        resetEvents.forEach(event => {
            document.addEventListener(event, resetIdleTimer, { passive: true });
        });
        
        // Start initial timer
        resetIdleTimer();
    });

    const buildings = @json($buildings);
    const isAdmin = @json($isAdmin);
    const dbEndpoints = @json($navigationEndpoints ?? []);
    
    // Image preloading cache
    let imagesPreloaded = false;
    const imageCache = new Map();
    
    // Preload all building images on first user interaction
    function preloadAllBuildingImages() {
        if (imagesPreloaded) return;
        imagesPreloaded = true;
        
        // Clear console first
        console.clear();
        
        let loadedCount = 0;
        let totalImages = 0;
        let currentFile = '';
        
        // Function to update progress
        const updateProgress = () => {
            console.clear();
            console.log(`Caching: ${currentFile}`);
            console.log(`Progress: ${loadedCount}/${totalImages}`);
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
                    imageCache.set(src, img);
                    loadedCount++;
                    currentFile = src;
                    updateProgress();
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`All ${totalImages}/${totalImages} images cached`);
                    }
                };
                img.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`All ${totalImages}/${totalImages} images cached`);
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
                    imageCache.set(dbSrc, dbImg);
                    loadedCount++;
                    currentFile = dbSrc;
                    updateProgress();
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`All ${totalImages}/${totalImages} images cached`);
                    }
                };
                dbImg.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        console.clear();
                        console.log(`All ${totalImages}/${totalImages} images cached`);
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
                        imageCache.set(gallerySrc, galleryImg);
                        loadedCount++;
                        currentFile = gallerySrc;
                        updateProgress();
                        if (loadedCount === totalImages) {
                            console.clear();
                            console.log(`All ${totalImages}/${totalImages} images cached`);
                        }
                    };
                    galleryImg.onerror = () => {
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            console.clear();
                            console.log(`All ${totalImages}/${totalImages} images cached`);
                        }
                    };
                    galleryImg.src = gallerySrc;
                });
            }
        });
    }
    
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
        'Administration': {x: 193, y: 50, roadConnection: 'r_north'},
        'CTE': {x: 257, y: 108, roadConnection: 'east_north'},
        'CTE Building': {x: 257, y: 108, roadConnection: 'east_north'},
        'CHS': {x: 157, y: 110, roadConnection: 'west_north'},
        'CHS Building': {x: 157, y: 110, roadConnection: 'west_north'},
        'CHS_Labs': {x: 175, y: 140, roadConnection: 'west_140'},
        'CCJE': {x: 261, y: 240, roadConnection: 'spine_south'},
        'CCJE Building': {x: 261, y: 240, roadConnection: 'spine_south'},
        'CCJE_ext': {x: 261, y: 256, roadConnection: 'spine_south_lower'},
        'CoM': {x: 282, y: 43, roadConnection: 'east_50'},
        'GS': {x: 207, y: 142, roadConnection: 'horiz_202'},
        
        // Facilities & Services
        'ULRC': {x: 168, y: 209, roadConnection: 'spine_south_210'},
        'ULRC Library': {x: 168, y: 209, roadConnection: 'spine_south_210'},
        'UG': {x: 158, y: 41, roadConnection: 'west_north'},
        'UC': {x: 148, y: 163, roadConnection: 'horiz_134'},
        'Function': {x: 135, y: 61, roadConnection: 'r_west'},
        'Function Hall': {x: 135, y: 61, roadConnection: 'r_west'},
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
        'LHS_ext': {x: 271, y: 23, roadConnection: 'east_50'},
        'LHS Ext': {x: 271, y: 23, roadConnection: 'east_top'},
        'ROTC': {x: 25, y: 152, roadConnection: 'horiz_30'},
        'SKSU-MPC': {x: 253, y: 16, roadConnection: 'east_50'},
        'MPC': {x: 253, y: 16, roadConnection: 'east_50'},
        'MPC-Dorm': {x: 262, y: 15, roadConnection: 'east_top'},
        'MPC Dorm': {x: 262, y: 15, roadConnection: 'east_top'},
        'MD_1': {x: 20, y: 76, roadConnection: 'far_west_80'},
        'MD 1': {x: 20, y: 76, roadConnection: 'far_west_80'},
        'MD_2': {x: 20, y: 88, roadConnection: 'far_west_80'},
        'MD 2': {x: 20, y: 88, roadConnection: 'far_west_80'},
        
        // Sports & Recreation
        'Field': {x: 69, y: 160, roadConnection: 'horiz_100'},
        'Bleacher': {x: 115, y: 72, roadConnection: 'west_80'},
        
        // Religious
        'mosque': {x: 149, y: 184, roadConnection: 'conn_134_185'},
        'Mosque': {x: 149, y: 184, roadConnection: 'conn_134_185'}
    };
    
    // Merge database endpoints with defaults (database values take priority)
    if (dbEndpoints && Object.keys(dbEndpoints).length > 0) {
        let loadedCount = 0;
        Object.keys(dbEndpoints).forEach(buildingCode => {
            const endpoint = dbEndpoints[buildingCode];
            const x = parseFloat(endpoint.x);
            const y = parseFloat(endpoint.y);
            
            // Only use database values if they are valid non-zero coordinates
            if (!isNaN(x) && !isNaN(y) && x > 0 && y > 0) {
                navigationPoints[buildingCode] = {
                    x: x,
                    y: y,
                    roadConnection: endpoint.roadConnection || navigationPoints[buildingCode]?.roadConnection || 'gate'
                };
                loadedCount++;
            }
        });
        console.log('Loaded', loadedCount, 'valid endpoint(s) from database');
    }
    
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.toLocaleTimeString();
    }
    updateClock();
    setInterval(updateClock, 1000);
    
    // ============================================
    // ENHANCED SEARCH FUNCTIONALITY WITH DROPDOWN
    // ============================================
    
    // Building categories for search
    const buildingCategories = {
        'Administration': 'Admin & Offices',
        'CTE': 'Academic Colleges',
        'CHS': 'Academic Colleges',
        'CHS_Labs': 'Academic Colleges',
        'CCJE': 'Academic Colleges',
        'CCJE_ext': 'Academic Colleges',
        'CoM': 'Academic Colleges',
        'GS': 'Academic Colleges',
        'ULRC': 'Facilities & Services',
        'UG': 'Facilities & Services',
        'UC': 'Facilities & Services',
        'Function': 'Facilities & Services',
        'UPP': 'Facilities & Services',
        'Motorpool': 'Facilities & Services',
        'FC': 'Facilities & Services',
        'Parking_Space': 'Facilities & Services',
        'Restroom': 'Facilities & Services',
        'BCSF': 'Medical & Training',
        'AMTC': 'Medical & Training',
        'LHS': 'Academic Colleges',
        'LHS_ext': 'Academic Colleges',
        'QMS': 'Admin & Offices',
        'ULD': 'Facilities & Services',
        'Field': 'Sports & Recreation',
        'Bleacher': 'Sports & Recreation',
        'OSAS': 'Admin & Offices',
        'ROTC': 'Facilities & Services',
        'DOST': 'Research & Innovation',
        'TCL': 'Research & Innovation',
        'Climate': 'Research & Innovation',
        'MD_1': 'Dormitories',
        'MD_2': 'Dormitories',
        'MPC-Dorm': 'Dormitories',
        'SKSU-MPC': 'Facilities & Services',
        'Reg_Office': 'Admin & Offices',
        'Alumni_Office': 'Admin & Offices',
        'GS-SBO': 'Admin & Offices',
        'GS-ext': 'Academic Colleges',
        'Univesity_AVR': 'Facilities & Services',
        'TIP_center': 'Research & Innovation',
        'Agri_bldg_1': 'Academic Colleges',
        'Agri_bldg_2': 'Academic Colleges'
    };
    
    const categoryIcons = {
        'Academic Colleges': '🎓',
        'Admin & Offices': '🏛️',
        'Facilities & Services': '🏢',
        'Medical & Training': '🏥',
        'Sports & Recreation': '⚽',
        'Research & Innovation': '🔬',
        'Dormitories': '🏠'
    };
    
    let searchHighlightIndex = -1;
    let searchResults = [];
    
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        searchHighlightIndex = -1;
        
        if (searchTerm.length < 1) {
            searchDropdown.classList.remove('active');
            searchDropdown.innerHTML = '';
            return;
        }
        
        // Search through all buildings
        searchResults = [];
        
        // Search in svgToDisplayName
        for (const [svgId, displayName] of Object.entries(svgToDisplayName)) {
            if (displayName.toLowerCase().includes(searchTerm) || svgId.toLowerCase().includes(searchTerm)) {
                searchResults.push({
                    id: svgId,
                    name: displayName,
                    category: buildingCategories[svgId] || 'Other'
                });
            }
        }
        
        // Remove duplicates
        searchResults = searchResults.filter((v, i, a) => a.findIndex(t => t.id === v.id) === i);
        
        // Limit results
        searchResults = searchResults.slice(0, 8);
        
        if (searchResults.length === 0) {
            searchDropdown.innerHTML = '<div class="search-no-results">No buildings found</div>';
            searchDropdown.classList.add('active');
            return;
        }
        
        // Build dropdown HTML
        let html = '';
        searchResults.forEach((result, index) => {
            const icon = categoryIcons[result.category] || '📍';
            html += `
                <div class="search-item" data-index="${index}" data-id="${result.id}" onclick="selectSearchResult('${result.id}')">
                    <div class="search-item-icon">${icon}</div>
                    <div class="search-item-text">
                        <div class="search-item-name">${highlightMatch(result.name, searchTerm)}</div>
                        <div class="search-item-category">${result.category}</div>
                    </div>
                </div>
            `;
        });
        
        searchDropdown.innerHTML = html;
        searchDropdown.classList.add('active');
    });
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchDropdown.querySelectorAll('.search-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            searchHighlightIndex = Math.min(searchHighlightIndex + 1, items.length - 1);
            updateSearchHighlight(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            searchHighlightIndex = Math.max(searchHighlightIndex - 1, 0);
            updateSearchHighlight(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (searchHighlightIndex >= 0 && searchResults[searchHighlightIndex]) {
                selectSearchResult(searchResults[searchHighlightIndex].id);
            } else if (searchResults.length > 0) {
                selectSearchResult(searchResults[0].id);
            }
        } else if (e.key === 'Escape') {
            searchDropdown.classList.remove('active');
            searchInput.blur();
        }
    });
    
    function updateSearchHighlight(items) {
        items.forEach((item, idx) => {
            if (idx === searchHighlightIndex) {
                item.classList.add('highlighted');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('highlighted');
            }
        });
    }
    
    function highlightMatch(text, term) {
        const regex = new RegExp(`(${term})`, 'gi');
        return text.replace(regex, '<strong style="color: var(--primary);">$1</strong>');
    }
    
    function selectSearchResult(buildingId) {
        searchDropdown.classList.remove('active');
        searchInput.value = '';
        searchInput.blur();
        navigateTo(buildingId);
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#searchContainer')) {
            searchDropdown.classList.remove('active');
        }
    });
    
    // ============================================
    // ACCESSIBILITY FUNCTIONS
    // ============================================
    
    function toggleAccessibilityPanel() {
        const panel = document.getElementById('accessibilityPanel');
        panel.classList.toggle('active');
    }
    
    function toggleLargeText() {
        const toggle = document.getElementById('toggleLargeText');
        toggle.classList.toggle('active');
        document.body.classList.toggle('large-text');
        localStorage.setItem('largeText', toggle.classList.contains('active'));
    }
    
    function toggleHighContrast() {
        const toggle = document.getElementById('toggleHighContrast');
        toggle.classList.toggle('active');
        document.body.classList.toggle('high-contrast');
        localStorage.setItem('highContrast', toggle.classList.contains('active'));
    }
    
    function toggleReducedMotion() {
        const toggle = document.getElementById('toggleReducedMotion');
        toggle.classList.toggle('active');
        window.reducedMotion = toggle.classList.contains('active');
        localStorage.setItem('reducedMotion', toggle.classList.contains('active'));
    }
    
    function resetAccessibility() {
        document.body.classList.remove('large-text', 'high-contrast');
        window.reducedMotion = false;
        document.getElementById('toggleLargeText').classList.remove('active');
        document.getElementById('toggleHighContrast').classList.remove('active');
        document.getElementById('toggleReducedMotion').classList.remove('active');
        localStorage.removeItem('largeText');
        localStorage.removeItem('highContrast');
        localStorage.removeItem('reducedMotion');
    }
    
    // Load saved accessibility settings
    function loadAccessibilitySettings() {
        if (localStorage.getItem('largeText') === 'true') {
            document.getElementById('toggleLargeText').classList.add('active');
            document.body.classList.add('large-text');
        }
        if (localStorage.getItem('highContrast') === 'true') {
            document.getElementById('toggleHighContrast').classList.add('active');
            document.body.classList.add('high-contrast');
        }
        if (localStorage.getItem('reducedMotion') === 'true') {
            document.getElementById('toggleReducedMotion').classList.add('active');
            window.reducedMotion = true;
        }
    }
    
    // Close accessibility panel when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#accessibilityPanel') && !e.target.closest('[onclick*="toggleAccessibilityPanel"]')) {
            document.getElementById('accessibilityPanel').classList.remove('active');
        }
    });
    
    // ============================================
    // WALKING TIME CALCULATION
    // ============================================
    
    // Scale factor: 1 SVG unit ≈ 1 meter (approximate campus scale)
    const METERS_PER_SVG_UNIT = 1.5;
    const WALKING_SPEED_MPS = 1.2; // Average walking speed: 1.2 m/s (~4.3 km/h)
    
    function calculateWalkingTime(pathLength) {
        const distanceMeters = pathLength * METERS_PER_SVG_UNIT;
        const timeSeconds = distanceMeters / WALKING_SPEED_MPS;
        const timeMinutes = Math.ceil(timeSeconds / 60);
        
        return {
            distance: Math.round(distanceMeters),
            minutes: Math.max(1, timeMinutes) // Minimum 1 minute
        };
    }
    
    function showWalkingTime(pathLength) {
        const { distance, minutes } = calculateWalkingTime(pathLength);
        
        const badge = document.getElementById('walkingTimeBadge');
        const timeValue = document.getElementById('walkingTimeValue');
        const distanceEl = document.getElementById('walkingDistance');
        
        timeValue.textContent = `~${minutes} min`;
        distanceEl.textContent = `~${distance}m`;
        
        badge.classList.add('active');
    }
    
    function hideWalkingTime() {
        document.getElementById('walkingTimeBadge').classList.remove('active');
    }
    
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
        'UG': 'University Gym',
        'Administration': 'Administration Building',
        'CTE': 'College of Teacher Education',
        'CHS': 'College of Health Sciences',
        'CCJE': 'College of Criminal Justice Education',
        'CCJE_ext': 'CCJE Extension Building',
        'ULRC': 'University Library Resource Center'
    };
    
    // Note: Building matching now uses the 'code' field from database
    // which matches the SVG ID (building_id from fullinfo.json)
    
    // Add click handlers to SVG buildings
    document.addEventListener('DOMContentLoaded', function() {
        // Load accessibility settings
        loadAccessibilitySettings();
        
        // Preload all building images immediately on page load
        preloadAllBuildingImages();
        
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
                    
                    // Get display name for navigation and show popup when clicking on map
                    const displayName = svgToDisplayName[buildingId] || buildingId;
                    navigateTo(displayName, true); // true = show popup
                });
                
                // Add hover tooltip functionality
                element.addEventListener('mouseenter', function(e) {
                    if (editMode) return;
                    
                    const displayName = svgToDisplayName[buildingId] || buildingId;
                    const tooltip = document.getElementById('buildingTooltip');
                    if (tooltip) {
                        tooltip.textContent = displayName;
                        tooltip.classList.add('show');
                    }
                });
                
                element.addEventListener('mousemove', function(e) {
                    if (editMode) return;
                    
                    const tooltip = document.getElementById('buildingTooltip');
                    if (tooltip) {
                        const offsetX = 15;
                        const offsetY = -30;
                        
                        // Position tooltip above and slightly to the right of cursor
                        tooltip.style.left = (e.clientX + offsetX) + 'px';
                        tooltip.style.top = (e.clientY + offsetY) + 'px';
                    }
                });
                
                element.addEventListener('mouseleave', function(e) {
                    const tooltip = document.getElementById('buildingTooltip');
                    if (tooltip) {
                        tooltip.classList.remove('show');
                    }
                });
            }
        });
    });
    
    async function showBuildingModal(buildingId) {
        if (editMode) return;
        
        // Store current building ID for details window
        window.currentBuildingId = buildingId;
        
        // Show building preview popup instead of sidebar
        const popup = document.getElementById('buildingPreviewPopup');
        popup.classList.remove('hidden');
        
        try {
            // Fetch building data
            const response = await fetch(`/api/buildings/${buildingId}`);
            if (!response.ok) {
                throw new Error('Building not found');
            }
            
            const building = await response.json();
            
            // Update popup content
            document.getElementById('previewBuildingName').textContent = building.name;
            
            // Handle image
            const publicImageJpg = `/images/buildings/${building.code}.jpg`;
            const publicImagePng = `/images/buildings/${building.code}.png`;
            const previewImage = document.getElementById('previewBuildingImage');
            const placeholder = document.getElementById('previewImagePlaceholder');
            
            // Check cached images
            const jpgCached = imageCache.has(publicImageJpg);
            const pngCached = imageCache.has(publicImagePng);
            const dbImageCached = building.image_path && imageCache.has(`/storage/${building.image_path}`);
            
            if (jpgCached || pngCached || dbImageCached) {
                let imgSrc = jpgCached ? publicImageJpg : (pngCached ? publicImagePng : `/storage/${building.image_path}`);
                previewImage.src = imgSrc;
                previewImage.style.display = 'block';
                placeholder.style.display = 'none';
            } else {
                previewImage.style.display = 'none';
                placeholder.style.display = 'flex';
            }
            
            // Update summary
            const officeCount = building.offices ? building.offices.length : 0;
            let serviceCount = 0;
            if (building.offices) {
                building.offices.forEach(office => {
                    if (office.services) serviceCount += office.services.length;
                });
            }
            
            document.getElementById('previewOfficeCount').innerHTML = `
                <span class="inline-flex items-center gap-1"><strong>${officeCount}</strong> ${officeCount === 1 ? 'Office' : 'Offices'}</span>
                <span class="mx-2">•</span>
                <span class="inline-flex items-center gap-1"><strong>${serviceCount}</strong> ${serviceCount === 1 ? 'Service' : 'Services'}</span>
            `;
            
        } catch (error) {
            console.error('Error loading building:', error);
            document.getElementById('previewBuildingName').textContent = 'Error loading building';
            document.getElementById('previewOfficeCount').textContent = error.message;
        }
    }
    
    function closeBuildingPreview(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('buildingPreviewPopup').classList.add('hidden');
    }
    
    // Tab navigation state
    let currentDetailsTab = 0;
    const tabNames = ['overview', 'offices', 'services', 'heads'];
    const tabLabels = ['Overview', 'Offices', 'Services', 'Office Heads'];
    let currentBuildingData = null;
    
    async function openBuildingDetailsModal() {
        if (!window.currentBuildingId) return;
        
        // Close preview and show details modal
        closeBuildingPreview();
        const modal = document.getElementById('buildingDetailsModal');
        modal.classList.remove('hidden');
        
        // Reset to first tab
        currentDetailsTab = 0;
        updateTabUI();
        
        // Show loading state in overview
        document.getElementById('panel-overview').innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-green-500 border-t-transparent"></div>
            </div>
        `;
        
        try {
            // Fetch building data with offices and services
            const response = await fetch(`/api/buildings/${window.currentBuildingId}`);
            if (!response.ok) throw new Error('Building not found');
            
            const building = await response.json();
            currentBuildingData = building;
            
            // Update modal title
            document.getElementById('detailsModalTitle').textContent = building.name;
            
            // Calculate counts
            const offices = building.offices || [];
            let allServices = [];
            let allHeads = [];
            
            offices.forEach(office => {
                if (office.services) {
                    office.services.forEach(s => {
                        allServices.push({ ...s, officeName: office.name });
                    });
                }
                if (office.head_name) {
                    allHeads.push({
                        name: office.head_name,
                        title: office.head_title,
                        officeName: office.name,
                        floor: office.floor_number
                    });
                }
            });
            
            // Update badge counts
            document.getElementById('officeCountBadge').textContent = offices.length;
            document.getElementById('serviceCountBadge').textContent = allServices.length;
            document.getElementById('headsCountBadge').textContent = allHeads.length;
            document.getElementById('detailsModalSubtitle').textContent = 
                `${offices.length} offices • ${allServices.length} services • ${allHeads.length} heads`;
            
            // Build Overview Tab
            buildOverviewTab(building);
            
            // Build Offices Tab
            buildOfficesTab(offices);
            
            // Build Services Tab
            buildServicesTab(allServices);
            
            // Build Heads Tab
            buildHeadsTab(allHeads);
            
            // Setup swipe gestures
            setupSwipeGestures();
            
        } catch (error) {
            console.error('Error loading building details:', error);
            document.getElementById('panel-overview').innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">Failed to load building details</p>
                    <p class="text-gray-400">${error.message}</p>
                </div>
            `;
        }
    }
    
    function buildOverviewTab(building) {
        let html = '';
        
        // Building Image
        const publicImageJpg = `/images/buildings/${building.code}.jpg`;
        const publicImagePng = `/images/buildings/${building.code}.png`;
        const hasImage = imageCache.has(publicImageJpg) || imageCache.has(publicImagePng) || building.image_path;
        
        if (hasImage) {
            let imgSrc = imageCache.has(publicImageJpg) ? publicImageJpg : 
                        (imageCache.has(publicImagePng) ? publicImagePng : `/storage/${building.image_path}`);
            html += `
                <div class="mb-6 rounded-xl overflow-hidden shadow-lg" style="height: 250px;">
                    <img src="${imgSrc}" alt="${building.name}" class="w-full h-full object-cover" 
                         onerror="this.parentElement.innerHTML='<div class=\\'flex items-center justify-center h-full bg-gradient-to-br from-green-50 to-green-100\\'><svg class=\\'w-20 h-20 text-green-300\\' fill=\\'none\\' stroke=\\'currentColor\\' viewBox=\\'0 0 24 24\\'><path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'2\\' d=\\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\\'></path></svg></div>'">
                </div>
            `;
        } else {
            html += `
                <div class="mb-6 rounded-xl overflow-hidden shadow-lg bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center" style="height: 200px;">
                    <div class="text-center">
                        <svg class="w-20 h-20 mx-auto text-green-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-green-400 text-sm">No image available</p>
                    </div>
                </div>
            `;
        }
        
        // Quick Stats
        const offices = building.offices || [];
        let serviceCount = 0;
        let headCount = 0;
        offices.forEach(o => {
            if (o.services) serviceCount += o.services.length;
            if (o.head_name) headCount++;
        });
        
        html += `
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-blue-600">${offices.length}</div>
                    <div class="text-sm text-blue-500">Offices</div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-green-600">${serviceCount}</div>
                    <div class="text-sm text-green-500">Services</div>
                </div>
                <div class="bg-purple-50 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-purple-600">${headCount}</div>
                    <div class="text-sm text-purple-500">Office Heads</div>
                </div>
            </div>
        `;
        
        // Building Description
        if (building.description) {
            html += `
                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <h3 class="text-lg font-bold mb-3 flex items-center gap-2" style="color: #248823;">
                        <span>📋</span> About This Building
                    </h3>
                    <p class="text-gray-700 leading-relaxed">${building.description}</p>
                </div>
            `;
        }
        
        // Quick Links to other tabs
        html += `
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Quick Navigation</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <button onclick="switchDetailsTab('offices')" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-left transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-500 rounded-lg text-white group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">View Offices</div>
                                <div class="text-sm text-gray-500">${offices.length} available</div>
                            </div>
                        </div>
                    </button>
                    <button onclick="switchDetailsTab('services')" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-left transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-500 rounded-lg text-white group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">View Services</div>
                                <div class="text-sm text-gray-500">${serviceCount} available</div>
                            </div>
                        </div>
                    </button>
                    <button onclick="switchDetailsTab('heads')" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-left transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-500 rounded-lg text-white group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">Office Heads</div>
                                <div class="text-sm text-gray-500">${headCount} personnel</div>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('panel-overview').innerHTML = html;
    }
    
    function buildOfficesTab(offices) {
        let html = '';
        
        if (offices.length === 0) {
            html = `
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No offices listed</p>
                    <p class="text-gray-400 text-sm">This building's office information is being updated</p>
                </div>
            `;
        } else {
            html = `<div class="space-y-4">`;
            offices.forEach((office, index) => {
                const serviceCount = office.services ? office.services.length : 0;
                html += `
                    <div class="office-card rounded-xl p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full text-white text-sm font-bold" style="background: #248823;">${index + 1}</span>
                                    <h4 class="font-bold text-xl text-gray-800">${office.name}</h4>
                                </div>
                                ${office.floor_number ? `
                                    <p class="text-gray-500 text-sm flex items-center gap-2 ml-11">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        Floor ${office.floor_number}
                                    </p>
                                ` : ''}
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ${serviceCount} ${serviceCount === 1 ? 'Service' : 'Services'}
                                </span>
                            </div>
                        </div>
                        
                        ${office.head_name ? `
                            <div class="ml-11 mb-3 p-3 bg-white rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Office Head</p>
                                <p class="font-semibold text-gray-800">${office.head_name}</p>
                                ${office.head_title ? `<p class="text-sm text-gray-500">${office.head_title}</p>` : ''}
                            </div>
                        ` : ''}
                        
                        ${serviceCount > 0 ? `
                            <div class="ml-11 mt-3">
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Services Offered</p>
                                <div class="flex flex-wrap gap-2">
                                    ${office.services.slice(0, 4).map(s => `
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                            <span class="text-green-500">✓</span> ${s.description.substring(0, 30)}${s.description.length > 30 ? '...' : ''}
                                        </span>
                                    `).join('')}
                                    ${serviceCount > 4 ? `<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">+${serviceCount - 4} more</span>` : ''}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            });
            html += `</div>`;
        }
        
        document.getElementById('panel-offices').innerHTML = html;
    }
    
    function buildServicesTab(services) {
        let html = '';
        
        if (services.length === 0) {
            html = `
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No services listed</p>
                    <p class="text-gray-400 text-sm">Service information is being updated</p>
                </div>
            `;
        } else {
            // Group services by office
            const grouped = {};
            services.forEach(s => {
                if (!grouped[s.officeName]) grouped[s.officeName] = [];
                grouped[s.officeName].push(s);
            });
            
            html = `
                <div class="mb-4 p-4 bg-green-50 rounded-xl">
                    <p class="text-green-700 text-sm">
                        <strong>${services.length}</strong> services available across <strong>${Object.keys(grouped).length}</strong> offices
                    </p>
                </div>
            `;
            
            Object.keys(grouped).forEach(officeName => {
                html += `
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" style="color: #248823;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                            </svg>
                            ${officeName}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            ${grouped[officeName].map(s => `
                                <div class="service-card rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-sm">✓</span>
                                        <p class="text-gray-700 text-sm">${s.description}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            });
        }
        
        document.getElementById('panel-services').innerHTML = html;
    }
    
    function buildHeadsTab(heads) {
        let html = '';
        
        if (heads.length === 0) {
            html = `
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No office heads listed</p>
                    <p class="text-gray-400 text-sm">Personnel information is being updated</p>
                </div>
            `;
        } else {
            html = `
                <div class="mb-4 p-4 bg-purple-50 rounded-xl">
                    <p class="text-purple-700 text-sm">
                        <strong>${heads.length}</strong> office heads in this building
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            `;
            
            heads.forEach(head => {
                html += `
                    <div class="head-card rounded-xl p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                ${head.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-lg">${head.name}</h4>
                                ${head.title ? `<p class="text-green-600 text-sm font-medium">${head.title}</p>` : ''}
                                <div class="mt-2 flex items-center gap-2 text-gray-500 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                                    </svg>
                                    ${head.officeName}
                                </div>
                                ${head.floor ? `
                                    <div class="flex items-center gap-2 text-gray-400 text-xs mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        Floor ${head.floor}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += `</div>`;
        }
        
        document.getElementById('panel-heads').innerHTML = html;
    }
    
    function switchDetailsTab(tabName) {
        const index = tabNames.indexOf(tabName);
        if (index !== -1) {
            currentDetailsTab = index;
            updateTabUI();
        }
    }
    
    function updateTabUI() {
        // Show/hide panels (simple approach - no sliding, guaranteed scrolling)
        tabNames.forEach((name, index) => {
            const panel = document.getElementById(`panel-${name}`);
            if (index === currentDetailsTab) {
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        });
        
        // Update tab buttons
        tabNames.forEach((name, index) => {
            const tab = document.getElementById(`tab-${name}`);
            if (index === currentDetailsTab) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
        
        // Update swipe dots
        document.querySelectorAll('.swipe-dot').forEach((dot, index) => {
            if (index === currentDetailsTab) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
        
        // Update current tab label
        document.getElementById('currentTabLabel').textContent = tabLabels[currentDetailsTab];
        
        // Update prev/next buttons
        const prevBtn = document.getElementById('prevTabBtn');
        const nextBtn = document.getElementById('nextTabBtn');
        
        if (currentDetailsTab === 0) {
            prevBtn.disabled = true;
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevBtn.disabled = false;
            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        
        if (currentDetailsTab === tabNames.length - 1) {
            nextBtn.innerHTML = `Close <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
            nextBtn.onclick = closeBuildingDetailsModal;
        } else {
            nextBtn.innerHTML = `Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>`;
            nextBtn.onclick = nextDetailsTab;
        }
    }
    
    function prevDetailsTab() {
        if (currentDetailsTab > 0) {
            currentDetailsTab--;
            updateTabUI();
        }
    }
    
    function nextDetailsTab() {
        if (currentDetailsTab < tabNames.length - 1) {
            currentDetailsTab++;
            updateTabUI();
        }
    }
    
    function setupSwipeGestures() {
        // ONLY attach swipe detection to the tab slider header area, NOT the content
        // This allows normal scrolling in the content panels
        
        // Swipe dot clicks
        document.querySelectorAll('.swipe-dot').forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentDetailsTab = index;
                updateTabUI();
            });
        });
        
        // Keyboard navigation for accessibility
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('buildingDetailsModal');
            if (modal && !modal.classList.contains('hidden')) {
                if (e.key === 'ArrowLeft') {
                    prevDetailsTab();
                } else if (e.key === 'ArrowRight') {
                    nextDetailsTab();
                }
            }
        });
    }
    
    function closeBuildingDetailsModal(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('buildingDetailsModal').classList.add('hidden');
        currentBuildingData = null;
    }
    
    function clearNavigationPath() {
        const svg = document.getElementById('campusMap');
        if (!svg) return;
        
        // Remove navigation path and markers
        const navPath = document.getElementById('navPath');
        if (navPath) navPath.remove();
        
        const navMarkers = document.getElementById('navMarkers');
        if (navMarkers) navMarkers.remove();
        
        // Hide walking time badge
        hideWalkingTime();
    }
    function showBuildingNotAvailable(buildingName) {
        // Hide legend, show details view
        document.getElementById('legendView').style.display = 'none';
        document.getElementById('buildingDetailsView').style.display = 'flex';
        
        document.getElementById('buildingDetailTitle').textContent = buildingName;
        document.getElementById('buildingDetailContent').innerHTML = `
            <div class="text-center py-12">
                <div class="mb-6">
                    <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2" style="color: #248823;">${buildingName}</h3>
                <p class="text-gray-600 mb-4">Information for this building is not yet available</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 max-w-md mx-auto">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold" style="color: #248823;">📝 Coming Soon</span><br>
                        Building details and office information will be added to the system shortly.
                    </p>
                </div>
            </div>
        `;
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
    
    // ==================== 3D MODE TOGGLE ====================
    let is3DMode = false;
    
    function toggle3DMode() {
        const mapContainer = document.getElementById('mapContainer');
        const toggleBtn = document.getElementById('toggle3DBtn');
        const toggleText = document.getElementById('toggle3DText');
        
        is3DMode = !is3DMode;
        
        if (is3DMode) {
            mapContainer.classList.add('map-3d-mode');
            toggleBtn.classList.add('active');
            toggleText.textContent = '2D VIEW';
        } else {
            mapContainer.classList.remove('map-3d-mode');
            toggleBtn.classList.remove('active');
            toggleText.textContent = '3D VIEW';
        }
        
        // Reset idle timer on interaction
        resetIdleTimer();
    }
    
    function navigateTo(buildingName, showPopup = false) {
        closeModal();
        closeBuildingPreview();
        
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
        
        // Show building preview popup only if showPopup is true (when clicking on map)
        if (showPopup) {
            const svgId = Object.keys(svgToDisplayName).find(key => 
                svgToDisplayName[key] === buildingName || key === navKey
            );
            
            if (svgId) {
                // Use building code (from fullinfo.json building_id) to match
                const building = buildings.find(b => b.code === svgId);
                if (building) {
                    showBuildingModal(building.id);
                } else {
                    showBuildingNotAvailable(svgToDisplayName[svgId] || buildingName);
                }
            }
        }
    }
    
    // Enhanced interactivity: Keyboard shortcuts and click-outside-to-close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBuildingDetailsModal();
            closeBuildingPreview();
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
        // Draw the road skeleton overlay (disabled - only for debugging)
        // drawRoadSkeleton();
        
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
        
        // Show debug points on page load if enabled
        if (showNavigationPoints) {
            showDebugPoints();
        }
    });
    
    // DEBUG MODE: Show all navigation points and road intersections
    const showNavigationPoints = false;
    
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
            'spine_south_left': {x: 188, y: 240},
            'spine_south': {x: 202.28, y: 226},
            'spine_south_210': {x: 188, y: 210},
            'spine_center': {x: 188.32, y: 167.47},
            'spine_north_130': {x: 188.32, y: 130},
            'spine_north': {x: 188.32, y: 111},
            
            // === CENTER HORIZONTAL ROAD (y≈167.47-167.95) - MAIN EAST-WEST ARTERY ===
            // path-4: M15.007 167.47 → 184.517 (15.007 + 169.51 = 184.517)
            'horiz_west_end': {x: 15.007, y: 167.47},
            'horiz_30': {x: 30.031, y: 167.477},
            'horiz_70': {x: 70, y: 167.47},
            'horiz_100': {x: 100, y: 167.47},
            'horiz_134': {x: 134.048, y: 167.47},
            'horiz_162': {x: 162.074, y: 167.47},
            // path-5: M200.776 168.003 → 286.339 (200.776 + 85.563 = 286.339)
            // path-6: M252.48 167.945 (eastern vertical intersects here)
            'horiz_202': {x: 202.28, y: 168.003},
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
            
            // === NORTHERN HORIZONTAL CONNECTORS (y≈110.939-111.071) ===
            // path-1-3: M159.412 51.001 with transform(43.417, 59.938) → (202.829, 110.939)
            // path-101-5-2-3-6-8-7-6-2: M161.494 110.994 → 184.906
            // path-101-5-2-3-6-8-7-2: M203.193 111.071 → 246.811
            'north_162': {x: 161.494, y: 110.994},
            'north_180': {x: 188.32, y: 110.994},
            'north_203': {x: 202.28, y: 111.071},
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
            'dirt_north_208': {x: 220, y: 55.093},
            'dirt_north_corner': {x: 238, y: 55.093},
            'dirt_north_top': {x: 238, y: 21.093},
            'dirt_north_end': {x: 220, y: 21.093},
            
            // === ROUNDABOUT / CIRCLE ===
            // Points matching exact SVG circular path coordinates
            'r_entry': {x: 206, y: 55},              // Entry from spine
            'r_north': {x: 184.243, y: 55},          // North point
            'r_west': {x: 178, y: 67},               // West point
            'r_sw': {x: 180, y: 77},                 // Southwest point
            'r_exit': {x: 188.32, y: 84.558},        // Exit point
            'r_se': {x: 209.55, y: 77},              // Southeast point
            'r_east': {x: 212, y: 67},               // East point
            'r_entry_right': {x: 202.28, y: 84.558}, // Right entry point
            
            // === SMALL CONNECTORS ===
            // path-1-2: M200.864 225.943 → 220.232 (200.864 + 19.368 = 220.232)
            // Using spine_south (202.28, 226) instead of redundant conn_201_226
            'conn_220_226': {x: 220.232, y: 225.763}
        },
        
        roads: [
            // === MAIN VERTICAL SPINE (Complete north-south) ===
            ['gate', 'spine_gate'],
            // Right spine road
            ['spine_gate', 'spine_south_lower'],
            ['spine_south_lower', 'spine_south'],
            // Left spine road
            ['spine_gate', 'spine_south_left'],
            ['spine_south_left', 'spine_south_210'],
            // Both merge to center
            ['spine_south', 'spine_south_210'],
            ['spine_south_210', 'spine_center'],
            ['spine_center', 'spine_north_130'],
            ['spine_north_130', 'spine_north'],
            ['spine_north', 'r_exit'],
            
            // === CENTER HORIZONTAL (Complete east-west at y≈167) ===
            ['horiz_west_end', 'horiz_30'],
            ['horiz_30', 'horiz_70'],
            ['horiz_70', 'horiz_100'],
            ['horiz_100', 'horiz_134'],
            ['horiz_134', 'horiz_162'],
            ['horiz_162', 'spine_center'],
            ['spine_center', 'horiz_202'],
            ['horiz_202', 'north_203'],
            ['horiz_202', 'horiz_220'],
            ['horiz_220', 'horiz_252'],
            ['horiz_252', 'horiz_east_end'],
            
            // === SOUTHERN HORIZONTAL (Complete at y≈203) ===
            ['south_west_end', 'south_30'],
            ['south_30', 'south_46'],
            ['south_46', 'south_70'],
            ['south_70', 'south_100'],
            ['south_100', 'south_134'],
            
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
            ['conn_134_185', 'horiz_134'],
            
            // === CROSS CONNECTIONS (Horizontal connectors) ===
            ['horiz_162', 'west_south'],
            ['horiz_30', 'far_west_south'],
            ['horiz_252', 'east_south'],
            ['south_30', 'far_west_south'],
            ['spine_south_210', 'spine_center'],
            
            // === NORTHERN HORIZONTAL CONNECTORS (y≈111) ===
            ['west_north', 'north_162'],
            ['north_162', 'north_180'],
            ['north_180', 'spine_north'],
            ['spine_north', 'north_203'],
            ['north_203', 'r_entry_right'],
            ['north_203', 'north_220'],
            ['north_203', 'east_north'],
            ['north_220', 'north_246'],
            ['north_220', 'east_north'], // Direct bypass to eastern route
            ['north_246', 'east_north'],
            
            // === SMALL CONNECTORS ===
            ['spine_south', 'conn_220_226'],
            
            // === VERTICAL CONNECTOR for Graduate School ===
            ['spine_south', 'horiz_202'],
            
            // === DIRT PATHS ===
            ['dirt_start', 'dirt_corner_1'],
            ['dirt_corner_1', 'dirt_horiz_244'],
            ['dirt_horiz_244', 'dirt_corner_2'],
            ['dirt_corner_2', 'dirt_vert'],
            ['dirt_vert', 'dirt_horiz_end'],
            ['r_entry', 'dirt_north_208'],
            ['dirt_north_208', 'dirt_north_corner'],
            ['dirt_north_corner', 'dirt_north_top'],
            ['dirt_north_top', 'dirt_north_end'],
            
            // === ROUNDABOUT CONNECTIONS - Following circular path ===
            // Left side (from exit): r_exit → r_sw → r_west → r_north → r_entry
            ['r_exit', 'r_sw'],
            ['r_sw', 'r_west'],
            ['r_west', 'r_north'],
            ['r_north', 'r_entry'],
            // Right side (from entry_right): r_entry_right → r_se → r_east → r_entry
            ['r_entry_right', 'r_se'],
            ['r_se', 'r_east'],
            ['r_east', 'r_entry'],
            // Connect exit to entry for full loop
            ['r_exit', 'spine_north'],
            
            // Exits to destinations (connect to nearest points)
            ['r_west', 'west_top']
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
            
            // Skip invalid coordinates
            if (curr.x === undefined || curr.y === undefined || prev.x === undefined || prev.y === undefined) {
                continue;
            }
            
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

    function showDebugPoints() {
        const svg = document.getElementById('campusMap');
        if (!svg) return;
        
        // Remove existing debug points if any
        const existingDebug = document.getElementById('debugNavigationPoints');
        if (existingDebug) existingDebug.remove();
        
        const debugGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        debugGroup.setAttribute('id', 'debugNavigationPoints');
        
        // Show all building navigation endpoints
        Object.keys(navigationPoints).forEach(buildingCode => {
            const navPoint = navigationPoints[buildingCode];
            
            // Endpoint circle (blue)
            const endpointCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            endpointCircle.setAttribute('cx', navPoint.x);
            endpointCircle.setAttribute('cy', navPoint.y);
            endpointCircle.setAttribute('r', '2.5');
            endpointCircle.setAttribute('fill', '#3b82f6');
            endpointCircle.setAttribute('stroke', '#fff');
            endpointCircle.setAttribute('stroke-width', '0.8');
            endpointCircle.setAttribute('opacity', '0.7');
            debugGroup.appendChild(endpointCircle);
            
            // Building code label
            const codeLabel = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            codeLabel.setAttribute('x', navPoint.x + 3);
            codeLabel.setAttribute('y', navPoint.y + 1);
            codeLabel.setAttribute('font-size', '2.5');
            codeLabel.setAttribute('fill', '#1e40af');
            codeLabel.setAttribute('font-weight', 'bold');
            codeLabel.textContent = buildingCode;
            debugGroup.appendChild(codeLabel);
        });
        
        // Show all road intersections (green)
        Object.keys(roadNetwork.intersections).forEach(intersectionName => {
            const intersection = roadNetwork.intersections[intersectionName];
            
            const intersectionCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            intersectionCircle.setAttribute('cx', intersection.x);
            intersectionCircle.setAttribute('cy', intersection.y);
            intersectionCircle.setAttribute('r', '1.5');
            intersectionCircle.setAttribute('fill', '#10b981');
            intersectionCircle.setAttribute('stroke', '#fff');
            intersectionCircle.setAttribute('stroke-width', '0.5');
            intersectionCircle.setAttribute('opacity', '0.6');
            debugGroup.appendChild(intersectionCircle);
            
            // Intersection name label (small)
            const nameLabel = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            nameLabel.setAttribute('x', intersection.x + 2);
            nameLabel.setAttribute('y', intersection.y - 2);
            nameLabel.setAttribute('font-size', '2');
            nameLabel.setAttribute('fill', '#065f46');
            nameLabel.setAttribute('font-weight', 'normal');
            nameLabel.textContent = intersectionName.replace('roundabout_', 'r_').replace('spine_', 's_').replace('horiz_', 'h_');
            debugGroup.appendChild(nameLabel);
        });
        
        svg.appendChild(debugGroup);
        
        // console.log('🔍 DEBUG MODE: Showing all navigation points and intersections');
        // console.log('  🔵 Blue dots = Building navigation endpoints');
        // console.log('  🟢 Green dots = Road intersection points');
    }

    function drawNavigationPath(buildingName) {
        const svg = document.getElementById('campusMap');
        
        // Remove existing navigation elements
        const existingPath = document.getElementById('navPath');
        if (existingPath) existingPath.remove();
        const existingMarkers = document.getElementById('navMarkers');
        if (existingMarkers) existingMarkers.remove();
        const existingDebugPath = document.getElementById('debugRoadPath');
        if (existingDebugPath) existingDebugPath.remove();

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
        
        // console.log('\n🗺️ ===== NAVIGATION PATH DEBUG =====');
        // console.log('Building:', buildingName);
        // console.log('Start:', startIntersection, '→', roadNetwork.intersections[startIntersection]);
        // console.log('End:', endIntersection, '→', roadNetwork.intersections[endIntersection]);
        // console.log('Path found:', intersectionPath);
        // console.log('Path length:', intersectionPath.length, 'intersections');
        
        if (!intersectionPath || intersectionPath.length === 0) {
            console.error('❌ No path found from', startIntersection, 'to', endIntersection);
            return;
        }
        
        // Show road network segments (intersection to intersection)
        // console.log('\n📍 ROAD NETWORK SEGMENTS:');
        const roundaboutIntersections = [];
        for (let i = 0; i < intersectionPath.length; i++) {
            const name = intersectionPath[i];
            const coords = roadNetwork.intersections[name];
            
            // if (i < intersectionPath.length - 1) {
            //     const nextName = intersectionPath[i + 1];
            //     const nextCoords = roadNetwork.intersections[nextName];
            //     console.log(`  ${i}. ${name} (${coords.x.toFixed(2)}, ${coords.y.toFixed(2)}) → ${nextName} (${nextCoords.x.toFixed(2)}, ${nextCoords.y.toFixed(2)})`);
            // } else {
            //     console.log(`  ${i}. ${name} (${coords.x.toFixed(2)}, ${coords.y.toFixed(2)}) [END]`);
            // }
            
            if (name.includes('roundabout')) {
                roundaboutIntersections.push(name);
            }
        }
        
        // if (roundaboutIntersections.length > 0) {
        //     console.log('\n🔄 ROUNDABOUT SEGMENTS:', roundaboutIntersections.length);
        //     console.log('   ', roundaboutIntersections.join(' → '));
        // }
        
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
        
        // console.log('\n📊 NAVIGATION PATH SEGMENTS (before cleaning):');
        // for (let i = 0; i < segments.length; i++) {
        //     if (i < segments.length - 1) {
        //         const curr = segments[i];
        //         const next = segments[i + 1];
        //         const dist = Math.sqrt(Math.pow(next.x - curr.x, 2) + Math.pow(next.y - curr.y, 2));
        //         console.log(`  ${i}. (${curr.x.toFixed(2)}, ${curr.y.toFixed(2)}) → (${next.x.toFixed(2)}, ${next.y.toFixed(2)}) [${dist.toFixed(2)} units]`);
        //     } else {
        //         console.log(`  ${i}. (${segments[i].x.toFixed(2)}, ${segments[i].y.toFixed(2)}) [DESTINATION]`);
        //     }
        // }
        
        // Remove duplicate consecutive points
        const cleanedSegments = [segments[0]];
        for (let i = 1; i < segments.length; i++) {
            const prev = cleanedSegments[cleanedSegments.length - 1];
            const curr = segments[i];
            if (prev.x !== curr.x || prev.y !== curr.y) {
                cleanedSegments.push(curr);
            }
        }
        
        // console.log('\n🧹 AFTER CLEANING (duplicates removed):', cleanedSegments.length, 'points');
        // cleanedSegments.forEach((seg, idx) => {
        //     console.log(`  ${idx}. (${seg.x.toFixed(2)}, ${seg.y.toFixed(2)})`);
        // });
        
        // DISABLED: Orthogonal routing - preserve natural curves
        // const orthogonalSegments = enforceOrthogonalPath(cleanedSegments);
        const orthogonalSegments = cleanedSegments;
        
        // console.log('\n🔲 USING DIRECT PATH (orthogonal disabled):', orthogonalSegments.length, 'points');
        // orthogonalSegments.forEach((seg, idx) => {
        //     console.log(`  ${idx}. (${seg.x.toFixed(2)}, ${seg.y.toFixed(2)})`);
        // });
        
        // Build smooth continuous path with rounded corners
        let pathData = `M ${orthogonalSegments[0].x} ${orthogonalSegments[0].y}`;
        
        // Create smooth path with quadratic curves at corners
        for (let i = 1; i < orthogonalSegments.length; i++) {
            const prev = orthogonalSegments[i - 1];
            const curr = orthogonalSegments[i];
            const next = orthogonalSegments[i + 1];
            
            if (next && i < orthogonalSegments.length - 1) {
                // Calculate corner radius (smaller for tighter corners)
                const cornerRadius = 8;
                
                // Determine if we're at a corner
                const isCorner = (prev.x !== curr.x && curr.x !== next.x) || 
                                (prev.y !== curr.y && curr.y !== next.y);
                
                if (isCorner) {
                    // Calculate direction vectors
                    const dx1 = curr.x - prev.x;
                    const dy1 = curr.y - prev.y;
                    const dx2 = next.x - curr.x;
                    const dy2 = next.y - curr.y;
                    
                    // Calculate distances
                    const dist1 = Math.sqrt(dx1 * dx1 + dy1 * dy1);
                    const dist2 = Math.sqrt(dx2 * dx2 + dy2 * dy2);
                    
                    // Calculate points before and after corner
                    const radius = Math.min(cornerRadius, dist1 / 2, dist2 / 2);
                    
                    const beforeX = curr.x - (dx1 / dist1) * radius;
                    const beforeY = curr.y - (dy1 / dist1) * radius;
                    const afterX = curr.x + (dx2 / dist2) * radius;
                    const afterY = curr.y + (dy2 / dist2) * radius;
                    
                    // Line to point before corner, then quadratic curve through corner
                    pathData += ` L ${beforeX} ${beforeY}`;
                    pathData += ` Q ${curr.x} ${curr.y} ${afterX} ${afterY}`;
                } else {
                    pathData += ` L ${curr.x} ${curr.y}`;
                }
            } else {
                // Last point - just draw straight line
                pathData += ` L ${curr.x} ${curr.y}`;
            }
        }
        
        // Calculate total path length for walking time and animation
        let totalPathLength = 0;
        for (let i = 1; i < orthogonalSegments.length; i++) {
            const prev = orthogonalSegments[i - 1];
            const curr = orthogonalSegments[i];
            totalPathLength += Math.sqrt(Math.pow(curr.x - prev.x, 2) + Math.pow(curr.y - prev.y, 2));
        }
        
        // Create navigation path - thin smooth red line
        const navPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        navPath.setAttribute('id', 'navPath');
        navPath.setAttribute('d', pathData);
        navPath.setAttribute('fill', 'none');
        navPath.setAttribute('stroke', '#ef4444');
        navPath.setAttribute('stroke-width', '2.5');
        navPath.setAttribute('stroke-linecap', 'round');
        navPath.setAttribute('stroke-linejoin', 'round');
        navPath.setAttribute('opacity', '0.9');
        navPath.setAttribute('filter', 'drop-shadow(0 1px 2px rgba(239, 68, 68, 0.3))');
        navPath.setAttribute('style', 'pointer-events: none;');
        
        // Add path animation (unless reduced motion is enabled)
        if (!window.reducedMotion) {
            const pathLen = navPath.getTotalLength ? navPath.getTotalLength() : totalPathLength * 3;
            navPath.style.setProperty('--path-length', pathLen);
            navPath.setAttribute('stroke-dasharray', pathLen);
            navPath.setAttribute('stroke-dashoffset', pathLen);
            navPath.classList.add('animated-path');
        }
        
        svg.appendChild(navPath);
        
        // Show walking time
        showWalkingTime(totalPathLength);
        
        // Create markers group
        const markersGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        markersGroup.setAttribute('id', 'navMarkers');
        
        // Start marker (main gate) - smaller refined green dot
        const startMarker = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        startMarker.setAttribute('cx', kioskX);
        startMarker.setAttribute('cy', kioskY);
        startMarker.setAttribute('r', '5');
        startMarker.setAttribute('fill', '#10b981');
        startMarker.setAttribute('stroke', '#fff');
        startMarker.setAttribute('stroke-width', '2');
        startMarker.setAttribute('filter', 'drop-shadow(0 1px 3px rgba(16, 185, 129, 0.4))');
        markersGroup.appendChild(startMarker);
        
        // Destination marker - smaller refined red circle with pulsing animation
        const destMarker = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        destMarker.setAttribute('cx', point.x);
        destMarker.setAttribute('cy', point.y);
        destMarker.setAttribute('r', '4');
        destMarker.setAttribute('fill', '#ef4444');
        destMarker.setAttribute('stroke', '#fff');
        destMarker.setAttribute('stroke-width', '1.5');
        destMarker.setAttribute('filter', 'drop-shadow(0 1px 3px rgba(239, 68, 68, 0.4))');
        destMarker.setAttribute('class', 'endpoint-marker');
        destMarker.dataset.buildingName = buildingName; // Store building name for drag functionality
        markersGroup.appendChild(destMarker);
        
        // Pulsing outer ring for destination
        const pulseRing = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        pulseRing.setAttribute('cx', point.x);
        pulseRing.setAttribute('cy', point.y);
        pulseRing.setAttribute('r', '4');
        pulseRing.setAttribute('fill', 'none');
        pulseRing.setAttribute('stroke', '#ef4444');
        pulseRing.setAttribute('stroke-width', '1');
        pulseRing.setAttribute('opacity', '0.6');
        markersGroup.appendChild(pulseRing);
        
        // Animate the pulse ring
        const animate = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
        animate.setAttribute('attributeName', 'r');
        animate.setAttribute('from', '6');
        animate.setAttribute('to', '12');
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
        
        // Smart label positioning - check if near edges and adjust accordingly
        let labelY = point.y - 14; // Default: above the marker
        let labelX = point.x;
        
        // If near top edge (y < 30), place label below the marker
        if (point.y < 30) {
            labelY = point.y + 20;
        }
        
        // If near left edge (x < 50), shift label to the right
        if (point.x < 50) {
            labelX = point.x + 25;
        }
        
        // If near right edge (x > 260), shift label to the left
        if (point.x > 260) {
            labelX = point.x - 25;
        }
        
        // If near bottom edge (y > 250), place label above
        if (point.y > 250) {
            labelY = point.y - 20;
        }
        
        labelText.setAttribute('x', labelX);
        labelText.setAttribute('y', labelY);
        labelText.setAttribute('text-anchor', 'middle');
        labelText.setAttribute('font-size', '9');
        labelText.setAttribute('font-weight', 'bold');
        labelText.setAttribute('fill', '#fff');
        labelText.setAttribute('class', 'endpoint-label');
        labelText.setAttribute('style', 'pointer-events: none;');
        
        // Measure text for background
        svg.appendChild(labelText);
        const bbox = labelText.getBBox();
        
        labelBg.setAttribute('x', bbox.x - 5);
        labelBg.setAttribute('y', bbox.y - 2);
        labelBg.setAttribute('width', bbox.width + 10);
        labelBg.setAttribute('height', bbox.height + 4);
        labelBg.setAttribute('fill', '#248823');
        labelBg.setAttribute('rx', '4');
        labelBg.setAttribute('opacity', '0.95');
        labelBg.setAttribute('filter', 'drop-shadow(0 2px 4px rgba(36, 136, 35, 0.4))');
        
        markersGroup.appendChild(labelBg);
        markersGroup.appendChild(labelText);
        
        svg.appendChild(markersGroup);
        
        // Refresh debug points if enabled
        if (showNavigationPoints) {
            showDebugPoints();
        }
        
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

    // Refresh CSRF token periodically to prevent 419 errors on kiosk
    function refreshCsrfToken() {
        fetch('/refresh-csrf', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.csrf_token) {
                // Update all CSRF token inputs
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.csrf_token;
                });
                console.log('CSRF token refreshed successfully');
            }
        })
        .catch(error => {
            console.error('Error refreshing CSRF token:', error);
        });
    }

    // Refresh CSRF token every 90 minutes (before the 120-minute session expires)
    setInterval(refreshCsrfToken, 90 * 60 * 1000);
    
    // Also refresh on page visibility change (when user returns to kiosk)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            refreshCsrfToken();
        }
    });
</script>
@endsection


