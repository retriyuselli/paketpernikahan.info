<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaketGalleryResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'image_url' => $this->firstImageUrl($this->image_video),
            'video_url' => $this->video_url,
            'caption'   => $this->caption,
        ];
    }
}
