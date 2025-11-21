<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use App\Models\Service;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InlineController extends Controller
{
    public function updateBuildingCoordinates(Request $request, Building $building)
    {
        $validated = $request->validate([
            'map_x' => 'required|numeric',
            'map_y' => 'required|numeric',
        ]);

        $building->update([
            'endpoint_x' => $validated['map_x'],
            'endpoint_y' => $validated['map_y'],
        ]);

        return response()->json(['success' => true, 'building' => $building]);
    }

    public function updateBuildingName(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $building->update($validated);

        return response()->json(['success' => true, 'building' => $building]);
    }

    public function updateBuildingImage(Request $request, Building $building)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($building->image_path) {
            Storage::disk('public')->delete($building->image_path);
        }

        $path = $request->file('image')->store('buildings', 'public');
        $building->update(['image_path' => $path]);

        return response()->json(['success' => true, 'image_url' => Storage::url($path)]);
    }

    public function updateOfficeName(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $office->update($validated);

        return response()->json(['success' => true, 'office' => $office]);
    }

    public function updateOfficeHead(Request $request, Office $office)
    {
        $validated = $request->validate([
            'head_name' => 'nullable|string|max:255',
            'head_title' => 'nullable|string|max:255',
        ]);

        $office->update($validated);

        return response()->json(['success' => true, 'office' => $office]);
    }

    public function addService(Request $request, $officeId)
    {
        $office = Office::findOrFail($officeId);
        
        $validated = $request->validate([
            'description' => 'required|string|max:500',
        ]);

        $service = $office->services()->create($validated);

        return response()->json(['success' => true, 'service' => $service]);
    }

    public function deleteService(Service $service)
    {
        $service->delete();

        return response()->json(['success' => true]);
    }

    public function updateAnnouncementImage(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        $path = $request->file('image')->store('announcements', 'public');
        $announcement->update(['image_path' => $path]);

        return response()->json(['success' => true, 'image_url' => Storage::url($path)]);
    }

    public function deleteAnnouncement(Announcement $announcement)
    {
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        $announcement->delete();

        return response()->json(['success' => true]);
    }

    public function createBuilding(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'map_x' => 'required|numeric',
            'map_y' => 'required|numeric',
        ]);

        $building = Building::create([
            'name' => $validated['name'],
            'endpoint_x' => $validated['map_x'],
            'endpoint_y' => $validated['map_y'],
        ]);

        return response()->json(['success' => true, 'building' => $building]);
    }

    public function createOffice(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor_number' => 'nullable|integer',
        ]);

        $office = $building->offices()->create($validated);

        return response()->json(['success' => true, 'office' => $office]);
    }

    public function createAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('announcements', 'public');

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'image_path' => $path,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'announcement' => $announcement]);
    }
}
