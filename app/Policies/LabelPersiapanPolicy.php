<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LabelPersiapan;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabelPersiapanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LabelPersiapan');
    }

    public function view(AuthUser $authUser, LabelPersiapan $labelPersiapan): bool
    {
        return $authUser->can('View:LabelPersiapan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LabelPersiapan');
    }

    public function update(AuthUser $authUser, LabelPersiapan $labelPersiapan): bool
    {
        return $authUser->can('Update:LabelPersiapan');
    }

    public function delete(AuthUser $authUser, LabelPersiapan $labelPersiapan): bool
    {
        return $authUser->can('Delete:LabelPersiapan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LabelPersiapan');
    }

    public function restore(AuthUser $authUser, LabelPersiapan $labelPersiapan): bool
    {
        return $authUser->can('Restore:LabelPersiapan');
    }

    public function forceDelete(AuthUser $authUser, LabelPersiapan $labelPersiapan): bool
    {
        return $authUser->can('ForceDelete:LabelPersiapan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LabelPersiapan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LabelPersiapan');
    }

    public function replicate(AuthUser $authUser, LabelPersiapan $labelPersiapan): bool
    {
        return $authUser->can('Replicate:LabelPersiapan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LabelPersiapan');
    }

}