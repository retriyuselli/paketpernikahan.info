<?php

namespace Database\Seeders;

use App\Models\PartnerLogo;
use Illuminate\Database\Seeder;

class PartnerLogoSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Aston Hotel Palembang',       'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Aston'],
            ['name' => 'Beston Hotel Palembang',       'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Beston'],
            ['name' => 'Swiss-Belhotel Palembang',     'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Swiss-Bel'],
            ['name' => 'The Zuri Hotel Palembang',     'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=The+Zuri'],
            ['name' => 'Novotel Palembang',            'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Novotel'],
            ['name' => 'Arista Hotel Palembang',       'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Arista'],
            ['name' => 'Horison Ultima Palembang',     'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Horison'],
            ['name' => 'Griya Agung Palembang',        'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Griya+Agung'],
            ['name' => 'Bukit Asam Convention Center', 'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=BACC'],
            ['name' => 'Jakabaring Sport City',        'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=JSC'],
            ['name' => 'Makna Kreatif Indonesia',      'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Makna'],
            ['name' => 'Sriwijaya Catering',           'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Sriwijaya'],
            ['name' => 'Ampera Dekorasi',              'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Ampera'],
            ['name' => 'Putri Catering Palembang',     'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Putri'],
            ['name' => 'Elegance Wedding Organizer',   'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Elegance'],
            ['name' => 'Royal Photo Studio',           'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Royal+Photo'],
            ['name' => 'Mahkota Bridal',               'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Mahkota'],
            ['name' => 'Bunga Rampai Florist',         'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Bunga+Rampai'],
            ['name' => 'Harmoni Entertainment',        'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Harmoni'],
            ['name' => 'Purnama Sound System',         'logo' => 'https://placehold.co/200x80/f5f0eb/6b7c5e?text=Purnama'],
        ];

        foreach ($partners as $index => $data) {
            PartnerLogo::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, [
                    'link_url'   => null,
                    'is_active'  => true,
                    'sort_order' => $index + 1,
                ])
            );
        }
    }
}
