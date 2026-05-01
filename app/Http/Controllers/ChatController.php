<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\User;
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

    // ── Public: mulai sesi baru ──────────────────────────────────────
    public function start(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:100',
        ]);

        $session = ChatSession::create([
            'guest_name'    => strip_tags(trim($request->guest_name)),
            'session_token' => Str::random(48),
            'status'        => 'open',
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
            'message' => 'required|string|max:2000',
        ]);

        $session = ChatSession::where('session_token', $token)
            ->where('status', 'open')
            ->firstOrFail();

        $msg = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender'          => 'guest',
            'message'         => strip_tags(trim($request->message)),
        ]);

        return response()->json(['id' => $msg->id, 'created_at' => $msg->created_at]);
    }

    // ── Public: polling pesan baru ───────────────────────────────────
    public function poll(Request $request, string $token)
    {
        $session = ChatSession::where('session_token', $token)->firstOrFail();

        $afterId = (int) $request->query('after', 0);

        $messages = ChatMessage::where('chat_session_id', $session->id)
            ->where('id', '>', $afterId)
            ->with('adminUser:id,name')
            ->orderBy('id')
            ->get(['id', 'sender', 'message', 'created_at', 'admin_user_id'])
            ->map(fn ($m) => [
                'id' => $m->id,
                'sender' => $m->sender,
                'message' => $m->message,
                'created_at' => $m->created_at,
                'admin_name' => $m->adminUser?->name,
            ]);

        return response()->json([
            'status'   => $session->status,
            'messages' => $messages,
        ]);
    }

    // ── Admin: daftar sesi ───────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        $sessions = ChatSession::withCount([
                'messages as unread_count' => fn($q) => $q->where('sender', 'guest')
                    ->where('created_at', '>=', now()->subHours(24)),
            ])
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
            ->paginate(30);

        $adminUsers = User::whereIn('id', $sessions->pluck('last_admin_user_id')->filter()->unique())
            ->get(['id', 'name'])
            ->keyBy('id');

        return view('dashboard.chat.index', array_merge($this->dashboardContext(), compact('sessions', 'adminUsers')));
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
        $session = ChatSession::where('session_token', $token)
            ->with('messages.adminUser:id,name')
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

    // ── Admin: polling pesan baru (untuk admin detail page) ─────────
    public function adminPoll(Request $request, string $token)
    {
        $session = ChatSession::where('session_token', $token)->firstOrFail();

        $afterId = (int) $request->query('after', 0);

        $messages = ChatMessage::where('chat_session_id', $session->id)
            ->where('id', '>', $afterId)
            ->with('adminUser:id,name')
            ->orderBy('id')
            ->get(['id', 'sender', 'message', 'created_at', 'admin_user_id'])
            ->map(fn ($m) => [
                'id' => $m->id,
                'sender' => $m->sender,
                'message' => $m->message,
                'created_at' => $m->created_at,
                'admin_name' => $m->adminUser?->name,
            ]);

        return response()->json([
            'status'   => $session->status,
            'messages' => $messages,
        ]);
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
