<?php

namespace Database\Seeders;

use App\Models\VenueReviewVideo;
use Illuminate\Database\Seeder;

class VenueReviewVideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'title'      => 'ASTON GRAND BALLROOM',
                'subtitle'   => 'Introducing',
                'location'   => 'at Aston Palembang',
                'thumbnail'  => 'https://picsum.photos/seed/ballroom1/300/533',
                'video_url'  => null,
                'is_active'  => true,
                'sort_order' => 1,
            ],
            [
                'title'      => 'BESTON BALLROOM',
                'subtitle'   => 'Introducing',
                'location'   => 'at Beston Hotel Palembang',
                'thumbnail'  => 'https://picsum.photos/seed/ballroom2/300/533',
                'video_url'  => null,
                'is_active'  => true,
                'sort_order' => 2,
            ],
            [
                'title'      => 'SWISS-BELHOTEL BALLROOM',
                'subtitle'   => 'Introducing',
                'location'   => 'at Swiss-Belhotel Palembang',
                'thumbnail'  => 'https://picsum.photos/seed/ballroom3/300/533',
                'video_url'  => null,
                'is_active'  => true,
                'sort_order' => 3,
            ],
            [
                'title'      => 'THE ZURI GRAND HALL',
                'subtitle'   => 'Introducing',
                'location'   => 'at The Zuri Hotel Palembang',
                'thumbnail'  => 'https://picsum.photos/seed/ballroom4/300/533',
                'video_url'  => null,
                'is_active'  => true,
                'sort_order' => 4,
            ],
            [
                'title'      => 'JAKABARING CONVENTION CENTER',
                'subtitle'   => 'Introducing',
                'location'   => 'at Jakabaring Palembang',
                'thumbnail'  => 'https://picsum.photos/seed/ballroom5/300/533',
                'video_url'  => null,
                'is_active'  => true,
                'sort_order' => 5,
            ],
            [
                'title'      => 'GARDEN BALLROOM',
                'subtitle'   => 'Introducing',
                'location'   => 'at Novotel Palembang',
                'thumbnail'  => 'https://picsum.photos/seed/ballroom6/300/533',
                'video_url'  => null,
                'is_active'  => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($videos as $data) {
            VenueReviewVideo::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
