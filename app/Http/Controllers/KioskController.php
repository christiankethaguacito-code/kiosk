<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Building;
use App\Models\Office;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    public function idle()
    {
        $announcements = Announcement::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();
        return view('kiosk.welcome', compact('announcements'));
    }
    
    public function dualWelcome()
    {
        $announcements = Announcement::where('is_active', true)->get();
        return view('dual-welcome', compact('announcements'));
    }

    public function map()
    {
        $buildings = Building::with('offices.services')->get();
        $isAdmin = false;
        
        $navigationEndpoints = [];
        foreach ($buildings as $building) {
            // Use building code to match SVG IDs and JavaScript navigationPoints keys
            if ($building->code && $building->endpoint_x && $building->endpoint_y) {
                $navigationEndpoints[$building->code] = [
                    'x' => $building->endpoint_x,
                    'y' => $building->endpoint_y,
                    'roadConnection' => $building->road_connection
                ];
            }
        }
        
        return view('kiosk.map', compact('buildings', 'isAdmin', 'navigationEndpoints'));
    }

    public function building($id)
    {
        $building = Building::with('offices.services')->findOrFail($id);
        return view('kiosk.building', compact('building'));
    }

    public function office($id)
    {
        $office = Office::with(['building', 'services'])->findOrFail($id);
        return view('kiosk.office', compact('office'));
    }

    public function navigate($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        return view('kiosk.navigation', compact('building'));
    }
}
