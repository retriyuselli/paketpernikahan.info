<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorEditController extends Controller
{
    private function authorizeRole(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->hasRole(['super_admin', 'admin']),
            403,
            'Anda tidak memiliki akses untuk mengedit vendor.'
        );
    }

    public function edit(Vendor $vendor)
    {
        $this->authorizeRole();

        $vendor->load(['packages', 'galleries']);

        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->authorizeRole();

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
            'price_start'  => 'nullable|integer|min:0',
            'events_done'  => 'nullable|integer|min:0',
            // rating, likes, comments_count dikelola otomatis dari frontend — tidak diubah di sini
            'badge'           => 'nullable|array',
            'badge.*'         => 'string',
            'promo'           => 'nullable|array',
            'promo.*'         => 'string',
            'is_active'       => 'boolean',
            'cover_image'     => 'nullable|array',
            'cover_image.*'   => 'image|max:1024',
            'cover_video'     => 'nullable|url|max:255',
        ]);

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
        $validated['badge']     = $request->input('badge', []);
        $validated['promo']     = $request->input('promo', []);

        $vendor->update($validated);

        return redirect()
            ->route('vendor.edit', $vendor->fresh()->slug)
            ->with('success', 'Data vendor berhasil diperbarui.');
    }
}
