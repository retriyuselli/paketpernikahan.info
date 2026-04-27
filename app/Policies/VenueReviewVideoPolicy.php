<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VenueReviewVideo;
use Illuminate\Auth\Access\HandlesAuthorization;

class VenueReviewVideoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VenueReviewVideo');
    }

    public function view(AuthUser $authUser, VenueReviewVideo $venueReviewVideo): bool
    {
        return $authUser->can('View:VenueReviewVideo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VenueReviewVideo');
    }

    public function update(AuthUser $authUser, VenueReviewVideo $venueReviewVideo): bool
    {
        return $authUser->can('Update:VenueReviewVideo');
    }

    public function delete(AuthUser $authUser, VenueReviewVideo $venueReviewVideo): bool
    {
        return $authUser->can('Delete:VenueReviewVideo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VenueReviewVideo');
    }

    public function restore(AuthUser $authUser, VenueReviewVideo $venueReviewVideo): bool
    {
        return $authUser->can('Restore:VenueReviewVideo');
    }

    public function forceDelete(AuthUser $authUser, VenueReviewVideo $venueReviewVideo): bool
    {
        return $authUser->can('ForceDelete:VenueReviewVideo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VenueReviewVideo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VenueReviewVideo');
    }

    public function replicate(AuthUser $authUser, VenueReviewVideo $venueReviewVideo): bool
    {
        return $authUser->can('Replicate:VenueReviewVideo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VenueReviewVideo');
    }

}