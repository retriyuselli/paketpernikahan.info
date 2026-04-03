<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PaketGallery;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaketGalleryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PaketGallery');
    }

    public function view(AuthUser $authUser, PaketGallery $paketGallery): bool
    {
        return $authUser->can('View:PaketGallery');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PaketGallery');
    }

    public function update(AuthUser $authUser, PaketGallery $paketGallery): bool
    {
        return $authUser->can('Update:PaketGallery');
    }

    public function delete(AuthUser $authUser, PaketGallery $paketGallery): bool
    {
        return $authUser->can('Delete:PaketGallery');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PaketGallery');
    }

    public function restore(AuthUser $authUser, PaketGallery $paketGallery): bool
    {
        return $authUser->can('Restore:PaketGallery');
    }

    public function forceDelete(AuthUser $authUser, PaketGallery $paketGallery): bool
    {
        return $authUser->can('ForceDelete:PaketGallery');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PaketGallery');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PaketGallery');
    }

    public function replicate(AuthUser $authUser, PaketGallery $paketGallery): bool
    {
        return $authUser->can('Replicate:PaketGallery');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PaketGallery');
    }

}