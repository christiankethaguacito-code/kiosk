<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Offices with head_name containing our target heads:\n";

$targets = ['Samson', 'Hassanal', 'Albano', 'Julie'];
foreach ($targets as $t) {
    $offices = DB::table('offices')->where('head_name', 'like', "%{$t}%")->get();
    echo "\nMatching '{$t}':\n";
    foreach ($offices as $o) {
        echo "  - ID: {$o->id}, Office: {$o->name}, Head: {$o->head_name}\n";
    }
}

echo "\n\nAll distinct head_names:\n";
$heads = DB::table('offices')->whereNotNull('head_name')->where('head_name', '!=', '')->distinct()->pluck('head_name');
foreach ($heads as $h) {
    echo "  - '{$h}'\n";
}
