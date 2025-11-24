<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::where('username', 'admin')->first();
        if ($admin) {
            $admin->password = Hash::make('admin123');
            $admin->save();
            echo "Admin password updated to 'admin123'\n";
        }
    }
}
