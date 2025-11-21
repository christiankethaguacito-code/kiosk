@extends('admin.layout')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Buildings</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_buildings'] }}</h3>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <span class="text-3xl">🏢</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Offices</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_offices'] }}</h3>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <span class="text-3xl">🏛️</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Announcements</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_announcements'] }}</h3>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <span class="text-3xl">📢</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
    <div class="flex gap-4 flex-wrap">
        <a href="{{ route('admin.buildings.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            ➕ Add New Building
        </a>
        <a href="{{ route('admin.offices.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            ➕ Add New Office
        </a>
        <a href="{{ route('admin.announcements.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            📢 Add Announcement
        </a>
        <a href="{{ route('admin.map_settings.edit') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            ⚙️ Update Map Data
        </a>
        <a href="{{ route('kiosk.map') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            🗺️ Open Visual Map Editor
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Buildings</h3>
        <div class="space-y-2">
            @forelse($buildings as $building)
                <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded">
                    <span class="font-medium">{{ $building->name }}</span>
                    <a href="{{ route('admin.buildings.edit', $building) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                </div>
            @empty
                <p class="text-gray-500">No buildings yet</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Offices</h3>
        <div class="space-y-2">
            @forelse($offices as $office)
                <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded">
                    <div>
                        <span class="font-medium">{{ $office->name }}</span>
                        <span class="text-sm text-gray-500 block">{{ $office->building->name }}</span>
                    </div>
                    <a href="{{ route('admin.offices.edit', $office) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                </div>
            @empty
                <p class="text-gray-500">No offices yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
