@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Announcements</h1>
        <a href="{{ route('admin.announcements.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg">
            Add New Announcement
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($announcements as $announcement)
                    <tr>
                        <td class="px-6 py-4">
                            @if($announcement->image_path)
                                <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-400">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-900">{{ $announcement->title }}</td>
                        <td class="px-6 py-4">
                            @if($announcement->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Active</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No announcements found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
