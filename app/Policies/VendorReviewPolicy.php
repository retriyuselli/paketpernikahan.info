<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VendorReview;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorReviewPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VendorReview');
    }

    public function view(AuthUser $authUser, VendorReview $vendorReview): bool
    {
        return $authUser->can('View:VendorReview');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VendorReview');
    }

    public function update(AuthUser $authUser, VendorReview $vendorReview): bool
    {
        return $authUser->can('Update:VendorReview');
    }

    public function delete(AuthUser $authUser, VendorReview $vendorReview): bool
    {
        return $authUser->can('Delete:VendorReview');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VendorReview');
    }

    public function restore(AuthUser $authUser, VendorReview $vendorReview): bool
    {
        return $authUser->can('Restore:VendorReview');
    }

    public function forceDelete(AuthUser $authUser, VendorReview $vendorReview): bool
    {
        return $authUser->can('ForceDelete:VendorReview');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VendorReview');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VendorReview');
    }

    public function replicate(AuthUser $authUser, VendorReview $vendorReview): bool
    {
        return $authUser->can('Replicate:VendorReview');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VendorReview');
    }

}