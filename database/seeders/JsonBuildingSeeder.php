<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Office;
use App\Models\Service;
use Illuminate\Support\Facades\File;

class JsonBuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read JSON file
        $jsonPath = database_path('seeders/buildings_data.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found at: $jsonPath");
            return;
        }

        $jsonContent = File::get($jsonPath);
        $buildingsData = json_decode($jsonContent, true);

        if (!$buildingsData) {
            $this->command->error("Failed to parse JSON file");
            return;
        }

        $stats = [
            'buildings' => 0,
            'offices' => 0,
            'services' => 0,
            'skipped' => 0
        ];

        $this->command->info("Starting JSON import...\n");

        foreach ($buildingsData as $buildingData) {
            try {
                // Create or find building
                $building = Building::firstOrCreate(
                    ['name' => $buildingData['building_name']],
                    [
                        'code' => $buildingData['building_code'] ?? null,
                        'image_path' => isset($buildingData['image']) ? 'buildings/' . $buildingData['image'] : null
                    ]
                );

                $stats['buildings']++;
                $this->command->line("✓ Building: {$building->name}");

                // Process offices
                if (isset($buildingData['offices']) && is_array($buildingData['offices'])) {
                    foreach ($buildingData['offices'] as $officeData) {
                        if (empty($officeData['name'])) {
                            $stats['skipped']++;
                            continue;
                        }

                        // Create office
                        $office = Office::create([
                            'building_id' => $building->id,
                            'name' => $officeData['name'],
                            'floor_number' => $officeData['floor'] ?? null,
                            'head_name' => $officeData['head_name'] ?? null,
                            'head_title' => $officeData['head_title'] ?? null
                        ]);

                        $stats['offices']++;
                        $this->command->line("  ✓ Office: {$office->name}");

                        // Process services
                        if (isset($officeData['services']) && is_array($officeData['services'])) {
                            foreach ($officeData['services'] as $serviceDescription) {
                                if (empty($serviceDescription)) {
                                    continue;
                                }

                                Service::create([
                                    'office_id' => $office->id,
                                    'description' => $serviceDescription
                                ]);

                                $stats['services']++;
                                $this->command->line("    ✓ Service: {$serviceDescription}");
                            }
                        } else {
                            // Add default service if none specified
                            Service::create([
                                'office_id' => $office->id,
                                'description' => '• General Inquiry'
                            ]);
                            $stats['services']++;
                        }
                    }
                }

                $this->command->newLine();

            } catch (\Exception $e) {
                $this->command->error("Error processing building: {$buildingData['building_name']}");
                $this->command->error($e->getMessage());
                $stats['skipped']++;
            }
        }

        // Display summary
        $this->command->newLine();
        $this->command->info("=== Import Summary ===");
        $this->command->info("Buildings created: {$stats['buildings']}");
        $this->command->info("Offices created: {$stats['offices']}");
        $this->command->info("Services created: {$stats['services']}");
        
        if ($stats['skipped'] > 0) {
            $this->command->warn("Items skipped: {$stats['skipped']}");
        }

        $this->command->newLine();
        $this->command->info("✓ JSON import completed successfully!");
    }
}
