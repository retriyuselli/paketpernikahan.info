<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PushNotificationLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class PushNotificationLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PushNotificationLog');
    }

    public function view(AuthUser $authUser, PushNotificationLog $pushNotificationLog): bool
    {
        return $authUser->can('View:PushNotificationLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PushNotificationLog');
    }

    public function update(AuthUser $authUser, PushNotificationLog $pushNotificationLog): bool
    {
        return $authUser->can('Update:PushNotificationLog');
    }

    public function delete(AuthUser $authUser, PushNotificationLog $pushNotificationLog): bool
    {
        return $authUser->can('Delete:PushNotificationLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PushNotificationLog');
    }

    public function restore(AuthUser $authUser, PushNotificationLog $pushNotificationLog): bool
    {
        return $authUser->can('Restore:PushNotificationLog');
    }

    public function forceDelete(AuthUser $authUser, PushNotificationLog $pushNotificationLog): bool
    {
        return $authUser->can('ForceDelete:PushNotificationLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PushNotificationLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PushNotificationLog');
    }

    public function replicate(AuthUser $authUser, PushNotificationLog $pushNotificationLog): bool
    {
        return $authUser->can('Replicate:PushNotificationLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PushNotificationLog');
    }

}