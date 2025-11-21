<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buildings' => Building::count(),
            'total_offices' => Office::count(),
            'active_announcements' => Announcement::where('is_active', true)->count(),
        ];

        $buildings = Building::latest()->take(10)->get();
        $offices = Office::with('building')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'buildings', 'offices'));
    }
}
