<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorPackageSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('wc-product-export-cleaned.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV tidak ditemukan: {$csvPath}");
            return;
        }

        // Bangun lookup: vendor name → id (fuzzy match: CSV name may differ from DB)
        $allVendors = DB::table('vendors')->select('id', 'name')->get();
        $vendorMap = [];
        foreach ($allVendors as $v) {
            $vendorMap[$v->name] = $v->id;
        }

        // Helper untuk fuzzy vendor lookup
        $findVendorId = function (string $csvName) use ($allVendors): ?int {
            // Exact match first
            foreach ($allVendors as $v) {
                if ($v->name === $csvName) return $v->id;
            }
            // Partial match: CSV name contains any keyword from DB name or vice versa
            $csvWords = array_filter(explode(' ', strtolower($csvName)), fn($w) => strlen($w) > 3);
            foreach ($allVendors as $v) {
                $dbLower = strtolower($v->name);
                foreach ($csvWords as $word) {
                    if (str_contains($dbLower, $word)) return $v->id;
                }
            }
            return null;
        };

        // Bangun lookup: category name → id
        $categoryMap = DB::table('category_vendors')
            ->pluck('id', 'name')
            ->toArray();

        $handle = fopen($csvPath, 'r');
        $headers = fgetcsv($handle); // skip header row

        $batch      = [];
        $batchSize  = 500;
        $total      = 0;
        $skipped    = 0;
        $sortOrder  = 1;
        $now        = now()->toDateTimeString();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 7) {
                $skipped++;
                continue;
            }

            [$vendorName, $categoryName, $name, $item, $priceRaw, $maxGuests, $dpPaket] = $row;

            $vendorName   = trim($vendorName);
            $categoryName = trim($categoryName);
            $name         = trim($name);

            $vendorId   = $findVendorId($vendorName);
            $categoryId = $categoryMap[$categoryName] ?? null;

            if (!$vendorId) {
                $skipped++;
                continue;
            }

            $priceRaw  = (int) preg_replace('/[^\d]/', '', $priceRaw);
            $dpPaket   = (int) preg_replace('/[^\d]/', '', $dpPaket);
            $maxGuests = (int) preg_replace('/[^\d]/', '', $maxGuests);

            // Deteksi tipe venue dari nama paket
            $nameLower = strtolower($name);
            $type = null;
            if (str_contains($nameLower, 'outdoor')) {
                $type = 'Outdoor';
            } elseif (str_contains($nameLower, 'semi outdoor') || str_contains($nameLower, 'semi-outdoor')) {
                $type = 'Semi Outdoor';
            } elseif (
                str_contains($nameLower, 'ballroom') ||
                str_contains($nameLower, 'gedung') ||
                str_contains($nameLower, 'convention') ||
                str_contains($nameLower, 'hall') ||
                str_contains($nameLower, 'hotel') ||
                str_contains($nameLower, 'indoor')
            ) {
                $type = 'Indoor';
            }

            // Warna kartu berdasarkan rentang harga
            [$cardColor, $cardText] = $this->resolveCardColors($priceRaw);

            // Fasilitas default untuk paket lengkap
            $facilities = $this->defaultFacilities($nameLower);

            $batch[] = [
                'vendor_id'          => $vendorId,
                'category_vendor_id' => $categoryId,
                'name'               => $name,
                'price'              => $priceRaw,
                'discount'           => 0,
                'dp_paket'           => $dpPaket,
                'max_guests'         => (string) $maxGuests,
                'card_color'         => $cardColor,
                'card_text_color'    => $cardText,
                'item'               => trim($item),
                'type'               => $type,
                'capacity'           => $maxGuests ?: null,
                'facilities'         => json_encode($facilities),
                'image_path'         => null,
                'sort_order'         => $sortOrder++,
                'is_active'          => 1,
                'created_at'         => $now,
                'updated_at'         => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('vendor_packages')->insert($batch);
                $total += count($batch);
                $batch = [];
                $this->command->info("  Inserted {$total} rows...");
            }
        }

        // Insert sisa batch
        if (!empty($batch)) {
            DB::table('vendor_packages')->insert($batch);
            $total += count($batch);
        }

        fclose($handle);

        $this->command->info("VendorPackageSeeder selesai: {$total} paket di-insert, {$skipped} baris dilewati.");
    }

    /**
     * Tentukan warna kartu berdasarkan rentang harga.
     * Mengembalikan [$cardColor, $cardTextColor].
     */
    private function resolveCardColors(int $price): array
    {
        return match (true) {
            $price >= 300_000_000 => ['#1B3A6B', '#FFFFFF'], // Navy premium
            $price >= 200_000_000 => ['#4A2C5E', '#FFFFFF'], // Ungu gelap
            $price >= 150_000_000 => ['#2C5F2E', '#FFFFFF'], // Hijau tua
            $price >= 100_000_000 => ['#7A5C2E', '#FFFFFF'], // Coklat emas
            $price >= 75_000_000  => ['#C9A84C', '#3B2F2F'], // Gold
            $price >= 50_000_000  => ['#9CAF88', '#3B2F2F'], // Sage green
            $price >= 25_000_000  => ['#C8D5B9', '#444444'], // Light sage
            default               => ['#F5ECD7', '#3B2F2F'], // Krem
        };
    }

    /**
     * Tentukan fasilitas default berdasarkan nama paket.
     */
    private function defaultFacilities(string $nameLower): array
    {
        $base = ['Toilet', 'Mushola', 'Parkir Luas'];

        if (
            str_contains($nameLower, 'ballroom') ||
            str_contains($nameLower, 'convention') ||
            str_contains($nameLower, 'hotel')
        ) {
            return array_unique(array_merge($base, ['AC', 'Sound System', 'WiFi', 'Ruang Rias']));
        }

        if (str_contains($nameLower, 'outdoor') || str_contains($nameLower, 'taman') || str_contains($nameLower, 'garden')) {
            return array_unique(array_merge($base, ['Sound System']));
        }

        return array_unique(array_merge($base, ['AC', 'Sound System', 'Ruang Rias']));
    }
}
