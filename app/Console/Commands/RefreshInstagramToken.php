<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshInstagramToken extends Command
{
    protected $signature   = 'instagram:refresh-token';
    protected $description = 'Refresh Instagram long-lived access token (berlaku 60 hari, jalankan tiap 30 hari)';

    public function handle(): int
    {
        $appId       = config('services.instagram.app_id');
        $appSecret   = config('services.instagram.app_secret');
        $currentToken = config('services.instagram.access_token');

        if (! $appId || ! $appSecret || ! $currentToken) {
            $this->error('Instagram credentials belum lengkap di .env');
            return Command::FAILURE;
        }

        $this->info('Mengambil token baru dari Facebook...');

        $response = Http::timeout(15)->get('https://graph.facebook.com/v19.0/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $appId,
            'client_secret'     => $appSecret,
            'fb_exchange_token' => $currentToken,
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown error');
            $this->error("Gagal refresh token: {$error}");
            Log::error('Instagram token refresh failed', ['response' => $response->json()]);
            return Command::FAILURE;
        }

        $newToken = $response->json('access_token');

        if (! $newToken) {
            $this->error('Token baru tidak ditemukan dalam response.');
            return Command::FAILURE;
        }

        // Update .env file
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $envContent = preg_replace(
            '/^INSTAGRAM_ACCESS_TOKEN=.*/m',
            "INSTAGRAM_ACCESS_TOKEN={$newToken}",
            $envContent
        );

        file_put_contents($envPath, $envContent);

        // Clear config cache agar nilai baru terbaca
        $this->call('config:clear');
        $this->call('cache:forget', ['key' => "instagram_posts_17841403256338426_12"]);

        $this->info('✅ Token Instagram berhasil diperbarui!');

        $expiresIn = $response->json('expires_in');
        if ($expiresIn) {
            $days = round($expiresIn / 86400);
            $this->info("   Token baru berlaku selama {$days} hari.");
        }

        Log::info('Instagram access token refreshed successfully.');

        return Command::SUCCESS;
    }
}
