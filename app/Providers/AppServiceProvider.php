<?php

namespace App\Providers;

use App\Models\Vendor;
use App\Models\VendorApplication;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use App\Models\VendorReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, \App\Http\Responses\LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layout.header', function ($view) {
            $user = Auth::user();

            $headerShowJoinVendorForAuth = false;
            if ($user instanceof \App\Models\User) {
                $isPrivileged = $user->hasRole(['super_admin', 'admin', 'vendor']);

                if (!$isPrivileged) {
                    $hasVendorApplication = VendorApplication::query()
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'approved'])
                        ->exists();

                    $headerShowJoinVendorForAuth = !$hasVendorApplication;
                }
            }

            $view->with(compact('headerShowJoinVendorForAuth'));
        });

        View::composer('layout.dashboard', function ($view) {
            $user = Auth::user();
            if (!$user instanceof \App\Models\User) {
                return;
            }

            $isAdmin = $user->hasRole(['super_admin', 'admin']);
            $isVendor = $user->hasRole(['vendor']);

            $vendorIds = null;
            if (!$isAdmin && $isVendor) {
                $vendorIds = Vendor::where('owner_user_id', $user->id)->pluck('id');
            }

            $menuVendorCount = $isVendor
                ? Vendor::where('owner_user_id', $user->id)->count()
                : 0;

            $menuVendorBookingPendingCount = ($isAdmin || $isVendor)
                ? VendorBooking::query()
                    ->where('status', 'pending')
                    ->when($vendorIds, fn ($q) => $q->whereIn('vendor_id', $vendorIds))
                    ->count()
                : 0;

            $menuVendorPaymentPendingCount = ($isAdmin || $isVendor)
                ? VendorBookingPayment::query()
                    ->where('status', 'pending_verification')
                    ->when($vendorIds, fn ($q) => $q->whereHas('booking', fn ($b) => $b->whereIn('vendor_id', $vendorIds)))
                    ->count()
                : 0;

            $menuAllVendorCount = $isAdmin ? Vendor::count() : 0;

            $menuVendorApplicationPendingCount = $isAdmin
                ? VendorApplication::where('status', 'pending')->count()
                : 0;

            $menuPaymentUserPendingCount = $isAdmin
                ? VendorBookingPayment::where('status', 'pending_verification')->count()
                : 0;

            $view->with(compact(
                'menuVendorCount',
                'menuVendorBookingPendingCount',
                'menuVendorPaymentPendingCount',
                'menuAllVendorCount',
                'menuVendorApplicationPendingCount',
                'menuPaymentUserPendingCount',
            ));
        });

        View::composer('dashboard.index', function ($view) {
            $user = Auth::user();
            if (!$user instanceof \App\Models\User) {
                return;
            }

            $isAdmin = $user->hasRole(['super_admin', 'admin']);
            $isVendor = $user->hasRole(['vendor']);

            $vendorIds = null;
            if (!$isAdmin && $isVendor) {
                $vendorIds = Vendor::where('owner_user_id', $user->id)->pluck('id');
            }

            $bookingQuery = VendorBooking::query();
            if ($isAdmin) {
            } elseif ($isVendor) {
                $bookingQuery->whereIn('vendor_id', $vendorIds ?: [-1]);
            } else {
                $bookingQuery->where('user_id', $user->id);
            }

            $bookingActiveCount = (clone $bookingQuery)->whereIn('status', ['pending', 'contacted', 'confirmed'])->count();
            $bookingDoneCount = (clone $bookingQuery)->where('status', 'done')->count();

            $paymentQuery = VendorBookingPayment::query();

            if ($isAdmin) {
            } elseif ($isVendor) {
                $paymentQuery->whereHas('booking', fn ($b) => $b->whereIn('vendor_id', $vendorIds ?: [-1]));
            } else {
                $paymentQuery->whereHas('booking', fn ($b) => $b->where('user_id', $user->id));
            }

            $paymentTotalCount = (clone $paymentQuery)->count();
            $paymentPendingCount = (clone $paymentQuery)->where('status', 'pending_verification')->count();
            $paymentApprovedCount = (clone $paymentQuery)->where('status', 'approved')->count();
            $paymentApprovedSum = (int) (clone $paymentQuery)->where('status', 'approved')->sum('amount');

            $paymentApprovedDpSum = (int) (clone $paymentQuery)->where('status', 'approved')->where('type', 'dp')->sum('amount');
            $paymentApprovedFinalSum = (int) (clone $paymentQuery)->where('status', 'approved')->where('type', 'final')->sum('amount');
            $paymentApprovedInstallmentSum = (int) (clone $paymentQuery)->where('status', 'approved')->where('type', 'installment')->sum('amount');

            $vendorActiveCount = 0;
            $vendorInactiveCount = 0;
            $vendorTotalCount = 0;
            $vendorIncompleteCount = 0;
            $reviewPendingCount = 0;
            if ($isAdmin) {
                $vendorActiveCount = Vendor::where('is_active', true)->count();
                $vendorInactiveCount = Vendor::where('is_active', false)->count();
                $vendorTotalCount = Vendor::count();
                $vendorIncompleteCount = Vendor::where('is_profile_complete', false)->count();
                $reviewPendingCount = VendorReview::where('is_approved', false)->count();
            }

            $view->with(compact(
                'bookingActiveCount',
                'bookingDoneCount',
                'paymentTotalCount',
                'paymentPendingCount',
                'paymentApprovedCount',
                'paymentApprovedSum',
                'paymentApprovedDpSum',
                'paymentApprovedFinalSum',
                'paymentApprovedInstallmentSum',
                'vendorActiveCount',
                'vendorInactiveCount',
                'vendorTotalCount',
                'vendorIncompleteCount',
                'reviewPendingCount',
            ));
        });
    }
}
