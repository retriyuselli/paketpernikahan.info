<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:to-webp
                            {--quality=85        : Kualitas WebP (1-100)}
                            {--dry-run           : Preview tanpa mengubah apapun}
                            {--backup-originals  : Pindahkan file asli ke folder backup}
                            {--purge-backup      : Hapus folder backup (jalankan setelah yakin WebP OK}';

    protected $description = 'Konversi gambar ke WebP, backup, dan kelola file asli';

    private const BACKUP_DIR = 'backup-originals';

    private array $targets = [
        'vendor_packages'     => [['image_path', 'array']],
        'vendors'             => [['cover_image', 'array'], ['logo_vendor', 'single']],
        'vendor_galleries'    => [['image_path', 'array']],
        'paket_galleries'     => [['image_video', 'single']],
        'blogs'               => [['cover_image', 'single']],
        'real_weddings'       => [['cover_image', 'single']],
        'home_ads'            => [['image', 'single']],
        'hero_circles'        => [['image_url', 'single']],
        'partner_logos'       => [['logo', 'single']],
        'venue_review_videos' => [['thumbnail', 'single']],
    ];

    private int $converted = 0;
    private int $skipped   = 0;
    private int $failed    = 0;
    private int $moved     = 0;
    private bool $dryRun   = false;
    private int $quality   = 85;

    public function handle(): int
    {
        $this->dryRun  = $this->option('dry-run');
        $this->quality = (int) $this->option('quality');

        // ── Purge backup folder ───────────────────────────────────────────
        if ($this->option('purge-backup')) {
            return $this->purgeBackup();
        }

        // ── Pindahkan file asli ke backup ────────────────────────────────
        if ($this->option('backup-originals')) {
            return $this->backupOriginals();
        }

        // ── Konversi ke WebP ─────────────────────────────────────────────
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

    // ── Backup originals ──────────────────────────────────────────────────

    private function backupOriginals(): int
    {
        $this->info('Memindahkan file asli ke folder backup...');

        $publicBase = Storage::disk('public')->path('');
        $backupBase = storage_path('app/' . self::BACKUP_DIR);

        $extensions = ['jpg', 'jpeg', 'png'];

        foreach ($extensions as $ext) {
            $files = glob($publicBase . '**/*.' . $ext);
            if (!$files) {
                $files = [];
            }
            // Rekursif manual untuk semua subfolder
            $files = array_merge($files, $this->globRecursive($publicBase, $ext));

            foreach ($files as $srcPath) {
                // Hanya pindahkan jika file WebP pasangannya sudah ada
                $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $srcPath);
                if (!file_exists($webpPath)) {
                    continue;
                }

                $relativePath = ltrim(str_replace($publicBase, '', $srcPath), '/');
                $destPath     = $backupBase . '/' . $relativePath;
                $destDir      = dirname($destPath);

                if ($this->dryRun) {
                    $this->line("  [DRY] Pindah: {$relativePath}");
                    $this->moved++;
                    continue;
                }

                if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
                    $this->error("  Gagal membuat direktori: {$destDir}");
                    $this->failed++;
                    continue;
                }

                if (copy($srcPath, $destPath)) {
                    unlink($srcPath);
                    $this->line("  <info>OK</info> Dipindahkan: {$relativePath}");
                    $this->moved++;
                } else {
                    $this->error("  Gagal memindahkan: {$relativePath}");
                    $this->failed++;
                }
            }
        }

        $this->newLine();
        $backupPath = storage_path('app/' . self::BACKUP_DIR);
        $this->info("Selesai. Dipindahkan: {$this->moved} file ke: {$backupPath}");
        $this->warn('Jalankan --purge-backup untuk menghapus folder backup jika sudah yakin.');

        return self::SUCCESS;
    }

    private function purgeBackup(): int
    {
        $backupPath = storage_path('app/' . self::BACKUP_DIR);

        if (!is_dir($backupPath)) {
            $this->warn('Folder backup tidak ditemukan: ' . $backupPath);
            return self::SUCCESS;
        }

        if (!$this->confirm("Yakin ingin HAPUS PERMANEN folder backup?\n  {$backupPath}")) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        $this->deleteDirectory($backupPath);
        $this->info('Folder backup berhasil dihapus.');

        return self::SUCCESS;
    }

    // ── Konversi WebP ─────────────────────────────────────────────────────

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

        if (str_starts_with($path, 'http') || str_ends_with(strtolower($path), '.webp')) {
            $this->skipped++;
            return $path;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $this->skipped++;
            return $path;
        }

        $diskPath     = Storage::disk('public')->path($path);
        $webpPath     = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
        $webpDiskPath = Storage::disk('public')->path($webpPath);

        if (!file_exists($diskPath)) {
            $this->warn("  File tidak ditemukan: {$path}");
            $this->skipped++;
            return $path;
        }

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
            $ext   = strtolower(pathinfo($src, PATHINFO_EXTENSION));
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
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        return $image;
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function globRecursive(string $base, string $ext): array
    {
        $results = [];
        $dirs    = glob(rtrim($base, '/') . '/*', GLOB_ONLYDIR);
        foreach ($dirs ?: [] as $dir) {
            $files = glob($dir . '/*.' . $ext) ?: [];
            $results = array_merge($results, $files, $this->globRecursive($dir, $ext));
        }
        return $results;
    }

    private function deleteDirectory(string $path): void
    {
        foreach (glob($path . '/*') ?: [] as $item) {
            is_dir($item) ? $this->deleteDirectory($item) : unlink($item);
        }
        rmdir($path);
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
