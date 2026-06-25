<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PreparationTaskTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreparationTaskTemplatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PreparationTaskTemplate');
    }

    public function view(AuthUser $authUser, PreparationTaskTemplate $preparationTaskTemplate): bool
    {
        return $authUser->can('View:PreparationTaskTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PreparationTaskTemplate');
    }

    public function update(AuthUser $authUser, PreparationTaskTemplate $preparationTaskTemplate): bool
    {
        return $authUser->can('Update:PreparationTaskTemplate');
    }

    public function delete(AuthUser $authUser, PreparationTaskTemplate $preparationTaskTemplate): bool
    {
        return $authUser->can('Delete:PreparationTaskTemplate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PreparationTaskTemplate');
    }

    public function restore(AuthUser $authUser, PreparationTaskTemplate $preparationTaskTemplate): bool
    {
        return $authUser->can('Restore:PreparationTaskTemplate');
    }

    public function forceDelete(AuthUser $authUser, PreparationTaskTemplate $preparationTaskTemplate): bool
    {
        return $authUser->can('ForceDelete:PreparationTaskTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PreparationTaskTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PreparationTaskTemplate');
    }

    public function replicate(AuthUser $authUser, PreparationTaskTemplate $preparationTaskTemplate): bool
    {
        return $authUser->can('Replicate:PreparationTaskTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PreparationTaskTemplate');
    }

}