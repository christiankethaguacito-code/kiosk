<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Office;
use App\Models\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class FullInfoSeeder extends Seeder
{
    /**
     * Run the database seeds using fullinfo.json
     */
    public function run(): void
    {
        // Read fullinfo.json file from the parent directory (PatisoyFinal)
        $jsonPath = dirname(base_path()) . '/fullinfo.json';
        
        if (!File::exists($jsonPath)) {
            $this->command->error("fullinfo.json file not found at: $jsonPath");
            return;
        }

        $jsonContent = File::get($jsonPath);
        $buildingsData = json_decode($jsonContent, true);

        if (!$buildingsData) {
            $this->command->error("Failed to parse fullinfo.json file");
            return;
        }

        $stats = [
            'buildings' => 0,
            'offices' => 0,
            'services' => 0,
            'updated' => 0,
            'skipped' => 0
        ];

        $this->command->info("Starting fullinfo.json import...\n");

        // Clear existing data
        $this->command->info("Clearing existing data...");
        DB::table('services')->delete();
        DB::table('offices')->delete();
        DB::table('buildings')->delete();
        $this->command->info("✓ Existing data cleared\n");

        foreach ($buildingsData as $buildingData) {
            try {
                // Skip buildings without building_id
                if (empty($buildingData['building_id'])) {
                    $this->command->warn("⚠ Skipping building without building_id: {$buildingData['building']}");
                    $stats['skipped']++;
                    continue;
                }

                // Create building
                $building = Building::create([
                    'name' => $buildingData['building'],
                    'code' => $buildingData['building_id'],
                    'image_path' => 'buildings/' . strtolower($buildingData['building_id']) . '.jpg'
                ]);

                $stats['buildings']++;
                $this->command->line("✓ Building: {$building->name} (Code: {$building->code})");

                // Process offices
                if (isset($buildingData['offices']) && is_array($buildingData['offices'])) {
                    foreach ($buildingData['offices'] as $officeData) {
                        $officeName = $officeData['office_name'] ?? 'nan';
                        
                        // Skip unnamed offices or 'nan' offices without services
                        if ($officeName === 'nan' && empty($officeData['services'])) {
                            continue;
                        }

                        // Use a proper office name if it's 'nan'
                        if ($officeName === 'nan') {
                            $officeName = $buildingData['building'] . ' Office';
                        }

                        // Create office
                        $office = Office::create([
                            'building_id' => $building->id,
                            'name' => $officeName,
                            'floor_number' => $officeData['floor'] ?? '',
                            'head_name' => $officeData['head_of_office'] ?? '',
                            'head_title' => $officeData['position'] ?? ''
                        ]);

                        $stats['offices']++;
                        $this->command->line("  ✓ Office: {$office->name}");
                        
                        if (!empty($officeData['head_of_office'])) {
                            $this->command->line("    Head: {$officeData['head_of_office']}");
                        }

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
                        }
                    }
                }

                $this->command->newLine();

            } catch (\Exception $e) {
                $this->command->error("Error processing building: {$buildingData['building']}");
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
        $this->command->info("✓ fullinfo.json import completed successfully!");
    }
}
