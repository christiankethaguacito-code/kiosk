<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        $buildings = [
            [
                'name' => 'Administration Building',
                'image_path' => 'buildings/administration.jpg',
                'map_x' => 200,
                'map_y' => 150
            ],
            [
                'name' => 'College of Teacher Education',
                'image_path' => 'buildings/cte.jpg',
                'map_x' => 500,
                'map_y' => 200
            ],
            [
                'name' => 'College of Health Sciences',
                'image_path' => 'buildings/chs.jpg',
                'map_x' => 800,
                'map_y' => 250
            ],
            [
                'name' => 'College of Criminal Justice Education',
                'image_path' => 'buildings/ccje.jpg',
                'map_x' => 350,
                'map_y' => 500
            ],
            [
                'name' => 'University Library and Resource Center',
                'image_path' => 'buildings/ulrc.jpg',
                'map_x' => 700,
                'map_y' => 550
            ],
        ];

        foreach ($buildings as $buildingData) {
            Building::create($buildingData);
        }
    }
}
