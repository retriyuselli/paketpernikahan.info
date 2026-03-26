<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HeroCircle;
use Illuminate\Auth\Access\HandlesAuthorization;

class HeroCirclePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HeroCircle');
    }

    public function view(AuthUser $authUser, HeroCircle $heroCircle): bool
    {
        return $authUser->can('View:HeroCircle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HeroCircle');
    }

    public function update(AuthUser $authUser, HeroCircle $heroCircle): bool
    {
        return $authUser->can('Update:HeroCircle');
    }

    public function delete(AuthUser $authUser, HeroCircle $heroCircle): bool
    {
        return $authUser->can('Delete:HeroCircle');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:HeroCircle');
    }

    public function restore(AuthUser $authUser, HeroCircle $heroCircle): bool
    {
        return $authUser->can('Restore:HeroCircle');
    }

    public function forceDelete(AuthUser $authUser, HeroCircle $heroCircle): bool
    {
        return $authUser->can('ForceDelete:HeroCircle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HeroCircle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HeroCircle');
    }

    public function replicate(AuthUser $authUser, HeroCircle $heroCircle): bool
    {
        return $authUser->can('Replicate:HeroCircle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HeroCircle');
    }

}