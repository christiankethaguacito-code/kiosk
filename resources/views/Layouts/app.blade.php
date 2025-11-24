<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Campus Directory Kiosk')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('head')
    <style>
            body { margin:0; font-family: Arial, sans-serif; }

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
                transition: margin-right 0.3s ease;
                position: relative;
            }
            
            .map-container.panel-open {
                margin-right: 350px;
            }

            .svg-map-wrapper {
                width: 850px;
                height: 600px;
                border: 2px solid #ccc;
                background-color: #fff;
                overflow: hidden;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                flex-shrink: 0;
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

            /* Side panel */
            .side-panel {
                position: fixed;
                top: 0;
                right: -700px;
                width: 700px;
                height: 100%;
                background: #f9f9fa;
                box-shadow: -2px 0 10px rgba(0,0,0,0.3);
                transition: right 0.3s ease;
                padding: 2rem;
                box-sizing: border-box;
                z-index: 1050;
                overflow-y: auto;
            }
            .side-panel.active { right: 0; }
            .side-panel header { display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem; }
            .close-btn { background:none; border:none; font-size:24px; cursor:pointer; }
    </style>
<body class="@yield('body-class', 'bg-gray-100')">
    @yield('content')
    @yield('scripts')
</body>
</html>
