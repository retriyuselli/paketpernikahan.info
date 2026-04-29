<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Blog;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBooking;
use App\Models\VendorPackage;
use App\Models\VendorReview;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppStatsWidget extends BaseWidget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalVendors      = Vendor::count();
        $activeVendors     = Vendor::where('is_active', true)->count();
        $totalPackages     = VendorPackage::count();
        $activePackages    = VendorPackage::where('is_active', true)->count();
        $totalBookings     = VendorBooking::count();
        $pendingBookings   = VendorBooking::where('status', 'pending')->count();
        $confirmedBookings = VendorBooking::where('status', 'confirmed')->count();
        $totalUsers        = User::count();
        $totalReviews      = VendorReview::count();
        $pendingReviews    = VendorReview::where('is_approved', false)->count();
        $totalBlogs        = Blog::count();

        return [
            Stat::make('Vendor', $totalVendors)
                ->description("{$activeVendors} aktif")
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('warning'),

            Stat::make('Paket', $totalPackages)
                ->description("{$activePackages} aktif")
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('success'),

            Stat::make('Booking', $totalBookings)
                ->description("{$pendingBookings} pending · {$confirmedBookings} confirmed")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make('Pengguna', $totalUsers)
                ->description('terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Ulasan', $totalReviews)
                ->description($pendingReviews > 0 ? "{$pendingReviews} perlu disetujui" : 'semua disetujui')
                ->descriptionIcon('heroicon-o-star')
                ->color($pendingReviews > 0 ? 'danger' : 'success'),

            Stat::make('Blog', $totalBlogs)
                ->description('artikel')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('gray'),
        ];
    }
}
