<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorSummaryResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'slug'            => $this->slug,
            'name'            => $this->name,
            'category'        => $this->category,
            'categories'      => $this->categories ?? [],
            'city'            => $this->city,
            'location'        => $this->location,
            'price_start'     => $this->price_start,
            'discount'        => $this->discount,
            'rating'          => $this->rating,
            'cover_image_url' => $this->firstImageUrl($this->cover_image),
        ];
    }
}
