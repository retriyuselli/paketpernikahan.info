<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramService
{
    protected string $accessToken;
    protected string $userId;
    protected int    $cacheTtl = 3600; // 1 jam

    public function __construct()
    {
        $this->accessToken = config('services.instagram.access_token', '');
        $this->userId      = config('services.instagram.user_id', '');
    }

    public function isConfigured(): bool
    {
        return filled($this->accessToken) && filled($this->userId);
    }

    /**
     * Ambil postingan terbaru dari Instagram.
     *
     * @param  int  $limit  Jumlah post (max 20)
     * @return \Illuminate\Support\Collection
     */
    public function getRecentPosts(int $limit = 12): \Illuminate\Support\Collection
    {
        if (! $this->isConfigured()) {
            return collect();
        }

        $cacheKey = "instagram_posts_{$this->userId}_{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit) {
            try {
                $response = Http::timeout(10)->get(
                    "https://graph.facebook.com/v19.0/{$this->userId}/media",
                    [
                        'fields'       => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp',
                        'limit'        => $limit,
                        'access_token' => $this->accessToken,
                    ]
                );

                if ($response->failed()) {
                    Log::warning('Instagram API error', ['status' => $response->status(), 'body' => $response->body()]);
                    return collect();
                }

                $data = $response->json('data', []);

                // Filter hanya IMAGE dan VIDEO yang punya thumbnail
                return collect($data)->filter(fn ($post) =>
                    in_array($post['media_type'] ?? '', ['IMAGE', 'CAROUSEL_ALBUM', 'VIDEO'])
                    && filled($post['media_url'] ?? $post['thumbnail_url'] ?? null)
                );
            } catch (\Throwable $e) {
                Log::error('Instagram fetch exception', ['message' => $e->getMessage()]);
                return collect();
            }
        });
    }

    /**
     * Hapus cache posts (panggil saat token diperbarui).
     */
    public function clearCache(): void
    {
        Cache::forget("instagram_posts_{$this->userId}_12");
    }
}
