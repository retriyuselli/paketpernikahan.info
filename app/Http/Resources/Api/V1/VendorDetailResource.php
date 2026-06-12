<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class VendorDetailResource extends VendorSummaryResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'description'    => $this->description,
            'province'       => $this->province,
            'phone'          => $this->phone,
            'instagram'      => $this->instagram,
            'experience'     => $this->experience,
            'events_done'    => $this->events_done,
            'likes'          => $this->likes,
            'comments_count' => $this->comments_count,
            'badge'          => $this->badge ?? [],
            'promo'          => $this->promo ?? [],
            'logo_url'       => $this->absoluteUrl($this->logo_vendor),
            'cover_video'    => $this->cover_video,
            'galleries'      => VendorGalleryResource::collection($this->whenLoaded('galleries')),
            'packages'       => PackageResource::collection($this->whenLoaded('packages')),
            'reviews'        => ReviewResource::collection($this->whenLoaded('approvedReviews')),
        ]);
    }
}
