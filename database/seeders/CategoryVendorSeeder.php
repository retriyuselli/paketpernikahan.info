<?php

namespace Database\Seeders;

use App\Models\CategoryVendor;
use Illuminate\Database\Seeder;

class CategoryVendorSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug'        => 'gedung',
                'name'        => 'Gedung Wedding Venue',
                'icon'        => 'building-office',
                'color'       => 'warning',
                'description' => 'Gedung serbaguna dan ballroom khusus untuk acara pernikahan.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'slug'        => 'paket-lengkap',
                'name'        => 'Paket Lengkap',
                'icon'        => 'shopping-bag',
                'color'       => 'success',
                'description' => 'Paket pernikahan lengkap dari berbagai vendor dalam satu paket.',
                'sort_order'  => 0,
                'is_active'   => true,
            ],
            [
                'slug'        => 'hotel',
                'name'        => 'Hotel & Ballroom',
                'icon'        => 'building-office-2',
                'color'       => 'info',
                'description' => 'Hotel berbintang dengan fasilitas ballroom mewah.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'slug'        => 'rumah',
                'name'        => 'Rumah & Taman',
                'icon'        => 'home',
                'color'       => 'success',
                'description' => 'Venue garden party dengan suasana asri dan alami.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'slug'        => 'wo',
                'name'        => 'Wedding Organizer',
                'icon'        => 'sparkles',
                'color'       => 'danger',
                'description' => 'Jasa wedding organizer profesional untuk semua lokasi.',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'slug'        => 'catering',
                'name'        => 'Catering & Makanan',
                'icon'        => 'cake',
                'color'       => 'warning',
                'description' => 'Jasa katering prasmanan, standing party, dan buffet untuk resepsi pernikahan.',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'slug'        => 'dekorasi',
                'name'        => 'Dekorasi & Florist',
                'icon'        => 'paint-brush',
                'color'       => 'success',
                'description' => 'Dekorasi pelaminan, backdrop, table setting, dan rangkaian bunga segar.',
                'sort_order'  => 6,
                'is_active'   => true,
            ],
            [
                'slug'        => 'foto-video',
                'name'        => 'Fotografer & Videografer',
                'icon'        => 'camera',
                'color'       => 'info',
                'description' => 'Dokumentasi foto dan video pernikahan profesional, prewedding, dan cinematic.',
                'sort_order'  => 7,
                'is_active'   => true,
            ],
            [
                'slug'        => 'makeup',
                'name'        => 'Makeup & Tata Rias',
                'icon'        => 'face-smile',
                'color'       => 'danger',
                'description' => 'Jasa makeup pengantin, tata rias wajah, sanggul, dan busana adat.',
                'sort_order'  => 8,
                'is_active'   => true,
            ],
            [
                'slug'        => 'gaun',
                'name'        => 'Gaun & Busana Pengantin',
                'icon'        => 'scissors',
                'color'       => 'warning',
                'description' => 'Sewa dan jual gaun pengantin, jas pengantin, kebaya, dan busana adat.',
                'sort_order'  => 9,
                'is_active'   => true,
            ],
            [
                'slug'        => 'hiburan',
                'name'        => 'Hiburan & Musik',
                'icon'        => 'musical-note',
                'color'       => 'info',
                'description' => 'Band live, DJ, tari tradisional, dan entertainment lainnya untuk resepsi.',
                'sort_order'  => 10,
                'is_active'   => true,
            ],
            [
                'slug'        => 'undangan',
                'name'        => 'Undangan & Souvenir',
                'icon'        => 'envelope',
                'color'       => 'success',
                'description' => 'Cetak undangan pernikahan, souvenir tamu, hampers, dan goodie bag.',
                'sort_order'  => 11,
                'is_active'   => true,
            ],
            [
                'slug'        => 'transportasi',
                'name'        => 'Transportasi Pengantin',
                'icon'        => 'truck',
                'color'       => 'gray',
                'description' => 'Sewa mobil pengantin mewah, limousine, dan kendaraan dekorasi.',
                'sort_order'  => 12,
                'is_active'   => true,
            ],
            [
                'slug'        => 'kue-pengantin',
                'name'        => 'Kue Pengantin',
                'icon'        => 'cake',
                'color'       => 'danger',
                'description' => 'Wedding cake, kue tart bertingkat, cupcake, dan dessert table.',
                'sort_order'  => 13,
                'is_active'   => true,
            ],
            [
                'slug'        => 'mc',
                'name'        => 'MC & Pembawa Acara',
                'icon'        => 'microphone',
                'color'       => 'warning',
                'description' => 'Master of ceremony profesional untuk resepsi dan akad nikah.',
                'sort_order'  => 14,
                'is_active'   => true,
            ],
            [
                'slug'        => 'fotobooth',
                'name'        => 'Fotobooth & Booth',
                'icon'        => 'photo',
                'color'       => 'info',
                'description' => 'Sewa fotobooth, polaroid, magic mirror, dan selfie station.',
                'sort_order'  => 15,
                'is_active'   => true,
            ],
            [
                'slug'        => 'perhiasan',
                'name'        => 'Perhiasan & Aksesori',
                'icon'        => 'star',
                'color'       => 'warning',
                'description' => 'Cincin pernikahan, perhiasan pengantin, mahkota, dan aksesori wedding.',
                'sort_order'  => 16,
                'is_active'   => true,
            ],
            [
                'slug'        => 'honeymoon',
                'name'        => 'Honeymoon & Travel',
                'icon'        => 'paper-airplane',
                'color'       => 'success',
                'description' => 'Paket honeymoon, tiket pesawat, hotel, dan tour untuk bulan madu.',
                'sort_order'  => 17,
                'is_active'   => true,
            ],
            [
                'slug'        => 'tenda-kursi',
                'name'        => 'Tenda & Perlengkapan',
                'icon'        => 'squares-plus',
                'color'       => 'gray',
                'description' => 'Sewa tenda, kursi, meja, podium, dan perlengkapan venue outdoor.',
                'sort_order'  => 18,
                'is_active'   => true,
            ],
            [
                'slug'        => 'lighting',
                'name'        => 'Lighting & Tata Cahaya',
                'icon'        => 'light-bulb',
                'color'       => 'warning',
                'description' => 'Sistem pencahayaan LED, spotlight, laser show, dan dekorasi lampu.',
                'sort_order'  => 19,
                'is_active'   => true,
            ],
            [
                'slug'        => 'sound-system',
                'name'        => 'Sound System',
                'icon'        => 'speaker-wave',
                'color'       => 'info',
                'description' => 'Sewa sound system, tata suara profesional, dan perlengkapan audio.',
                'sort_order'  => 20,
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            CategoryVendor::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
