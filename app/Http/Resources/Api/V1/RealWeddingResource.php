<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RealWeddingResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'slug'            => $this->slug,
            'title'           => $this->title,
            'couple_names'    => $this->couple_names,
            'venue_name'      => $this->venue_name,
            'badge'           => $this->badge,
            'cover_image_url' => $this->firstImageUrl($this->cover_image),
            'wedding_date'    => $this->wedding_date?->toDateString(),
            'views_count'     => $this->views_count,
        ];
    }
}
