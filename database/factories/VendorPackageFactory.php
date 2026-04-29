<?php

namespace Database\Factories;

use App\Models\VendorPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorPackage>
 */
class VendorPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tiers = [
            ['name' => 'Paket Silver',   'color' => '#C8D5B9', 'text' => '#444444', 'multiplier' => 1],
            ['name' => 'Paket Gold',     'color' => '#F9D5E5', 'text' => '#444444', 'multiplier' => 2],
            ['name' => 'Paket Platinum', 'color' => '#9CAF88', 'text' => '#FFFFFF', 'multiplier' => 3],
        ];
        $tier     = $this->faker->randomElement($tiers);
        $priceRaw = $this->faker->numberBetween(15, 50) * 1_000_000 * $tier['multiplier'];

        return [
            'vendor_id'       => \App\Models\Vendor::factory(),
            'name'            => $tier['name'],
            'price'           => $priceRaw,
            'max_guests'      => 'Maks. ' . $this->faker->randomElement([200, 300, 500, 800, 1000]) . ' tamu',
            'card_color'      => $tier['color'],
            'card_text_color' => $tier['text'],
            'items'           => $this->faker->randomElements([
                'Gedung 6 jam', 'Katering 500 pax', 'Dekorasi Pelaminan',
                'Sound System', 'Dokumentasi Foto', 'Dokumentasi Video',
                'Wedding Planner', 'Kamar Pengantin', 'MC Profesional',
                'Perlengkapan Ibadah', 'Kue Pengantin', 'Mobil Pengantin',
            ], $this->faker->numberBetween(4, 8)),
            'sort_order' => 0,
            'is_active'  => true,
        ];
    }
}
