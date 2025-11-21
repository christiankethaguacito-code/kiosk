<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapSettingsController extends Controller
{
    public function edit()
    {
        $kioskX = Setting::get('kiosk_x', 195);
        $kioskY = Setting::get('kiosk_y', 260);
        $mapImagePath = Setting::get('map_image_path');
        
        return view('admin.map_settings', compact('kioskX', 'kioskY', 'mapImagePath'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'kiosk_x' => 'required|numeric',
            'kiosk_y' => 'required|numeric',
            'map_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
        ]);
        
        Setting::set('kiosk_x', $request->kiosk_x);
        Setting::set('kiosk_y', $request->kiosk_y);
        
        if ($request->hasFile('map_image')) {
            $oldImage = Setting::get('map_image_path');
            if ($oldImage && Storage::exists($oldImage)) {
                Storage::delete($oldImage);
            }
            
            $path = $request->file('map_image')->store('maps', 'public');
            Setting::set('map_image_path', $path);
        }
        
        return redirect()->route('admin.map_settings.edit')->with('success', 'Map settings updated successfully');
    }
}
