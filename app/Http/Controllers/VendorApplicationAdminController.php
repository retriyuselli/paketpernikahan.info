<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorApplication;
use App\Models\CategoryVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorApplicationAdminController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
        $favoriteCount = $user->likedVendors()->count();
        $bookingCount = $user->vendorBookings()->count();
        $bookingUserCount = \App\Models\VendorBooking::where('status', 'pending')->count();

        $status = request('status');

        $applications = VendorApplication::with(['user', 'vendor'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->limit(300)
            ->get();

        return view('dashboard.vendor-applications', compact(
            'user',
            'reviewCount',
            'favoriteCount',
            'bookingCount',
            'bookingUserCount',
            'applications',
            'status'
        ));
    }

    public function approve(VendorApplication $application)
    {
        $admin = User::findOrFail(Auth::id());
        abort_unless($admin->hasRole(['super_admin', 'admin']), 403);

        if ($application->status === 'approved') {
            return back()->with('vendor_app_success', 'Pengajuan sudah disetujui.');
        }

        $applicant = User::findOrFail($application->user_id);

        $vendor = $application->vendor_id ? Vendor::find($application->vendor_id) : null;
        if (!$vendor) {
            $appCategories = is_array($application->categories) ? $application->categories : [];
            $primaryCategory = $application->category ?: ($appCategories[0] ?? null);
            $slug = $this->uniqueVendorSlug($application->business_name);
            $vendorType = $application->type
                ?: ($primaryCategory ? CategoryVendor::where('slug', $primaryCategory)->value('name') : null)
                ?: ($primaryCategory ?: $application->category);
            $vendor = Vendor::create([
                'name' => $application->business_name,
                'slug' => $slug,
                'category' => $primaryCategory ?: $application->category,
                'type' => $vendorType,
                'location' => $application->location,
                'province' => $application->province,
                'city' => $application->city ?: 'Palembang',
                'description' => $application->note,
                'phone' => $application->phone,
                'email' => $application->email,
                'instagram' => $application->instagram,
                'logo_vendor' => $application->logo_vendor,
                'is_active' => true,
                'is_profile_complete' => false,
                'owner_user_id' => $applicant->id,
            ]);
        } else {
            $vendor->update([
                'owner_user_id' => $applicant->id,
                'logo_vendor' => $application->logo_vendor,
            ]);
        }

        if (!$applicant->hasRole(['vendor'])) {
            $applicant->assignRole('vendor');
        }

        $application->update([
            'status' => 'approved',
            'admin_note' => null,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'vendor_id' => $vendor->id,
        ]);

        return back()->with('vendor_app_success', 'Pengajuan vendor berhasil disetujui.');
    }

    public function reject(Request $request, VendorApplication $application)
    {
        $admin = User::findOrFail(Auth::id());
        abort_unless($admin->hasRole(['super_admin', 'admin']), 403);

        $data = $request->validateWithBag('vendor_app', [
            'admin_note' => ['required', 'string', 'max:2000'],
        ]);

        $application->update([
            'status' => 'rejected',
            'admin_note' => trim($data['admin_note']),
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('vendor_app_success', 'Pengajuan vendor berhasil ditolak.');
    }

    private function uniqueVendorSlug(string $name): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : Str::random(8);
        $slug = $base;
        $i = 2;

        while (Vendor::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
