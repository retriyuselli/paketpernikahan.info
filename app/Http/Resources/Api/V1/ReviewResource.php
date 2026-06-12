<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'reviewer_name'   => $this->reviewer_name,
            'reviewer_avatar' => $this->absoluteUrl($this->reviewer_avatar),
            'rating'          => $this->rating,
            'body'            => $this->body,
            'photo_url'       => $this->absoluteUrl($this->photo),
            'admin_reply'     => $this->admin_reply,
            'reviewed_at'     => $this->reviewed_at?->toDateString(),
        ];
    }
}
