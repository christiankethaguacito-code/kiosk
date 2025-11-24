@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📢 Announcements</h1>
        <a href="{{ route('admin.announcements.create') }}" class="text-white px-6 py-3 rounded-lg text-lg font-semibold transition" style="background: linear-gradient(135deg, #248823 0%, #1a6619 100%);">
            + Add New Announcement
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title & Content</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Schedule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($announcements as $announcement)
                    <tr>
                        <td class="px-6 py-4">
                            @if($announcement->image_path)
                                <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gradient-to-br from-teal-100 to-green-100 rounded-lg flex items-center justify-center text-2xl">
                                    📢
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $announcement->title }}</div>
                            <div class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 80) }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold">
                                {{ $announcement->display_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($announcement->starts_at || $announcement->ends_at)
                                <div>{{ $announcement->starts_at?->format('M d, Y') ?? 'No start' }}</div>
                                <div class="text-xs">to {{ $announcement->ends_at?->format('M d, Y') ?? 'No end' }}</div>
                            @else
                                <span class="text-gray-400">Always active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($announcement->is_active)
                                <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: rgba(36, 136, 35, 0.2); color: #2d6a5f;">Active</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-semibold">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Delete this announcement?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-5xl mb-3">📢</div>
                            <div class="text-gray-500 mb-3">No announcements found</div>
                            <a href="{{ route('admin.announcements.create') }}" class="text-sm underline" style="color: #248823;">
                                Create your first announcement
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

