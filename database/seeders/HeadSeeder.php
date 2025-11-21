<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Head;

class HeadSeeder extends Seeder
{
    public function run()
    {
        $heads = [
            [
                'name' => 'Dr. Maria Santos',
                'title' => 'University Registrar',
                'credentials' => 'PhD in Education Management'
            ],
            [
                'name' => 'Dr. Juan Dela Cruz',
                'title' => 'University President',
                'credentials' => 'PhD in Educational Leadership, MBA'
            ],
            [
                'name' => 'Prof. Ana Garcia',
                'title' => 'Dean, College of Teacher Education',
                'credentials' => 'EdD, MA in Education'
            ],
            [
                'name' => 'Dr. Pedro Reyes',
                'title' => 'Dean, College of Health Sciences',
                'credentials' => 'MD, MPH'
            ],
            [
                'name' => 'Engr. Lisa Fernandez',
                'title' => 'Director, University Library',
                'credentials' => 'MS in Library Science'
            ],
        ];

        foreach ($heads as $head) {
            Head::create($head);
        }
    }
}
