<?php

namespace Database\Factories;

use App\Models\VendorGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorGallery>
 */
class VendorGalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $w = $this->faker->randomElement([800, 900, 1000]);
        $h = $this->faker->randomElement([600, 700, 800]);

        return [
            'vendor_id'  => \App\Models\Vendor::factory(),
            'image_path' => 'https://picsum.photos/seed/' . $this->faker->unique()->word() . '/' . $w . '/' . $h,
            'caption'    => $this->faker->optional(0.5)->sentence(4),
            'sort_order' => $this->faker->numberBetween(0, 20),
            'is_cover'   => false,
        ];
    }
}
