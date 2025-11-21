<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function showMapConfig()
    {
        $currentMap = config('map.image_path', 'campus-map.svg');
        $mapExists = Storage::disk('public')->exists($currentMap);
        
        return view('admin.map-config', [
            'currentMap' => $currentMap,
            'mapExists' => $mapExists
        ]);
    }

    public function updateMapConfig(Request $request)
    {
        try {
            $request->validate([
                'map_image' => 'required|file|mimes:svg,png,jpg,jpeg|max:10240',
                'map_name' => 'nullable|string|max:255',
            ]);

            $file = $request->file('map_image');
            $filename = 'campus-map-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('maps', $filename, 'public');

            config(['map.image_path' => $path]);
            
            \DB::table('map_configs')->insert([
                'path' => $path,
                'name' => $request->input('map_name', 'Campus Map'),
                'uploaded_by' => auth()->id(),
                'created_at' => now(),
            ]);

            return redirect()->route('admin.map-config')
                ->with('success', 'Map data updated successfully! The new campus map has been uploaded.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update map: ' . $e->getMessage())
                ->withInput();
        }
    }
}
