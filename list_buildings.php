<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Current buildings in database:\n";
$buildings = App\Models\Building::all();
foreach ($buildings as $b) {
    echo "ID {$b->id}: {$b->name}\n";
}
