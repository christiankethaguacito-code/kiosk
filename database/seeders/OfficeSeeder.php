<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Office;
use App\Models\Building;
use App\Models\Head;

class OfficeSeeder extends Seeder
{
    public function run()
    {
        $admin = Building::where('name', 'Administration Building')->first();
        $cte = Building::where('name', 'College of Teacher Education')->first();
        $chs = Building::where('name', 'College of Health Sciences')->first();
        $ulrc = Building::where('name', 'University Library and Resource Center')->first();

        $offices = [
            [
                'name' => 'Office of the Registrar',
                'building_id' => $admin->id,
                'floor_number' => '1st Floor',
                'head_name' => 'Dr. Maria Santos',
                'head_title' => 'University Registrar',
            ],
            [
                'name' => 'Office of the President',
                'building_id' => $admin->id,
                'floor_number' => '2nd Floor',
                'head_name' => 'Dr. Juan Dela Cruz',
                'head_title' => 'University President',
            ],
            [
                'name' => 'Finance Office',
                'building_id' => $admin->id,
                'floor_number' => '1st Floor',
                'head_name' => 'Mr. Pedro Garcia',
                'head_title' => 'Finance Officer',
            ],
            [
                'name' => 'CTE Dean\'s Office',
                'building_id' => $cte->id,
                'floor_number' => '3rd Floor',
                'head_name' => 'Dr. Ana Reyes',
                'head_title' => 'Dean, College of Teacher Education',
            ],
            [
                'name' => 'CTE Faculty Room',
                'building_id' => $cte->id,
                'floor_number' => '2nd Floor',
                'head_name' => null,
                'head_title' => null,
            ],
            [
                'name' => 'CHS Dean\'s Office',
                'building_id' => $chs->id,
                'floor_number' => '4th Floor',
                'head_name' => 'Dr. Rosa Martinez',
                'head_title' => 'Dean, College of Health Sciences',
            ],
            [
                'name' => 'Library Services',
                'building_id' => $ulrc->id,
                'floor_number' => 'Ground Floor',
                'head_name' => 'Ms. Carmen Lopez',
                'head_title' => 'Library Director',
            ],
        ];

        foreach ($offices as $officeData) {
            $office = Office::create($officeData);
            
            if ($office->name === 'Office of the Registrar') {
                \App\Models\Service::create([
                    'office_id' => $office->id,
                    'description' => 'Enrollment and Registration'
                ]);
                \App\Models\Service::create([
                    'office_id' => $office->id,
                    'description' => 'Transcripts of Records'
                ]);
            }
        }
    }
}
