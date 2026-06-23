<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VipGuestDelegate;
use Illuminate\Auth\Access\HandlesAuthorization;

class VipGuestDelegatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VipGuestDelegate');
    }

    public function view(AuthUser $authUser, VipGuestDelegate $vipGuestDelegate): bool
    {
        return $authUser->can('View:VipGuestDelegate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VipGuestDelegate');
    }

    public function update(AuthUser $authUser, VipGuestDelegate $vipGuestDelegate): bool
    {
        return $authUser->can('Update:VipGuestDelegate');
    }

    public function delete(AuthUser $authUser, VipGuestDelegate $vipGuestDelegate): bool
    {
        return $authUser->can('Delete:VipGuestDelegate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VipGuestDelegate');
    }

    public function restore(AuthUser $authUser, VipGuestDelegate $vipGuestDelegate): bool
    {
        return $authUser->can('Restore:VipGuestDelegate');
    }

    public function forceDelete(AuthUser $authUser, VipGuestDelegate $vipGuestDelegate): bool
    {
        return $authUser->can('ForceDelete:VipGuestDelegate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VipGuestDelegate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VipGuestDelegate');
    }

    public function replicate(AuthUser $authUser, VipGuestDelegate $vipGuestDelegate): bool
    {
        return $authUser->can('Replicate:VipGuestDelegate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VipGuestDelegate');
    }

}