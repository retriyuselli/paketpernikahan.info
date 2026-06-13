<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'slug'            => $this->slug,
            'title'           => $this->title,
            'category'        => $this->category,
            'excerpt'         => $this->excerpt,
            'tags'            => $this->normalizedTags(),
            'cover_image_url' => $this->absoluteUrl($this->cover_image),
            'views_count'     => $this->views_count,
            'published_at'    => $this->published_at?->toIso8601String(),
        ];
    }

    /**
     * Data lama menyimpan tags sebagai string biasa, bukan array JSON —
     * normalisasi agar API selalu mengembalikan array string.
     */
    protected function normalizedTags(): array
    {
        $tags = $this->tags;

        if (is_string($tags)) {
            $decoded = json_decode($tags, true);
            $tags = is_array($decoded)
                ? $decoded
                : array_values(array_filter(array_map('trim', explode(',', $tags))));
        }

        return is_array($tags) ? $tags : [];
    }
}
