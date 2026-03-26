<?php

namespace Database\Seeders;

use App\Models\HeroCircle;
use Illuminate\Database\Seeder;

class HeroCircleSeeder extends Seeder
{
    public function run(): void
    {
        $circles = [
            [
                'image_url'          => 'https://images.unsplash.com/photo-1490490849894-425cda7c9f27?w=100&h=100&fit=crop',
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
                'image_url'          => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=120&h=120&fit=crop',
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
                'image_url'          => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=100&h=100&fit=crop',
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
                'image_url'          => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&h=100&fit=crop',
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
                'image_url'          => 'https://images.unsplash.com/photo-1517457373614-b7152f800fd1?w=120&h=120&fit=crop',
                'alt'                => 'Wedding celebration',
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
                'image_url'          => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=100&h=100&fit=crop',
                'alt'                => 'Wedding bride',
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
                'image_url'          => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop',
                'alt'                => 'Wedding groom',
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
                'image_url'          => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=120&h=120&fit=crop',
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
                'image_url'          => 'https://images.unsplash.com/photo-1511379938547-c1f69b13d835?w=100&h=100&fit=crop',
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
                'image_url'          => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=100&h=100&fit=crop',
                'alt'                => 'Wedding celebration',
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
