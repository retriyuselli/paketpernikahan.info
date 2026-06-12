<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class PackageDetailResource extends PackageResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'items'      => $this->items,
            'facilities' => $this->facilities ?? [],
            'images'     => collect(is_array($this->image_path) ? $this->image_path : [$this->image_path])
                ->filter()
                ->map(fn ($p) => $this->absoluteUrl($p))
                ->values(),
            'galleries'  => PaketGalleryResource::collection($this->whenLoaded('galleries')),
        ]);
    }
}
