<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapSettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('map_settings')->insert([
            'map_image_path' => '/images/campus-map.png',
            'kiosk_x' => 100,
            'kiosk_y' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
