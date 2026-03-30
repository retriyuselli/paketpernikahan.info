<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorGalleryController extends Controller
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
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'caption'    => 'nullable|string|max:255',
            'video_url'  => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $path = $request->file('image')->store('galleries', 'public');

        $gallery = $vendor->galleries()->create([
            'image_path' => [$path],
            'caption'    => $validated['caption'] ?? null,
            'video_url'  => $validated['video_url'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_cover'   => false,
        ]);

        $vendor->refresh();
        $isComplete = $vendor->computeProfileComplete();
        if (!$isAdmin && !$isComplete) {
            $vendor->update([
                'is_profile_complete' => false,
                'is_active' => false,
            ]);
        } else {
            $vendor->update([
                'is_profile_complete' => $isComplete,
                'is_active' => $isComplete ? true : $vendor->is_active,
            ]);
        }

        return response()->json([
            'success' => true,
            'gallery' => [
                'id'        => $gallery->id,
                'image_url' => $gallery->image_url,
                'caption'   => $gallery->caption,
                'video_url' => $gallery->video_url,
            ],
        ]);
    }

    public function update(Request $request, Vendor $vendor, VendorGallery $gallery)
    {
        $this->authorizeRole($vendor);
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        $isAdmin = $user?->hasRole(['super_admin', 'admin']) ?? false;

        abort_if($gallery->vendor_id !== $vendor->id, 404);

        $validated = $request->validate([
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'caption'   => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:255',
        ]);

        $data = [
            'caption'   => $validated['caption'] ?: null,
            'video_url' => $validated['video_url'] ?: null,
        ];

        if ($request->hasFile('image')) {
            if (is_array($gallery->image_path)) {
                foreach ($gallery->image_path as $path) {
                    if (!str_starts_with((string) $path, 'http')) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            $data['image_path'] = [$request->file('image')->store('galleries', 'public')];
        }

        $gallery->update($data);
        $gallery->refresh();

        $vendor->refresh();
        $isComplete = $vendor->computeProfileComplete();
        if (!$isAdmin && !$isComplete) {
            $vendor->update([
                'is_profile_complete' => false,
                'is_active' => false,
            ]);
        } else {
            $vendor->update([
                'is_profile_complete' => $isComplete,
                'is_active' => $isComplete ? true : $vendor->is_active,
            ]);
        }

        return response()->json([
            'success' => true,
            'gallery' => [
                'id'        => $gallery->id,
                'image_url' => $gallery->image_url,
                'caption'   => $gallery->caption,
                'video_url' => $gallery->video_url,
            ],
        ]);
    }

    public function destroy(Vendor $vendor, VendorGallery $gallery)
    {
        $this->authorizeRole($vendor);
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        $isAdmin = $user?->hasRole(['super_admin', 'admin']) ?? false;

        abort_if($gallery->vendor_id !== $vendor->id, 404);

        if (is_array($gallery->image_path)) {
            foreach ($gallery->image_path as $path) {
                if (!str_starts_with((string) $path, 'http')) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $gallery->delete();

        $vendor->refresh();
        $isComplete = $vendor->computeProfileComplete();
        if (!$isAdmin && !$isComplete) {
            $vendor->update([
                'is_profile_complete' => false,
                'is_active' => false,
            ]);
        } else {
            $vendor->update([
                'is_profile_complete' => $isComplete,
                'is_active' => $isComplete ? true : $vendor->is_active,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
