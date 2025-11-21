<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Building;
use App\Models\MapSetting;

class DualModeController extends Controller
{
    public function welcome()
    {
        $announcements = Announcement::where('is_active', true)->get();
        return view('dual-welcome', compact('announcements'));
    }

    public function map()
    {
        $buildings = Building::with('offices.services')->get();
        
        $mapSettings = MapSetting::first();
        $mapImage = $mapSettings->map_image_path ?? '/images/campus-map.png';
        $kioskX = $mapSettings->kiosk_x ?? 100;
        $kioskY = $mapSettings->kiosk_y ?? 100;
        
        return view('dual-map', compact('buildings', 'mapImage', 'kioskX', 'kioskY'));
    }
}
