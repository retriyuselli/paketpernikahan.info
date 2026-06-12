<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'slug'                => $this->slug,
            'name'                => $this->name,
            'price'               => (int) $this->price,
            'discount'            => (int) ($this->discount ?? 0),
            'dp_paket'            => $this->dp_paket,
            'max_guests'          => $this->max_guests,
            'type'                => $this->type,
            'capacity'            => $this->capacity,
            'card_color'          => $this->card_color,
            'card_text_color'     => $this->card_text_color,
            'image_url'           => $this->firstImageUrl($this->image_path),
            'discount_expires_at' => $this->discount_expires_at?->toIso8601String(),
            'vendor'              => new VendorSummaryResource($this->whenLoaded('vendor')),
        ];
    }
}
