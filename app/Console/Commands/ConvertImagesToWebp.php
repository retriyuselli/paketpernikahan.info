<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:to-webp
                            {--quality=85 : Kualitas WebP (1-100)}
                            {--dry-run    : Preview tanpa mengubah apapun}';

    protected $description = 'Konversi semua gambar (jpg/jpeg/png) ke format WebP dan update path di database';

    // Model => [kolom, tipe: single|array]
    private array $targets = [
        'vendor_packages'    => [['image_path', 'array']],
        'vendors'            => [['cover_image', 'array'], ['logo_vendor', 'single']],
        'vendor_galleries'   => [['image_path', 'array']],
        'paket_galleries'    => [['image_video', 'single']],
        'blogs'              => [['cover_image', 'single']],
        'real_weddings'      => [['cover_image', 'single']],
        'home_ads'           => [['image', 'single']],
        'hero_circles'       => [['image_url', 'single']],
        'partner_logos'      => [['logo', 'single']],
        'venue_review_videos'=> [['thumbnail', 'single']],
    ];

    private int $converted = 0;
    private int $skipped   = 0;
    private int $failed    = 0;
    private bool $dryRun   = false;
    private int $quality   = 85;

    public function handle(): int
    {
        $this->dryRun  = $this->option('dry-run');
        $this->quality = (int) $this->option('quality');

        if ($this->dryRun) {
            $this->warn('Mode DRY-RUN aktif — tidak ada perubahan yang disimpan.');
        }

        foreach ($this->targets as $table => $columns) {
            if (!$this->tableExists($table)) {
                continue;
            }
            $this->processTable($table, $columns);
        }

        $this->newLine();
        $this->info("Selesai. Converted: {$this->converted} | Skipped: {$this->skipped} | Failed: {$this->failed}");

        return self::SUCCESS;
    }

    private function processTable(string $table, array $columns): void
    {
        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            $updates = [];

            foreach ($columns as [$column, $type]) {
                if (!isset($row->$column) || empty($row->$column)) {
                    continue;
                }

                if ($type === 'array') {
                    $paths = json_decode($row->$column, true);
                    if (!is_array($paths)) {
                        continue;
                    }
                    $newPaths = array_map(fn($p) => $this->convertPath($p), $paths);
                    if ($newPaths !== $paths) {
                        $updates[$column] = json_encode($newPaths);
                    }
                } else {
                    $newPath = $this->convertPath($row->$column);
                    if ($newPath !== $row->$column) {
                        $updates[$column] = $newPath;
                    }
                }
            }

            if (!empty($updates) && !$this->dryRun) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        }
    }

    private function convertPath(mixed $path): mixed
    {
        if (!is_string($path) || empty($path)) {
            return $path;
        }

        // Skip URL eksternal dan yang sudah WebP
        if (str_starts_with($path, 'http') || str_ends_with(strtolower($path), '.webp')) {
            $this->skipped++;
            return $path;
        }

        // Skip bukan gambar
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $this->skipped++;
            return $path;
        }

        $diskPath = Storage::disk('public')->path($path);

        if (!file_exists($diskPath)) {
            $this->warn("  File tidak ditemukan: {$path}");
            $this->skipped++;
            return $path;
        }

        $webpPath     = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
        $webpDiskPath = Storage::disk('public')->path($webpPath);

        if ($this->dryRun) {
            $this->line("  [DRY] {$path} → {$webpPath}");
            $this->converted++;
            return $webpPath;
        }

        if ($this->doConvert($diskPath, $webpDiskPath)) {
            $this->line("  <info>OK</info> {$path} → {$webpPath}");
            $this->converted++;
            return $webpPath;
        }

        $this->failed++;
        return $path;
    }

    private function doConvert(string $src, string $dest): bool
    {
        try {
            $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));

            $image = match ($ext) {
                'jpg', 'jpeg' => imagecreatefromjpeg($src),
                'png'         => $this->loadPng($src),
                default       => false,
            };

            if (!$image) {
                return false;
            }

            $ok = imagewebp($image, $dest, $this->quality);
            unset($image);

            return $ok;
        } catch (\Throwable $e) {
            $this->error("  Gagal: {$src} — " . $e->getMessage());
            return false;
        }
    }

    private function loadPng(string $src): \GdImage|false
    {
        $image = imagecreatefrompng($src);
        if (!$image) {
            return false;
        }
        // Preserve transparency
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        return $image;
    }

    private function tableExists(string $table): bool
    {
        try {
            DB::table($table)->limit(1)->get();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
