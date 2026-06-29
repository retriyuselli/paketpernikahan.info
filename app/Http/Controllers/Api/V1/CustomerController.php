<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Imports\FamilyMemberImport;
use App\Imports\VipGuestImport;
use App\Models\CustomerNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CustomerPaymentMethod;
use App\Models\CustomerPreparationTask;
use App\Models\FamilyMember;
use App\Models\VipGuest;
use App\Models\VipGuestDelegate;
use App\Models\VendorBooking;
use App\Models\VendorBookingPayment;
use App\Models\WeddingIncomingPayment;
use App\Models\WeddingPaymentSchedule;
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

        // Preparation summary — hitung semua task milik user (event-based + general)
        $allTasksQuery = CustomerPreparationTask::where('user_id', $user->id);
        $totalTasks    = (clone $allTasksQuery)->count();
        $doneTasks     = (clone $allTasksQuery)->where('status', 'done')->count();
        $pendingTasks  = (clone $allTasksQuery)->where('status', 'pending')->count();
        $todoTasks     = (clone $allTasksQuery)->where('status', 'todo')->count();

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
        $budget = $user->weddingBudget;

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
                'budget'       => (float) ($budget?->total_budget ?? 0),
                'currency'     => $budget?->currency ?? 'IDR',
                'budaya'       => $info?->budaya,
                'songlist'     => $info?->songlist ?? [],
                'events'       => $events,
            ],
        ]);
    }

    public function updateWeddingInfo(Request $request): JsonResponse
    {
        $request->validate([
            'groom_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'bride_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'budget'     => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'currency'   => ['sometimes', 'nullable', 'string', 'size:3'],
            'budaya'     => ['sometimes', 'nullable', 'string', 'max:100'],
            'songlist'   => ['sometimes', 'nullable', 'array'],
            'songlist.*' => ['nullable', 'string', 'max:150'],
        ]);

        $user = $request->user();
        $info = WeddingInfo::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['groom_name', 'bride_name', 'budaya', 'songlist']),
        );

        if ($request->has('budget') || $request->has('currency')) {
            $budgetData = [];

            if ($request->has('budget')) {
                $budgetData['total_budget'] = (float) ($request->input('budget') ?? 0);
            }

            if ($request->filled('currency')) {
                $budgetData['currency'] = strtoupper($request->input('currency'));
            }

            $user->weddingBudget()
                ->updateOrCreate(['user_id' => $user->id], $budgetData);
        }

        $budget = $user->weddingBudget()->first();

        return response()->json([
            'data' => [
                'groom_name' => $info->groom_name,
                'bride_name' => $info->bride_name,
                'budget'     => (float) ($budget?->total_budget ?? 0),
                'currency'   => $budget?->currency ?? 'IDR',
                'budaya'     => $info->budaya,
                'songlist'   => $info->songlist ?? [],
            ],
        ]);
    }

    // ─────────────────────────────────────────
    // Family Members
    // ─────────────────────────────────────────

    public function familyMembers(Request $request): JsonResponse
    {
        $members = $request->user()
            ->familyMembers()
            ->orderByRaw('no IS NULL, no ASC')
            ->orderBy('id')
            ->get(['id', 'no', 'name', 'role', 'phone', 'rsvp_status', 'rsvp_updated_by_name', 'rsvp_updated_at'])
            ->map(fn (FamilyMember $member): array => [
                'id'                   => $member->id,
                'no'                   => $member->no,
                'name'                 => $member->name,
                'role'                 => $member->role,
                'phone'                => $member->phone,
                'rsvp_status'          => $member->rsvp_status,
                'rsvp_label'           => FamilyMember::$rsvpOptions[$member->rsvp_status] ?? $member->rsvp_status,
                'rsvp_updated_by_name' => $member->rsvp_updated_by_name,
                'rsvp_updated_at'      => $member->rsvp_updated_at,
            ]);

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
                'group'       => ($n->created_at->isToday()
                    ? 'Hari Ini'
                    : ($n->created_at->gte(now()->subDays(7)) ? 'Minggu Ini' : 'Sebelumnya')),
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
        $userId = $request->user()->id;
        $now    = now()->startOfDay();

        $eventIcons = [
            'lamaran'   => 'gift.fill',
            'pengajian' => 'book.fill',
            'akad'      => 'heart.fill',
            'resepsi'   => 'sparkles',
        ];

        $events = WeddingEvent::where('user_id', $userId)
            ->orderBy('tgl_acara')
            ->with(['preparationTasks' => fn ($q) => $q->orderBy('sort_order')])
            ->get()
            ->map(fn ($e) => [
                'id'          => $e->id,
                'jenis_acara' => $e->jenis_acara,
                'label'       => WeddingEvent::$jenisOptions[$e->jenis_acara] ?? $e->jenis_acara,
                'tgl_acara'   => $e->tgl_acara?->toDateString(),
                'days_until'  => $e->tgl_acara ? (int) $now->diffInDays($e->tgl_acara, false) : null,
                'icon'        => $eventIcons[$e->jenis_acara] ?? 'calendar',
                'done'        => $e->preparationTasks->where('status', 'done')->count(),
                'total'       => $e->preparationTasks->count(),
                'tasks'       => $e->preparationTasks->map(fn ($t) => [
                    'id'       => $t->id,
                    'label'    => $t->label,
                    'title'    => $t->title,
                    'status'   => $t->status,
                    'due_date' => $t->due_date?->toDateString(),
                ])->values(),
            ]);

        return response()->json([
            'data' => [
                'events' => $events,
            ],
        ]);
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

    public function paymentSummary(Request $request): JsonResponse
    {
        $user      = $request->user();
        $budget    = $user->weddingBudget;
        $schedules = $user->paymentSchedules;

        $totalBudget = (float) ($budget?->total_budget ?? 0);
        $paid        = (float) $schedules->where('status', 'paid')->sum('amount');
        $remaining   = (float) $schedules->whereIn('status', ['pending', 'overdue'])->sum('amount');
        $paidPct     = $totalBudget > 0 ? round(($paid / $totalBudget) * 100, 1) : 0;

        return response()->json([
            'data' => [
                'total_budget'    => $totalBudget,
                'currency'        => $budget?->currency ?? 'IDR',
                'total_paid'      => $paid,
                'total_remaining' => $remaining,
                'paid_percentage' => $paidPct,
            ],
        ]);
    }

    public function paymentSchedules(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['sometimes', 'in:pending,paid,overdue'],
        ]);

        $query = $request->user()->paymentSchedules();

        if ($request->filled('status')) {
            $query->where('status', (string) $request->string('status'));
        }

        $items = $query
            ->with('paymentMethod')
            ->orderBy('sort_order')
            ->orderBy('due_date')
            ->get()
            ->map(fn (WeddingPaymentSchedule $schedule): array => $this->serializePaymentSchedule($schedule));

        return response()->json(['data' => $items]);
    }

    public function paymentTransactions(Request $request): JsonResponse
    {
        $items = $request->user()
            ->paymentSchedules()
            ->where('status', 'paid')
            ->with('paymentMethod')
            ->orderByDesc('paid_at')
            ->get()
            ->map(fn (WeddingPaymentSchedule $schedule): array => [
                'id'             => $schedule->id,
                'title'          => $schedule->title,
                'vendor_name'    => $schedule->vendor_name,
                'category_icon'  => $schedule->category_icon,
                'amount'         => $schedule->amount,
                'paid_at'        => $schedule->paid_at?->toDateString(),
                'payment_method' => $this->serializePaymentMethodRelation($schedule),
            ]);

        return response()->json(['data' => $items]);
    }

    public function incomingPayments(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['sometimes', 'in:pending,confirmed,rejected'],
        ]);

        $query = $request->user()->incomingPayments();

        if ($request->filled('status')) {
            $query->where('status', (string) $request->string('status'));
        }

        $items = $query
            ->get()
            ->map(fn (WeddingIncomingPayment $payment): array => $this->serializeIncomingPayment($payment));

        return response()->json(['data' => $items]);
    }

    public function storePaymentSchedule(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'vendor_name' => ['required', 'string', 'max:200'],
            'category'    => ['required', 'in:venue,catering,decoration,photo_video,entertainment,makeup,transport,wo,other'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'due_date'    => ['required', 'date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $schedule = $request->user()->paymentSchedules()->create($data);

        return response()->json([
            'data'    => $this->serializePaymentSchedule($schedule),
            'message' => 'Tagihan berhasil ditambahkan.',
        ], 201);
    }

    public function updatePaymentSchedule(Request $request, int $id): JsonResponse
    {
        $schedule = $request->user()->paymentSchedules()->with('paymentMethod')->findOrFail($id);

        $data = $request->validate([
            'title'          => ['sometimes', 'string', 'max:200'],
            'vendor_name'    => ['sometimes', 'string', 'max:200'],
            'category'       => ['sometimes', 'in:venue,catering,decoration,photo_video,entertainment,makeup,transport,wo,other'],
            'amount'         => ['sometimes', 'numeric', 'min:0'],
            'due_date'       => ['sometimes', 'date'],
            'status'                    => ['sometimes', 'in:pending,paid,overdue'],
            'customer_payment_method_id' => ['nullable', 'exists:customer_payment_methods,id'],
            'proof_url'                 => ['nullable', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
        ]);

        if (($data['status'] ?? null) === 'paid' && !$schedule->paid_at) {
            $data['paid_at'] = now();
        }

        if (($data['status'] ?? null) !== 'paid' && isset($data['status'])) {
            $data['paid_at'] = null;
        }

        $schedule->update($data);

        return response()->json([
            'data'    => $this->serializePaymentSchedule($schedule),
            'message' => 'Tagihan berhasil diperbarui.',
        ]);
    }

    public function destroyPaymentSchedule(Request $request, int $id): JsonResponse
    {
        $request->user()->paymentSchedules()->findOrFail($id)->delete();

        return response()->json(['message' => 'Tagihan berhasil dihapus.']);
    }

    public function uploadScheduleProof(Request $request, int $id): JsonResponse
    {
        $schedule = $request->user()->paymentSchedules()->findOrFail($id);

        $request->validate([
            'proof' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $path = $request->file('proof')->store('payment-proofs', 'public');
        $url  = \Illuminate\Support\Facades\Storage::url($path);

        $schedule->update(['proof_url' => $url]);

        return response()->json([
            'data'    => $this->serializePaymentSchedule($schedule->refresh()),
            'message' => 'Bukti pembayaran berhasil diupload.',
        ]);
    }

    public function storeIncomingPayment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'source_name'      => ['required_without:bank_name', 'string', 'max:100'],
            'bank_name'        => ['required_without:source_name', 'string', 'max:100'],
            'amount'           => ['required', 'numeric', 'min:1'],
            'received_date'    => ['required_without:transfer_date', 'date'],
            'transfer_date'    => ['required_without:received_date', 'date'],
            'contributor_name' => ['required_without:sender_name', 'string', 'max:200'],
            'sender_name'      => ['required_without:contributor_name', 'string', 'max:200'],
            'description'      => ['nullable', 'string', 'max:300'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'proof_url'        => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string'],
        ]);

        $data = $this->normalizeIncomingPaymentData($data);

        $payment = $request->user()->incomingPayments()->create($data);

        return response()->json([
            'data'    => $this->serializeIncomingPayment($payment),
            'message' => 'Dana masuk berhasil dicatat.',
        ], 201);
    }

    public function updateIncomingPayment(Request $request, int $id): JsonResponse
    {
        $payment = $request->user()->incomingPayments()->findOrFail($id);

        $data = $request->validate([
            'source_name'      => ['sometimes', 'string', 'max:100'],
            'bank_name'        => ['sometimes', 'string', 'max:100'],
            'amount'           => ['sometimes', 'numeric', 'min:1'],
            'received_date'    => ['sometimes', 'date'],
            'transfer_date'    => ['sometimes', 'date'],
            'contributor_name' => ['sometimes', 'string', 'max:200'],
            'sender_name'      => ['sometimes', 'string', 'max:200'],
            'description'      => ['nullable', 'string', 'max:300'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'proof_url'        => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string'],
        ]);

        $data = $this->normalizeIncomingPaymentData($data);

        $payment->update($data);

        return response()->json([
            'data'    => $this->serializeIncomingPayment($payment),
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function destroyIncomingPayment(Request $request, int $id): JsonResponse
    {
        $request->user()->incomingPayments()->findOrFail($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function budget(Request $request): JsonResponse
    {
        $budget = $request->user()->weddingBudget;

        return response()->json([
            'data' => [
                'total_budget' => (float) ($budget?->total_budget ?? 0),
                'currency'     => $budget?->currency ?? 'IDR',
                'notes'        => $budget?->notes,
            ],
        ]);
    }

    public function updateBudget(Request $request): JsonResponse
    {
        $data = $request->validate([
            'total_budget' => ['required', 'numeric', 'min:0'],
            'currency'     => ['sometimes', 'nullable', 'string', 'size:3'],
            'notes'        => ['nullable', 'string'],
        ]);

        if (! empty($data['currency'])) {
            $data['currency'] = strtoupper($data['currency']);
        }

        $budget = $request->user()
            ->weddingBudget()
            ->updateOrCreate(['user_id' => $request->user()->id], $data);

        return response()->json([
            'data' => [
                'total_budget' => (float) $budget->total_budget,
                'currency'     => $budget->currency,
                'notes'        => $budget->notes,
            ],
            'message' => 'Budget berhasil disimpan.',
        ]);
    }

    public function weddingBudget(Request $request): JsonResponse
    {
        $budget = $request->user()->weddingBudget;

        return response()->json([
            'data' => [
                'budget' => $budget ? [
                    'id'            => $budget->id,
                    'nominalBudget' => (float) $budget->total_budget,
                    'currency'      => $budget->currency ?? 'IDR',
                    'catatan'       => $budget->notes,
                ] : null,
            ],
        ]);
    }

    public function storeWeddingBudget(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nominal_budget' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'total_budget'   => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'currency'       => ['sometimes', 'nullable', 'string', 'size:3'],
            'catatan'        => ['sometimes', 'nullable', 'string'],
            'notes'          => ['sometimes', 'nullable', 'string'],
        ]);

        $totalBudget = $data['nominal_budget'] ?? $data['total_budget'] ?? 0;
        $currency    = strtoupper($data['currency'] ?? 'IDR');
        $notes       = $data['catatan'] ?? $data['notes'] ?? null;

        $budget = $request->user()
            ->weddingBudget()
            ->updateOrCreate(
                ['user_id' => $request->user()->id],
                ['total_budget' => $totalBudget, 'currency' => $currency, 'notes' => $notes]
            );

        return response()->json([
            'data' => [
                'budget' => [
                    'id'            => $budget->id,
                    'nominalBudget' => (float) $budget->total_budget,
                    'currency'      => $budget->currency,
                    'catatan'       => $budget->notes,
                ],
            ],
            'message' => 'Budget berhasil disimpan.',
        ]);
    }

    public function destroyWeddingBudget(Request $request): JsonResponse
    {
        $request->user()->weddingBudget?->delete();

        return response()->json(['message' => 'Budget berhasil dihapus.']);
    }

    private function serializePaymentSchedule(WeddingPaymentSchedule $schedule): array
    {
        return [
            'id'                         => $schedule->id,
            'wedding_event_id'           => $schedule->wedding_event_id,
            'source_template_id'         => $schedule->source_template_id,
            'title'                      => $schedule->title,
            'vendor_name'                => $schedule->vendor_name,
            'category'                   => $schedule->category,
            'category_label'             => $schedule->category_label,
            'category_icon'              => $schedule->category_icon,
            'amount'                     => $schedule->amount,
            'due_date'                   => $schedule->due_date?->toDateString(),
            'status'                     => $schedule->status,
            'paid_at'                    => $schedule->paid_at?->toISOString(),
            'payment_method'             => $this->serializePaymentMethodRelation($schedule),
            'proof_url'                  => $schedule->proof_url,
            'notes'                      => $schedule->notes,
        ];
    }

    private function serializePaymentMethodRelation(WeddingPaymentSchedule $schedule): ?array
    {
        $pm = $schedule->relationLoaded('paymentMethod')
            ? $schedule->paymentMethod
            : $schedule->paymentMethod()->first();

        if (! $pm) {
            return null;
        }

        return [
            'id'             => $pm->id,
            'name'           => $pm->name,
            'type'           => $pm->type,
            'logo_icon'      => $pm->logo_icon,
            'account_number' => $pm->account_number,
            'account_name'   => $pm->account_name,
        ];
    }

    private function serializeIncomingPayment(WeddingIncomingPayment $payment): array
    {
        return [
            'id'               => $payment->id,
            'source_name'      => $payment->bank_name,
            'bank_name'        => $payment->bank_name,
            'amount'           => $payment->amount,
            'received_date'    => $payment->transfer_date?->toDateString(),
            'transfer_date'    => $payment->transfer_date?->toDateString(),
            'contributor_name' => $payment->sender_name,
            'sender_name'      => $payment->sender_name,
            'description'      => $payment->description,
            'reference_number' => $payment->reference_number,
            'proof_url'        => $payment->proof_url,
            'status'           => $payment->status,
            'status_label'     => $payment->status_label,
            'confirmed_at'     => $payment->confirmed_at?->toISOString(),
            'confirmed_by'     => $payment->confirmed_by,
            'rejection_reason' => $payment->rejection_reason,
            'notes'            => $payment->notes,
        ];
    }

    private function normalizeIncomingPaymentData(array $data): array
    {
        if (array_key_exists('source_name', $data)) {
            $data['bank_name'] = $data['source_name'];
        }

        if (array_key_exists('received_date', $data)) {
            $data['transfer_date'] = $data['received_date'];
        }

        if (array_key_exists('contributor_name', $data)) {
            $data['sender_name'] = $data['contributor_name'];
        }

        unset($data['source_name'], $data['received_date'], $data['contributor_name']);

        return $data;
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
            'no'    => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
            'name'  => ['required', 'string', 'max:100'],
            'role'  => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'rsvp_status' => ['sometimes', 'in:menunggu,hadir,tidak_hadir'],
        ]);

        $nextNo = (FamilyMember::where('user_id', $request->user()->id)->max('no') ?? 0) + 1;

        $member = FamilyMember::create(
            array_merge($data, [
                'user_id' => $request->user()->id,
                'no'      => $data['no'] ?? $nextNo,
                'rsvp_status' => $data['rsvp_status'] ?? 'menunggu',
            ])
        );

        return response()->json(['data' => [
            'id'                   => $member->id,
            'no'                   => $member->no,
            'name'                 => $member->name,
            'role'                 => $member->role,
            'phone'                => $member->phone,
            'rsvp_status'          => $member->rsvp_status,
            'rsvp_label'           => FamilyMember::$rsvpOptions[$member->rsvp_status] ?? $member->rsvp_status,
            'rsvp_updated_by_name' => $member->rsvp_updated_by_name,
            'rsvp_updated_at'      => $member->rsvp_updated_at,
        ]], 201);
    }

    public function updateFamilyMember(Request $request, int $id): JsonResponse
    {
        $member = FamilyMember::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'no'    => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
            'name'  => ['sometimes', 'string', 'max:100'],
            'role'  => ['sometimes', 'string', 'max:100'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'rsvp_status' => ['sometimes', 'in:menunggu,hadir,tidak_hadir'],
        ]);

        if (isset($data['rsvp_status'])) {
            $data['rsvp_updated_by_name'] = $request->user()->name;
            $data['rsvp_updated_at']      = now();
        }

        $member->update($data);

        return response()->json(['data' => [
            'id'                   => $member->id,
            'no'                   => $member->no,
            'name'                 => $member->name,
            'role'                 => $member->role,
            'phone'                => $member->phone,
            'rsvp_status'          => $member->rsvp_status,
            'rsvp_label'           => FamilyMember::$rsvpOptions[$member->rsvp_status] ?? $member->rsvp_status,
            'rsvp_updated_by_name' => $member->rsvp_updated_by_name,
            'rsvp_updated_at'      => $member->rsvp_updated_at,
        ]]);
    }

    public function destroyFamilyMember(Request $request, int $id): JsonResponse
    {
        FamilyMember::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'ok']);
    }

    public function destroyAllFamilyMembers(Request $request): JsonResponse
    {
        FamilyMember::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Semua anggota keluarga berhasil dihapus.']);
    }

    public function importFamilyMembers(Request $request): JsonResponse
    {
        $request->validate([
            'file'        => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:2048'],
            'replace_all' => ['sometimes', 'boolean'],
        ]);

        if ($request->boolean('replace_all')) {
            FamilyMember::where('user_id', $request->user()->id)->delete();
        }

        $import = new FamilyMemberImport($request->user()->id);
        Excel::import($import, $request->file('file'));

        return response()->json([
            'data' => [
                'imported' => $import->getImported(),
                'skipped'  => $import->getSkipped(),
            ],
            'message' => "Berhasil mengimpor {$import->getImported()} anggota keluarga.",
        ]);
    }

    // ─────────────────────────────────────────
    // Preparation Tasks CRUD
    // ─────────────────────────────────────────

    public function storePreparationTask(Request $request): JsonResponse
    {
        $data = $request->validate([
            'wedding_event_id' => ['required', 'integer'],
            'label'            => ['nullable', 'string', 'max:100'],
            'title'            => ['required', 'string', 'max:200'],
            'status'           => ['sometimes', 'in:todo,done,pending'],
            'due_date'         => ['nullable', 'date'],
        ]);

        $user = $request->user();

        WeddingEvent::where('user_id', $user->id)
            ->where('id', $data['wedding_event_id'])
            ->firstOrFail();

        $task = CustomerPreparationTask::create([
            'user_id'          => $user->id,
            'wedding_event_id' => $data['wedding_event_id'],
            'section_id'       => null,
            'label'            => $data['label'] ?? null,
            'title'            => $data['title'],
            'status'           => $data['status'] ?? 'todo',
            'due_date'         => $data['due_date'] ?? null,
            'sort_order'       => CustomerPreparationTask::where('wedding_event_id', $data['wedding_event_id'])->max('sort_order') + 1,
        ]);

        return response()->json([
            'data' => [
                'id'               => $task->id,
                'label'            => $task->label,
                'title'            => $task->title,
                'status'           => $task->status,
                'due_date'         => $task->due_date?->toDateString(),
                'wedding_event_id' => $task->wedding_event_id,
            ],
        ], 201);
    }

    public function updatePreparationTask(Request $request, int $id): JsonResponse
    {
        $task = CustomerPreparationTask::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'label'    => ['sometimes', 'nullable', 'string', 'max:100'],
            'title'    => ['sometimes', 'string', 'max:200'],
            'status'   => ['sometimes', 'in:todo,done,pending'],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ]);

        $task->update($data);

        return response()->json([
            'data' => [
                'id'       => $task->id,
                'label'    => $task->label,
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
    // VIP Guests CRUD
    // ─────────────────────────────────────────

    public function vipGuests(Request $request): JsonResponse
    {
        $guests = VipGuest::where('user_id', $request->user()->id)
            ->orderByRaw('no IS NULL, no ASC')
            ->orderBy('id')
            ->get()
            ->map(fn ($g) => [
                'id'          => $g->id,
                'no'          => $g->no,
                'name'        => $g->name,
                'jabatan'     => $g->jabatan,
                'instansi'    => $g->instansi,
                'phone'       => $g->phone,
                'kategori'    => $g->kategori,
                'kategori_label'        => VipGuest::$kategoriOptions[$g->kategori] ?? $g->kategori,
                'rsvp_status'           => $g->rsvp_status,
                'rsvp_label'            => VipGuest::$rsvpOptions[$g->rsvp_status] ?? $g->rsvp_status,
                'rsvp_updated_by_name'  => $g->rsvp_updated_by_name,
                'rsvp_updated_at'       => $g->rsvp_updated_at,
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
            'no'          => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
            'name'        => ['required', 'string', 'max:150'],
            'jabatan'     => ['nullable', 'string', 'max:150'],
            'instansi'    => ['nullable', 'string', 'max:150'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'kategori'    => ['sometimes', 'in:vip,keluarga_besar,pejabat,tokoh_masyarakat,rekan_bisnis,teman'],
            'rsvp_status' => ['sometimes', 'in:menunggu,hadir,tidak_hadir'],
            'catatan'     => ['nullable', 'string', 'max:500'],
        ]);

        $nextNo = (VipGuest::where('user_id', $request->user()->id)->max('no') ?? 0) + 1;

        $guest = VipGuest::create(array_merge($data, [
            'user_id'     => $request->user()->id,
            'no'          => $data['no'] ?? $nextNo,
            'kategori'    => $data['kategori'] ?? 'vip',
            'rsvp_status' => $data['rsvp_status'] ?? 'menunggu',
        ]));

        return response()->json([
            'data' => [
                'id'                   => $guest->id,
                'no'                   => $guest->no,
                'name'                 => $guest->name,
                'jabatan'              => $guest->jabatan,
                'instansi'             => $guest->instansi,
                'phone'                => $guest->phone,
                'kategori'             => $guest->kategori,
                'kategori_label'       => VipGuest::$kategoriOptions[$guest->kategori] ?? $guest->kategori,
                'rsvp_status'          => $guest->rsvp_status,
                'rsvp_label'           => VipGuest::$rsvpOptions[$guest->rsvp_status] ?? $guest->rsvp_status,
                'rsvp_updated_by_name' => $guest->rsvp_updated_by_name,
                'rsvp_updated_at'      => $guest->rsvp_updated_at,
                'catatan'              => $guest->catatan,
            ],
        ], 201);
    }

    public function updateVipGuest(Request $request, int $id): JsonResponse
    {
        $guest = VipGuest::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $request->validate([
            'no'          => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
            'name'        => ['sometimes', 'string', 'max:150'],
            'jabatan'     => ['sometimes', 'nullable', 'string', 'max:150'],
            'instansi'    => ['sometimes', 'nullable', 'string', 'max:150'],
            'phone'       => ['sometimes', 'nullable', 'string', 'max:30'],
            'kategori'    => ['sometimes', 'in:keluarga_besar,pejabat,tokoh_masyarakat,rekan_bisnis,teman'],
            'rsvp_status' => ['sometimes', 'in:menunggu,hadir,tidak_hadir'],
            'catatan'     => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        if (isset($data['rsvp_status'])) {
            $data['rsvp_updated_by_name'] = $request->user()->name;
            $data['rsvp_updated_at']      = now();
        }

        $guest->update($data);

        return response()->json([
            'data' => [
                'id'                   => $guest->id,
                'no'                   => $guest->no,
                'name'                 => $guest->name,
                'jabatan'              => $guest->jabatan,
                'instansi'             => $guest->instansi,
                'phone'                => $guest->phone,
                'kategori'             => $guest->kategori,
                'kategori_label'       => VipGuest::$kategoriOptions[$guest->kategori] ?? $guest->kategori,
                'rsvp_status'          => $guest->rsvp_status,
                'rsvp_label'           => VipGuest::$rsvpOptions[$guest->rsvp_status] ?? $guest->rsvp_status,
                'rsvp_updated_by_name' => $guest->rsvp_updated_by_name,
                'rsvp_updated_at'      => $guest->rsvp_updated_at,
                'catatan'              => $guest->catatan,
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

    public function destroyAllVipGuests(Request $request): JsonResponse
    {
        VipGuest::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Semua tamu VIP berhasil dihapus.']);
    }

    // ── VIP Guest Delegates ────────────────────────────────────────────────

    public function vipGuestDelegates(Request $request): JsonResponse
    {
        $delegates = VipGuestDelegate::where('user_id', $request->user()->id)
            ->with('claimedBy')
            ->latest()
            ->get()
            ->map(fn ($d) => [
                'id'                  => $d->id,
                'name'                => $d->name,
                'token'               => $d->token,
                'claimed_by_user_id'  => $d->claimed_by_user_id,
                'claimed_by_name'     => $d->claimedBy?->name,
                'claimed_by_email'    => $d->claimedBy?->email,
                'expires_at'          => $d->expires_at?->toIso8601String(),
                'last_accessed_at'    => $d->last_accessed_at?->toIso8601String(),
                'is_active'           => $d->isActive(),
                'created_at'          => $d->created_at->toIso8601String(),
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
                'id'                 => $delegate->id,
                'name'               => $delegate->name,
                'token'              => $delegate->token,
                'claimed_by_user_id'  => null,
                'claimed_by_name'     => null,
                'claimed_by_email'    => null,
                'expires_at'         => $delegate->expires_at?->toIso8601String(),
                'last_accessed_at'   => null,
                'is_active'          => true,
                'created_at'         => $delegate->created_at->toIso8601String(),
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

    // ── VIP Guest Shared Access (butuh auth:sanctum, via token) ──────────────────

    public function sharedVipGuests(Request $request, string $token): JsonResponse
    {
        $delegate = VipGuestDelegate::where('token', $token)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->firstOrFail();

        $user = $request->user();

        // Atomic claim: hanya berhasil jika claimed_by_user_id masih NULL
        $claimed = VipGuestDelegate::where('id', $delegate->id)
            ->whereNull('claimed_by_user_id')
            ->update(['claimed_by_user_id' => $user->id, 'last_accessed_at' => now()]);

        if (!$claimed) {
            // Token sudah pernah diklaim — reload data terbaru dari DB
            $delegate->refresh();

            if ($delegate->claimed_by_user_id !== $user->id) {
                return response()->json(['message' => 'Token ini sudah digunakan oleh akun lain.'], 403);
            }

            // User yang sama boleh akses ulang
            $delegate->update(['last_accessed_at' => now()]);
        }

        $wedding = WeddingInfo::where('user_id', $delegate->user_id)->first();

        $guests = VipGuest::where('user_id', $delegate->user_id)
            ->orderByRaw('no IS NULL, no ASC')
            ->orderBy('id')
            ->get()
            ->map(fn ($g) => [
                'id'                   => $g->id,
                'no'                   => $g->no,
                'name'                 => $g->name,
                'jabatan'              => $g->jabatan,
                'instansi'             => $g->instansi,
                'phone'                => $g->phone,
                'kategori'             => $g->kategori,
                'kategori_label'       => VipGuest::$kategoriOptions[$g->kategori] ?? $g->kategori,
                'rsvp_status'          => $g->rsvp_status,
                'rsvp_label'           => VipGuest::$rsvpOptions[$g->rsvp_status] ?? $g->rsvp_status,
                'rsvp_updated_by_name' => $g->rsvp_updated_by_name,
                'rsvp_updated_at'      => $g->rsvp_updated_at,
                'catatan'              => $g->catatan,
            ]);

        return response()->json([
            'data' => [
                'delegate_name' => $delegate->name,
                'groom_name'    => $wedding?->groom_name,
                'bride_name'    => $wedding?->bride_name,
                'expires_at'    => $delegate->expires_at?->toIso8601String(),
                'created_at'    => $delegate->created_at?->toIso8601String(),
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
        $delegate = VipGuestDelegate::where('token', $token)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->firstOrFail();

        if ($delegate->claimed_by_user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'rsvp_status' => ['required', 'in:menunggu,hadir,tidak_hadir'],
        ]);

        $guest = VipGuest::where('user_id', $delegate->user_id)
            ->where('id', $guestId)
            ->firstOrFail();

        $guest->update([
            'rsvp_status'          => $data['rsvp_status'],
            'rsvp_updated_by_name' => $delegate->name,
            'rsvp_updated_at'      => now(),
        ]);

        return response()->json([
            'data' => [
                'id'                   => $guest->id,
                'name'                 => $guest->name,
                'rsvp_status'          => $guest->rsvp_status,
                'rsvp_label'           => VipGuest::$rsvpOptions[$guest->rsvp_status] ?? $guest->rsvp_status,
                'rsvp_updated_by_name' => $guest->rsvp_updated_by_name,
                'rsvp_updated_at'      => $guest->rsvp_updated_at,
            ],
            'message' => 'Status RSVP berhasil diperbarui.',
        ]);
    }
}
