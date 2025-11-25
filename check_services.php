<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Offices and Services ===\n\n";

// Check total counts
echo "Total Offices: " . App\Models\Office::count() . "\n";
echo "Total Services: " . App\Models\Service::count() . "\n\n";

// Check offices from building 1 (Administration)
echo "=== Administration Building Offices ===\n";
$building = App\Models\Building::with('offices.services')->where('name', 'LIKE', '%ADMINISTRATION%')->first();
echo "Building: " . $building->name . "\n";
echo "Offices count: " . $building->offices->count() . "\n\n";

foreach ($building->offices as $office) {
    echo "Office #{$office->id}: {$office->name}\n";
    $servicesCount = $office->services ? $office->services->count() : 0;
    echo "  Services: {$servicesCount}\n";
    if ($servicesCount > 0) {
        foreach ($office->services->take(3) as $service) {
            echo "    - {$service->description}\n";
        }
    }
    echo "\n";
}
