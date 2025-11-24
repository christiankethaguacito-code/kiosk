<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Welcome to PHINMA COC Campus',
                'content' => 'Explore our interactive campus map and discover all the amazing facilities and services available to our students and visitors.',
                'is_active' => true,
                'display_order' => 1,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(6),
            ],
            [
                'title' => 'New Library Hours',
                'content' => 'The campus library is now open extended hours! Monday-Friday: 7:00 AM - 9:00 PM, Saturday: 8:00 AM - 6:00 PM. Come visit us!',
                'is_active' => true,
                'display_order' => 2,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
            ],
            [
                'title' => 'Campus Events This Week',
                'content' => 'Join us for exciting events: Monday - Student Orientation, Wednesday - Career Fair, Friday - Sports Fest. Check the map for event locations!',
                'is_active' => true,
                'display_order' => 3,
                'starts_at' => now(),
                'ends_at' => now()->addWeeks(1),
            ],
            [
                'title' => 'Find Your Way Around',
                'content' => 'Need directions? Simply tap on any building to see its location and get instant navigation paths. We\'re here to help you explore!',
                'is_active' => true,
                'display_order' => 4,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(12),
            ],
            [
                'title' => 'Campus Services Available',
                'content' => 'Visit our Registrar, Cashier, Guidance Office, and more! All campus services are marked on the map for your convenience.',
                'is_active' => true,
                'display_order' => 5,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(12),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
