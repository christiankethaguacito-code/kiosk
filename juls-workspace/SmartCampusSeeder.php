<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Office;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartCampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder imports campus data from a CSV file with the following logic:
     * - Forward-fills empty building cells (merged cells in Excel)
     * - Creates buildings, offices, and default services
     * - Handles messy data with trimming and null checks
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/campus_data.csv');

        // Verify CSV file exists
        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            $this->command->warn("Please place your campus_data.csv file in the database/seeders/ directory.");
            return;
        }

        // Open CSV file
        $handle = fopen($csvPath, 'r');
        
        if ($handle === false) {
            $this->command->error("Failed to open CSV file.");
            return;
        }

        DB::beginTransaction();

        try {
            // Read and skip header row
            $header = fgetcsv($handle);
            
            if ($header === false) {
                throw new \Exception("CSV file is empty or invalid.");
            }

            $this->command->info("CSV Headers detected: " . implode(', ', $header));
            $this->command->info("Starting import...\n");

            // Initialize tracking variables
            $currentBuilding = null;
            $rowNumber = 1; // Start at 1 (after header)
            $stats = [
                'buildings_created' => 0,
                'offices_created' => 0,
                'services_created' => 0,
                'rows_processed' => 0,
                'rows_skipped' => 0,
            ];

            // Process each row
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                $stats['rows_processed']++;

                // Extract and clean columns (CSV structure: ID, Building, Office, Floor, Head, Position)
                $id = isset($row[0]) ? trim($row[0]) : '';
                $buildingName = isset($row[1]) ? trim($row[1]) : '';
                $officeName = isset($row[2]) ? trim($row[2]) : '';
                $floorNumber = isset($row[3]) ? trim($row[3]) : '';
                $headName = isset($row[4]) ? trim($row[4]) : '';
                $headTitle = isset($row[5]) ? trim($row[5]) : '';

                // Forward-fill logic: Update current building if column is not empty
                if (!empty($buildingName)) {
                    $currentBuilding = $buildingName;
                }

                // Skip row if no current building context exists
                if (empty($currentBuilding)) {
                    $this->command->warn("Row {$rowNumber}: Skipped - No building context.");
                    $stats['rows_skipped']++;
                    continue;
                }

                // Skip row if office name is empty (nothing to process)
                if (empty($officeName)) {
                    $this->command->warn("Row {$rowNumber}: Skipped - Empty office name.");
                    $stats['rows_skipped']++;
                    continue;
                }

                // Create or retrieve building
                $building = Building::firstOrCreate(
                    ['name' => $currentBuilding],
                    [
                        'image_path' => null,
                        'image_gallery' => json_encode([]),
                        'map_x' => 0,
                        'map_y' => 0,
                    ]
                );

                if ($building->wasRecentlyCreated) {
                    $stats['buildings_created']++;
                    $this->command->info("✓ Created Building: {$currentBuilding}");
                }

                // Create office linked to current building
                $office = Office::create([
                    'building_id' => $building->id,
                    'name' => $officeName,
                    'floor_number' => !empty($floorNumber) ? $floorNumber : null,
                    'head_name' => !empty($headName) ? $headName : null,
                    'head_title' => !empty($headTitle) ? $headTitle : null,
                ]);

                $stats['offices_created']++;
                $this->command->line("  ✓ Created Office: {$officeName} (Building: {$currentBuilding})");

                // Create default service for this office
                $service = Service::create([
                    'office_id' => $office->id,
                    'description' => '• General Inquiry',
                ]);

                $stats['services_created']++;
                $this->command->line("    ✓ Created Service: General Inquiry");
            }

            fclose($handle);
            DB::commit();

            // Display summary
            $this->command->info("\n" . str_repeat('=', 60));
            $this->command->info("IMPORT COMPLETED SUCCESSFULLY");
            $this->command->info(str_repeat('=', 60));
            $this->command->table(
                ['Metric', 'Count'],
                [
                    ['Rows Processed', $stats['rows_processed']],
                    ['Rows Skipped', $stats['rows_skipped']],
                    ['Buildings Created', $stats['buildings_created']],
                    ['Offices Created', $stats['offices_created']],
                    ['Services Created', $stats['services_created']],
                ]
            );

        } catch (\Exception $e) {
            fclose($handle);
            DB::rollBack();
            
            $this->command->error("\n" . str_repeat('=', 60));
            $this->command->error("IMPORT FAILED");
            $this->command->error(str_repeat('=', 60));
            $this->command->error("Error: " . $e->getMessage());
            $this->command->error("Row Number: {$rowNumber}");
            
            Log::error("SmartCampusSeeder failed", [
                'row' => $rowNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
