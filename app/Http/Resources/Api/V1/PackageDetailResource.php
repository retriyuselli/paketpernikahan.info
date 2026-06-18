<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class PackageDetailResource extends PackageResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'wishlisted'    => (function () use ($request) {
                // Route ini public (tanpa auth middleware), sehingga $request->user()
                // selalu null. Kita resolve langsung dari Bearer token via Sanctum PAT.
                $userId = $request->user()?->id;
                if (!$userId && $token = $request->bearerToken()) {
                    $userId = \Laravel\Sanctum\PersonalAccessToken::findToken($token)?->tokenable_id;
                }
                return $userId
                    ? $this->wishlists()->where('user_id', $userId)->exists()
                    : false;
            })(),
            'description'   => $this->description,
            'items'         => $this->items,
            'items_grouped' => $this->items_grouped,
            'facilities'  => $this->facilities ?? [],
            'images'     => collect(is_array($this->image_path) ? $this->image_path : [$this->image_path])
                ->filter()
                ->map(fn ($p) => $this->absoluteUrl($p))
                ->values(),
            'galleries'  => PaketGalleryResource::collection($this->whenLoaded('galleries')),
        ]);
    }
}
