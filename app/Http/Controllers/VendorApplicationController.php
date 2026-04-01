<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CategoryVendor;
use App\Models\VendorApplication;
use App\Enums\ProvinsiEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorApplicationController extends Controller
{
    public function create()
    {
        $user = User::findOrFail(Auth::id());
        $application = VendorApplication::where('user_id', $user->id)->latest()->first();
        $categories = CategoryVendor::orderBy('sort_order')->get(['slug', 'name']);
        $categoryMap = $categories->pluck('name', 'slug');
        $provinces = ProvinsiEnum::toArray();

        return view('front.join-vendor', compact('user', 'application', 'categories', 'categoryMap', 'provinces'));
    }

    public function cities(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasVerifiedEmail(), 403);

        $province = (string) $request->query('province', '');
        if ($province === '' || !array_key_exists($province, ProvinsiEnum::toArray())) {
            return response()->json(['cities' => []]);
        }

        return response()->json(['cities' => ProvinsiEnum::getKotaKabupaten($province)]);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $latest = VendorApplication::where('user_id', $user->id)->latest()->first();

        if ($latest && in_array($latest->status, ['pending', 'approved'], true)) {
            return back()->with('join_vendor_error', 'Pengajuan vendor Anda masih diproses atau sudah disetujui.');
        }

        $citiesForProvince = $request->filled('province')
            ? ProvinsiEnum::getKotaKabupaten($request->input('province'))
            : [];

        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'categories'    => ['required', 'array', 'min:1'],
            'categories.*'  => ['required', 'string', 'distinct', Rule::exists('category_vendors', 'slug')],
            'province'      => ['required', 'string', Rule::in(array_keys(ProvinsiEnum::toArray()))],
            'city'          => ['required', 'string', Rule::in($citiesForProvince)],
            'location'      => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'string', 'max:120'],
            'instagram'     => ['nullable', 'string', 'max:120'],
            'note'          => ['nullable', 'string', 'max:2000'],
            'logo_vendor'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_vendor')) {
            $logoPath = $request->file('logo_vendor')->store('vendor_logos', 'public');
        }

        $categories = collect($data['categories'] ?? [])
            ->filter()
            ->values()
            ->all();
        $primaryCategory = $categories[0] ?? null;

        VendorApplication::create([
            'user_id'        => $user->id,
            'business_name'  => $data['business_name'],
            'category'       => $primaryCategory,
            'categories'     => $categories,
            'city'           => $data['city'] ?? null,
            'province'       => $data['province'] ?? null,
            'location'       => $data['location'],
            'phone'          => $data['phone'] ?? null,
            'email'          => $data['email'] ?? null,
            'instagram'      => $data['instagram'] ?? null,
            'logo_vendor'    => $logoPath,
            'note'           => $data['note'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('join.vendor')
            ->with('join_vendor_success', 'Pengajuan berhasil dikirim. Menunggu verifikasi admin.');
    }
}
