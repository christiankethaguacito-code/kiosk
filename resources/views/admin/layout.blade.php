<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Campus Kiosk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <aside class="w-64 bg-green-600 text-white">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Campus Kiosk</h2>
                <p class="text-green-200 text-sm">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-3 px-6 hover:bg-green-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-700' : '' }}">
                    <span class="text-lg">📊 Dashboard</span>
                </a>
                <a href="{{ route('buildings.index') }}" class="block py-3 px-6 hover:bg-green-700 {{ request()->routeIs('buildings.*') ? 'bg-green-700' : '' }}">
                    <span class="text-lg">🏢 Buildings</span>
                </a>
                <a href="{{ route('offices.index') }}" class="block py-3 px-6 hover:bg-green-700 {{ request()->routeIs('offices.*') ? 'bg-green-700' : '' }}">
                    <span class="text-lg">🏛️ Offices</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-8 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600">{{ auth()->user()->email }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
