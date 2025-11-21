<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Building;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::with('building')->orderBy('name')->paginate(20);
        return view('admin.offices.index', compact('offices'));
    }

    public function create()
    {
        $buildings = Building::orderBy('name')->get();
        return view('admin.offices.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'name' => 'required|string|max:150',
                'floor_number' => 'nullable|integer|min:0',
                'head_name' => 'required|string|max:100',
                'head_title' => 'required|string|max:100',
                'head_credentials' => 'nullable|string',
                'services' => 'nullable|array',
                'services.*' => 'nullable|string|max:255',
            ]);

            $services = array_filter($validated['services'] ?? []);

            Office::create([
                'building_id' => $validated['building_id'],
                'name' => $validated['name'],
                'floor_number' => $validated['floor_number'] ?? null,
                'head_name' => $validated['head_name'],
                'head_title' => $validated['head_title'],
                'services' => json_encode($services),
            ]);

            return redirect()->route('admin.offices.index')
                ->with('success', 'Office information added successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create office: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Office $office)
    {
        $buildings = Building::orderBy('name')->get();
        return view('admin.offices.edit', compact('office', 'buildings'));
    }

    public function update(Request $request, Office $office)
    {
        try {
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'name' => 'required|string|max:150',
                'floor_number' => 'nullable|integer|min:0',
                'head_name' => 'required|string|max:100',
                'head_title' => 'required|string|max:100',
                'head_credentials' => 'nullable|string',
                'services' => 'nullable|array',
                'services.*' => 'nullable|string|max:255',
            ]);

            $services = array_filter($validated['services'] ?? []);

            $office->update([
                'building_id' => $validated['building_id'],
                'name' => $validated['name'],
                'floor_number' => $validated['floor_number'] ?? null,
                'head_name' => $validated['head_name'],
                'head_title' => $validated['head_title'],
                'services' => json_encode($services),
            ]);

            return redirect()->route('admin.offices.index')
                ->with('success', 'Office information updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update office: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Office $office)
    {
        try {
            $office->delete();

            return redirect()->route('admin.offices.index')
                ->with('success', 'Office deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete office: ' . $e->getMessage());
        }
    }

    public function updateServices(Request $request, Office $office)
    {
        try {
            $validated = $request->validate([
                'services' => 'required|array',
                'services.*' => 'nullable|string|max:255',
            ]);

            $office->update([
                'services' => json_encode(array_filter($validated['services'])),
            ]);

            return redirect()->back()
                ->with('success', 'Services updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update services: ' . $e->getMessage());
        }
    }
}
