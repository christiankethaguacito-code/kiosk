<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Building;

// Map building names to their SVG element IDs (codes)
$updates = [
    'ADMINISTRATION BUILDING' => 'Administration',
    'QMS CENTER BUILDING' => 'QMS',
    'LABORATORY HIGH SCHOOL' => 'LHS',
    'COLLEGE OF TEACHER EDUCATION' => 'CTE',
    'COLLEGE OF HEALTH AND SCIENCES' => 'CHS',
    'COLLEGES OF HEALTH AND SCIENCES EXTENSION' => 'CHS_Labs',
    'GRADUATE SCHOOL' => 'GS',
    'ALUMNI RELATIONSHIP OFFICE' => 'Alumni_Office',
    'GS-SBO' => 'GS-SBO',
    'UNIVERSITY ACCESS CLINIC' => 'UG',
    'OFFICE OF STUDENT AFFAIRS AND SERVICES BLDG.' => 'ROTC',
    'CLIMATE' => 'Climate',
    'TECHNOLOGY AND INNOVATION BUILDING (TIP)' => 'TIP_center',
    'UNIVERSITY LEARNING RESOURCE CENTER (LIBRARY)' => 'ULRC',
    'MAIN ENTRANCE' => 'MD_1',
    'FUNCTION HALL' => 'Function',
    "REGISTRAR'S OFFICE" => 'Reg_Office',
    'LABORATORY HIGH SCHOOL EXTENSION' => 'LHS_ext',
    'MINI DORMITORY 2' => 'MD_2',
    'CCJE EXTENSION BUILDING' => 'CCJE_ext'
];

echo "Updating building codes...\n\n";

foreach ($updates as $name => $code) {
    $building = Building::where('name', $name)->first();
    
    if ($building) {
        $building->code = $code;
        $building->save();
        echo "✓ Updated: {$name} => {$code}\n";
    } else {
        echo "✗ Not found: {$name}\n";
    }
}

echo "\n✓ Done! All building codes updated.\n";
