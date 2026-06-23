<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\User;
use App\Models\VendorBooking;
use App\Models\VendorGallery;
use App\Models\VendorPackage;
use App\Models\VendorReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // ── Vendor data – 10 vendor per kategori (20 kategori = 200 vendor) ──
        $vendors = [
            // ── PAKET LENGKAP (10) ───────────────────────────────────────────────
            ['name'=>'PT Makna Signature Wedding',      'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Sudirman No. 1',            'price'=>85_000_000,'cap'=>'300 – 1.000 tamu','rating'=>4.9,'promo'=>'Hemat 10jt',   'badge'=>'TOP PICK'],
            ['name'=>'CV Royal Palembang Wedding Co.',  'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Demang Lebar Daun No. 1',   'price'=>75_000_000,'cap'=>'250 – 900 tamu', 'rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Siger Wedding Collective',        'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Kolonel Atmo No. 1',        'price'=>65_000_000,'cap'=>'200 – 800 tamu', 'rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Harmoni Wedding Partners',        'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Veteran No. 1',             'price'=>70_000_000,'cap'=>'250 – 850 tamu', 'rating'=>4.7,'promo'=>'Free MC',     'badge'=>null],
            ['name'=>'Pesona Garden Wedding House',     'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Merdeka No. 1',             'price'=>60_000_000,'cap'=>'180 – 650 tamu', 'rating'=>4.6,'promo'=>'Disc 10%',    'badge'=>null],
            ['name'=>'Ballroom Pro Wedding Group',      'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. POM IX No. 1',              'price'=>90_000_000,'cap'=>'400 – 1.500 tamu','rating'=>4.9,'promo'=>'Disc 20%',    'badge'=>'TOP PICK'],
            ['name'=>'Elegant Day Wedding Studio',      'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. R. Sukamto No. 1',          'price'=>58_000_000,'cap'=>'150 – 600 tamu', 'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'All-in Wedding Enterprise',       'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Basuki Rahmat No. 1',       'price'=>95_000_000,'cap'=>'500 – 2.000 tamu','rating'=>5.0,'promo'=>'All-Inclusive','badge'=>'TOP PICK'],
            ['name'=>'Cendana Suite Wedding Company',   'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Letkol Iskandar No. 1',     'price'=>72_000_000,'cap'=>'250 – 900 tamu', 'rating'=>4.7,'promo'=>'Hemat 5jt',   'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Sriwijaya Wedding Package Co.',   'category'=>'paket-lengkap','type'=>'Paket Lengkap', 'location'=>'Jl. Talang Semut No. 1',        'price'=>55_000_000,'cap'=>'120 – 500 tamu', 'rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── GEDUNG (10) ──────────────────────────────────────────────────────
            ['name'=>'Grand Ballroom Sriwijaya',     'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Sudirman No. 12',          'price'=>45_000_000,'cap'=>'500 – 1.500 tamu','rating'=>4.9,'promo'=>'Hemat 10jt',   'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Ballroom Mahkota Indah',       'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Demang Lebar Daun No. 5',  'price'=>40_000_000,'cap'=>'400 – 1.200 tamu','rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>'TOP PICK'],
            ['name'=>'Gedung Permata Palembang',     'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Kolonel Atmo No. 20',      'price'=>35_000_000,'cap'=>'300 – 1.000 tamu','rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Aula Sriwijaya Garden',        'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Veteran No. 8',            'price'=>28_000_000,'cap'=>'200 – 800 tamu', 'rating'=>4.6,'promo'=>'Free Lighting','badge'=>null],
            ['name'=>'Gedung Putri Kencana',         'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Basuki Rahmat No. 11',     'price'=>32_000_000,'cap'=>'300 – 900 tamu', 'rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Wedding Hall Bukit Siguntang', 'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Bukit Siguntang No. 3',    'price'=>25_000_000,'cap'=>'200 – 700 tamu', 'rating'=>4.5,'promo'=>'Disc 10%',    'badge'=>null],
            ['name'=>'Balai Agung Riverside',        'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Merdeka No. 33',           'price'=>38_000_000,'cap'=>'400 – 1.100 tamu','rating'=>4.8,'promo'=>'Hemat 5jt',   'badge'=>'TOP PICK'],
            ['name'=>'Gedung Siger Wedding',         'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. POM IX No. 14',            'price'=>22_000_000,'cap'=>'150 – 600 tamu', 'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Graha Agung Permai',           'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Kapten A. Rivai No. 15',   'price'=>30_000_000,'cap'=>'250 – 850 tamu', 'rating'=>4.6,'promo'=>'Free MC',     'badge'=>'POPULAR'],
            ['name'=>'Aula Bina Graha Palembang',    'category'=>'gedung',     'type'=>'Indoor & Outdoor',     'location'=>'Jl. Letkol Iskandar No. 7',    'price'=>27_000_000,'cap'=>'200 – 750 tamu', 'rating'=>4.6,'promo'=>null,          'badge'=>null],

            // ── HOTEL (10) ───────────────────────────────────────────────────────
            ['name'=>'Aryaduta Palembang',           'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. POM IX No. 1',             'price'=>60_000_000,'cap'=>'500 – 2.000 tamu','rating'=>4.9,'promo'=>'Disc 20%',    'badge'=>'TOP PICK'],
            ['name'=>'Novotel Palembang',            'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Rajawali No. 8',           'price'=>55_000_000,'cap'=>'400 – 1.500 tamu','rating'=>4.8,'promo'=>'Free 1 Kamar','badge'=>'NEW REAL WEDDING'],
            ['name'=>'Swissbell Hotel Palembang',    'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Radial No. 2',             'price'=>48_000_000,'cap'=>'300 – 1.200 tamu','rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Grand Inna Daira',             'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Jend. Sudirman No. 177',   'price'=>42_000_000,'cap'=>'300 – 1.000 tamu','rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>null],
            ['name'=>'Hotel Santika Palembang',      'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Basuki Rahmat No. 37',     'price'=>38_000_000,'cap'=>'250 – 900 tamu', 'rating'=>4.6,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Whiz Prime Hotel',             'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Angkatan 45 No. 5',        'price'=>30_000_000,'cap'=>'200 – 600 tamu', 'rating'=>4.5,'promo'=>'Hemat 8jt',   'badge'=>null],
            ['name'=>'Hotel Bumi Asih Palembang',    'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Veteran No. 36',           'price'=>25_000_000,'cap'=>'150 – 500 tamu', 'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Horison Ultima Palembang',     'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Demang Lebar Daun No. 26', 'price'=>44_000_000,'cap'=>'300 – 1.100 tamu','rating'=>4.7,'promo'=>'Free Dessert','badge'=>'NEW REAL WEDDING'],
            ['name'=>'Harper Palembang',             'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. Letkol Iskandar No. 21',   'price'=>50_000_000,'cap'=>'400 – 1.300 tamu','rating'=>4.8,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Aston Palembang Hotel',        'category'=>'hotel',      'type'=>'Indoor',         'location'=>'Jl. R. Sukamto No. 9',         'price'=>35_000_000,'cap'=>'250 – 800 tamu', 'rating'=>4.6,'promo'=>'Disc 15%',    'badge'=>null],

            // ── RUMAH & TAMAN (10) ───────────────────────────────────────────────
            ['name'=>'Villa Kambang Sari',           'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Soekarno-Hatta',           'price'=>20_000_000,'cap'=>'100 – 300 tamu', 'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Taman Orchid Wedding Garden',  'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Talang Semut No. 5',       'price'=>15_000_000,'cap'=>'80 – 250 tamu',  'rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Villa Hijau Palembang',        'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Kapten Anwar Sastro No. 3','price'=>18_000_000,'cap'=>'100 – 280 tamu', 'rating'=>4.7,'promo'=>'Free Tenda',  'badge'=>null],
            ['name'=>'Kebun Sari Outdoor Wedding',   'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Alang-Alang Lebar No. 2',  'price'=>12_000_000,'cap'=>'70 – 200 tamu',  'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Taman Bunga Indah',            'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Merdeka No. 7',            'price'=>16_000_000,'cap'=>'80 – 230 tamu',  'rating'=>4.6,'promo'=>'Disc 5%',     'badge'=>'TOP PICK'],
            ['name'=>'Gazebo Garden Palembang',      'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Bukit Kecil No. 4',        'price'=>10_000_000,'cap'=>'50 – 150 tamu',  'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'Villa Cantik Riverside',       'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Riverside No. 12',         'price'=>22_000_000,'cap'=>'120 – 350 tamu', 'rating'=>4.8,'promo'=>'Hemat 3jt',   'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Taman Rindang Venue',          'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Golf No. 1',               'price'=>14_000_000,'cap'=>'80 – 220 tamu',  'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Green Garden Palembang',       'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Sriwijaya No. 9',          'price'=>17_000_000,'cap'=>'90 – 260 tamu',  'rating'=>4.6,'promo'=>'Free Bunga',  'badge'=>'POPULAR'],
            ['name'=>'Rumah Taman Anggrek',          'category'=>'rumah',      'type'=>'Outdoor',            'location'=>'Jl. Demang Lebar Daun No. 10', 'price'=>13_000_000,'cap'=>'60 – 180 tamu',  'rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── WEDDING ORGANIZER (10) ───────────────────────────────────────────
            ['name'=>'Sriwijaya Wedding Organizer',  'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Veteran No. 20',           'price'=>10_000_000,'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Harmoni Wedding Planner',      'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Demang Lebar Daun No. 14', 'price'=>8_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Mulia Wedding Organizer',      'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Sudirman No. 30',          'price'=>7_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Pesona Events Planner',        'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Angkatan 45 No. 12',       'price'=>6_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Free Konsul', 'badge'=>null],
            ['name'=>'Bahagia Wedding Team',         'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Kolonel Atmo No. 5',       'price'=>9_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Pernikahan Impian WO',         'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Letkol Iskandar No. 3',    'price'=>5_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Surya Wedding Organizer',      'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Kapten A. Rivai No. 7',    'price'=>7_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Kenangan Indah Event',         'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Merdeka No. 22',           'price'=>6_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Royal Wedding Coordinator',    'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. Basuki Rahmat No. 17',     'price'=>12_000_000,'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>'Hemat 2jt',   'badge'=>'TOP PICK'],
            ['name'=>'Prima Bridal Organizer',       'category'=>'wo',         'type'=>'Wedding Organizer',        'location'=>'Jl. R. Sukamto No. 4',         'price'=>5_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── CATERING (10) ────────────────────────────────────────────────────
            ['name'=>'Puspa Catering Palembang',     'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Demang Lebar Daun',        'price'=>8_500_000, 'cap'=>'300 – 2.000 pax','rating'=>4.8,'promo'=>'Gratis Cicip','badge'=>'TOP PICK'],
            ['name'=>'Sari Rasa Catering',           'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Sudirman No. 44',          'price'=>7_000_000, 'cap'=>'200 – 1.500 pax','rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Berkah Katering Palembang',    'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Veteran No. 11',           'price'=>6_500_000, 'cap'=>'200 – 1.000 pax','rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],
            ['name'=>'Makan Enak Catering',          'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Angkatan 45 No. 7',        'price'=>5_500_000, 'cap'=>'150 – 800 pax',  'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Rumah Makan Sriwijaya',        'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Merdeka No. 18',           'price'=>9_000_000, 'cap'=>'300 – 2.500 pax','rating'=>4.8,'promo'=>'Free Minuman','badge'=>'TOP PICK'],
            ['name'=>'Dapur Berkah Catering',        'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Kolonel Atmo No. 3',       'price'=>5_000_000, 'cap'=>'100 – 700 pax',  'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Chef Wedding Palembang',       'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Kapten A. Rivai No. 10',   'price'=>10_000_000,'cap'=>'300 – 3.000 pax','rating'=>4.9,'promo'=>'Free Cicip', 'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Nusantara Catering',           'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Letkol Iskandar No. 14',   'price'=>6_000_000, 'cap'=>'200 – 1.200 pax','rating'=>4.6,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Bunda Catering Palembang',     'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. POM IX No. 22',            'price'=>7_500_000, 'cap'=>'250 – 1.800 pax','rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Pesona Rasa Catering',         'category'=>'catering',   'type'=>'Catering & Makanan',       'location'=>'Jl. Basuki Rahmat No. 6',      'price'=>4_500_000, 'cap'=>'100 – 600 pax',  'rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── DEKORASI & FLORIST (10) ──────────────────────────────────────────
            ['name'=>'Bunga Rampai Dekorasi',        'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Letkol Iskandar No. 5',    'price'=>7_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>null],
            ['name'=>'Floral Dream Palembang',       'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Sudirman No. 28',          'price'=>9_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Indah Dekorasi Wedding',       'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Basuki Rahmat No. 9',      'price'=>6_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Free Buket',  'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Taman Sari Florist',           'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Veteran No. 34',           'price'=>5_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Cantik Florist & Dekor',       'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Demang Lebar Daun No. 8',  'price'=>8_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],
            ['name'=>'Anggrek Florist Palembang',    'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Angkatan 45 No. 16',       'price'=>4_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Pesona Decor Studio',          'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Talang Semut No. 7',       'price'=>10_000_000,'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>'Free Konsul', 'badge'=>'TOP PICK'],
            ['name'=>'Kenanga Florist & Dekor',      'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Kolonel Atmo No. 12',      'price'=>6_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Matahari Dekorasi',            'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. R. Sukamto No. 21',        'price'=>7_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Disc 5%',     'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Dekorasi Permata Sari',        'category'=>'dekorasi',   'type'=>'Dekorasi & Florist',       'location'=>'Jl. Merdeka No. 29',           'price'=>5_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── FOTOGRAFER & VIDEOGRAFER (10) ────────────────────────────────────
            ['name'=>'Sriwijaya Frame Studio',       'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Angkatan 45 No. 21',       'price'=>6_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Moment Story Photography',     'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Demang Lebar Daun No. 19', 'price'=>5_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'TOP PICK'],
            ['name'=>'Kenangan Abadi Studio',        'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Sudirman No. 50',          'price'=>7_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Sinema Wedding Palembang',     'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Veteran No. 16',           'price'=>8_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>'Free SDE',    'badge'=>'TOP PICK'],
            ['name'=>'Foto Impian Palembang',        'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Kolonel Atmo No. 22',      'price'=>4_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Visual Story Media',           'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Basuki Rahmat No. 14',     'price'=>5_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Disc 5%',     'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Lensa Kita Photography',       'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Kapten A. Rivai No. 16',   'price'=>3_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Cinematic Arts Palembang',     'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. POM IX No. 18',            'price'=>9_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>'Free Drone',  'badge'=>'TOP PICK'],
            ['name'=>'Kreasi Studio Wedding',        'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. R. Sukamto No. 5',         'price'=>4_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Mutiara Foto & Film',          'category'=>'foto-video', 'type'=>'Fotografer & Videografer', 'location'=>'Jl. Merdeka No. 41',           'price'=>6_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],

            // ── MAKEUP & TATA RIAS (10) ──────────────────────────────────────────
            ['name'=>'Cantik Bestari MUA',           'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Kapten A. Rivai No. 8',    'price'=>3_500_000, 'cap'=>'Pengantin & Tim','rating'=>4.8,'promo'=>'Free Touch-up','badge'=>null],
            ['name'=>'Mahkota Rias Pengantin',       'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Sudirman No. 35',          'price'=>4_000_000, 'cap'=>'Pengantin & Tim','rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Ayu Beauty Studio',            'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Basuki Rahmat No. 20',     'price'=>3_000_000, 'cap'=>'Pengantin & Tim','rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Srikandi MUA Palembang',       'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Veteran No. 25',           'price'=>3_200_000, 'cap'=>'Pengantin & Tim','rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Glam & Glow Bridal',           'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Demang Lebar Daun No. 22', 'price'=>5_000_000, 'cap'=>'Pengantin & Tim','rating'=>4.9,'promo'=>'Free Skincare','badge'=>'TOP PICK'],
            ['name'=>'Safira Beauty Artist',         'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Talang Semut No. 3',       'price'=>2_500_000, 'cap'=>'Pengantin & Tim','rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Putri Rias Studio',            'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Kolonel Atmo No. 18',      'price'=>3_800_000, 'cap'=>'Pengantin & Tim','rating'=>4.8,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Nadia MUA Profesional',        'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Angkatan 45 No. 9',        'price'=>2_800_000, 'cap'=>'Pengantin & Tim','rating'=>4.6,'promo'=>'Free Lash',   'badge'=>null],
            ['name'=>'Cantika Beauty House',         'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. POM IX No. 11',            'price'=>4_500_000, 'cap'=>'Pengantin & Tim','rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>'POPULAR'],
            ['name'=>'Rara Makeup Artist',           'category'=>'makeup',     'type'=>'Makeup & Tata Rias',       'location'=>'Jl. Letkol Iskandar No. 9',    'price'=>2_200_000, 'cap'=>'Pengantin & Tim','rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── GAUN & BUSANA (10) ───────────────────────────────────────────────
            ['name'=>'Mahkota Pengantin Boutique',   'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Talang Semut No. 12',      'price'=>4_000_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.7,'promo'=>null,  'badge'=>'POPULAR'],
            ['name'=>'Bridal House Palembang',       'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Sudirman No. 40',          'price'=>5_500_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.8,'promo'=>'Disc 15%','badge'=>'TOP PICK'],
            ['name'=>'Kebaya Cantik Studio',         'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Demang Lebar Daun No. 16', 'price'=>3_500_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.7,'promo'=>null,  'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Gaun Impian Boutique',         'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Basuki Rahmat No. 32',     'price'=>6_000_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.8,'promo'=>'Free Jas','badge'=>null],
            ['name'=>'Adat Sriwijaya Busana',        'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Veteran No. 28',           'price'=>4_500_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.7,'promo'=>null,  'badge'=>'POPULAR'],
            ['name'=>'Putri Bridal Collection',      'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Kolonel Atmo No. 9',       'price'=>3_000_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.5,'promo'=>null,  'badge'=>null],
            ['name'=>'Moda Pengantin Palembang',     'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Letkol Iskandar No. 21',   'price'=>7_000_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.9,'promo'=>'Free Fitting','badge'=>'TOP PICK'],
            ['name'=>'Srikandi Bridal Fashion',      'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Angkatan 45 No. 14',       'price'=>2_500_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.4,'promo'=>null,  'badge'=>null],
            ['name'=>'Elegan Bridal Store',          'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. Merdeka No. 33',           'price'=>5_000_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.7,'promo'=>'Disc 10%','badge'=>'NEW REAL WEDDING'],
            ['name'=>'Busana Prima Pengantin',       'category'=>'gaun',       'type'=>'Gaun & Busana Pengantin',  'location'=>'Jl. R. Sukamto No. 14',        'price'=>2_000_000, 'cap'=>'Pengantin & Keluarga','rating'=>4.3,'promo'=>null,  'badge'=>null],

            // ── HIBURAN & MUSIK (10) ─────────────────────────────────────────────
            ['name'=>'Harmoni Live Entertainment',   'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Basuki Rahmat No. 3',      'price'=>5_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Free 1 Lagu','badge'=>null],
            ['name'=>'Gita Nada Band Palembang',     'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Sudirman No. 47',          'price'=>6_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Sriwijaya Music Entertainment','category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Veteran No. 42',           'price'=>4_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'DJ Palembang Pro',             'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Angkatan 45 No. 19',       'price'=>3_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Sanggar Seni Sriwijaya',       'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Kolonel Atmo No. 16',      'price'=>4_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Live Band Wedding Pro',        'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Letkol Iskandar No. 28',   'price'=>7_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Free 2 Lagu', 'badge'=>'TOP PICK'],
            ['name'=>'Keroncong Sriwijaya',          'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Talang Semut No. 16',      'price'=>2_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Akustika Wedding Band',        'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Demang Lebar Daun No. 30', 'price'=>3_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Maestro Entertainment',        'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. POM IX No. 26',            'price'=>8_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Nada Indah Music',             'category'=>'hiburan',    'type'=>'Hiburan & Musik',          'location'=>'Jl. Kapten A. Rivai No. 22',   'price'=>2_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.4,'promo'=>null,          'badge'=>null],

            // ── UNDANGAN & SOUVENIR (10) ─────────────────────────────────────────
            ['name'=>'Titian Rasa Undangan',         'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Merdeka No. 15',           'price'=>2_500_000, 'cap'=>'Min. 100 pcs',   'rating'=>4.6,'promo'=>'Disc 10%',    'badge'=>null],
            ['name'=>'Karya Indah Undangan',         'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Sudirman No. 55',          'price'=>3_000_000, 'cap'=>'Min. 100 pcs',   'rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Percetakan Sriwijaya',         'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Veteran No. 38',           'price'=>2_000_000, 'cap'=>'Min. 50 pcs',    'rating'=>4.5,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Souvenir Cantik Palembang',    'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Basuki Rahmat No. 26',     'price'=>3_500_000, 'cap'=>'Min. 200 pcs',   'rating'=>4.7,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Undangan Digital Pro',         'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Angkatan 45 No. 23',       'price'=>1_500_000, 'cap'=>'Tidak terbatas', 'rating'=>4.6,'promo'=>'Free QR Code','badge'=>'POPULAR'],
            ['name'=>'Craftwork Souvenir Studio',    'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Letkol Iskandar No. 32',   'price'=>4_000_000, 'cap'=>'Min. 200 pcs',   'rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Hampers Pengantin Palembang',  'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Kolonel Atmo No. 25',      'price'=>5_000_000, 'cap'=>'Min. 100 pcs',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Kertas Tinta Percetakan',      'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. R. Sukamto No. 17',        'price'=>1_800_000, 'cap'=>'Min. 50 pcs',    'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'Cinta Cetak Undangan',         'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. Talang Semut No. 20',      'price'=>2_200_000, 'cap'=>'Min. 100 pcs',   'rating'=>4.5,'promo'=>'Disc 10%',    'badge'=>null],
            ['name'=>'Premium Cards Palembang',      'category'=>'undangan',   'type'=>'Undangan & Souvenir',      'location'=>'Jl. POM IX No. 30',            'price'=>4_500_000, 'cap'=>'Min. 200 pcs',   'rating'=>4.8,'promo'=>null,          'badge'=>'POPULAR'],

            // ── TRANSPORTASI (10) ────────────────────────────────────────────────
            ['name'=>'Elegance Wedding Cars',        'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Kolonel Atmo No. 7',       'price'=>1_500_000, 'cap'=>'1 – 10 unit',    'rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Mewah Rental Pengantin',       'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Sudirman No. 60',          'price'=>2_000_000, 'cap'=>'1 – 5 unit',     'rating'=>4.7,'promo'=>'Free Dekor',  'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Royal Car Wedding',            'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Veteran No. 44',           'price'=>2_500_000, 'cap'=>'1 – 8 unit',     'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Classic Car Palembang',        'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Demang Lebar Daun No. 32', 'price'=>3_000_000, 'cap'=>'1 – 4 unit',     'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],
            ['name'=>'Limousine Service Palembang',  'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Angkatan 45 No. 27',       'price'=>3_500_000, 'cap'=>'1 – 3 unit',     'rating'=>4.7,'promo'=>null,          'badge'=>null],
            ['name'=>'Motor Pengantin Palembang',    'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Basuki Rahmat No. 41',     'price'=>500_000,   'cap'=>'1 – 5 unit',     'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'Bis Shuttle Tamu',             'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Letkol Iskandar No. 39',   'price'=>1_200_000, 'cap'=>'1 – 10 unit',    'rating'=>4.5,'promo'=>'Free Air',    'badge'=>null],
            ['name'=>'Star Wedding Cars',            'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. Kapten A. Rivai No. 24',   'price'=>1_800_000, 'cap'=>'1 – 7 unit',     'rating'=>4.6,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Platinum Auto Wedding',        'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. POM IX No. 34',            'price'=>2_800_000, 'cap'=>'1 – 6 unit',     'rating'=>4.7,'promo'=>'Disc 15%',    'badge'=>null],
            ['name'=>'Garuda Transport Pengantin',   'category'=>'transportasi','type'=>'Transportasi Pengantin',   'location'=>'Jl. R. Sukamto No. 10',        'price'=>1_000_000, 'cap'=>'1 – 12 unit',    'rating'=>4.5,'promo'=>null,          'badge'=>null],

            // ── KUE PENGANTIN (10) ───────────────────────────────────────────────
            ['name'=>'Tarts & Treats Palembang',     'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Demang No. 22',            'price'=>1_800_000, 'cap'=>'Custom semua',   'rating'=>4.8,'promo'=>'Free Tasting','badge'=>'NEW REAL WEDDING'],
            ['name'=>'Sweet Moments Cake',           'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Sudirman No. 62',          'price'=>2_200_000, 'cap'=>'Custom semua',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Elegant Cake Studio',          'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Demang Lebar Daun No. 35', 'price'=>2_500_000, 'cap'=>'Custom semua',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],
            ['name'=>'Dapur Kue Palembang',          'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Basuki Rahmat No. 48',     'price'=>1_200_000, 'cap'=>'Custom semua',   'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Cake Dream Palembang',         'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Veteran No. 50',           'price'=>3_000_000, 'cap'=>'Custom semua',   'rating'=>4.9,'promo'=>'Free Tasting','badge'=>'TOP PICK'],
            ['name'=>'Manis Cake House',             'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Angkatan 45 No. 30',       'price'=>1_500_000, 'cap'=>'Custom semua',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Artisan Wedding Cake',         'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Letkol Iskandar No. 42',   'price'=>3_500_000, 'cap'=>'Custom semua',   'rating'=>4.9,'promo'=>'Free Cupcake','badge'=>'NEW REAL WEDDING'],
            ['name'=>'Putri Bakery Palembang',       'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Kolonel Atmo No. 28',      'price'=>1_000_000, 'cap'=>'Custom semua',   'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'Choco Delight Wedding',        'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. Talang Semut No. 22',      'price'=>2_000_000, 'cap'=>'Custom semua',   'rating'=>4.7,'promo'=>'Disc 15%',    'badge'=>'POPULAR'],
            ['name'=>'Vanilla Dream Cakery',         'category'=>'kue-pengantin','type'=>'Kue Pengantin',           'location'=>'Jl. R. Sukamto No. 25',        'price'=>2_800_000, 'cap'=>'Custom semua',   'rating'=>4.8,'promo'=>null,          'badge'=>null],

            // ── MC & PEMBAWA ACARA (10) ──────────────────────────────────────────
            ['name'=>'Suara Emas MC Profesional',    'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Sriwijaya Raya No. 9',     'price'=>2_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>null],
            ['name'=>'MC Profesional Palembang',     'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Sudirman No. 65',          'price'=>2_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'TOP PICK'],
            ['name'=>'Presenter Wedding Pro',        'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Veteran No. 52',           'price'=>1_800_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Hendra MC Sriwijaya',          'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Basuki Rahmat No. 54',     'price'=>1_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Voice of Wedding Palembang',   'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Demang Lebar Daun No. 40', 'price'=>3_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>'Free Rundown','badge'=>'TOP PICK'],
            ['name'=>'MC Adat Sriwijaya',            'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Letkol Iskandar No. 45',   'price'=>2_200_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Bilingual MC Profesional',     'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Angkatan 45 No. 33',       'price'=>3_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Dwi MC Wedding',               'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Kolonel Atmo No. 31',      'price'=>1_200_000, 'cap'=>'Semua lokasi',   'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'MC Elok Palembang',            'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. Kapten A. Rivai No. 27',   'price'=>1_800_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Grand MC Indonesia',           'category'=>'mc',         'type'=>'MC & Pembawa Acara',        'location'=>'Jl. POM IX No. 38',            'price'=>4_000_000, 'cap'=>'Semua lokasi',   'rating'=>5.0,'promo'=>'Free Full',   'badge'=>'TOP PICK'],

            // ── FOTOBOOTH (10) ───────────────────────────────────────────────────
            ['name'=>'Snap Moment Fotobooth',        'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Alang-Alang Lebar',        'price'=>1_200_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Free Props',  'badge'=>null],
            ['name'=>'Magic Mirror Palembang',       'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Sudirman No. 68',          'price'=>1_800_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'360 Spin Booth Studio',        'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Veteran No. 55',           'price'=>2_200_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'TOP PICK'],
            ['name'=>'Insta Print Booth',            'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Basuki Rahmat No. 60',     'price'=>1_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'GIF Booth Palembang',          'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Demang Lebar Daun No. 45', 'price'=>1_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Free GIF',    'badge'=>'POPULAR'],
            ['name'=>'Selfie Station Wedding',       'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Angkatan 45 No. 36',       'price'=>900_000,   'cap'=>'Semua lokasi',   'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'Vintage Photo Booth',          'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Letkol Iskandar No. 48',   'price'=>1_300_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Premium Booth Palembang',      'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Kapitten A. Rivai No. 30', 'price'=>2_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Neon Photo Booth',             'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Talang Semut No. 27',      'price'=>1_700_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Free Neon',   'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Open Air Booth Palembang',     'category'=>'fotobooth',  'type'=>'Fotobooth & Booth',         'location'=>'Jl. Kolonel Atmo No. 34',      'price'=>800_000,   'cap'=>'Semua lokasi',   'rating'=>4.3,'promo'=>null,          'badge'=>null],

            // ── PERHIASAN & AKSESORI (10) ────────────────────────────────────────
            ['name'=>'Emas Sriwijaya Jewellery',     'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Sudirman No. 45',          'price'=>3_000_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.9,'promo'=>'Free Ukir','badge'=>'TOP PICK'],
            ['name'=>'Diamond Palace Palembang',     'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Demang Lebar Daun No. 48', 'price'=>5_000_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.9,'promo'=>null,   'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Toko Emas Cahaya',             'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Veteran No. 57',           'price'=>2_000_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.6,'promo'=>'Disc 5%','badge'=>null],
            ['name'=>'Berlian Wedding Collection',   'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Basuki Rahmat No. 66',     'price'=>8_000_000, 'cap'=>'Pasangan & Keluarga','rating'=>5.0,'promo'=>null,   'badge'=>'TOP PICK'],
            ['name'=>'Silver Craft Palembang',       'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Angkatan 45 No. 40',       'price'=>1_500_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.5,'promo'=>'Free Ukir','badge'=>null],
            ['name'=>'Mahkota Jewel Bridal',         'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Letkol Iskandar No. 53',   'price'=>4_000_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.8,'promo'=>null,   'badge'=>'POPULAR'],
            ['name'=>'Gold Art Palembang',           'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Kolonel Atmo No. 37',      'price'=>2_500_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.7,'promo'=>'Disc 10%','badge'=>'NEW REAL WEDDING'],
            ['name'=>'Aksesoris Pengantin Premium',  'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. Kapten A. Rivai No. 33',   'price'=>1_200_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.4,'promo'=>null,   'badge'=>null],
            ['name'=>'Cincin Kawin Palembang',       'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. R. Sukamto No. 27',        'price'=>3_500_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.8,'promo'=>'Free Box','badge'=>'POPULAR'],
            ['name'=>'Toko Intan Permata',           'category'=>'perhiasan',  'type'=>'Perhiasan & Aksesori',      'location'=>'Jl. POM IX No. 42',            'price'=>6_000_000, 'cap'=>'Pasangan & Keluarga','rating'=>4.9,'promo'=>null,   'badge'=>'TOP PICK'],

            // ── HONEYMOON & TRAVEL (10) ──────────────────────────────────────────
            ['name'=>'Bahagia Tour & Travel',        'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Kapt. A. Rivai No. 2',     'price'=>5_500_000, 'cap'=>'2 orang',        'rating'=>4.7,'promo'=>'Disc 20%',    'badge'=>null],
            ['name'=>'Pesona Wisata Honeymoon',      'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Sudirman No. 72',          'price'=>7_000_000, 'cap'=>'2 orang',        'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Romantis Travel Palembang',    'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Veteran No. 60',           'price'=>4_500_000, 'cap'=>'2 orang',        'rating'=>4.6,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Dream Holiday Travel',         'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Demang Lebar Daun No. 52', 'price'=>10_000_000,'cap'=>'2 orang',        'rating'=>4.9,'promo'=>'Free Spa',    'badge'=>'TOP PICK'],
            ['name'=>'Bulan Madu Wisata',            'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Angkatan 45 No. 44',       'price'=>3_500_000, 'cap'=>'2 orang',        'rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'Bali Dream Honeymoon',         'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Letkol Iskandar No. 57',   'price'=>8_000_000, 'cap'=>'2 orang',        'rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>'POPULAR'],
            ['name'=>'International Honeymoon Tour', 'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Basuki Rahmat No. 72',     'price'=>15_000_000,'cap'=>'2 orang',        'rating'=>4.9,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Wisata Impian Pasangan',       'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Kolonel Atmo No. 41',      'price'=>5_000_000, 'cap'=>'2 orang',        'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Raja Ampat Honeymoon',         'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. Kapten A. Rivai No. 36',   'price'=>12_000_000,'cap'=>'2 orang',        'rating'=>4.9,'promo'=>'Disc 10%',    'badge'=>'TOP PICK'],
            ['name'=>'Lombok & Gili Trip',           'category'=>'honeymoon',  'type'=>'Honeymoon & Travel',        'location'=>'Jl. POM IX No. 45',            'price'=>6_500_000, 'cap'=>'2 orang',        'rating'=>4.7,'promo'=>null,          'badge'=>'POPULAR'],

            // ── TENDA & PERLENGKAPAN (10) ────────────────────────────────────────
            ['name'=>'Prima Tenda & Event Supply',   'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. POM IX No. 5',             'price'=>4_500_000, 'cap'=>'100 – 5.000 pax','rating'=>4.6,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Mulia Tenda Palembang',        'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Sudirman No. 75',          'price'=>5_500_000, 'cap'=>'200 – 6.000 pax','rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>'TOP PICK'],
            ['name'=>'Bersama Event Rental',         'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Veteran No. 63',           'price'=>3_500_000, 'cap'=>'100 – 3.000 pax','rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Sewa Kursi Palembang',         'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Basuki Rahmat No. 78',     'price'=>2_000_000, 'cap'=>'100 – 2.000 pax','rating'=>4.4,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Tenda Kuat Event Supply',      'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Demang Lebar Daun No. 57', 'price'=>6_000_000, 'cap'=>'300 – 7.000 pax','rating'=>4.8,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Elegance Event Furniture',     'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Letkol Iskandar No. 61',   'price'=>8_000_000, 'cap'=>'300 – 5.000 pax','rating'=>4.8,'promo'=>'Free Setup',  'badge'=>'TOP PICK'],
            ['name'=>'Partisi & Dekor Event',        'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Angkatan 45 No. 48',       'price'=>3_000_000, 'cap'=>'100 – 2.500 pax','rating'=>4.5,'promo'=>null,          'badge'=>null],
            ['name'=>'VIP Table & Chair Rental',     'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Kolonel Atmo No. 44',      'price'=>4_000_000, 'cap'=>'150 – 4.000 pax','rating'=>4.6,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],
            ['name'=>'Tenda Besar Palembang',        'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. Kapten A. Rivai No. 39',   'price'=>7_000_000, 'cap'=>'500 – 8.000 pax','rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Event Pro Equipment',          'category'=>'tenda-kursi','type'=>'Tenda & Perlengkapan',      'location'=>'Jl. R. Sukamto No. 32',        'price'=>2_500_000, 'cap'=>'80 – 1.500 pax', 'rating'=>4.4,'promo'=>null,          'badge'=>null],

            // ── LIGHTING (10) ────────────────────────────────────────────────────
            ['name'=>'Cahaya Indah Lighting',        'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. R. Sukamto No. 18',        'price'=>3_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Gratis Design','badge'=>null],
            ['name'=>'LED Wedding Light',            'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Sudirman No. 78',          'price'=>4_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Stage Lighting Palembang',     'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Veteran No. 66',           'price'=>5_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 10%',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Sparkle Light Events',         'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Basuki Rahmat No. 84',     'price'=>3_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],
            ['name'=>'Moving Head Pro',              'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Demang Lebar Daun No. 60', 'price'=>6_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Free Laser',  'badge'=>'TOP PICK'],
            ['name'=>'Atmosphere Lighting Bali',     'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Angkatan 45 No. 52',       'price'=>7_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>null,          'badge'=>'POPULAR'],
            ['name'=>'Fairy Light Palembang',        'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Letkol Iskandar No. 65',   'price'=>2_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Neon & LED Event',             'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Kolonel Atmo No. 47',      'price'=>4_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Photon Light Studio',          'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. Kapten A. Rivai No. 42',   'price'=>5_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Disc 15%',    'badge'=>'POPULAR'],
            ['name'=>'Glamour Light Rental',         'category'=>'lighting',   'type'=>'Lighting & Tata Cahaya',    'location'=>'Jl. POM IX No. 50',            'price'=>3_200_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>null,          'badge'=>null],

            // ── SOUND SYSTEM (10) ────────────────────────────────────────────────
            ['name'=>'Suara Prima Audio',            'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Angkatan 45 No. 3',        'price'=>2_800_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Free Mic',    'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Pro Sound Palembang',          'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Sudirman No. 80',          'price'=>3_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Audio Pro Wedding',            'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Veteran No. 68',           'price'=>4_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.7,'promo'=>'Disc 10%',    'badge'=>'POPULAR'],
            ['name'=>'Line Array Sound Rental',      'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Basuki Rahmat No. 90',     'price'=>5_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>null,          'badge'=>'NEW REAL WEDDING'],
            ['name'=>'Suara Jernih Audio',           'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Demang Lebar Daun No. 65', 'price'=>2_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.5,'promo'=>'Free Setup',  'badge'=>null],
            ['name'=>'WL Sound System Palembang',    'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Letkol Iskandar No. 69',   'price'=>6_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>null,          'badge'=>'TOP PICK'],
            ['name'=>'Live Audio Events',            'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Angkatan 45 No. 56',       'price'=>3_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.6,'promo'=>'Disc 5%',     'badge'=>null],
            ['name'=>'Speaker Aktif Rental',         'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Kolonel Atmo No. 50',      'price'=>1_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.4,'promo'=>null,          'badge'=>null],
            ['name'=>'Sound Stage Palembang',        'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. Kapten A. Rivai No. 45',   'price'=>4_500_000, 'cap'=>'Semua lokasi',   'rating'=>4.8,'promo'=>'Free Monitor','badge'=>'POPULAR'],
            ['name'=>'Elite Audio Wedding',          'category'=>'sound-system','type'=>'Sound System',             'location'=>'Jl. R. Sukamto No. 36',        'price'=>7_000_000, 'cap'=>'Semua lokasi',   'rating'=>4.9,'promo'=>null,          'badge'=>'TOP PICK'],
        ];

        // Package templates per category
        $packageTpl = [
            'paket-lengkap' => [
                ['name'=>'Paket Lengkap Silver',   'mult'=>1, 'cap'=>300,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['WO & Koordinasi', 'Venue / Gedung', 'Dekorasi Dasar', 'Katering', 'Sound System', 'MC', 'Dokumentasi Foto']],
                ['name'=>'Paket Lengkap Gold',     'mult'=>2, 'cap'=>500,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['WO Full Planning', 'Venue Premium', 'Dekorasi Premium', 'Katering', 'Sound & Lighting', 'MC Profesional', 'Foto & Video']],
                ['name'=>'Paket Lengkap Platinum', 'mult'=>3, 'cap'=>1000, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['WO Full Service', 'Venue Full Day', 'Dekorasi Eksklusif', 'Katering', 'Full AV System', 'MC + Hiburan', 'Foto & Video Sinema', 'Mobil Pengantin', 'Kue Pengantin']],
            ],
            'gedung' => [
                ['name'=>'Paket Silver',   'mult'=>1,'cap'=>300,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Gedung 6 jam','Dekorasi Dasar','Sound System','MC Lokal','Perlengkapan Ibadah']],
                ['name'=>'Paket Gold',     'mult'=>2,'cap'=>500,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Gedung 8 jam','Dekorasi Premium','Sound System Pro','MC Profesional','Kamar Pengantin','Dokumentasi Foto']],
                ['name'=>'Paket Platinum', 'mult'=>3,'cap'=>1000, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Gedung Full Day','Dekorasi Eksklusif','Sound & Lighting','MC + Hiburan','Kamar Pengantin VIP','Foto & Video Sinema','Kue Pengantin','Mobil Pengantin']],
            ],
            'hotel' => [
                ['name'=>'Paket Silver',   'mult'=>1,'cap'=>200,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Ballroom 6 jam','Dekorasi Standar','Katering 200 pax','Sound System','MC']],
                ['name'=>'Paket Gold',     'mult'=>2,'cap'=>500,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Ballroom 8 jam','Dekorasi Mewah','Katering 500 pax','Sound & Lighting','MC Profesional','2 Kamar Hotel']],
                ['name'=>'Paket Platinum', 'mult'=>3,'cap'=>1000, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Ballroom Full Day','Dekorasi VIP','Katering 1000 pax','Full AV System','MC + Entertainer','5 Kamar Hotel','Foto & Video','Foto Prewedding']],
            ],
            'rumah' => [
                ['name'=>'Paket Basic',    'mult'=>1,'cap'=>100,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Tenda Dekorasi','Sound System','MC','Perlengkapan Ibadah']],
                ['name'=>'Paket Lengkap',  'mult'=>2,'cap'=>200,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Tenda & Dekorasi Premium','Sound System','MC','Katering 200 pax','Dokumentasi Foto']],
                ['name'=>'Paket All-in',   'mult'=>3,'cap'=>300,  'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Tenda & Dekorasi Eksklusif','Sound & Lighting','MC Profesional','Katering 300 pax','Foto & Video','Kue Pengantin']],
            ],
            'wo' => [
                ['name'=>'Paket Dasar',    'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Koordinasi Hari H','Rundown Acara','1 WO Tim']],
                ['name'=>'Paket Standar',  'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Koordinasi Penuh','Dekorasi Konsultasi','Vendor Rekomendasi','2 WO Tim','Rundown Detail']],
                ['name'=>'Paket Premium',  'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Full Planning','Dekorasi Eksklusif','Semua Vendor Diurus','4 WO Tim','Dokumentasi','Koordinasi H-3']],
            ],
            'catering' => [
                ['name'=>'Paket Basic',    'mult'=>1,'cap'=>200,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Prasmanan 200 pax','5 Menu Pilihan','Petugas 4 Orang','Peralatan Makan']],
                ['name'=>'Paket Standar',  'mult'=>2,'cap'=>500,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Prasmanan 500 pax','8 Menu Pilihan','Petugas 8 Orang','Peralatan Makan','Welcome Drink','Dessert Table']],
                ['name'=>'Paket Premium',  'mult'=>3,'cap'=>1000, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Prasmanan 1000 pax','12 Menu Pilihan','Petugas 15 Orang','Peralatan Makan Eksklusif','Welcome Drink','Dessert Table','Live Cooking Station','Minuman Unlimited']],
            ],
            'dekorasi' => [
                ['name'=>'Paket Minimalis','mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Dekorasi Pelaminan','Backdrop Standar','Rangkaian Bunga Meja','Pita & Aksesori']],
                ['name'=>'Paket Elegan',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Dekorasi Pelaminan Premium','Backdrop Bunga Hidup','Rangkaian Bunga Segar','Buket Pengantin','Dekorasi Lorong','Photo Corner']],
                ['name'=>'Paket Mewah',    'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Dekorasi Pelaminan Eksklusif','Seluruh Venue Terdekorasi','Bunga Hidup Segar','Buket & Corsage','Gate Bunga','Canopy Floral','Floating Candle','Photobooth Corner Premium']],
            ],
            'foto-video' => [
                ['name'=>'Paket Foto',     'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Foto 8 Jam','2 Fotografer','Soft File HD','Album Digital']],
                ['name'=>'Paket Foto+Video','mult'=>2,'cap'=>null,'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Foto & Video 10 Jam','2 Fotografer + Videografer','Video Highlight 5 Menit','Soft File HD','Album Fisik']],
                ['name'=>'Paket Sinematik','mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Foto & Video Full Day','3 Fotografer + 2 Videografer','Cinematic Video','Same Day Edit','Drone Footage','Foto Prewedding','Album Fisik Premium','Raw File']],
            ],
            'makeup' => [
                ['name'=>'Paket Basic',    'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Rias Pengantin','1x Ganti Busana','Sanggul Tradisional']],
                ['name'=>'Paket Lengkap',  'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Rias Pengantin','2x Ganti Busana','Sanggul Modern & Tradisional','Rias Ibu Pengantin','Free Touch-up']],
                ['name'=>'Paket All-Day',  'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Rias Pengantin Full Day','3x Ganti Busana','Sanggul Custom','Rias 2 Ibu Pengantin','Rias 2 Bridesmaid','Free Touch-up Unlimited','Akad + Resepsi']],
            ],
            'gaun' => [
                ['name'=>'Sewa Dasar',     'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Gaun Pengantin 1 Hari','Alterasi Dasar','Steaming','Gantungan Gaun']],
                ['name'=>'Sewa Premium',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Gaun Pengantin + Kebaya 1 Hari','Alterasi Lengkap','Steaming','Aksesoris Dasar','Fitting 2x','Gaun Pesta']],
                ['name'=>'Paket Beli',     'mult'=>4,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Gaun Custom Penuh','Jas Pengantin','Kebaya Adat','Fitting Unlimited','Alterasi Unlimited','Aksesori Lengkap','Busana Keluarga Inti']],
            ],
            'hiburan' => [
                ['name'=>'Paket Musik',    'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Band Akustik 3 Jam','3 Personil','Lagu Pilihan 10 Judul','Peralatan Musik']],
                ['name'=>'Paket Show',     'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Band 5 Jam','5 Personil','Lagu Pilihan 20 Judul','Sound System','Tari Tradisional 1 Sesi']],
                ['name'=>'Paket Full Entertainment','mult'=>3,'cap'=>null,'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Live Band Full Day','DJ Set','Tari Tradisional & Modern','MC Entertainment','Sulap Mini Show','Laser Show','Kembang Api Mini']],
            ],
            'undangan' => [
                ['name'=>'Paket 100 pcs',  'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['100 Undangan Cetak','Desain Custom','Amplop','Pita Dekorasi']],
                ['name'=>'Paket 300 pcs',  'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['300 Undangan Cetak','Desain Custom','Amplop','Pita','50 Souvenir Gantungan Kunci','Undangan Digital']],
                ['name'=>'Paket 500 pcs',  'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['500 Undangan Cetak','Desain Premium','Amplop Eksklusif','100 Souvenir','Hampers Mini 50 pcs','Undangan Digital + QR Code','Sticker & Label']],
            ],
            'transportasi' => [
                ['name'=>'1 Unit',          'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['1 Mobil Pengantin Mewah','Dekorasi Kendaraan','Pengemudi Profesional','Hari H']],
                ['name'=>'2 Unit',          'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['2 Mobil Pengantin','Dekorasi Premium','Pengemudi Profesional','Hari H','Akad + Resepsi']],
                ['name'=>'Paket Lengkap',   'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['4 Mobil Pengantin','Dekorasi Eksklusif','Pengemudi Profesional','Limousine 1 Unit','Akad + Resepsi + After Party','Hiasan Bunga Segar']],
            ],
            'kue-pengantin' => [
                ['name'=>'Kue 3 Tingkat',   'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Kue Tart 3 Tingkat','Desain Custom','Topper Nama','Rasa Pilihan']],
                ['name'=>'Kue 5 Tingkat',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Kue Tart 5 Tingkat','Desain Custom','Topper & Aksesori','Rasa Pilihan','Cupcake 50 pcs']],
                ['name'=>'Paket Dessert',   'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Kue Tart 7 Tingkat','Desain Eksklusif','Topper & Aksesori Premium','Cupcake 100 pcs','Dessert Table Setup','Macaron 50 pcs','Cake Pop 50 pcs']],
            ],
            'mc' => [
                ['name'=>'Paket Akad',      'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['MC Akad Nikah','Rundown Acara','Bahasa Indonesia']],
                ['name'=>'Paket Resepsi',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['MC Resepsi','Rundown Lengkap','Bahasa Indonesia + Adat','Hiburan Interaktif']],
                ['name'=>'Paket Full Day',  'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['MC Akad + Resepsi','Rundown Komprehensif','Bilingual (Indonesia + Adat)','Hiburan Interaktif','Kuis Pengantin','Koordinasi dengan WO']],
            ],
            'fotobooth' => [
                ['name'=>'Paket 4 Jam',     'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Fotobooth 4 Jam','Cetak Instan Unlimited','Props Standar','Template Foto Custom']],
                ['name'=>'Paket 8 Jam',     'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Fotobooth 8 Jam','Cetak Instan Unlimited','Props Lengkap','Template Premium','Soft File']],
                ['name'=>'Paket Magic Mirror','mult'=>3,'cap'=>null,'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Magic Mirror Full Day','Cetak & Digital Unlimited','Props Premium','GIF & Boomerang','Email Foto Otomatis','Operator Profesional','Template Eksklusif']],
            ],
            'perhiasan' => [
                ['name'=>'Paket Cincin',    'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Cincin Pasangan','Emas 18K','Ukiran Nama Gratis','Kotak Eksklusif']],
                ['name'=>'Paket Lengkap',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Cincin Pasangan','Gelang Pengantin','Kalung','Emas 18K / Berlian','Ukiran Nama','Kotak & Sertifikat']],
                ['name'=>'Paket Mewah',     'mult'=>4,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Cincin Pasangan Berlian','Gelang Emas Putih','Kalung Berlian','Anting Pengantin','Mahkota Pengantin','Ukiran Custom','Sertifikat Keaslian','Tas Eksklusif']],
            ],
            'honeymoon' => [
                ['name'=>'Domestik 3D2N',   'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Tiket PP (2 Orang)','Hotel Bintang 3','Sarapan','Transfer Airport']],
                ['name'=>'Domestik 5D4N',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Tiket PP (2 Orang)','Hotel Bintang 4','Breakfast & Dinner','Transfer Airport & Hotel','1 Destinasi Wisata']],
                ['name'=>'Internasional',   'mult'=>5,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Tiket Internasional PP (2 Orang)','Hotel Bintang 5 (7 Malam)','Full Board','Private Transfer','City Tour','Spa Couple','Romantic Dinner','Travel Insurance']],
            ],
            'tenda-kursi' => [
                ['name'=>'Paket 100 Kursi', 'mult'=>1,'cap'=>100,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Tenda 6x12m','100 Kursi Plastik','10 Meja Bundar','Karpet Merah']],
                ['name'=>'Paket 300 Kursi', 'mult'=>2,'cap'=>300,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Tenda 12x24m','300 Kursi Futura','30 Meja Bundar','Karpet',  'Lampu Dasar','Podium']],
                ['name'=>'Paket 1000 Kursi','mult'=>3,'cap'=>1000, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Tenda Besar 20x40m','1000 Kursi Futura','100 Meja Bundar','Karpet Premium','Lighting Dasar','Podium & Panggung','Kursi VVIP 50 set','Partition Ruang']],
            ],
            'lighting' => [
                ['name'=>'Paket Standar',   'mult'=>1,'cap'=>null, 'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Lampu PAR LED 20 Unit','Operator','Instalasi & Bongkar','1 Hari']],
                ['name'=>'Paket Premium',   'mult'=>2,'cap'=>null, 'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Lampu PAR + Spot LED','Moving Head 4 Unit','Operator Profesional','Instalasi & Bongkar','Konsultasi Desain','1 Hari']],
                ['name'=>'Paket Show',      'mult'=>3,'cap'=>null, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Full Lighting System','Moving Head 8 Unit','Laser Show','LED Wall','Operator Tim','Konsultasi & Desain Cahaya','Instalasi & Bongkar','Genset Jika Perlu']],
            ],
            'sound-system' => [
                ['name'=>'Paket Kecil',     'mult'=>1,'cap'=>200,  'color'=>'#C8D5B9','tcol'=>'#444444','items'=>['Speaker Aktif 2 Unit','Mixer','2 Mic Wireless','Operator']],
                ['name'=>'Paket Sedang',    'mult'=>2,'cap'=>500,  'color'=>'#F9D5E5','tcol'=>'#444444','items'=>['Line Array 4 Unit','Subwoofer 2 Unit','Mixer 16 Channel','4 Mic Wireless','Monitor Stage','Operator Profesional']],
                ['name'=>'Paket Besar',     'mult'=>3,'cap'=>2000, 'color'=>'#9CAF88','tcol'=>'#FFFFFF','items'=>['Line Array 8 Unit','Subwoofer 4 Unit','Mixer 32 Channel','8 Mic Wireless','IEM Monitor','Operator Tim 3 Orang','Instalasi & Sound Check','Backup Equipment']],
            ],
        ];

        // Map legacy free-text badge/promo values to valid enum backing values
        $badgeMap = [
            'POPULAR'          => 'unggulan',
        ];
        $promoMap = [
            'Hemat 10jt'    => 'paket_hemat',
            'Hemat 5jt'     => 'paket_hemat',
            'Hemat 8jt'     => 'paket_hemat',
            'Hemat 3jt'     => 'paket_hemat',
            'Hemat 2jt'     => 'paket_hemat',
            'Disc 15%'      => 'diskon',
            'Disc 10%'      => 'diskon',
            'Disc 20%'      => 'diskon',
            'Disc 5%'       => 'diskon',
            'All-Inclusive' => 'paket_hemat',
            'Free Konsul'   => 'gratis_konsultasi',
            'Free Skincare' => 'gratis_konsultasi',
            'Free Touch-up' => 'gratis_konsultasi',
            'Free Rundown'  => 'gratis_konsultasi',
            'Gratis Cicip'  => 'gratis_konsultasi',
            'Gratis Design' => 'gratis_konsultasi',
        ];

        // Province + city pool for randomization
        $provincePool = [
            'DKI Jakarta'      => ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Utara', 'Jakarta Timur'],
            'Jawa Barat'       => ['Bandung', 'Bekasi', 'Bogor', 'Depok', 'Cirebon', 'Tasikmalaya'],
            'Jawa Tengah'      => ['Semarang', 'Surakarta (Solo)', 'Magelang', 'Pekalongan', 'Kudus', 'Cilacap'],
            'Jawa Timur'       => ['Surabaya', 'Malang', 'Kediri', 'Madiun', 'Gresik', 'Sidoarjo'],
            'DI Yogyakarta'    => ['Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'],
            'Banten'           => ['Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon'],
            'Bali'             => ['Denpasar', 'Badung', 'Gianyar', 'Tabanan', 'Buleleng'],
            'Sumatera Utara'   => ['Medan', 'Binjai', 'Pematangsiantar', 'Sibolga', 'Deli Serdang'],
            'Sumatera Barat'   => ['Padang', 'Bukittinggi', 'Payakumbuh', 'Solok', 'Padang Pariaman'],
            'Sumatera Selatan' => ['Palembang', 'Prabumulih', 'Lubuklinggau', 'Lahat', 'Musi Banyuasin'],
            'Lampung'          => ['Bandar Lampung', 'Metro', 'Pringsewu', 'Pesawaran', 'Lampung Selatan'],
            'Kalimantan Timur' => ['Samarinda', 'Balikpapan', 'Bontang', 'Kutai Kartanegara'],
            'Sulawesi Selatan' => ['Makassar', 'Parepare', 'Palopo', 'Bone', 'Gowa'],
            'Riau'             => ['Pekanbaru', 'Dumai', 'Kampar', 'Siak', 'Rokan Hulu'],
        ];
        $provinceKeys = array_keys($provincePool);

        Role::findOrCreate('pengunjung', 'web');
        $pengunjungUsers = User::role('pengunjung')->pluck('id')->all();
        if (count($pengunjungUsers) < 20) {
            $need = 20 - count($pengunjungUsers);
            $extra = User::whereDoesntHave('roles')->inRandomOrder()->limit($need)->get();
            foreach ($extra as $u) {
                $u->assignRole('pengunjung');
            }
            $pengunjungUsers = User::role('pengunjung')->pluck('id')->all();
        }

        foreach ($vendors as $i => $data) {
            $randProvince = $provinceKeys[$i % count($provinceKeys)];
            $randCity     = $provincePool[$randProvince][array_rand($provincePool[$randProvince])];
            $brandName = $this->brandName((string) $data['name'], (string) $data['category']);

            // Hitung discount dari promo
            $discountRaw = 0;
            if ($data['promo']) {
                if (preg_match('/Hemat (\d+)jt/', $data['promo'], $m)) {
                    $discountRaw = (int)$m[1] * 1_000_000;
                } elseif (preg_match('/Disc (\d+)%/', $data['promo'], $m)) {
                    $discountRaw = (int) round($data['price'] * $m[1] / 100);
                }
            }

            $badgeValue = $data['badge'] ? ($badgeMap[$data['badge']] ?? null) : null;
            $promoValue = $data['promo'] ? ($promoMap[$data['promo']] ?? 'bonus_gift') : null;
            $createdAt = $i < 15
                ? now()->subDays(rand(0, 20))
                : now()->subDays(rand(31, 365));

            $coverImages = [];
            for ($c = 0; $c < 5; $c++) {
                $coverImages[] = 'https://picsum.photos/seed/cover-' . ($i + 1) . '-' . $c . '/1200/800';
            }

            $vendor = Vendor::create([
                'name'            => $brandName,
                'slug'            => Str::slug($brandName),
                'category'        => $data['category'],
                'location'        => $data['location'],
                'city'            => $randCity,
                'province'        => $randProvince,
                'description'     => 'Kami adalah ' . $brandName . ', menyediakan layanan pernikahan terbaik di Palembang dengan pengalaman bertahun-tahun. Kepuasan pasangan adalah prioritas utama kami.',
                'phone'           => '0811-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '-2025',
                'email'           => Str::slug($brandName) . '@example.com',
                'instagram'       => '@' . Str::slug($brandName, ''),
                'price_start'     => $data['price'],
                'discount'        => $discountRaw,
                'experience'      => rand(2, 10),
                'events_done'     => ($i + 1) * 15 + 20,
                'likes'           => 0,
                'comments_count'  => 0,
                'rating'          => 0,
                'badge'           => $badgeValue ? [$badgeValue] : null,
                'promo'           => $promoValue ? [$promoValue] : null,
                'cover_image'     => $coverImages,
                'is_active'       => true,
                'is_profile_complete' => true,
                'created_at'      => $createdAt,
                'updated_at'      => $createdAt,
            ]);

            $reviewCount = $i % 20 === 0 ? 25 : ($i % 10 === 0 ? 10 : ($i % 5 === 0 ? 5 : 0));
            $bookingCount = $i % 25 === 0 ? 20 : ($i % 7 === 0 ? 6 : 0);

            for ($b = 0; $b < $bookingCount; $b++) {
                $uid = $pengunjungUsers[array_rand($pengunjungUsers)];
                VendorBooking::create([
                    'vendor_id' => $vendor->id,
                    'user_id' => $uid,
                    'vendor_package_id' => null,
                    'event_date' => now()->addDays(rand(7, 180)),
                    'phone' => '0812' . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'notes' => null,
                    'status' => 'pending',
                ]);
            }

            for ($r = 0; $r < $reviewCount; $r++) {
                $uid = $pengunjungUsers[array_rand($pengunjungUsers)];
                $u = User::find($uid);
                VendorReview::create([
                    'vendor_id' => $vendor->id,
                    'user_id' => $uid,
                    'reviewer_name' => $u?->name ?? 'Pengunjung',
                    'reviewer_avatar' => null,
                    'rating' => rand(4, 5),
                    'body' => 'Pelayanan ramah dan respons cepat. Recommended.',
                    'reviewed_at' => now()->subDays(rand(1, 180)),
                    'is_approved' => true,
                    'reviewer_ip' => '127.0.0.1',
                ]);
            }

            // Pool of demo YouTube video IDs (ganti dengan video asli sesuai vendor)
            $demoYoutubeIds = [
                'LXb3EKWsInQ', 'ZRv_GLiinVU', 'TLV4_xaYynY', 'YQHsXMglC9A',
                'pRpeEdMmmQ0', 'hT_nvWreIhg', 'OPf0YbXqDm0', 'LDU_Txk06tM',
                'rUxyKA_-grg', 'FTQbiNvZqaY', 'dHMNeNapL1E', 'vx2u5uUszyw',
                'M7lc1UVf-VE', 'jNQXAC9IVRw', 'UxxajLWwzqY', 'tgbNymZ7vqY',
                'BgBqR1d0UKI', 'qSLDDpBItlA', 'WiqThJGBQmg', 'nKIu9yen5nc',
            ];
            $youtubeId = $demoYoutubeIds[$i % count($demoYoutubeIds)];

            // Galleries (9 photos each)
            for ($g = 0; $g < 9; $g++) {
                VendorGallery::create([
                    'vendor_id'  => $vendor->id,
                    'image_path' => 'https://picsum.photos/seed/' . $vendor->id . '-' . $g . '/800/600',
                    'video_url'  => $g === 0 ? 'https://www.youtube.com/watch?v=' . $youtubeId : null,
                    'sort_order' => $g,
                    'is_cover'   => $g === 0,
                ]);
            }

            // Packages (3 per vendor based on category)
            $tpls = $packageTpl[$data['category']] ?? $packageTpl['paket-lengkap'];
            foreach ($tpls as $idx => $tpl) {
                $priceRaw = (int) round($data['price'] * ($tpl['mult'] * 0.75 + 0.5));
                $dpPaket = (int) round($priceRaw * 0.3);
                $cap = (int)preg_replace('/\D/', '', str_replace('.', '', $tpl['cap'] ?? '0'));
                
                VendorPackage::create([
                    'vendor_id'       => $vendor->id,
                    'name'            => $tpl['name'],
                    'slug'            => $this->makePackageSlug($vendor->slug, $tpl['name']),
                    'price'           => $priceRaw,
                    'dp_paket'        => $dpPaket,
                    'max_guests'      => $tpl['cap'] ? 'Maks. ' . $tpl['cap'] . ' tamu' : 'Tanpa batas',
                    'type'            => $data['category'] === 'gedung' ? 'Indoor' : null,
                    'capacity'        => $cap ?: null,
                    'facilities'      => $data['category'] === 'gedung' ? ['Parkir Luas', 'Ruang Rias', 'Toilet', 'AC'] : null,
                    'card_color'      => $tpl['color'],
                    'card_text_color' => $tpl['tcol'],
                    'item'            => '<ul><li>' . implode('</li><li>', $tpl['items']) . '</li></ul>',
                    'sort_order'      => $idx,
                    'is_active'       => true,
                ]);
            }
        }

        // ── 2 Vendor premium dengan banyak fasilitas (uji tampilan modal) ──
        $premiumVendors = [
            [
                'name'     => 'The Royal Sriwijaya Palace',
                'slug'     => 'the-royal-sriwijaya-palace',
                'type'     => 'Indoor & Outdoor',
                'category' => 'gedung',
                'location' => 'Jl. Gubernur H. Bastari No. 1',
                'price'    => 120_000_000,
                'cap'      => '1.000 – 5.000 tamu',
                'rating'   => 5.0,
                'promo'    => 'All-Inclusive',
                'badge'    => 'TOP PICK',
                'packages' => [
                    [
                        'name'  => 'Paket Silver',
                        'mult'  => 0.6,
                        'cap'   => 'Maks. 500 tamu',
                        'color' => '#C8D5B9',
                        'tcol'  => '#444444',
                        'items' => [
                            'Gedung 6 jam', 'Dekorasi Standar', 'Sound System', 'MC Lokal',
                            'Perlengkapan Ibadah', 'Parkir Luas', 'AC Sentral', 'Toilet Eksklusif',
                            'Mushola', 'Loker Tamu', 'Petugas Kebersihan', 'Genset Backup',
                            'Lampu Dekorasi Dasar', 'Backdrop Foto', '2 Meja Registrasi',
                        ],
                    ],
                    [
                        'name'  => 'Paket Gold',
                        'mult'  => 1.0,
                        'cap'   => 'Maks. 1.500 tamu',
                        'color' => '#F9D5E5',
                        'tcol'  => '#444444',
                        'items' => [
                            'Gedung 8 jam', 'Dekorasi Premium Floral', 'Sound System Pro',
                            'MC Profesional', 'Kamar Pengantin Suite', 'Dokumentasi Foto 8 jam',
                            'Parkir Luas + Valet', 'AC Sentral', 'Toilet Eksklusif',
                            'Mushola', 'Lounge Keluarga VIP', 'Genset Backup Otomatis',
                            'Lighting Dekorasi', 'Backdrop Premium + Photo Corner',
                            'LED Canopy Entrance', 'Karpet Merah', 'Gate Bunga Hidup',
                            'Katering Cicip 50 pax', 'Wedding Car (2 unit)', 'Kue Tart 3 Tingkat',
                        ],
                    ],
                    [
                        'name'  => 'Paket Platinum',
                        'mult'  => 1.6,
                        'cap'   => 'Maks. 5.000 tamu',
                        'color' => '#9CAF88',
                        'tcol'  => '#FFFFFF',
                        'items' => [
                            'Gedung Full Day (12 jam)', 'Dekorasi Eksklusif Internasional',
                            'Full Sound & Lighting System', 'MC + Live Band', 'Kamar Pengantin Presidential',
                            'Foto Sinematik 12 jam', 'Video Highlight + Teaser', 'Drone Footage',
                            'Foto Prewedding (1 lokasi)', 'Parkir VIP + Valet', 'AC Sentral',
                            'Lounge Keluarga VIP + Katering Private', 'Genset Backup Otomatis',
                            'Full Lighting Dekorasi Indoor & Outdoor', 'LED Wall Display',
                            'Gate Monumental Bunga Segar', 'Karpet Merah + Red Carpet Guard',
                            'Photobooth Booth Digital', 'Wedding Car Mewah (4 unit)',
                            'Kue Tart 5 Tingkat Custom', 'Undangan Digital 200 pcs',
                            'Souvenir Pernikahan 200 pcs', 'Buket Bunga Pengantin',
                            'Koordinator Acara Dedicated', 'Tim WO 6 Orang',
                            'Dekorasi Pelaminan Custom', 'Perlengkapan Adat Lengkap',
                            'Katering 1.000 pax (10 menu)', 'Minuman Welcome Drink',
                            'After Party Setup 3 jam',
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'Pesona Grand Wedding Resort',
                'slug'     => 'pesona-grand-wedding-resort',
                'type'     => 'Indoor',
                'category' => 'hotel',
                'location' => 'Jl. PTC Palembang No. 88',
                'price'    => 95_000_000,
                'cap'      => '500 – 3.000 tamu',
                'rating'   => 4.9,
                'promo'    => 'Hemat 25jt',
                'badge'    => 'NEW REAL WEDDING',
                'packages' => [
                    [
                        'name'  => 'Paket Silver',
                        'mult'  => 0.5,
                        'cap'   => 'Maks. 300 tamu',
                        'color' => '#C8D5B9',
                        'tcol'  => '#444444',
                        'items' => [
                            'Ballroom 6 jam', 'Dekorasi Standar', 'Katering 300 pax',
                            'Sound System', 'MC Lokal', '1 Kamar Hotel Superior',
                            'Parkir Gratis', 'Wifi Premium', 'Toilet Eksklusif',
                            'Mushola Dalam Gedung', 'Genset Backup', 'Petugas Kebersihan',
                            'Lampu Dekorasi Dasar', 'Backdrop Standar',
                        ],
                    ],
                    [
                        'name'  => 'Paket Gold',
                        'mult'  => 0.9,
                        'cap'   => 'Maks. 1.000 tamu',
                        'color' => '#F9D5E5',
                        'tcol'  => '#444444',
                        'items' => [
                            'Ballroom 8 jam', 'Dekorasi Floral Premium', 'Katering 1.000 pax (8 menu)',
                            'Sound System Pro', 'MC Profesional', '2 Kamar Hotel Deluxe',
                            'Sarapan 2 hari untuk 10 orang', 'Parkir VIP', 'Wifi Premium',
                            'Lounge Keluarga VIP', 'Genset Otomatis', 'Lighting Dekorasi',
                            'Backdrop Premium + Bunga Hidup', 'Welcome Drink 500 pax',
                            'Kue Tart 3 Tingkat', 'Wedding Car (2 unit)',
                            'Foto Dokumentasi 8 jam', 'Video Clip 3 menit',
                            'Koordinator Acara', 'Tim Rias & Busana (1 sesi)',
                        ],
                    ],
                    [
                        'name'  => 'Paket Platinum',
                        'mult'  => 1.5,
                        'cap'   => 'Maks. 3.000 tamu',
                        'color' => '#9CAF88',
                        'tcol'  => '#FFFFFF',
                        'items' => [
                            'Ballroom Full Day (14 jam)', 'Dekorasi Internasional Eksklusif',
                            'Katering 3.000 pax (15 menu pilihan)', 'Full AV & Sound System',
                            'MC + Live Entertainment', '5 Kamar Hotel Junior Suite',
                            'Check-in Early & Check-out Late', 'Sarapan 3 hari untuk 20 orang',
                            'Welcome Dinner Malam Sebelumnya', 'Parkir VIP + Valet Unlimited',
                            'Lounge VVIP Keluarga', 'Bridal Suite + Honeymoon Setup',
                            'Foto Sinematik Full Day', 'Video Feature Film Pendek',
                            'Drone Footage Outdoor', 'Foto Prewedding (2 lokasi)',
                            'Same Day Edit Video', 'Photobooth Digital Unlimited',
                            'Lighting Dekorasi Indoor & Outdoor', 'LED Screen Stage',
                            'Kembang Api Mini Indoor', 'Gate Bunga Segar Monumental',
                            'Karpet Merah + Red Carpet Guard', 'Kue Tart 7 Tingkat Custom',
                            'Undangan Digital + Cetak 500 pcs', 'Souvenir 500 pcs',
                            'Buket & Corsage Pengantin', 'Tim WO 8 Orang Dedicated',
                            'Koordinasi Vendor Penuh', 'After Party 4 jam',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($premiumVendors as $pi => $pd) {
            $pvProvince = $provinceKeys[($pi + 5) % count($provinceKeys)];
            $pvCity     = $provincePool[$pvProvince][array_rand($provincePool[$pvProvince])];

            // Hitung discount dari promo
            $pvDiscountRaw = 0;
            if (!empty($pd['promo'])) {
                if (preg_match('/Hemat (\d+)jt/', $pd['promo'], $m2)) {
                    $pvDiscountRaw = (int)$m2[1] * 1_000_000;
                } elseif (preg_match('/Disc (\d+)%/', $pd['promo'], $m2)) {
                    $pvDiscountRaw = (int) round($pd['price'] * $m2[1] / 100);
                }
            }

            $pvBadgeValue = $pd['badge'] ? ($badgeMap[$pd['badge']] ?? null) : null;
            $pvPromoValue = $pd['promo'] ? ($promoMap[$pd['promo']] ?? 'bonus_gift') : null;
            $pvCreatedAt = now()->subDays(rand(60, 365));

            $pvCoverImages = [];
            for ($c = 0; $c < 6; $c++) {
                $pvCoverImages[] = 'https://picsum.photos/seed/premium-cover-' . ($pi + 1) . '-' . $c . '/1200/800';
            }

            $pv = Vendor::create([
                'name'            => $pd['name'],
                'slug'            => $pd['slug'],
                'category'        => $pd['category'],
                'location'        => $pd['location'],
                'city'            => $pvCity,
                'province'        => $pvProvince,
                'description'     => $pd['name'] . ' adalah destinasi wedding paling prestisius. Dengan fasilitas bintang 5 dan tim profesional berpengalaman, kami menghadirkan pengalaman pernikahan impian yang tak terlupakan.',
                'phone'           => '0812-' . str_pad($pi + 1, 4, '0', STR_PAD_LEFT) . '-8888',
                'email'           => $pd['slug'] . '@example.com',
                'instagram'       => '@' . str_replace('-', '', $pd['slug']),
                'price_start'     => $pd['price'],
                'discount'        => $pvDiscountRaw,
                'experience'      => rand(8, 20),
                'events_done'     => 300 + $pi * 50,
                'likes'           => 800 + $pi * 100,
                'comments_count'  => 120 + $pi * 30,
                'rating'          => $pd['rating'],
                'badge'           => $pvBadgeValue ? [$pvBadgeValue] : null,
                'promo'           => $pvPromoValue ? [$pvPromoValue] : null,
                'cover_image'     => $pvCoverImages,
                'is_active'       => true,
                'is_profile_complete' => true,
                'created_at'      => $pvCreatedAt,
                'updated_at'      => $pvCreatedAt,
            ]);

            $pvReviewCount = 60 + $pi * 20;
            $pvBookingCount = 50 + $pi * 20;
            for ($b = 0; $b < $pvBookingCount; $b++) {
                $uid = $pengunjungUsers[array_rand($pengunjungUsers)];
                VendorBooking::create([
                    'vendor_id' => $pv->id,
                    'user_id' => $uid,
                    'vendor_package_id' => null,
                    'event_date' => now()->addDays(rand(7, 240)),
                    'phone' => '0812' . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'notes' => null,
                    'status' => 'pending',
                ]);
            }

            for ($r = 0; $r < $pvReviewCount; $r++) {
                $uid = $pengunjungUsers[array_rand($pengunjungUsers)];
                $u = User::find($uid);
                VendorReview::create([
                    'vendor_id' => $pv->id,
                    'user_id' => $uid,
                    'reviewer_name' => $u?->name ?? 'Pengunjung',
                    'reviewer_avatar' => null,
                    'rating' => rand(4, 5),
                    'body' => 'Venue sangat bagus dan tim sangat profesional.',
                    'reviewed_at' => now()->subDays(rand(1, 240)),
                    'is_approved' => true,
                    'reviewer_ip' => '127.0.0.1',
                ]);
            }

            $premiumYoutubeIds = ['LXb3EKWsInQ', 'ZRv_GLiinVU'];
            $pvYoutubeId = $premiumYoutubeIds[$pi % 2];

            for ($g = 0; $g < 9; $g++) {
                VendorGallery::create([
                    'vendor_id'  => $pv->id,
                    'image_path' => 'https://picsum.photos/seed/premium-' . $pv->id . '-' . $g . '/800/600',
                    'video_url'  => $g === 0 ? 'https://www.youtube.com/watch?v=' . $pvYoutubeId : null,
                    'sort_order' => $g,
                    'is_cover'   => $g === 0,
                ]);
            }

            foreach ($pd['packages'] as $idx => $tpl) {
                $priceRaw = (int) round($pd['price'] * $tpl['mult']);
                $dpPaket = (int) round($priceRaw * 0.3);
                $cap = (int)preg_replace('/\D/', '', str_replace('.', '', $tpl['cap'] ?? '0'));
                
                VendorPackage::create([
                    'vendor_id'       => $pv->id,
                    'name'            => $tpl['name'],
                    'slug'            => $this->makePackageSlug($pv->slug, $tpl['name']),
                    'price'           => $priceRaw,
                    'dp_paket'        => $dpPaket,
                    'max_guests'      => $tpl['cap'],
                    'type'            => $pd['category'] === 'gedung' ? 'Indoor' : null,
                    'capacity'        => $cap ?: null,
                    'facilities'      => $pd['category'] === 'gedung' ? ['Parkir VIP', 'AC Sentral', 'Toilet Eksklusif', 'Ruang Rias', 'Sound System Pro'] : null,
                    'card_color'      => $tpl['color'],
                    'card_text_color' => $tpl['tcol'],
                    'item'            => '<ul><li>' . implode('</li><li>', $tpl['items']) . '</li></ul>',
                    'sort_order'      => $idx,
                    'is_active'       => true,
                ]);
            }
        }

        $this->command->info('VendorSeeder: ' . (count($vendors) + count($premiumVendors)) . ' vendors seeded with galleries, packages, bookings & reviews.');
    }

    /** Slug unik untuk VendorPackage — dipakai karena WithoutModelEvents menonaktifkan boot() */
    private array $usedPackageSlugs = [];

    private function makePackageSlug(string $vendorSlug, string $packageName): string
    {
        $base = $vendorSlug . '-' . Str::slug($packageName ?: 'paket');
        $slug = $base;
        $i    = 2;
        while (in_array($slug, $this->usedPackageSlugs)) {
            $slug = $base . '-' . $i++;
        }
        $this->usedPackageSlugs[] = $slug;
        return $slug;
    }

    private function brandName(string $name, string $category): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));
        if ($name === '') {
            return $name;
        }

        $lower = strtolower($name);
        $hasWeddingBrand = str_contains($lower, 'wedding') || str_contains($lower, 'bridal');

        if ($hasWeddingBrand) {
            return $name;
        }

        $suffix = match ($category) {
            'gedung', 'hotel', 'rumah' => 'Wedding Venue',
            'wo' => 'Wedding Organizer',
            'catering' => 'Wedding Catering',
            'dekorasi' => 'Wedding Decor',
            'foto-video' => 'Wedding Photography',
            'makeup' => 'Bridal Makeup',
            'gaun' => 'Bridal Boutique',
            'hiburan' => 'Wedding Entertainment',
            'undangan' => 'Wedding Invitations',
            'transportasi' => 'Wedding Transport',
            'kue-pengantin' => 'Wedding Cake',
            'mc' => 'Wedding MC',
            'fotobooth' => 'Wedding Photobooth',
            'perhiasan' => 'Bridal Jewelry',
            default => '',
        };

        return $suffix !== '' ? ($name . ' ' . $suffix) : $name;
    }
}
