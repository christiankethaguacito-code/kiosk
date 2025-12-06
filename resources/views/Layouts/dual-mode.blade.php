<!DOCTYPE html>
<html lang="en" x-data="{ editMode: false, menuOpen: false, currentTime: '' }" 
      x-init="setInterval(() => { 
          const now = new Date(); 
          currentTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); 
      }, 1000)">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Campus Kiosk')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        .draggable { cursor: move; }
        .dragging { opacity: 0.5; }
        .editable-text:hover { outline: 2px dashed #3b82f6; }
        
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .toast.success { background: #10b981; }
        .toast.error { background: #ef4444; }
    </style>
    @yield('styles')
</head>
<body class="@yield('body-class', 'bg-gray-100')" x-data="{ loading: false, toast: null }">
    <div x-show="loading" x-cloak class="loading-overlay">
        <div class="spinner"></div>
    </div>
    
    <div x-show="toast" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:leave="transition ease-in duration-200"
         :class="toast?.type === 'success' ? 'toast success' : 'toast error'"
         x-text="toast?.message">
    </div>

    @auth
    <div class="fixed bottom-4 right-4 z-50">
        <button @click="editMode = !editMode" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition">
            <span x-show="!editMode">🔒 Enable Edit Mode</span>
            <span x-show="editMode" x-cloak>✏️ Exit Edit Mode</span>
        </button>
    </div>
    @endauth

    @yield('content')

    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function showLoading() {
            Alpine.store('app', { loading: true });
            document.body.__x.$data.loading = true;
        }
        
        function hideLoading() {
            document.body.__x.$data.loading = false;
        }
        
        function showToast(message, type = 'success') {
            document.body.__x.$data.toast = { message, type };
            setTimeout(() => {
                document.body.__x.$data.toast = null;
            }, 3000);
        }
        
        async function ajaxPost(url, data) {
            showLoading();
            try {
                const formData = new FormData();
                for (let key in data) {
                    formData.append(key, data[key]);
                }
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': window.csrfToken },
                    body: formData
                });
                
                hideLoading();
                
                if (!response.ok) {
                    const error = await response.json();
                    showToast(error.message || 'Request failed', 'error');
                    throw new Error(error.message || 'Request failed');
                }
                
                const result = await response.json();
                showToast('Changes saved successfully!', 'success');
                return result;
            } catch (error) {
                hideLoading();
                console.error('AJAX Error:', error);
                throw error;
            }
        }
        
        async function ajaxDelete(url) {
            showLoading();
            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                hideLoading();
                
                if (!response.ok) {
                    const error = await response.json();
                    showToast(error.message || 'Delete failed', 'error');
                    throw new Error(error.message || 'Delete failed');
                }
                
                const result = await response.json();
                showToast('Deleted successfully!', 'success');
                return result;
            } catch (error) {
                hideLoading();
                console.error('AJAX Delete Error:', error);
                throw error;
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>
