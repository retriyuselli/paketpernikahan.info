<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WeddingPaymentScheduleTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeddingPaymentScheduleTemplatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WeddingPaymentScheduleTemplate');
    }

    public function view(AuthUser $authUser, WeddingPaymentScheduleTemplate $weddingPaymentScheduleTemplate): bool
    {
        return $authUser->can('View:WeddingPaymentScheduleTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WeddingPaymentScheduleTemplate');
    }

    public function update(AuthUser $authUser, WeddingPaymentScheduleTemplate $weddingPaymentScheduleTemplate): bool
    {
        return $authUser->can('Update:WeddingPaymentScheduleTemplate');
    }

    public function delete(AuthUser $authUser, WeddingPaymentScheduleTemplate $weddingPaymentScheduleTemplate): bool
    {
        return $authUser->can('Delete:WeddingPaymentScheduleTemplate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WeddingPaymentScheduleTemplate');
    }

    public function restore(AuthUser $authUser, WeddingPaymentScheduleTemplate $weddingPaymentScheduleTemplate): bool
    {
        return $authUser->can('Restore:WeddingPaymentScheduleTemplate');
    }

    public function forceDelete(AuthUser $authUser, WeddingPaymentScheduleTemplate $weddingPaymentScheduleTemplate): bool
    {
        return $authUser->can('ForceDelete:WeddingPaymentScheduleTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WeddingPaymentScheduleTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WeddingPaymentScheduleTemplate');
    }

    public function replicate(AuthUser $authUser, WeddingPaymentScheduleTemplate $weddingPaymentScheduleTemplate): bool
    {
        return $authUser->can('Replicate:WeddingPaymentScheduleTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WeddingPaymentScheduleTemplate');
    }

}