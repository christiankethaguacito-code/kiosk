<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Office table structure:\n";
$columns = DB::select('PRAGMA table_info(offices)');
foreach($columns as $col) {
    echo "  - {$col->name} ({$col->type})\n";
}

echo "\nFirst office data:\n";
$office = DB::table('offices')->first();
print_r($office);

echo "\nServices for office ID {$office->id}:\n";
$services = DB::table('services')->where('office_id', $office->id)->get();
echo "Count: " . $services->count() . "\n";
foreach($services as $service) {
    echo "  - {$service->description}\n";
}
