<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CategoryVendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryVendorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CategoryVendor');
    }

    public function view(AuthUser $authUser, CategoryVendor $categoryVendor): bool
    {
        return $authUser->can('View:CategoryVendor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CategoryVendor');
    }

    public function update(AuthUser $authUser, CategoryVendor $categoryVendor): bool
    {
        return $authUser->can('Update:CategoryVendor');
    }

    public function delete(AuthUser $authUser, CategoryVendor $categoryVendor): bool
    {
        return $authUser->can('Delete:CategoryVendor');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CategoryVendor');
    }

    public function restore(AuthUser $authUser, CategoryVendor $categoryVendor): bool
    {
        return $authUser->can('Restore:CategoryVendor');
    }

    public function forceDelete(AuthUser $authUser, CategoryVendor $categoryVendor): bool
    {
        return $authUser->can('ForceDelete:CategoryVendor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CategoryVendor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CategoryVendor');
    }

    public function replicate(AuthUser $authUser, CategoryVendor $categoryVendor): bool
    {
        return $authUser->can('Replicate:CategoryVendor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CategoryVendor');
    }

}