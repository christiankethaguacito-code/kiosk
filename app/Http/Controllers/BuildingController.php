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
        $buildings = Building::latest()->get();
        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('admin.buildings.form', ['building' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image_path' => 'nullable|image|max:2048',
            'map_x' => 'required|integer',
            'map_y' => 'required|integer',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('uploads', 'public');
            $validated['image_path'] = $path;
        }

        Building::create($validated);
        return redirect()->route('buildings.index')->with('success', 'Building created successfully.');
    }

    public function show(Building $building)
    {
        $building->load('offices.services');
        return view('admin.buildings.show', compact('building'));
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.form', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image_path' => 'nullable|image|max:2048',
            'map_x' => 'required|integer',
            'map_y' => 'required|integer',
        ]);

        if ($request->hasFile('image_path')) {
            if ($building->image_path) {
                Storage::disk('public')->delete($building->image_path);
            }
            $path = $request->file('image_path')->store('uploads', 'public');
            $validated['image_path'] = $path;
        }

        $building->update($validated);
        return redirect()->route('buildings.index')->with('success', 'Building updated successfully.');
    }

    public function destroy(Building $building)
    {
        if ($building->image_path) {
            Storage::disk('public')->delete($building->image_path);
        }
        $building->delete();
        return redirect()->route('buildings.index')->with('success', 'Building deleted successfully.');
    }
}