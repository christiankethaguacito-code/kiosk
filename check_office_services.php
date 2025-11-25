<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Office;

echo "Checking office services...\n\n";

// Get first office with services
$office = Office::with('services')->first();

if ($office) {
    echo "Office: {$office->name}\n";
    echo "Head: {$office->head_name}\n";
    echo "Position: {$office->head_title}\n";
    echo "Services count: {$office->services->count()}\n";
    
    if ($office->services->count() > 0) {
        echo "\nServices:\n";
        foreach ($office->services as $service) {
            echo "  - {$service->description}\n";
        }
    } else {
        echo "\nNo services found for this office.\n";
    }
} else {
    echo "No offices found in database.\n";
}
