<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorPackage;
use Illuminate\Http\Request;

class VendorPackageController extends Controller
{
    private function authorizeRole(Vendor $vendor): void
    {
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        abort_unless($user, 403);

        if ($user->hasRole(['super_admin', 'admin'])) {
            return;
        }

        abort_unless(
            (int) $vendor->owner_user_id === (int) $user->id,
            403
        );
    }

    public function store(Request $request, Vendor $vendor)
    {
        $this->authorizeRole($vendor);
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        $isAdmin = $user?->hasRole(['super_admin', 'admin']) ?? false;

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'price_raw'       => 'required|integer|min:0',
            'discount'        => 'nullable|integer|min:0',
            'max_guests'      => 'nullable|string|max:100',
            'items'           => 'nullable|string',
            'card_color'      => 'nullable|string|max:20',
            'card_text_color' => 'nullable|string|max:20',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'nullable|boolean',
        ]);

        $package = $vendor->packages()->create([
            'name'            => $validated['name'],
            'price'           => 'Rp ' . number_format((int)$validated['price_raw'], 0, ',', '.'),
            'price_raw'       => (int)$validated['price_raw'],
            'discount'        => (int)($validated['discount'] ?? 0),
            'max_guests'      => $validated['max_guests'] ?? '',
            'items'           => $this->parseItems($validated['items'] ?? ''),
            'card_color'      => $validated['card_color'] ?? '#C8D5B9',
            'card_text_color' => $validated['card_text_color'] ?? '#444444',
            'sort_order'      => (int)($validated['sort_order'] ?? 0),
            'is_active'       => (bool)($validated['is_active'] ?? true),
        ]);

        $vendor->refresh();
        $isComplete = $vendor->computeProfileComplete();
        $priceStart = $vendor->computePriceStartFromPackages();
        if (!$isAdmin && !$isComplete) {
            $vendor->update([
                'is_profile_complete' => false,
                'is_active' => false,
                'price_start' => $priceStart,
            ]);
        } else {
            $vendor->update([
                'is_profile_complete' => $isComplete,
                'is_active' => $isComplete ? true : $vendor->is_active,
                'price_start' => $priceStart,
            ]);
        }

        return response()->json(['success' => true, 'package' => $package]);
    }

    public function update(Request $request, Vendor $vendor, VendorPackage $package)
    {
        $this->authorizeRole($vendor);
        abort_if($package->vendor_id !== $vendor->id, 404);
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        $isAdmin = $user?->hasRole(['super_admin', 'admin']) ?? false;

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'price_raw'       => 'required|integer|min:0',
            'discount'        => 'nullable|integer|min:0',
            'max_guests'      => 'nullable|string|max:100',
            'items'           => 'nullable|string',
            'card_color'      => 'nullable|string|max:20',
            'card_text_color' => 'nullable|string|max:20',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'nullable|boolean',
        ]);

        $package->update([
            'name'            => $validated['name'],
            'price'           => 'Rp ' . number_format((int)$validated['price_raw'], 0, ',', '.'),
            'price_raw'       => (int)$validated['price_raw'],
            'discount'        => (int)($validated['discount'] ?? 0),
            'max_guests'      => $validated['max_guests'] ?? '',
            'items'           => $this->parseItems($validated['items'] ?? ''),
            'card_color'      => $validated['card_color'] ?? '#C8D5B9',
            'card_text_color' => $validated['card_text_color'] ?? '#444444',
            'sort_order'      => (int)($validated['sort_order'] ?? 0),
            'is_active'       => (bool)($validated['is_active'] ?? true),
        ]);

        $vendor->refresh();
        $isComplete = $vendor->computeProfileComplete();
        $priceStart = $vendor->computePriceStartFromPackages();
        if (!$isAdmin && !$isComplete) {
            $vendor->update([
                'is_profile_complete' => false,
                'is_active' => false,
                'price_start' => $priceStart,
            ]);
        } else {
            $vendor->update([
                'is_profile_complete' => $isComplete,
                'is_active' => $isComplete ? true : $vendor->is_active,
                'price_start' => $priceStart,
            ]);
        }

        return response()->json(['success' => true, 'package' => $package->fresh()]);
    }

    public function destroy(Vendor $vendor, VendorPackage $package)
    {
        $this->authorizeRole($vendor);
        abort_if($package->vendor_id !== $vendor->id, 404);
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        $isAdmin = $user?->hasRole(['super_admin', 'admin']) ?? false;

        $package->delete();

        $vendor->refresh();
        $isComplete = $vendor->computeProfileComplete();
        $priceStart = $vendor->computePriceStartFromPackages();
        if (!$isAdmin && !$isComplete) {
            $vendor->update([
                'is_profile_complete' => false,
                'is_active' => false,
                'price_start' => $priceStart,
            ]);
        } else {
            $vendor->update([
                'is_profile_complete' => $isComplete,
                'is_active' => $isComplete ? true : $vendor->is_active,
                'price_start' => $priceStart,
            ]);
        }

        return response()->json(['success' => true]);
    }

    private function parseItems(string $raw): array
    {
        return array_values(
            array_filter(
                array_map('trim', explode("\n", str_replace("\r", '', $raw)))
            )
        );
    }
}
