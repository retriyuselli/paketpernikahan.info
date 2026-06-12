<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'status'             => $this->status,
            'payment_status'     => $this->payment_status,
            'event_date'         => $this->event_date?->toDateString(),
            'agreed_total'       => $this->agreed_total,
            'dp_required_amount' => $this->dp_required_amount,
            'promo_code'         => $this->promo_code,
            'promo_discount'     => $this->promo_discount,
            'phone'              => $this->phone,
            'notes'              => $this->notes,
            'created_at'         => $this->created_at?->toIso8601String(),
            'vendor'             => new VendorSummaryResource($this->whenLoaded('vendor')),
            'package'            => new PackageResource($this->whenLoaded('vendorPackage')),
        ];
    }
}
