<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HomeAd;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeAdPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HomeAd');
    }

    public function view(AuthUser $authUser, HomeAd $homeAd): bool
    {
        return $authUser->can('View:HomeAd');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HomeAd');
    }

    public function update(AuthUser $authUser, HomeAd $homeAd): bool
    {
        return $authUser->can('Update:HomeAd');
    }

    public function delete(AuthUser $authUser, HomeAd $homeAd): bool
    {
        return $authUser->can('Delete:HomeAd');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:HomeAd');
    }

    public function restore(AuthUser $authUser, HomeAd $homeAd): bool
    {
        return $authUser->can('Restore:HomeAd');
    }

    public function forceDelete(AuthUser $authUser, HomeAd $homeAd): bool
    {
        return $authUser->can('ForceDelete:HomeAd');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HomeAd');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HomeAd');
    }

    public function replicate(AuthUser $authUser, HomeAd $homeAd): bool
    {
        return $authUser->can('Replicate:HomeAd');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HomeAd');
    }

}