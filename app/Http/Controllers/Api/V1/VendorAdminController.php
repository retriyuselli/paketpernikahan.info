<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\Vendor;
use App\Models\VendorApplication;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use Illuminate\Http\Request;

class VendorAdminController extends Controller
{
    // ── VENDOR ───────────────────────────────────────────────────────────────

    /** Paket milik vendor yang sedang login. */
    public function vendorPackages(Request $request)
    {
        $vendor = Vendor::where('owner_user_id', $request->user()->id)->first();
        if (!$vendor) return response()->json(['data' => []]);

        $packages = $vendor->packages()->orderBy('sort_order')->orderBy('price')->get();

        return response()->json([
            'data' => $packages->map(fn ($p) => [
                'id'         => $p->id,
                'slug'       => $p->slug,
                'name'       => $p->name,
                'price'      => (int) $p->price,
                'discount'   => (int) ($p->discount ?? 0),
                'image_url'  => $p->image_url,
                'is_active'  => (bool) $p->is_active,
                'created_at' => $p->created_at?->toIso8601String(),
            ]),
        ]);
    }

    /** Booking masuk untuk vendor yang login. */
    public function vendorBookings(Request $request)
    {
        $vendor = Vendor::where('owner_user_id', $request->user()->id)->first();
        if (!$vendor) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $bookings = VendorBooking::where('vendor_id', $vendor->id)
            ->with(['user:id,name,email', 'vendorPackage:id,name'])
            ->latest()
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $bookings->map(fn ($b) => [
                'id'             => $b->id,
                'status'         => $b->status,
                'payment_status' => $b->payment_status,
                'event_date'     => $b->event_date?->toDateString(),
                'agreed_total'   => (int) ($b->agreed_total ?? 0),
                'phone'          => $b->phone,
                'notes'          => $b->notes,
                'created_at'     => $b->created_at?->toIso8601String(),
                'user'           => $b->user ? ['id' => $b->user->id, 'name' => $b->user->name, 'email' => $b->user->email] : null,
                'package'        => $b->vendorPackage ? ['id' => $b->vendorPackage->id, 'name' => $b->vendorPackage->name] : null,
            ]),
            'meta' => ['total' => $bookings->total(), 'last_page' => $bookings->lastPage()],
        ]);
    }

    /** Pembayaran masuk untuk vendor yang login. */
    public function vendorPayments(Request $request)
    {
        $vendor = Vendor::where('owner_user_id', $request->user()->id)->first();
        if (!$vendor) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $payments = VendorBookingPayment::whereHas('booking', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->with(['booking.user:id,name'])
            ->latest()
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $payments->map(fn ($p) => [
                'id'         => $p->id,
                'amount'     => (int) $p->amount,
                'status'     => $p->status,
                'created_at' => $p->created_at?->toIso8601String(),
                'booking'    => $p->booking ? [
                    'id'   => $p->booking->id,
                    'user' => $p->booking->user ? ['id' => $p->booking->user->id, 'name' => $p->booking->user->name, 'email' => null] : null,
                ] : null,
            ]),
            'meta' => ['total' => $payments->total(), 'last_page' => $payments->lastPage()],
        ]);
    }

    /** Sesi chat yang melibatkan vendor yang login. */
    public function vendorChats(Request $request)
    {
        $vendor = Vendor::where('owner_user_id', $request->user()->id)->first();
        if (!$vendor) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $sessions = ChatSession::where('vendor_id', $vendor->id)
            ->with(['latestMessage'])
            ->orderByDesc('updated_at')
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $sessions->map(fn ($s) => [
                'id'             => $s->id,
                'token'          => $s->session_token,
                'status'         => $s->status,
                'guest_name'     => $s->guest_name,
                'vendor'         => null,
                'package'        => null,
                'latest_message' => $s->latestMessage ? ['id' => $s->latestMessage->id, 'sender' => $s->latestMessage->sender, 'message' => $s->latestMessage->message, 'created_at' => null, 'is_mine' => false] : null,
                'updated_at'     => $s->updated_at?->toIso8601String(),
                'created_at'     => $s->created_at?->toIso8601String(),
            ]),
        ]);
    }

    // ── ADMIN ────────────────────────────────────────────────────────────────

    /** Semua booking di sistem (admin). */
    public function adminBookings(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $bookings = VendorBooking::with(['user:id,name,email', 'vendor:id,name', 'vendorPackage:id,name'])
            ->latest()
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $bookings->map(fn ($b) => [
                'id'             => $b->id,
                'status'         => $b->status,
                'payment_status' => $b->payment_status,
                'event_date'     => $b->event_date?->toDateString(),
                'agreed_total'   => (int) ($b->agreed_total ?? 0),
                'phone'          => $b->phone,
                'created_at'     => $b->created_at?->toIso8601String(),
                'user'           => $b->user ? ['id' => $b->user->id, 'name' => $b->user->name, 'email' => $b->user->email] : null,
                'vendor'         => $b->vendor ? ['id' => $b->vendor->id, 'name' => $b->vendor->name] : null,
                'package'        => $b->vendorPackage ? ['id' => $b->vendorPackage->id, 'name' => $b->vendorPackage->name] : null,
            ]),
            'meta' => ['total' => $bookings->total(), 'last_page' => $bookings->lastPage()],
        ]);
    }

    /** Semua vendor (admin). */
    public function adminVendors(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $vendors = Vendor::withCount(['packages', 'bookings'])
            ->orderBy('name')
            ->paginate($request->integer('per_page', 50));

        return response()->json([
            'data' => $vendors->map(fn ($v) => [
                'id'                  => $v->id,
                'name'                => $v->name,
                'slug'                => $v->slug,
                'category'            => $v->category,
                'city'                => $v->city,
                'is_active'           => (bool) $v->is_active,
                'is_profile_complete' => (bool) $v->is_profile_complete,
                'packages_count'      => (int) $v->packages_count,
                'bookings_count'      => (int) $v->bookings_count,
                'cover_image'         => $v->cover_image_url,
            ]),
            'meta' => ['total' => $vendors->total(), 'last_page' => $vendors->lastPage()],
        ]);
    }

    /** Semua pengajuan vendor (admin). */
    public function adminApplications(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $apps = VendorApplication::with('user:id,name,email')
            ->latest()
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $apps->map(fn ($a) => [
                'id'            => $a->id,
                'business_name' => $a->business_name,
                'category'      => $a->category,
                'city'          => $a->city,
                'status'        => $a->status,
                'admin_note'    => $a->admin_note,
                'created_at'    => $a->created_at?->toIso8601String(),
                'user'          => $a->user ? ['id' => $a->user->id, 'name' => $a->user->name, 'email' => $a->user->email] : null,
            ]),
            'meta' => ['total' => $apps->total(), 'last_page' => $apps->lastPage()],
        ]);
    }

    /** Semua pembayaran user (admin). */
    public function adminPayments(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $payments = VendorBookingPayment::with(['booking.user:id,name', 'booking.vendor:id,name'])
            ->latest()
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $payments->map(fn ($p) => [
                'id'         => $p->id,
                'amount'     => (int) $p->amount,
                'status'     => $p->status,
                'created_at' => $p->created_at?->toIso8601String(),
                'booking'    => $p->booking ? [
                    'id'     => $p->booking->id,
                    'status' => $p->booking->status,
                    'user'   => $p->booking->user ? ['id' => $p->booking->user->id, 'name' => $p->booking->user->name, 'email' => null] : null,
                    'vendor' => $p->booking->vendor ? ['id' => $p->booking->vendor->id, 'name' => $p->booking->vendor->name] : null,
                ] : null,
            ]),
            'meta' => ['total' => $payments->total(), 'last_page' => $payments->lastPage()],
        ]);
    }

    /** Statistik sistem untuk Panel Admin. */
    public function adminStats(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => []]);

        return response()->json([
            'data' => [
                'total_vendors'       => \App\Models\Vendor::count(),
                'active_vendors'      => \App\Models\Vendor::where('is_active', true)->count(),
                'total_bookings'      => VendorBooking::count(),
                'pending_bookings'    => VendorBooking::where('status', 'pending')->count(),
                'total_users'         => \App\Models\User::count(),
                'pending_applications' => VendorApplication::where('status', 'pending')->count(),
                'pending_payments'    => VendorBookingPayment::where('status', 'pending_verification')->count(),
                'total_revenue'       => (int) VendorBookingPayment::where('status', 'verified')->sum('amount'),
            ],
        ]);
    }

    /** Semua chat yang melibatkan vendor (admin). */
    public function adminVendorChats(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $sessions = ChatSession::whereNotNull('vendor_id')
            ->with(['vendor:id,name,slug,cover_image', 'latestMessage'])
            ->orderByDesc('updated_at')
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $sessions->map(fn ($s) => [
                'id'         => $s->id,
                'token'      => $s->session_token,
                'status'     => $s->status,
                'guest_name' => $s->guest_name,
                'vendor'     => $s->vendor ? [
                    'id'              => $s->vendor->id,
                    'name'            => $s->vendor->name,
                    'slug'            => $s->vendor->slug,
                    'cover_image_url' => $s->vendor->cover_image_url,
                    'city'            => null,
                ] : null,
                'package'        => null,
                'latest_message' => $s->latestMessage ? ['id' => $s->latestMessage->id, 'sender' => $s->latestMessage->sender, 'message' => $s->latestMessage->message, 'created_at' => null, 'is_mine' => false] : null,
                'updated_at'     => $s->updated_at?->toIso8601String(),
                'created_at'     => $s->created_at?->toIso8601String(),
            ]),
            'meta' => ['total' => $sessions->total(), 'last_page' => $sessions->lastPage()],
        ]);
    }

    /** Semua sesi chat publik (admin). */
    public function adminChats(Request $request)
    {
        if (!$request->user()->isAdmin()) return response()->json(['data' => [], 'meta' => ['total' => 0, 'last_page' => 1]]);

        $sessions = ChatSession::where('type', 'public')
            ->with(['vendor:id,name,slug,cover_image', 'vendorPackage:id,name', 'latestMessage'])
            ->orderByDesc('updated_at')
            ->paginate($request->integer('per_page', 30));

        return response()->json([
            'data' => $sessions->map(fn ($s) => [
                'id'         => $s->id,
                'token'      => $s->session_token,
                'status'     => $s->status,
                'guest_name' => $s->guest_name,
                'vendor'     => $s->vendor ? [
                    'id'              => $s->vendor->id,
                    'name'            => $s->vendor->name,
                    'slug'            => $s->vendor->slug,
                    'cover_image_url' => $s->vendor->cover_image_url,
                    'city'            => null,
                ] : null,
                'package'        => $s->vendorPackage ? ['id' => $s->vendorPackage->id, 'name' => $s->vendorPackage->name, 'price' => 0, 'image_url' => null] : null,
                'latest_message' => $s->latestMessage ? ['id' => $s->latestMessage->id, 'sender' => $s->latestMessage->sender, 'message' => $s->latestMessage->message, 'created_at' => null, 'is_mine' => false] : null,
                'updated_at'     => $s->updated_at?->toIso8601String(),
                'created_at'     => $s->created_at?->toIso8601String(),
            ]),
            'meta' => ['total' => $sessions->total(), 'last_page' => $sessions->lastPage()],
        ]);
    }
}
