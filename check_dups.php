<?php
require 'vendor/autoload.php';
$db = new PDO('sqlite:database/database.sqlite');

// Check all buildings and their offices
$buildings = $db->query('SELECT * FROM buildings ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

foreach ($buildings as $b) {
    $stmt = $db->prepare('SELECT * FROM offices WHERE building_id = ? ORDER BY name');
    $stmt->execute([$b['id']]);
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($offices) > 0) {
        echo "\n" . $b['name'] . " (ID: " . $b['id'] . ") - " . count($offices) . " offices:\n";
        foreach ($offices as $o) {
            echo "  - " . $o['name'] . " | Head: " . ($o['head_name'] ?? 'N/A') . "\n";
        }
    }
}

// Check for duplicates
echo "\n\n=== CHECKING FOR DUPLICATES ===\n";
$dups = $db->query('SELECT name, building_id, COUNT(*) as cnt FROM offices GROUP BY name, building_id HAVING cnt > 1')->fetchAll(PDO::FETCH_ASSOC);
if (count($dups) > 0) {
    foreach ($dups as $d) {
        echo "DUPLICATE: " . $d['name'] . " (count: " . $d['cnt'] . ")\n";
    }
} else {
    echo "No duplicate offices found in database.\n";
}
