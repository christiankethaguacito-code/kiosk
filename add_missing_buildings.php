<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Building;

// Buildings that exist on SVG map but not in database
$missingBuildings = [
    ['code' => 'mosque', 'name' => 'University Mosque'],
    ['code' => 'Agri_bldg_1', 'name' => 'Agriculture Building 1'],
    ['code' => 'Agri_bldg_2', 'name' => 'Agriculture Building 2'],
    ['code' => 'Univesity_AVR', 'name' => 'University Audio-Visual Room'],
    ['code' => 'GS-ext', 'name' => 'Graduate School Extension'],
    ['code' => 'Parking_Space', 'name' => 'Campus Parking Area'],
    ['code' => 'SKSU-MPC', 'name' => 'SKSU Multi-Purpose Center'],
    ['code' => 'MPC-Dorm', 'name' => 'MPC Dormitory'],
    ['code' => 'MD_1', 'name' => 'Mini Dorm 1'],
    ['code' => 'BCSF', 'name' => 'Basic & Clinical Sciences Facility'],
    ['code' => 'UPP', 'name' => 'University Printing Press'],
    ['code' => 'AMTC', 'name' => 'Advanced Medical Training Center'],
    ['code' => 'TCL', 'name' => 'Technology & Computer Laboratory'],
    ['code' => 'DOST', 'name' => 'DOST Innovation Center'],
    ['code' => 'Motorpool', 'name' => 'University Motorpool'],
    ['code' => 'FC', 'name' => 'Facilities Center'],
    ['code' => 'OSAS', 'name' => 'Office of Student Affairs & Services'],
    ['code' => 'UC', 'name' => 'University Canteen'],
    ['code' => 'Field', 'name' => 'University Athletic Field'],
    ['code' => 'Bleacher', 'name' => 'Field Bleachers'],
    ['code' => 'CoM', 'name' => 'College of Medicine'],
    ['code' => 'Restroom', 'name' => 'Public Restroom Facility'],
    ['code' => 'ULD', 'name' => 'University Language Development Center'],
    ['code' => 'CCJE', 'name' => 'College of Criminal Justice Education']
];

echo "Adding missing buildings...\n\n";

foreach ($missingBuildings as $data) {
    // Check if building already exists
    $existing = Building::where('code', $data['code'])->first();
    
    if (!$existing) {
        Building::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'image_path' => "buildings/{$data['code']}.jpg"
        ]);
        echo "✓ Added: {$data['name']} ({$data['code']})\n";
    } else {
        echo "⚬ Already exists: {$data['name']} ({$data['code']})\n";
    }
}

echo "\n✓ Done! All missing buildings added.\n";
