<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Building;
use App\Models\Office;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

// Read fullinfo.json
$fullInfoPath = __DIR__ . '/fullinfo.json';
$fullInfoData = json_decode(file_get_contents($fullInfoPath), true);

echo "Starting comprehensive data sync from fullinfo.json...\n\n";

$stats = [
    'buildings_updated' => 0,
    'buildings_created' => 0,
    'offices_created' => 0,
    'services_created' => 0,
    'buildings_without_offices' => 0
];

foreach ($fullInfoData as $buildingData) {
    $buildingName = $buildingData['building'];
    $buildingCode = $buildingData['building_id'];
    
    // Skip if no building_id
    if (empty($buildingCode)) {
        echo "⚠ Skipping '{$buildingName}' - no building_id\n";
        continue;
    }
    
    // Find or create building
    $building = Building::where('code', $buildingCode)->first();
    
    if (!$building) {
        $building = Building::create([
            'code' => $buildingCode,
            'name' => $buildingName,
            'image_path' => "buildings/{$buildingCode}.jpg"
        ]);
        echo "✓ Created building: {$buildingName} ({$buildingCode})\n";
        $stats['buildings_created']++;
    } else {
        // Update name if different
        if ($building->name !== $buildingName) {
            $building->name = $buildingName;
            $building->save();
        }
        $stats['buildings_updated']++;
    }
    
    // Process offices
    if (isset($buildingData['offices']) && is_array($buildingData['offices']) && count($buildingData['offices']) > 0) {
        foreach ($buildingData['offices'] as $officeData) {
            $officeName = $officeData['office_name'] ?? null;
            $headName = $officeData['head_of_office'] ?? null;
            $position = $officeData['position'] ?? null;
            $services = $officeData['services'] ?? [];
            
            // Handle buildings with no office structure but have head/services directly
            if (empty($officeName) || $officeName === 'nan') {
                // Use building name as office name
                $officeName = $buildingName;
            }
            
            // Check if office already exists for this building
            $office = Office::where('building_id', $building->id)
                           ->where('name', $officeName)
                           ->first();
            
            if (!$office) {
                $office = Office::create([
                    'building_id' => $building->id,
                    'name' => $officeName,
                    'head_name' => $headName,
                    'head_title' => $position
                ]);
                echo "  ✓ Office: {$officeName}\n";
                $stats['offices_created']++;
            } else {
                // Update head info if changed
                $updated = false;
                if ($office->head_name !== $headName && !empty($headName)) {
                    $office->head_name = $headName;
                    $updated = true;
                }
                if ($office->head_title !== $position && !empty($position)) {
                    $office->head_title = $position;
                    $updated = true;
                }
                if ($updated) {
                    $office->save();
                    echo "  ↻ Updated: {$officeName}\n";
                }
            }
            
            // Add services
            if (is_array($services) && count($services) > 0) {
                foreach ($services as $serviceDescription) {
                    if (empty($serviceDescription)) continue;
                    
                    // Check if service already exists
                    $existingService = Service::where('office_id', $office->id)
                                             ->where('description', $serviceDescription)
                                             ->first();
                    
                    if (!$existingService) {
                        Service::create([
                            'office_id' => $office->id,
                            'description' => $serviceDescription
                        ]);
                        $stats['services_created']++;
                    }
                }
            }
        }
    } else {
        // Building has no offices in fullinfo.json
        // Check if there's any data we can still use
        $stats['buildings_without_offices']++;
        echo "  ⚬ No offices defined in fullinfo.json\n";
    }
    
    echo "\n";
}

// Final statistics
echo "\n=== COMPREHENSIVE SYNC COMPLETE ===\n";
echo "Buildings created: {$stats['buildings_created']}\n";
echo "Buildings updated: {$stats['buildings_updated']}\n";
echo "Offices created: {$stats['offices_created']}\n";
echo "Services created: {$stats['services_created']}\n";
echo "Buildings without offices: {$stats['buildings_without_offices']}\n";

// Show final counts
$totalBuildings = Building::count();
$totalOffices = Office::count();
$totalServices = Service::count();

echo "\n=== DATABASE TOTALS ===\n";
echo "Total Buildings: {$totalBuildings}\n";
echo "Total Offices: {$totalOffices}\n";
echo "Total Services: {$totalServices}\n";

echo "\n✓ All data from fullinfo.json has been synchronized!\n";
