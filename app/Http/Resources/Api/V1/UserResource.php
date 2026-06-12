<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ResolvesUrls;

    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'avatar_url'        => $this->absoluteUrl($this->avatar_url),
            'whatsapp'          => $this->whatsapp,
            'theme_color'       => $this->theme_color,
            'email_verified'    => $this->email_verified_at !== null,
            'roles'             => $this->getRoleNames(),
        ];
    }
}
