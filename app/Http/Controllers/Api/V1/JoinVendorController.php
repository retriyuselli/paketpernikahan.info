<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ProvinsiEnum;
use App\Http\Controllers\Controller;
use App\Models\CategoryVendor;
use App\Models\VendorApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JoinVendorController extends Controller
{
    /** Daftar provinsi. */
    public function provinces(): JsonResponse
    {
        return response()->json([
            'data' => array_values(array_keys(ProvinsiEnum::toArray())),
        ]);
    }

    /** Daftar kota/kabupaten untuk provinsi tertentu. */
    public function cities(Request $request): JsonResponse
    {
        $province = $request->query('province', '');
        $cities = ProvinsiEnum::getKotaKabupaten($province);
        return response()->json(['data' => $cities]);
    }

    /** Daftar kategori vendor. */
    public function categories(): JsonResponse
    {
        $cats = CategoryVendor::orderBy('sort_order')->get(['slug', 'name']);
        return response()->json([
            'data' => $cats->map(fn($c) => ['slug' => $c->slug, 'name' => $c->name]),
        ]);
    }

    /** Status pengajuan vendor milik user yang login. */
    public function status(Request $request): JsonResponse
    {
        $application = VendorApplication::where('user_id', $request->user()->id)
            ->latest()
            ->first();

        if (!$application) {
            return response()->json(['data' => null]);
        }

        $cats = is_array($application->categories) && count($application->categories)
            ? $application->categories
            : ($application->category ? [$application->category] : []);

        $categoryMap = CategoryVendor::whereIn('slug', $cats)->pluck('name', 'slug');
        $categoryNames = collect($cats)->map(fn($c) => $categoryMap[$c] ?? $c)->values()->all();

        return response()->json([
            'data' => [
                'id'             => $application->id,
                'business_name'  => $application->business_name,
                'status'         => $application->status,
                'admin_note'     => $application->admin_note,
                'categories'     => $categoryNames,
                'province'       => $application->province,
                'city'           => $application->city,
                'created_at'     => $application->created_at?->toIso8601String(),
            ],
        ]);
    }

    /** Kirim pengajuan menjadi vendor. */
    public function store(Request $request): JsonResponse
    {
        $citiesForProvince = $request->filled('province')
            ? ProvinsiEnum::getKotaKabupaten($request->input('province'))
            : [];

        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'categories'    => ['required', 'array', 'min:1'],
            'categories.*'  => ['required', 'string', Rule::exists('category_vendors', 'slug')],
            'province'      => ['required', 'string', Rule::in(array_keys(ProvinsiEnum::toArray()))],
            'city'          => ['required', 'string', Rule::in($citiesForProvince)],
            'location'      => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'email', 'max:120'],
            'instagram'     => ['nullable', 'string', 'max:120'],
            'note'          => ['nullable', 'string', 'max:2000'],
        ]);

        // Satu user hanya boleh punya satu pengajuan pending/approved
        $existing = VendorApplication::where('user_id', $request->user()->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Anda sudah memiliki pengajuan yang sedang diproses.',
                'data'    => ['status' => $existing->status],
            ], 422);
        }

        $categories      = collect($data['categories'])->filter()->values()->all();
        $primaryCategory = $categories[0] ?? null;

        VendorApplication::create([
            'user_id'       => $request->user()->id,
            'business_name' => $data['business_name'],
            'category'      => $primaryCategory,
            'categories'    => $categories,
            'city'          => $data['city'],
            'province'      => $data['province'],
            'location'      => $data['location'],
            'phone'         => $data['phone'] ?? null,
            'email'         => $data['email'] ?? null,
            'instagram'     => $data['instagram'] ?? null,
            'note'          => $data['note'] ?? null,
            'status'        => 'pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim. Menunggu verifikasi admin.',
        ], 201);
    }
}
