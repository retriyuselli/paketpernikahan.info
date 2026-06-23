<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CategoryVendorSeeder::class);
        $this->call(VendorSeeder::class);
        $this->call(VendorPackageSeeder::class);
        $this->call(HeroCircleSeeder::class);
        $this->call(VenueReviewVideoSeeder::class);
        $this->call(RealWeddingSeeder::class);
        $this->call(PartnerLogoSeeder::class);
        $this->call(CustomerSeeder::class);
    }
}
