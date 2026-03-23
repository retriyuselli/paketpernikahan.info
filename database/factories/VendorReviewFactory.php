<?php

namespace Database\Factories;

use App\Models\VendorReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorReview>
 */
class VendorReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_id'       => \App\Models\Vendor::factory(),
            'user_id'         => null,
            'reviewer_name'   => $this->faker->name(),
            'reviewer_avatar' => null,
            'rating'          => $this->faker->numberBetween(3, 5),
            'body'            => $this->faker->paragraph($this->faker->numberBetween(2, 5)),
            'reviewed_at'     => $this->faker->dateTimeBetween('-2 years', 'now'),
            'is_approved'     => true,
        ];
    }
}
