<?php

namespace App\Http\Controllers;

use App\Models\CategoryVendor;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorEditController extends Controller
{
    private function authorizeRole(Vendor $vendor): void
    {
        if (!Auth::check()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit vendor.');
        }

        $user = \App\Models\User::find(Auth::id());
        if (!$user) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit vendor.');
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return;
        }

        abort_unless(
            (int) $vendor->owner_user_id === (int) $user->id,
            403,
            'Anda tidak memiliki akses untuk mengedit vendor.'
        );
    }

    public function edit(Vendor $vendor)
    {
        $this->authorizeRole($vendor);

        $vendor->load(['packages', 'galleries']);

        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->authorizeRole($vendor);

        $user = \App\Models\User::find(Auth::id());
        $isAdmin = $user?->hasRole(['super_admin', 'admin']) ?? false;

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'nullable|string|max:100',
            'category'     => 'required|string|max:100',
            'location'     => 'required|string|max:255',
            'province'     => 'nullable|string|max:100',
            'city'         => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'instagram'    => 'nullable|string|max:100',
            'capacity'     => 'nullable|string|max:100',
            'venue_type'   => 'nullable|string|max:100',
            'experience'   => 'nullable|string|max:50',
            'facilities'   => 'nullable|string',
            'events_done'  => 'nullable|integer|min:0',
            // rating, likes, comments_count dikelola otomatis dari frontend — tidak diubah di sini
            'promo'           => 'nullable|array',
            'promo.*'         => 'string',
            'is_active'       => 'boolean',
            'cover_image'     => 'nullable|array',
            'cover_image.*'   => 'image|max:1024',
            'cover_video'     => 'nullable|url|max:255',
        ]);

        if ($isAdmin) {
            $labelValidated = $request->validate([
                'badge'           => 'nullable|array',
                'badge.*'         => 'string',
            ]);
            $validated = array_merge($validated, $labelValidated);
        }

        if (empty($validated['type'])) {
            $validated['type'] = CategoryVendor::where('slug', $validated['category'])->value('name') ?: $validated['category'];
        }

        // Handle slug update if name changed
        if ($validated['name'] !== $vendor->name) {
            $slug = Str::slug($validated['name']);
            $count = Vendor::where('slug', 'like', $slug . '%')
                ->where('id', '!=', $vendor->id)
                ->count();
            $validated['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        }

        // Handle cover image: merge kept existing paths + new uploads
        $kept = array_values(array_filter($request->input('cover_image_keep', [])));
        $newPaths = [];
        if ($request->hasFile('cover_image')) {
            foreach ($request->file('cover_image') as $file) {
                $newPaths[] = $file->store('galleries', 'public');
            }
        }
        $validated['cover_image'] = array_merge($kept, $newPaths) ?: null;

        // Normalize checkbox/toggle
        $validated['is_active'] = $request->boolean('is_active');
        if ($isAdmin) {
            $validated['badge']     = $request->input('badge', []);
            $validated['promo']     = $request->input('promo', []);
        } else {
            $validated['promo']     = $request->input('promo', []);
            unset($validated['badge']);
        }

        $vendor->update($validated);
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

        return redirect()
            ->route('vendor.edit', $vendor->fresh()->slug)
            ->with('success', 'Data vendor berhasil diperbarui.');
    }
}
