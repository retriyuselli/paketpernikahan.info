<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'sender'     => $this->sender,
            'message'    => $this->message,
            'admin_name' => $this->adminUser?->name,
            'package'    => new PackageResource($this->whenLoaded('vendorPackage')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
