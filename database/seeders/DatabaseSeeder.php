<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\Service;
use App\Models\Testimonial;
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
        $this->call(AdminUserSeeder::class);

        // Initial Services
        $services = [
            [
                'title' => 'Business & Market Strategy',
                'slug' => 'business-market-strategy',
                'description' => 'Positioning, planning and a clear growth roadmap before a single ad goes live.',
                'icon' => 'Strategy',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Marketing & Brand Growth',
                'slug' => 'marketing-brand-growth',
                'description' => 'Content, ads and brand-building that get a business found and remembered.',
                'icon' => 'Marketing',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Events & Experiences',
                'slug' => 'events-experiences',
                'description' => 'Concept to execution — production, promotion, and the night itself.',
                'icon' => 'Events',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Revenue & Sales Growth',
                'slug' => 'revenue-sales-growth',
                'description' => 'Turning attention into bookings, ticket sales, and repeat business.',
                'icon' => 'Revenue',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['slug' => $service['slug']], $service);
        }

        // Initial Gallery Showcase Items
        $galleryItems = [
            [
                'title' => 'Concert Night ft. Atif Aslam',
                'description' => "One of RK Vibes' standout nights — a full-scale concert where the RK Vibes band opened and shared the stage with Atif Aslam. Handled end-to-end: venue production, promotion, ticketing, and the live run of show.",
                'image_path' => 'gallery/atif_aslam.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'RK Vibes Live Series',
                'description' => 'Multiple ticketed music nights produced and promoted end-to-end.',
                'image_path' => 'gallery/live_series.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Local Business Rebrand',
                'description' => 'Positioning, content system and ad strategy for a growing local business.',
                'image_path' => 'gallery/rebrand.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Founder Visibility Campaign',
                'description' => "Content and positioning strategy to build a founder's public presence.",
                'image_path' => 'gallery/founder.jpg',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($galleryItems as $item) {
            GalleryItem::firstOrCreate(['title' => $item['title']], $item);
        }

        // Initial Testimonials
        $testimonials = [
            [
                'client_name' => 'Farhan Tariq',
                'position' => 'Concert Attendee & Host',
                'message' => 'RK Vibes handled our music night with incredible professionalism. The crowd energy, the stage production, and the ticketing were flawless.',
                'rating' => 5,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'client_name' => 'Amina Sheikh',
                'position' => 'Brand Director',
                'message' => "We don't hand off to a different vendor for every piece. The same team that builds your strategy runs your campaign and produces your event.",
                'rating' => 5,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'client_name' => 'Zayn Malik',
                'position' => 'Independent Artist',
                'message' => 'Working with RK Vibes elevated our reach and brand presence across multiple cities. Truly end-to-end growth.',
                'rating' => 5,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                ['client_name' => $testimonial['client_name'], 'message' => $testimonial['message']],
                $testimonial
            );
        }
    }
}
