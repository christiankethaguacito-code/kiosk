<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SKSU Campus Directory Kiosk')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
            -webkit-user-select: none;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            touch-action: manipulation;
        }
        
        ::-webkit-scrollbar {
            display: none;
        }
        
        button, a {
            -webkit-tap-highlight-color: rgba(16, 185, 129, 0.3);
            tap-highlight-color: rgba(16, 185, 129, 0.3);
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
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease;
        }
        
        .animate-slideUp {
            animation: slideUp 0.5s ease;
        }
        
        .touch-button {
            min-width: 80px;
            min-height: 60px;
            font-size: 1.125rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .touch-button:active {
            transform: scale(0.95);
        }
        
        @yield('styles')
    </style>
</head>
<body class="@yield('body-class', 'bg-gray-100')">
    @yield('content')
    
    <script>
        let inactivityTimer;
        const INACTIVITY_TIMEOUT = 120000; // 2 minutes
        
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                if (window.location.pathname !== '/') {
                    window.location.href = '/';
                }
            }, INACTIVITY_TIMEOUT);
        }
        
        document.addEventListener('touchstart', resetInactivityTimer);
        document.addEventListener('click', resetInactivityTimer);
        document.addEventListener('keypress', resetInactivityTimer);
        
        resetInactivityTimer();
    </script>
    
    @yield('scripts')
</body>
</html>
