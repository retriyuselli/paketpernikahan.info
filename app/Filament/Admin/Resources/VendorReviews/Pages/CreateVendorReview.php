<?php

namespace App\Filament\Admin\Resources\VendorReviews\Pages;

use App\Filament\Admin\Resources\VendorReviews\VendorReviewResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateVendorReview extends CreateRecord
{
    protected static string $resource = VendorReviewResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $adminReply = trim((string) ($data['admin_reply'] ?? ''));

        if ($adminReply === '') {
            $data['admin_reply'] = null;
            $data['admin_reply_by'] = null;
            $data['admin_replied_at'] = null;
            return $data;
        }

        $data['admin_reply'] = $adminReply;
        $data['admin_reply_by'] = Auth::id();
        $data['admin_replied_at'] = now();

        return $data;
    }
}
