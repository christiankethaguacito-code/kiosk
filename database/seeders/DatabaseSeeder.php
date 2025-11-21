<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in order (heads must be created before offices)
        $this->call([
            HeadSeeder::class,
            BuildingSeeder::class,
            OfficeSeeder::class,
            AdminSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
