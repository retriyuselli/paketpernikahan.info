<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RealWedding;
use Illuminate\Auth\Access\HandlesAuthorization;

class RealWeddingPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RealWedding');
    }

    public function view(AuthUser $authUser, RealWedding $realWedding): bool
    {
        return $authUser->can('View:RealWedding');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RealWedding');
    }

    public function update(AuthUser $authUser, RealWedding $realWedding): bool
    {
        return $authUser->can('Update:RealWedding');
    }

    public function delete(AuthUser $authUser, RealWedding $realWedding): bool
    {
        return $authUser->can('Delete:RealWedding');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RealWedding');
    }

    public function restore(AuthUser $authUser, RealWedding $realWedding): bool
    {
        return $authUser->can('Restore:RealWedding');
    }

    public function forceDelete(AuthUser $authUser, RealWedding $realWedding): bool
    {
        return $authUser->can('ForceDelete:RealWedding');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RealWedding');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RealWedding');
    }

    public function replicate(AuthUser $authUser, RealWedding $realWedding): bool
    {
        return $authUser->can('Replicate:RealWedding');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RealWedding');
    }

}