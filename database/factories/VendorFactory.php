<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['gedung', 'hotel', 'rumah', 'wo'];
        $types = [
            'gedung' => 'Gedung Wedding Venue',
            'hotel'  => 'Hotel & Ballroom',
            'rumah'  => 'Rumah & Taman',
            'wo'     => 'Wedding Organizer',
        ];
        $category  = $this->faker->randomElement($categories);
        $name      = $this->faker->company() . ' Wedding';
        $priceRaw  = $this->faker->numberBetween(15, 150) * 1_000_000;

        return [
            'name'           => $name,
            'slug'           => \Illuminate\Support\Str::slug($name) . '-' . $this->faker->unique()->numerify('###'),
            'type'           => $types[$category],
            'category'       => $category,
            'location'       => $this->faker->randomElement(['Jl. Sudirman', 'Jl. Demang', 'Jl. Kapten A. Rivai', 'Jl. Soekarno-Hatta']) . ' No. ' . $this->faker->buildingNumber(),
            'city'           => 'Palembang',
            'description'    => $this->faker->paragraph(3),
            'phone'          => '08' . $this->faker->numerify('##########'),
            'email'          => $this->faker->safeEmail(),
            'instagram'      => '@' . $this->faker->userName(),
            'capacity'       => $this->faker->randomElement(['200 – 500', '300 – 800', '500 – 1.500', '100 – 300']) . ' tamu',
            'price_start'    => 'Rp ' . number_format($priceRaw, 0, ',', '.'),
            'price_start_raw'=> $priceRaw,
            'experience'     => $this->faker->numberBetween(3, 20) . '+ Tahun',
            'venue_type'     => $this->faker->randomElement(['Indoor', 'Outdoor', 'Indoor & Outdoor']),
            'facilities'     => implode(', ', $this->faker->randomElements(['AC', 'Parkir', 'Lift', 'Kamar Pengantin', 'Katering', 'Dekorasi', 'Ibadah'], 4)),
            'events_done'    => $this->faker->numberBetween(30, 500),
            'likes'          => $this->faker->numberBetween(10, 999),
            'comments_count' => $this->faker->numberBetween(0, 200),
            'rating'         => $this->faker->randomFloat(1, 4.0, 5.0),
            'badge'          => $this->faker->optional(0.4)->randomElement(['NEW REAL WEDDING', 'TOP PICK', 'POPULAR']),
            'promo'          => $this->faker->optional(0.5)->randomElement(['Hemat 10jt', 'Disc 15%', 'Gratis Dekor', 'Bonus Kamar']),
            'cover_image'    => null,
            'is_active'      => true,
        ];
    }
}
