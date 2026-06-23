<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Imports\VipGuestImport;
use App\Models\CustomerNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CustomerPaymentMethod;
use App\Models\CustomerPreparationSection;
use App\Models\CustomerPreparationTask;
use App\Models\FamilyMember;
use App\Models\VipGuest;
use App\Models\VipGuestDelegate;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use App\Models\WeddingEvent;
use App\Models\WeddingInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    // ─────────────────────────────────────────
    // Dashboard
    // ─────────────────────────────────────────

    public function dashboard(Request $request): JsonResponse
    {
        $user     = $request->user();
        $bookings = VendorBooking::where('user_id', $user->id);

        $bookingCounts = [
            'total'     => (clone $bookings)->count(),
            'pending'   => (clone $bookings)->where('status', 'pending')->count(),
            'contacted' => (clone $bookings)->where('status', 'contacted')->count(),
            'confirmed' => (clone $bookings)->where('status', 'confirmed')->count(),
            'completed' => (clone $bookings)->where('status', 'completed')->count(),
            'cancelled' => (clone $bookings)->whereIn('status', ['cancelled', 'rejected'])->count(),
        ];

        // Total tagihan dari semua booking
        $totalBudget = (clone $bookings)->sum('agreed_total');

        // Total yang sudah terbayar
        $usedBudget = VendorBookingPayment::whereIn(
            'vendor_booking_id',
            VendorBooking::where('user_id', $user->id)->pluck('id')
        )
            ->where('status', 'verified')
            ->sum('amount');

        // Preparation summary
        $sections    = CustomerPreparationSection::where('user_id', $user->id)->with('tasks')->get();
        $allTasks    = $sections->flatMap(fn ($s) => $s->tasks);
        $totalTasks  = $allTasks->count();
        $doneTasks   = $allTasks->where('status', 'done')->count();
        $pendingTasks = $allTasks->where('status', 'pending')->count();
        $todoTasks   = $allTasks->where('status', 'todo')->count();

        // Wedding day countdown
        $akad        = WeddingEvent::where('user_id', $user->id)
            ->whereIn('jenis_acara', ['akad', 'resepsi'])
            ->orderBy('tgl_acara')
            ->first();
        $daysLeft    = $akad?->tgl_acara
            ? (int) now()->startOfDay()->diffInDays($akad->tgl_acara->startOfDay(), false)
            : null;

        return response()->json([
            'data' => [
                'booking_counts'  => $bookingCounts,
                'total_budget'    => $totalBudget,
                'used_budget'     => $usedBudget,
                'preparation'     => [
                    'total'   => $totalTasks,
                    'done'    => $doneTasks,
                    'pending' => $pendingTasks,
                    'todo'    => $todoTasks,
                ],
                'days_to_wedding' => $daysLeft,
            ],
        ]);
    }

    // ─────────────────────────────────────────
    // Wedding Info
    // ─────────────────────────────────────────

    public function weddingInfo(Request $request): JsonResponse
    {
        $user = $request->user();
        $info = WeddingInfo::where('user_id', $user->id)->first();

        // Ambil tanggal & venue dari event akad (atau resepsi jika belum ada akad)
        $akad = WeddingEvent::where('user_id', $user->id)
            ->where('jenis_acara', 'akad')
            ->first();

        $resepsi = WeddingEvent::where('user_id', $user->id)
            ->where('jenis_acara', 'resepsi')
            ->first();

        $weddingDateEvent = $akad ?? $resepsi;
        $venueEvent       = $resepsi ?? $akad;

        // Package name dari booking yang terhubung ke event
        $packageName = null;
        if ($resepsi?->vendor_booking_id) {
            $booking     = VendorBooking::with('vendorPackage')
                ->find($resepsi->vendor_booking_id);
            $packageName = $booking?->vendorPackage?->name;
        }

        // Semua event untuk dikirim ke iOS
        $events = WeddingEvent::where('user_id', $user->id)
            ->orderBy('tgl_acara')
            ->get()
            ->map(fn ($e) => [
                'id'          => $e->id,
                'jenis_acara' => $e->jenis_acara,
                'label'       => WeddingEvent::$jenisOptions[$e->jenis_acara] ?? $e->jenis_acara,
                'tgl_acara'   => $e->tgl_acara?->toDateString(),
                'lokasi_acara' => $e->lokasi_acara,
                'catatan'     => $e->catatan,
            ]);

        return response()->json([
            'data' => [
                'groom_name'   => $info?->groom_name,
                'bride_name'   => $info?->bride_name,
                'wedding_date' => $weddingDateEvent?->tgl_acara?->toDateString(),
                'venue'        => $venueEvent?->lokasi_acara,
                'package_name' => $packageName,
                'events'       => $events,
            ],
        ]);
    }

    public function updateWeddingInfo(Request $request): JsonResponse
    {
        $request->validate([
            'groom_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'bride_name' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $user = $request->user();
        $info = WeddingInfo::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['groom_name', 'bride_name']),
        );

        return response()->json(['data' => $info->only(['groom_name', 'bride_name'])]);
    }

    // ─────────────────────────────────────────
    // Family Members
    // ─────────────────────────────────────────

    public function familyMembers(Request $request): JsonResponse
    {
        $members = $request->user()
            ->familyMembers()
            ->get(['id', 'name', 'role', 'phone']);

        return response()->json(['data' => $members]);
    }

    // ─────────────────────────────────────────
    // Notifications
    // ─────────────────────────────────────────

    public function notifications(Request $request): JsonResponse
    {
        $notifications = CustomerNotification::where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn ($n) => [
                'id'          => $n->id,
                'group'       => $n->group,
                'title'       => $n->title,
                'message'     => $n->message,
                'time'        => $n->created_at->diffForHumans(),
                'icon'        => $n->icon,
                'destination' => $n->destination,
                'tint'        => $n->tint,
                'is_unread'   => $n->is_unread,
            ]);

        return response()->json(['data' => $notifications]);
    }

    public function markNotificationRead(Request $request, int $id): JsonResponse
    {
        CustomerNotification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->update(['is_unread' => false]);

        return response()->json(['message' => 'ok']);
    }

    // ─────────────────────────────────────────
    // Preparation
    // ─────────────────────────────────────────

    public function preparationSections(Request $request): JsonResponse
    {
        $sections = CustomerPreparationSection::where('user_id', $request->user()->id)
            ->orderBy('sort_order')
            ->with(['tasks' => fn ($q) => $q->orderBy('sort_order')])
            ->get()
            ->map(fn ($s) => [
                'id'    => $s->id,
                'title' => $s->title,
                'icon'  => $s->icon,
                'done'  => $s->tasks->where('status', 'done')->count(),
                'total' => $s->tasks->count(),
                'tasks' => $s->tasks->map(fn ($t) => [
                    'id'       => $t->id,
                    'title'    => $t->title,
                    'status'   => $t->status,
                    'due_date' => $t->due_date?->toDateString(),
                ]),
            ]);

        return response()->json(['data' => $sections]);
    }

    public function preparationVendors(Request $request): JsonResponse
    {
        $bookings = VendorBooking::where('user_id', $request->user()->id)
            ->with(['vendor:id,name,category_vendor_id', 'vendor.categories:id,name,icon'])
            ->get()
            ->map(fn ($b) => [
                'id'       => $b->id,
                'category' => $b->vendor?->categories?->first()?->name ?? 'Vendor',
                'name'     => $b->vendor?->name ?? '—',
                'icon'     => $b->vendor?->categories?->first()?->icon ?? 'bag.fill',
                'status'   => $b->status,
            ]);

        return response()->json(['data' => $bookings]);
    }

    // ─────────────────────────────────────────
    // Payments
    // ─────────────────────────────────────────

    public function paymentsUpcoming(Request $request): JsonResponse
    {
        $bookingIds = VendorBooking::where('user_id', $request->user()->id)->pluck('id');

        $upcoming = VendorBookingPayment::whereIn('vendor_booking_id', $bookingIds)
            ->whereNull('paid_at')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->toDateString())
            ->with(['vendorBooking.vendor:id,name'])
            ->orderBy('due_date')
            ->get()
            ->map(fn ($p) => [
                'id'       => $p->id,
                'title'    => $p->type === 'dp' ? 'DP / Uang Muka' : 'Pelunasan',
                'vendor'   => $p->vendorBooking?->vendor?->name ?? '—',
                'due_date' => $p->due_date?->toDateString(),
                'amount'   => 'Rp ' . number_format($p->amount, 0, ',', '.'),
                'icon'     => 'creditcard.fill',
            ]);

        return response()->json(['data' => $upcoming]);
    }

    public function paymentsSchedule(Request $request): JsonResponse
    {
        $bookingIds = VendorBooking::where('user_id', $request->user()->id)->pluck('id');

        $payments = VendorBookingPayment::whereIn('vendor_booking_id', $bookingIds)
            ->with(['vendorBooking.vendor:id,name'])
            ->orderBy('due_date')
            ->get()
            ->map(fn ($p) => [
                'id'      => $p->id,
                'title'   => $p->type === 'dp' ? 'DP / Uang Muka' : 'Pelunasan',
                'vendor'  => $p->vendorBooking?->vendor?->name ?? '—',
                'due_date' => $p->due_date?->toDateString(),
                'month'   => $p->due_date?->translatedFormat('F Y'),
                'amount'  => 'Rp ' . number_format($p->amount, 0, ',', '.'),
                'icon'    => 'creditcard.fill',
                'status'  => $p->paid_at ? 'completed' : 'pending',
            ]);

        return response()->json(['data' => $payments]);
    }

    public function paymentsAll(Request $request): JsonResponse
    {
        $bookingIds = VendorBooking::where('user_id', $request->user()->id)->pluck('id');

        $payments = VendorBookingPayment::whereIn('vendor_booking_id', $bookingIds)
            ->with(['vendorBooking.vendor:id,name'])
            ->latest('paid_at')
            ->get()
            ->map(fn ($p) => [
                'id'     => $p->id,
                'title'  => $p->type === 'dp' ? 'DP / Uang Muka' : 'Pelunasan',
                'vendor' => $p->vendorBooking?->vendor?->name ?? '—',
                'date'   => $p->paid_at?->toDateString() ?? $p->due_date?->toDateString(),
                'amount' => 'Rp ' . number_format($p->amount, 0, ',', '.'),
                'icon'   => 'creditcard.fill',
                'type'   => 'debit',
                'status' => $p->paid_at ? 'completed' : 'pending',
            ]);

        return response()->json(['data' => $payments]);
    }

    public function budget(Request $request): JsonResponse
    {
        $bookings = VendorBooking::where('user_id', $request->user()->id)
            ->with(['vendor:id,name', 'vendorPackage:id,name', 'payments'])
            ->get();

        $categories = $bookings->map(function ($b) {
            $totalAmount  = $b->agreed_total ?? 0;
            $paidAmount   = $b->payments->where('status', 'verified')->sum('amount');
            $paidPercent  = $totalAmount > 0
                ? (int) round($paidAmount / $totalAmount * 100)
                : 0;

            return [
                'id'           => $b->id,
                'name'         => $b->vendor?->name ?? '—',
                'icon'         => 'bag.fill',
                'amount'       => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'paid_percent' => $paidPercent,
                'vendors'      => [[
                    'id'           => $b->id,
                    'name'         => $b->vendorPackage?->name ?? $b->vendor?->name ?? '—',
                    'icon'         => 'bag.fill',
                    'amount'       => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                    'paid_percent' => $paidPercent,
                ]],
            ];
        });

        return response()->json(['data' => $categories]);
    }

    // ─────────────────────────────────────────
    // Payment Methods
    // ─────────────────────────────────────────

    public function paymentMethods(Request $request): JsonResponse
    {
        $methods = CustomerPaymentMethod::where('user_id', $request->user()->id)
            ->orderByDesc('is_primary')
            ->get(['id', 'name', 'logo_icon', 'account_number', 'account_name', 'is_primary', 'type']);

        return response()->json(['data' => $methods]);
    }

    public function storePaymentMethod(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'logo_icon'      => ['nullable', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name'   => ['required', 'string', 'max:100'],
            'is_primary'     => ['boolean'],
            'type'           => ['required', 'in:bank,ewallet'],
        ]);

        $user = $request->user();

        // Jika set primary, reset yang lain
        if (!empty($data['is_primary'])) {
            CustomerPaymentMethod::where('user_id', $user->id)
                ->update(['is_primary' => false]);
        }

        $method = CustomerPaymentMethod::create(
            array_merge($data, ['user_id' => $user->id])
        );

        return response()->json(['data' => $method], 201);
    }

    public function destroyPaymentMethod(Request $request, int $id): JsonResponse
    {
        CustomerPaymentMethod::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'ok']);
    }

    // ─────────────────────────────────────────
    // Profile update & password
    // ─────────────────────────────────────────

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'name'      => ['sometimes', 'string', 'max:255'],
            'whatsapp'  => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        $user = $request->user();
        $user->update($request->only(['name', 'whatsapp']));

        return response()->json([
            'data' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'whatsapp' => $user->whatsapp,
            ],
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata sandi lama tidak sesuai.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Kata sandi berhasil diperbarui.']);
    }

    // ─────────────────────────────────────────
    // Wedding Events CRUD
    // ─────────────────────────────────────────

    public function storeWeddingEvent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'jenis_acara'       => ['required', 'in:lamaran,pengajian,akad,resepsi'],
            'tgl_acara'         => ['nullable', 'date'],
            'lokasi_acara'      => ['nullable', 'string', 'max:200'],
            'catatan'           => ['nullable', 'string', 'max:500'],
            'vendor_booking_id' => ['nullable', 'integer', 'exists:vendor_bookings,id'],
        ]);

        $user = $request->user();

        // Pastikan vendor_booking_id milik user ini
        if (!empty($data['vendor_booking_id'])) {
            $owns = VendorBooking::where('id', $data['vendor_booking_id'])
                ->where('user_id', $user->id)
                ->exists();
            if (!$owns) {
                return response()->json(['message' => 'Booking tidak ditemukan.'], 422);
            }
        }

        $event = WeddingEvent::create(array_merge($data, ['user_id' => $user->id]));

        return response()->json([
            'data' => [
                'id'           => $event->id,
                'jenis_acara'  => $event->jenis_acara,
                'label'        => WeddingEvent::$jenisOptions[$event->jenis_acara] ?? $event->jenis_acara,
                'tgl_acara'    => $event->tgl_acara?->toDateString(),
                'lokasi_acara' => $event->lokasi_acara,
                'catatan'      => $event->catatan,
            ],
        ], 201);
    }

    public function updateWeddingEvent(Request $request, int $id): JsonResponse
    {
        $event = WeddingEvent::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'jenis_acara'       => ['sometimes', 'in:lamaran,pengajian,akad,resepsi'],
            'tgl_acara'         => ['sometimes', 'nullable', 'date'],
            'lokasi_acara'      => ['sometimes', 'nullable', 'string', 'max:200'],
            'catatan'           => ['sometimes', 'nullable', 'string', 'max:500'],
            'vendor_booking_id' => ['sometimes', 'nullable', 'integer', 'exists:vendor_bookings,id'],
        ]);

        $event->update($data);

        return response()->json([
            'data' => [
                'id'           => $event->id,
                'jenis_acara'  => $event->jenis_acara,
                'label'        => WeddingEvent::$jenisOptions[$event->jenis_acara] ?? $event->jenis_acara,
                'tgl_acara'    => $event->tgl_acara?->toDateString(),
                'lokasi_acara' => $event->lokasi_acara,
                'catatan'      => $event->catatan,
            ],
        ]);
    }

    public function destroyWeddingEvent(Request $request, int $id): JsonResponse
    {
        WeddingEvent::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'ok']);
    }

    // ─────────────────────────────────────────
    // Family Members CRUD
    // ─────────────────────────────────────────

    public function storeFamilyMember(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'role'  => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $member = FamilyMember::create(
            array_merge($data, ['user_id' => $request->user()->id])
        );

        return response()->json(['data' => $member->only(['id', 'name', 'role', 'phone'])], 201);
    }

    public function updateFamilyMember(Request $request, int $id): JsonResponse
    {
        $member = FamilyMember::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'name'  => ['sometimes', 'string', 'max:100'],
            'role'  => ['sometimes', 'string', 'max:100'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        $member->update($data);

        return response()->json(['data' => $member->only(['id', 'name', 'role', 'phone'])]);
    }

    public function destroyFamilyMember(Request $request, int $id): JsonResponse
    {
        FamilyMember::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'ok']);
    }

    // ─────────────────────────────────────────
    // Preparation Tasks CRUD
    // ─────────────────────────────────────────

    public function storePreparationTask(Request $request): JsonResponse
    {
        $data = $request->validate([
            'section_id' => ['required', 'integer'],
            'title'      => ['required', 'string', 'max:200'],
            'status'     => ['sometimes', 'in:todo,done,pending'],
            'due_date'   => ['nullable', 'date'],
        ]);

        $user = $request->user();

        // Pastikan section milik user ini
        $section = CustomerPreparationSection::where('user_id', $user->id)
            ->where('id', $data['section_id'])
            ->firstOrFail();

        $maxSort = CustomerPreparationTask::where('section_id', $section->id)->max('sort_order') ?? 0;

        $task = CustomerPreparationTask::create(array_merge($data, [
            'user_id'    => $user->id,
            'status'     => $data['status'] ?? 'todo',
            'sort_order' => $maxSort + 1,
        ]));

        return response()->json([
            'data' => [
                'id'         => $task->id,
                'title'      => $task->title,
                'status'     => $task->status,
                'due_date'   => $task->due_date?->toDateString(),
                'section_id' => $task->section_id,
            ],
        ], 201);
    }

    public function updatePreparationTask(Request $request, int $id): JsonResponse
    {
        $task = CustomerPreparationTask::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'title'    => ['sometimes', 'string', 'max:200'],
            'status'   => ['sometimes', 'in:todo,done,pending'],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ]);

        $task->update($data);

        return response()->json([
            'data' => [
                'id'       => $task->id,
                'title'    => $task->title,
                'status'   => $task->status,
                'due_date' => $task->due_date?->toDateString(),
            ],
        ]);
    }

    public function destroyPreparationTask(Request $request, int $id): JsonResponse
    {
        CustomerPreparationTask::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'ok']);
    }

    // ─────────────────────────────────────────
    // Preparation Sections CRUD
    // ─────────────────────────────────────────

    public function storePreparationSection(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'      => ['required', 'string', 'max:100'],
            'icon'       => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $user    = $request->user();
        $maxSort = CustomerPreparationSection::where('user_id', $user->id)->max('sort_order') ?? 0;

        $section = CustomerPreparationSection::create(array_merge($data, [
            'user_id'    => $user->id,
            'sort_order' => $data['sort_order'] ?? $maxSort + 1,
        ]));

        return response()->json([
            'data' => [
                'id'         => $section->id,
                'title'      => $section->title,
                'icon'       => $section->icon,
                'done'       => 0,
                'total'      => 0,
                'tasks'      => [],
            ],
        ], 201);
    }

    public function destroyPreparationSection(Request $request, int $id): JsonResponse
    {
        $section = CustomerPreparationSection::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $section->tasks()->delete();
        $section->delete();

        return response()->json(['message' => 'ok']);
    }

    // ─────────────────────────────────────────
    // VIP Guests CRUD
    // ─────────────────────────────────────────

    public function vipGuests(Request $request): JsonResponse
    {
        $guests = VipGuest::where('user_id', $request->user()->id)
            ->orderBy('kategori')
            ->orderBy('name')
            ->get()
            ->map(fn ($g) => [
                'id'          => $g->id,
                'name'        => $g->name,
                'jabatan'     => $g->jabatan,
                'instansi'    => $g->instansi,
                'phone'       => $g->phone,
                'kategori'    => $g->kategori,
                'kategori_label' => VipGuest::$kategoriOptions[$g->kategori] ?? $g->kategori,
                'rsvp_status' => $g->rsvp_status,
                'rsvp_label'  => VipGuest::$rsvpOptions[$g->rsvp_status] ?? $g->rsvp_status,
                'catatan'     => $g->catatan,
            ]);

        $summary = [
            'total'        => $guests->count(),
            'hadir'        => $guests->where('rsvp_status', 'hadir')->count(),
            'tidak_hadir'  => $guests->where('rsvp_status', 'tidak_hadir')->count(),
            'menunggu'     => $guests->where('rsvp_status', 'menunggu')->count(),
        ];

        return response()->json([
            'data'    => $guests,
            'summary' => $summary,
        ]);
    }

    public function storeVipGuest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'jabatan'     => ['nullable', 'string', 'max:150'],
            'instansi'    => ['nullable', 'string', 'max:150'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'kategori'    => ['required', 'in:keluarga_besar,pejabat,tokoh_masyarakat,rekan_bisnis,teman'],
            'rsvp_status' => ['sometimes', 'in:menunggu,hadir,tidak_hadir'],
            'catatan'     => ['nullable', 'string', 'max:500'],
        ]);

        $guest = VipGuest::create(array_merge($data, [
            'user_id'     => $request->user()->id,
            'rsvp_status' => $data['rsvp_status'] ?? 'menunggu',
        ]));

        return response()->json([
            'data' => [
                'id'             => $guest->id,
                'name'           => $guest->name,
                'jabatan'        => $guest->jabatan,
                'instansi'       => $guest->instansi,
                'phone'          => $guest->phone,
                'kategori'       => $guest->kategori,
                'kategori_label' => VipGuest::$kategoriOptions[$guest->kategori] ?? $guest->kategori,
                'rsvp_status'    => $guest->rsvp_status,
                'rsvp_label'     => VipGuest::$rsvpOptions[$guest->rsvp_status] ?? $guest->rsvp_status,
                'catatan'        => $guest->catatan,
            ],
        ], 201);
    }

    public function updateVipGuest(Request $request, int $id): JsonResponse
    {
        $guest = VipGuest::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:150'],
            'jabatan'     => ['sometimes', 'nullable', 'string', 'max:150'],
            'instansi'    => ['sometimes', 'nullable', 'string', 'max:150'],
            'phone'       => ['sometimes', 'nullable', 'string', 'max:30'],
            'kategori'    => ['sometimes', 'in:keluarga_besar,pejabat,tokoh_masyarakat,rekan_bisnis,teman'],
            'rsvp_status' => ['sometimes', 'in:menunggu,hadir,tidak_hadir'],
            'catatan'     => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $guest->update($data);

        return response()->json([
            'data' => [
                'id'             => $guest->id,
                'name'           => $guest->name,
                'jabatan'        => $guest->jabatan,
                'instansi'       => $guest->instansi,
                'phone'          => $guest->phone,
                'kategori'       => $guest->kategori,
                'kategori_label' => VipGuest::$kategoriOptions[$guest->kategori] ?? $guest->kategori,
                'rsvp_status'    => $guest->rsvp_status,
                'rsvp_label'     => VipGuest::$rsvpOptions[$guest->rsvp_status] ?? $guest->rsvp_status,
                'catatan'        => $guest->catatan,
            ],
        ]);
    }

    public function importVipGuests(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $import = new VipGuestImport($request->user()->id);
        Excel::import($import, $request->file('file'));

        return response()->json([
            'data' => [
                'imported' => $import->getImported(),
                'skipped'  => $import->getSkipped(),
            ],
            'message' => "Berhasil mengimpor {$import->getImported()} tamu.",
        ]);
    }

    public function destroyVipGuest(Request $request, int $id): JsonResponse
    {
        VipGuest::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'ok']);
    }

    // ── VIP Guest Delegates ────────────────────────────────────────────────

    public function vipGuestDelegates(Request $request): JsonResponse
    {
        $delegates = VipGuestDelegate::where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn ($d) => [
                'id'               => $d->id,
                'name'             => $d->name,
                'token'            => $d->token,
                'expires_at'       => $d->expires_at?->toIso8601String(),
                'last_accessed_at' => $d->last_accessed_at?->toIso8601String(),
                'is_active'        => $d->isActive(),
                'created_at'       => $d->created_at->toIso8601String(),
            ]);

        return response()->json(['data' => $delegates]);
    }

    public function storeVipGuestDelegate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:now'],
        ]);

        $delegate = VipGuestDelegate::create([
            'user_id'    => $request->user()->id,
            'name'       => $data['name'],
            'token'      => VipGuestDelegate::generateToken(),
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return response()->json([
            'data' => [
                'id'         => $delegate->id,
                'name'       => $delegate->name,
                'token'      => $delegate->token,
                'expires_at' => $delegate->expires_at?->toIso8601String(),
                'is_active'  => true,
                'created_at' => $delegate->created_at->toIso8601String(),
            ],
            'message' => 'Akses delegasi berhasil dibuat.',
        ], 201);
    }

    public function destroyVipGuestDelegate(Request $request, int $id): JsonResponse
    {
        VipGuestDelegate::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'Akses delegasi berhasil dicabut.']);
    }

    // ── VIP Guest Shared Access (tanpa login, via token) ──────────────────

    public function sharedVipGuests(Request $request, string $token): JsonResponse
    {
        $delegate = VipGuestDelegate::where('token', $token)->firstOrFail();

        if ($delegate->isExpired()) {
            return response()->json(['message' => 'Token akses sudah kadaluarsa.'], 403);
        }

        $delegate->update(['last_accessed_at' => now()]);

        $guests = VipGuest::where('user_id', $delegate->user_id)
            ->orderBy('kategori')
            ->orderBy('name')
            ->get()
            ->map(fn ($g) => [
                'id'             => $g->id,
                'name'           => $g->name,
                'jabatan'        => $g->jabatan,
                'instansi'       => $g->instansi,
                'phone'          => $g->phone,
                'kategori'       => $g->kategori,
                'kategori_label' => VipGuest::$kategoriOptions[$g->kategori] ?? $g->kategori,
                'rsvp_status'    => $g->rsvp_status,
                'rsvp_label'     => VipGuest::$rsvpOptions[$g->rsvp_status] ?? $g->rsvp_status,
                'catatan'        => $g->catatan,
            ]);

        return response()->json([
            'data' => [
                'delegate_name' => $delegate->name,
                'guests'        => $guests,
                'summary' => [
                    'total'       => $guests->count(),
                    'hadir'       => $guests->where('rsvp_status', 'hadir')->count(),
                    'tidak_hadir' => $guests->where('rsvp_status', 'tidak_hadir')->count(),
                    'menunggu'    => $guests->where('rsvp_status', 'menunggu')->count(),
                ],
            ],
        ]);
    }

    public function updateSharedVipGuestRsvp(Request $request, string $token, int $guestId): JsonResponse
    {
        $delegate = VipGuestDelegate::where('token', $token)->firstOrFail();

        if ($delegate->isExpired()) {
            return response()->json(['message' => 'Token akses sudah kadaluarsa.'], 403);
        }

        $data = $request->validate([
            'rsvp_status' => ['required', 'in:menunggu,hadir,tidak_hadir'],
        ]);

        $guest = VipGuest::where('user_id', $delegate->user_id)
            ->where('id', $guestId)
            ->firstOrFail();

        $guest->update(['rsvp_status' => $data['rsvp_status']]);

        return response()->json([
            'data' => [
                'id'          => $guest->id,
                'name'        => $guest->name,
                'rsvp_status' => $guest->rsvp_status,
                'rsvp_label'  => VipGuest::$rsvpOptions[$guest->rsvp_status] ?? $guest->rsvp_status,
            ],
            'message' => 'Status RSVP berhasil diperbarui.',
        ]);
    }
}
