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
            'tags'            => $this->tags ?? [],
            'cover_image_url' => $this->absoluteUrl($this->cover_image),
            'views_count'     => $this->views_count,
            'published_at'    => $this->published_at?->toIso8601String(),
        ];
    }
}
