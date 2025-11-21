<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admins = [
            [
                'username' => 'admin',
                'email' => 'admin@sksu.edu.ph',
                'password' => 'password123', // Will be hashed automatically
                'role' => 'superadmin'
            ],
            [
                'username' => 'kiosk_admin',
                'email' => 'kiosk@sksu.edu.ph',
                'password' => 'kiosk2025',
                'role' => 'admin'
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
