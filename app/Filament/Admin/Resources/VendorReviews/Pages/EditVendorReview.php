<?php

namespace App\Filament\Admin\Resources\VendorReviews\Pages;

use App\Filament\Admin\Resources\VendorReviews\VendorReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class EditVendorReview extends EditRecord
{
    protected static string $resource = VendorReviewResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
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

        $original = $this->getRecord()->getOriginal('admin_reply');
        if ((string) $original !== $adminReply) {
            $data['admin_replied_at'] = now();
        } else {
            $data = Arr::except($data, ['admin_replied_at']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
