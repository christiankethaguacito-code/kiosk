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
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_images' => 'nullable|array|max:10',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'map_x' => 'required|integer|min:0',
                'map_y' => 'required|integer|min:0',
            ]);

            $imagePath = null;
            if ($request->hasFile('image_path')) {
                $imagePath = $request->file('image_path')->store('buildings', 'public');
            }

            // Handle gallery images
            $galleryPaths = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $path = $image->store('buildings/gallery', 'public');
                    $galleryPaths[] = $path;
                }
            }

            Building::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'image_gallery' => $galleryPaths,
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
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_images' => 'nullable|array|max:10',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'map_x' => 'required|integer|min:0',
                'map_y' => 'required|integer|min:0',
            ]);

            $imagePath = $building->image_path;
            if ($request->hasFile('image_path')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image_path')->store('buildings', 'public');
            }

            // Handle gallery images - add to existing gallery
            $existingGallery = $building->image_gallery ?? [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $path = $image->store('buildings/gallery', 'public');
                    $existingGallery[] = $path;
                }
            }

            $building->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'image_gallery' => $existingGallery,
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

            // Delete all gallery images
            if ($building->image_gallery) {
                foreach ($building->image_gallery as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            $building->delete();

            return redirect()->route('admin.buildings.index')
                ->with('success', 'Building deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete building: ' . $e->getMessage());
        }
    }

    public function deleteGalleryImage(Building $building, $index)
    {
        try {
            $gallery = $building->image_gallery ?? [];
            
            if (!isset($gallery[$index])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found'
                ], 404);
            }

            $imagePath = $gallery[$index];
            
            // Delete file from storage
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Remove from array and reindex
            array_splice($gallery, $index, 1);
            $building->image_gallery = array_values($gallery);
            $building->save();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}