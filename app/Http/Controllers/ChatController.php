<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    private function dashboardContext(): array
    {
        $user = User::findOrFail(Auth::id());

        $reviewCount = \App\Models\VendorReview::where('user_id', $user->id)->count();
        $favoriteCount = $user->likedVendors()->count();
        $bookingCount = $user->vendorBookings()->count();
        $bookingUserCount = \App\Models\VendorBooking::where('status', 'pending')->count();

        return compact('user', 'reviewCount', 'favoriteCount', 'bookingCount', 'bookingUserCount');
    }

    public function publicPage(Request $request, ?string $token = null)
    {
        $session = null;

        $sessionNotFound = false;

        if ($token) {
            $session = ChatSession::where('session_token', $token)->first();
            if (! $session) {
                $sessionNotFound = true;
            }
        }

        $package = null;
        $vendor = null;
        $packageId = (int) $request->query('package', 0);

        if ($packageId > 0) {
            $package = VendorPackage::query()
                ->where('is_active', true)
                ->with('vendor')
                ->find($packageId);

            $vendor = $package?->vendor;
        }

        $isGuest   = !Auth::check();
        $guestName = $isGuest ? '' : (string) (Auth::user()?->name ?? '');

        return view('chat.public', compact('session', 'package', 'vendor', 'guestName', 'sessionNotFound', 'isGuest'));
    }

    // ── Public: mulai sesi baru ──────────────────────────────────────
    public function start(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:100',
            'vendor_id'  => 'nullable|integer|exists:vendors,id',
            'package_id' => 'nullable|integer|exists:vendor_packages,id',
        ]);

        $firstPackage = null;
        $packageId = (int) $request->input('package_id', 0);
        $vendorId = $request->input('vendor_id') ?: null;

        if ($packageId > 0) {
            $firstPackage = VendorPackage::query()->find($packageId);
            $vendorId = $firstPackage?->vendor_id ?: $vendorId;
        }

        $session = ChatSession::create([
            'guest_name'    => strip_tags(trim($request->guest_name)),
            'session_token' => Str::random(48),
            'status'        => 'open',
            'vendor_id'     => $vendorId,
            'vendor_package_id' => $firstPackage?->id,
        ]);

        // Pesan selamat datang otomatis dari admin
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender'          => 'admin',
            'message'         => 'Halo ' . $session->guest_name . '! 👋 Selamat datang di Makna Wedding. Ada yang bisa kami bantu?',
        ]);

        return response()->json([
            'token'      => $session->session_token,
            'guest_name' => $session->guest_name,
        ]);
    }

    // ── Public: kirim pesan dari tamu ────────────────────────────────
    public function send(Request $request, string $token)
    {
        $request->validate([
            'message'    => 'required|string|max:2000',
            'package_id' => 'nullable|integer|exists:vendor_packages,id',
        ]);

        $session = ChatSession::where('session_token', $token)
            ->where('status', 'open')
            ->firstOrFail();

        $packageId = (int) $request->input('package_id', 0);
        $pkg = $packageId > 0 ? VendorPackage::query()->find($packageId) : null;

        $msg = ChatMessage::create([
            'chat_session_id'   => $session->id,
            'sender'            => 'guest',
            'message'           => strip_tags(trim($request->message)),
            'vendor_package_id' => $pkg?->id,
        ]);

        $session->touch();

        return response()->json(['id' => $msg->id, 'created_at' => $msg->created_at]);
    }

    public function syncContext(Request $request, string $token)
    {
        $request->validate([
            'vendor_id'  => 'nullable|integer|exists:vendors,id',
            'package_id' => 'nullable|integer|exists:vendor_packages,id',
        ]);

        $session = ChatSession::where('session_token', $token)->firstOrFail();

        $packageId = (int) $request->input('package_id', 0);
        $package = $packageId > 0 ? VendorPackage::query()->find($packageId) : null;
        $nextVendorId = $package?->vendor_id ?: ($request->input('vendor_id') ?: null);

        $updates = [];

        if (! $session->vendor_id && $nextVendorId) {
            $updates['vendor_id'] = $nextVendorId;
        }

        if (! $session->vendor_package_id && $package?->id) {
            $updates['vendor_package_id'] = $package->id;
        }

        if ($updates !== []) {
            $session->fill($updates)->save();
        }

        return response()->json([
            'vendor_id' => $session->fresh()->vendor_id,
            'vendor_package_id' => $session->fresh()->vendor_package_id,
        ]);
    }

    // ── Public: polling pesan baru ───────────────────────────────────
    public function poll(Request $request, string $token)
    {
        $session = ChatSession::where('session_token', $token)->firstOrFail();

        $afterId = (int) $request->query('after', 0);

        $messages = ChatMessage::where('chat_session_id', $session->id)
            ->where('id', '>', $afterId)
            ->with(['adminUser:id,name', 'vendorPackage:id,name,price,discount,image_path'])
            ->orderBy('id')
            ->get(['id', 'sender', 'message', 'created_at', 'admin_user_id', 'vendor_package_id'])
            ->map(function ($m) {
                $pkg = $m->vendorPackage;
                $finalPrice = $pkg ? max(((int)$pkg->price) - ((int)$pkg->discount), 0) : null;
                return [
                    'id'                => $m->id,
                    'sender'            => $m->sender,
                    'message'           => $m->message,
                    'created_at'        => $m->created_at,
                    'admin_name'        => $m->adminUser?->name,
                    'vendor_package_id' => $m->vendor_package_id,
                    'package_name'      => $pkg?->name,
                    'package_price'     => $finalPrice !== null ? 'Rp' . number_format($finalPrice, 0, ',', '.') : null,
                    'package_image_url' => $pkg?->image_url,
                ];
            });

        return response()->json([
            'status'   => $session->status,
            'messages' => $messages,
        ]);
    }

    // ── Admin: daftar sesi ───────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $selectedVendorId = (int) $request->query('vendor_id', 0);

        $sessions = ChatSession::with(['vendor:id,name', 'vendorPackage:id,vendor_id,name'])
            ->when($selectedVendorId > 0, fn ($query) => $query->where('vendor_id', $selectedVendorId))
            ->select('chat_sessions.*')
            ->selectSub(
                DB::table('chat_messages')
                    ->select('admin_user_id')
                    ->whereColumn('chat_messages.chat_session_id', 'chat_sessions.id')
                    ->where('sender', 'admin')
                    ->whereNotNull('admin_user_id')
                    ->orderByDesc('id')
                    ->limit(1),
                'last_admin_user_id'
            )
            ->orderByDesc('updated_at')
            ->paginate(30)
            ->withQueryString();

        $vendors = Vendor::query()
            ->withCount('chatSessions')
            ->whereHas('chatSessions')
            ->orderBy('name')
            ->get(['id', 'name']);

        $adminUsers = User::whereIn('id', $sessions->pluck('last_admin_user_id')->filter()->unique())
            ->get(['id', 'name'])
            ->keyBy('id');

        return view('dashboard.chat.index', array_merge($this->dashboardContext(), compact('sessions', 'adminUsers', 'vendors', 'selectedVendorId')));
    }

    public function adminNotify(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $latestGuestMessage = ChatMessage::where('sender', 'guest')
            ->latest('id')
            ->with('session:id,session_token,guest_name,status')
            ->first();

        $openGuestSessionsCount = ChatSession::where('status', 'open')
            ->whereHas('messages', fn ($q) => $q->where('sender', 'guest'))
            ->count();

        return response()->json([
            'latest_guest_message_id' => $latestGuestMessage?->id,
            'latest_guest_name' => $latestGuestMessage?->session?->guest_name,
            'latest_session_token' => $latestGuestMessage?->session?->session_token,
            'open_guest_sessions_count' => $openGuestSessionsCount,
        ]);
    }

    // ── Admin: detail sesi ───────────────────────────────────────────
    public function adminDetail(string $token)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $session = ChatSession::where('session_token', $token)
            ->with([
                'messages.adminUser:id,name',
                'messages.vendorPackage:id,name,price,discount,image_path',
                'vendor:id,name',
                'vendorPackage:id,vendor_id,name,price,discount,image_path',
            ])
            ->firstOrFail();

        return view('dashboard.chat.detail', array_merge($this->dashboardContext(), compact('session')));
    }

    // ── Admin: balas pesan ───────────────────────────────────────────
    public function adminReply(Request $request, string $token)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $session = ChatSession::where('session_token', $token)->firstOrFail();

        $user = User::findOrFail(Auth::id());

        $msg = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender'          => 'admin',
            'message'         => strip_tags(trim($request->message)),
            'admin_user_id'   => $user->id,
        ]);

        $session->touch();

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $msg->id,
                'created_at' => $msg->created_at,
                'admin_name' => $user->name,
            ]);
        }

        return back();
    }

    public function adminDeleteMessage(Request $request, string $token, ChatMessage $message)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $session = ChatSession::where('session_token', $token)->firstOrFail();
        abort_unless((int) $message->chat_session_id === (int) $session->id, 404);

        $message->delete();

        if ($request->expectsJson()) {
            return response()->json(['deleted' => true]);
        }

        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    public function adminDeleteSession(Request $request, string $token)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $session = ChatSession::where('session_token', $token)->firstOrFail();
        $session->delete();

        if ($request->expectsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->route('chat.admin')->with('success', 'Sesi chat berhasil dihapus.');
    }

    // ── Admin: tutup sesi ────────────────────────────────────────────
    public function adminClose(string $token)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $session = ChatSession::where('session_token', $token)->firstOrFail();
        $session->update(['status' => 'closed']);

        return redirect()->route('chat.admin')->with('success', 'Sesi chat ditutup.');
    }

    public function adminOpen(string $token)
    {
        $user = User::findOrFail(Auth::id());
        abort_unless($user->hasRole(['super_admin', 'admin']), 403);

        $session = ChatSession::where('session_token', $token)->firstOrFail();
        $session->update(['status' => 'open']);

        return redirect()->route('chat.admin.detail', $token)->with('success', 'Sesi chat dibuka kembali.');
    }
}
