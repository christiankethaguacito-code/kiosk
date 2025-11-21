<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::orderBy('name')->paginate(20);
        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:100|unique:buildings,code',
                'name' => 'required|string|max:150',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'map_x' => 'required|integer|min:0',
                'map_y' => 'required|integer|min:0',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('buildings', 'public');
            }

            Building::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'map_x' => $validated['map_x'],
                'map_y' => $validated['map_y'],
            ]);

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building information added successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create building: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:100|unique:buildings,code,' . $building->id,
                'name' => 'required|string|max:150',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'map_x' => 'required|integer|min:0',
                'map_y' => 'required|integer|min:0',
            ]);

            $imagePath = $building->image_path;
            if ($request->hasFile('image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('buildings', 'public');
            }

            $building->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'map_x' => $validated['map_x'],
                'map_y' => $validated['map_y'],
            ]);

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building information updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update building: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Building $building)
    {
        try {
            if ($building->image_path && Storage::disk('public')->exists($building->image_path)) {
                Storage::disk('public')->delete($building->image_path);
            }

            $building->delete();

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete building: ' . $e->getMessage());
        }
    }
}