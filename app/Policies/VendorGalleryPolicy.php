<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VendorGallery;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorGalleryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VendorGallery');
    }

    public function view(AuthUser $authUser, VendorGallery $vendorGallery): bool
    {
        return $authUser->can('View:VendorGallery');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VendorGallery');
    }

    public function update(AuthUser $authUser, VendorGallery $vendorGallery): bool
    {
        return $authUser->can('Update:VendorGallery');
    }

    public function delete(AuthUser $authUser, VendorGallery $vendorGallery): bool
    {
        return $authUser->can('Delete:VendorGallery');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VendorGallery');
    }

    public function restore(AuthUser $authUser, VendorGallery $vendorGallery): bool
    {
        return $authUser->can('Restore:VendorGallery');
    }

    public function forceDelete(AuthUser $authUser, VendorGallery $vendorGallery): bool
    {
        return $authUser->can('ForceDelete:VendorGallery');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VendorGallery');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VendorGallery');
    }

    public function replicate(AuthUser $authUser, VendorGallery $vendorGallery): bool
    {
        return $authUser->can('Replicate:VendorGallery');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VendorGallery');
    }

}