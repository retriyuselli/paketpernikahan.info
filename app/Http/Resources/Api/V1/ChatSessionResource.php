<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'token'          => $this->session_token,
            'status'         => $this->status,
            'vendor'         => new VendorSummaryResource($this->whenLoaded('vendor')),
            'package'        => new PackageResource($this->whenLoaded('vendorPackage')),
            'latest_message' => new ChatMessageResource($this->whenLoaded('latestMessage')),
            'created_at'     => $this->created_at?->toIso8601String(),
            'updated_at'     => $this->updated_at?->toIso8601String(),
        ];
    }
}
