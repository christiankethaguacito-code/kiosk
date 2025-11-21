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
            'buildings' => Building::count(),
            'offices' => Office::count(),
            'services' => \App\Models\Service::count(),
            'announcements' => Announcement::count()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
