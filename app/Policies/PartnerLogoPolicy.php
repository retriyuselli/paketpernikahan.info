<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PartnerLogo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerLogoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PartnerLogo');
    }

    public function view(AuthUser $authUser, PartnerLogo $partnerLogo): bool
    {
        return $authUser->can('View:PartnerLogo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PartnerLogo');
    }

    public function update(AuthUser $authUser, PartnerLogo $partnerLogo): bool
    {
        return $authUser->can('Update:PartnerLogo');
    }

    public function delete(AuthUser $authUser, PartnerLogo $partnerLogo): bool
    {
        return $authUser->can('Delete:PartnerLogo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PartnerLogo');
    }

    public function restore(AuthUser $authUser, PartnerLogo $partnerLogo): bool
    {
        return $authUser->can('Restore:PartnerLogo');
    }

    public function forceDelete(AuthUser $authUser, PartnerLogo $partnerLogo): bool
    {
        return $authUser->can('ForceDelete:PartnerLogo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PartnerLogo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PartnerLogo');
    }

    public function replicate(AuthUser $authUser, PartnerLogo $partnerLogo): bool
    {
        return $authUser->can('Replicate:PartnerLogo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PartnerLogo');
    }

}