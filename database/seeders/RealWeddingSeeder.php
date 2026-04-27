<?php

namespace Database\Seeders;

use App\Models\RealWedding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealWeddingSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'couple_names' => 'MARCEL & STELCYA',
                'title'        => 'Wedding Rasa Private Party, dari Ceremony Sampai Cocktail yang Interaktif',
                'venue_name'   => 'Atma Sahita',
                'wedding_date' => '2026-03-07',
                'published_at' => '2026-04-16 08:00:00',
                'badge'        => "Editor's Collection",
                'cover_image'  => 'https://picsum.photos/seed/rw-marcel/400/533',
                'content'      => 'Stelcya & Marcel memilih daerah Tabanan, Bali, menjadi venue untuk mengucap janji suci keduanya, yaitu di Atma Sahita.',
                'is_active'    => true,
                'sort_order'   => 1,
            ],
            [
                'couple_names' => 'REZA & AULIA',
                'title'        => 'Intimate Garden Wedding penuh Warna di Tengah Kota',
                'venue_name'   => 'The Zuri Hotel Palembang',
                'wedding_date' => '2026-02-14',
                'published_at' => '2026-03-01 09:00:00',
                'badge'        => "Editor's Collection",
                'cover_image'  => 'https://picsum.photos/seed/rw-reza/400/533',
                'content'      => null,
                'is_active'    => true,
                'sort_order'   => 2,
            ],
            [
                'couple_names' => 'BAGAS & TIARA',
                'title'        => 'Grand Ballroom Wedding dengan Sentuhan Modern Elegant',
                'venue_name'   => 'Aston Palembang',
                'wedding_date' => '2026-01-25',
                'published_at' => '2026-02-10 10:00:00',
                'badge'        => "Editor's Collection",
                'cover_image'  => 'https://picsum.photos/seed/rw-bagas/400/533',
                'content'      => null,
                'is_active'    => true,
                'sort_order'   => 3,
            ],
            [
                'couple_names' => 'DIMAS & SARI',
                'title'        => 'Pernikahan Sakral di Tepi Pantai dengan Nuansa Tradisional',
                'venue_name'   => 'Hotel Santika Palembang',
                'wedding_date' => '2025-12-20',
                'published_at' => '2026-01-05 08:00:00',
                'badge'        => "Editor's Collection",
                'cover_image'  => 'https://picsum.photos/seed/rw-dimas/400/533',
                'content'      => null,
                'is_active'    => true,
                'sort_order'   => 4,
            ],
            [
                'couple_names' => 'ANDI & DEWI',
                'title'        => 'Wedding Mewah dengan Dekorasi Bunga Segar yang Memukau',
                'venue_name'   => 'Beston Hotel Palembang',
                'wedding_date' => '2025-11-11',
                'published_at' => '2025-12-01 09:00:00',
                'badge'        => "Editor's Collection",
                'cover_image'  => 'https://picsum.photos/seed/rw-andi/400/533',
                'content'      => null,
                'is_active'    => true,
                'sort_order'   => 5,
            ],
            [
                'couple_names' => 'FAJAR & NISA',
                'title'        => 'Pernikahan Outdoor Romantis di Bawah Langit Sore',
                'venue_name'   => null,
                'wedding_date' => '2025-10-05',
                'published_at' => '2025-10-20 08:00:00',
                'badge'        => "Editor's Collection",
                'cover_image'  => 'https://picsum.photos/seed/rw-fajar/400/533',
                'content'      => null,
                'is_active'    => true,
                'sort_order'   => 6,
            ],
        ];

        foreach ($items as $item) {
            RealWedding::firstOrCreate(
                ['slug' => Str::slug($item['title'])],
                $item
            );
        }
    }
}
