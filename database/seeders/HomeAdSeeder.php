<?php

namespace Database\Seeders;

use App\Models\HomeAd;
use Illuminate\Database\Seeder;

class HomeAdSeeder extends Seeder
{
    public function run(): void
    {
        HomeAd::truncate();

        HomeAd::create([
            'title'          => 'Promo Spesial Hari Ini',
            'image'          => 'https://picsum.photos/seed/makna-ad/800/800',
            'caption'        => 'Dapatkan promo spesial untuk booking vendor pilihanmu hari ini.',
            'link_url'       => '/store/promo',
            'link_label'     => 'Lihat Promo',
            'delay_seconds'  => 5,
            'is_active'      => true,
            'sort_order'     => 1,
        ]);
    }
}
