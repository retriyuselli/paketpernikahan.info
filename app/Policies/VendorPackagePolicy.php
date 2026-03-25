<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VendorPackage;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPackagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VendorPackage');
    }

    public function view(AuthUser $authUser, VendorPackage $vendorPackage): bool
    {
        return $authUser->can('View:VendorPackage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VendorPackage');
    }

    public function update(AuthUser $authUser, VendorPackage $vendorPackage): bool
    {
        return $authUser->can('Update:VendorPackage');
    }

    public function delete(AuthUser $authUser, VendorPackage $vendorPackage): bool
    {
        return $authUser->can('Delete:VendorPackage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VendorPackage');
    }

    public function restore(AuthUser $authUser, VendorPackage $vendorPackage): bool
    {
        return $authUser->can('Restore:VendorPackage');
    }

    public function forceDelete(AuthUser $authUser, VendorPackage $vendorPackage): bool
    {
        return $authUser->can('ForceDelete:VendorPackage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VendorPackage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VendorPackage');
    }

    public function replicate(AuthUser $authUser, VendorPackage $vendorPackage): bool
    {
        return $authUser->can('Replicate:VendorPackage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VendorPackage');
    }

}