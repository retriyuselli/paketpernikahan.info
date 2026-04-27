<?php

namespace Database\Seeders;

use App\Models\HeroCircle;
use Illuminate\Database\Seeder;

class HeroCircleSeeder extends Seeder
{
    public function run(): void
    {
        HeroCircle::truncate();

        // Menggunakan picsum.photos dengan seed tetap agar gambar konsisten
        $circles = [
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-flowers/120/120',
                'alt'                => 'Wedding flowers',
                'size_px'            => 64,
                'color_from'         => '#9CAF88',
                'color_to'           => '#F9D5E5',
                'animation_delay'    => 0.0,
                'animation_duration' => 17.0,
                'position_side'      => 'left',
                'position_x'         => '5%',
                'position_bottom'    => '-80px',
                'sort_order'         => 1,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/couple-happy/120/120',
                'alt'                => 'Happy couple',
                'size_px'            => 80,
                'color_from'         => '#9CAF88',
                'color_to'           => '#9CAF88',
                'animation_delay'    => 0.5,
                'animation_duration' => 19.0,
                'position_side'      => 'left',
                'position_x'         => '15%',
                'position_bottom'    => '20px',
                'sort_order'         => 2,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-ring/120/120',
                'alt'                => 'Wedding ring',
                'size_px'            => 56,
                'color_from'         => '#F9D5E5',
                'color_to'           => '#C8D5B9',
                'animation_delay'    => 1.0,
                'animation_duration' => 21.0,
                'position_side'      => 'right',
                'position_x'         => '10%',
                'position_bottom'    => '-120px',
                'sort_order'         => 3,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-cake/120/120',
                'alt'                => 'Wedding cake',
                'size_px'            => 64,
                'color_from'         => '#FAF3E7',
                'color_to'           => '#C8D5B9',
                'animation_delay'    => 1.5,
                'animation_duration' => 18.0,
                'position_side'      => 'right',
                'position_x'         => '20%',
                'position_bottom'    => '180px',
                'sort_order'         => 4,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-decor/120/120',
                'alt'                => 'Wedding decoration',
                'size_px'            => 72,
                'color_from'         => '#9CAF88',
                'color_to'           => '#C8D5B9',
                'animation_delay'    => 0.3,
                'animation_duration' => 20.0,
                'position_side'      => 'left',
                'position_x'         => '25%',
                'position_bottom'    => '-40px',
                'sort_order'         => 5,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/bride-bouquet/120/120',
                'alt'                => 'Bride bouquet',
                'size_px'            => 64,
                'color_from'         => '#9CAF88',
                'color_to'           => '#F9D5E5',
                'animation_delay'    => 0.8,
                'animation_duration' => 17.4,
                'position_side'      => 'left',
                'position_x'         => '35%',
                'position_bottom'    => '80px',
                'sort_order'         => 6,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/groom-suit/120/120',
                'alt'                => 'Groom suit',
                'size_px'            => 56,
                'color_from'         => '#C8D5B9',
                'color_to'           => '#9CAF88',
                'animation_delay'    => 1.2,
                'animation_duration' => 20.6,
                'position_side'      => 'right',
                'position_x'         => '30%',
                'position_bottom'    => '-100px',
                'sort_order'         => 7,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-venue/120/120',
                'alt'                => 'Wedding venue',
                'size_px'            => 80,
                'color_from'         => '#FAF3E7',
                'color_to'           => '#C8D5B9',
                'animation_delay'    => 0.2,
                'animation_duration' => 21.4,
                'position_side'      => 'left',
                'position_x'         => '45%',
                'position_bottom'    => '140px',
                'sort_order'         => 8,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-light/120/120',
                'alt'                => 'Wedding lights',
                'size_px'            => 64,
                'color_from'         => '#9CAF88',
                'color_to'           => '#C8D5B9',
                'animation_delay'    => 1.8,
                'animation_duration' => 18.6,
                'position_side'      => 'right',
                'position_x'         => '5%',
                'position_bottom'    => '-60px',
                'sort_order'         => 9,
            ],
            [
                'image_url'          => 'https://picsum.photos/seed/wedding-party/120/120',
                'alt'                => 'Wedding party',
                'size_px'            => 56,
                'color_from'         => '#F9D5E5',
                'color_to'           => '#9CAF88',
                'animation_delay'    => 0.6,
                'animation_duration' => 19.6,
                'position_side'      => 'left',
                'position_x'         => '55%',
                'position_bottom'    => '40px',
                'sort_order'         => 10,
            ],
        ];

        foreach ($circles as $circle) {
            HeroCircle::create($circle);
        }
    }
}
