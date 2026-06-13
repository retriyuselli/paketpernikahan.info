<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ChatMessageResource;
use App\Http\Resources\Api\V1\ChatSessionResource;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\VendorPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /** Daftar sesi chat milik user yang sedang login. */
    public function index(Request $request)
    {
        $sessions = ChatSession::where('user_id', $request->user()->id)
            ->where('type', 'public')
            ->with(['vendor', 'vendorPackage', 'latestMessage.adminUser:id,name'])
            ->orderByDesc('updated_at')
            ->get();

        return ChatSessionResource::collection($sessions);
    }

    /**
     * Mulai sesi chat baru. Logika sama dengan ChatController::start
     * versi web — user yang login memakai namanya sebagai guest_name.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vendor_id'  => ['nullable', 'integer', 'exists:vendors,id'],
            'package_id' => ['nullable', 'integer', 'exists:vendor_packages,id'],
        ]);

        $package  = !empty($data['package_id']) ? VendorPackage::find($data['package_id']) : null;
        $vendorId = $package?->vendor_id ?: ($data['vendor_id'] ?? null);

        $session = ChatSession::create([
            'user_id'           => $request->user()->id,
            'guest_name'        => $request->user()->name,
            'session_token'     => Str::random(48),
            'status'            => 'open',
            'vendor_id'         => $vendorId,
            'vendor_package_id' => $package?->id,
        ]);

        // Pesan selamat datang otomatis dari admin (sama dengan web)
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender'          => 'admin',
            'message'         => 'Halo ' . $session->guest_name . '! 👋 Selamat datang di Makna Wedding. Ada yang bisa kami bantu?',
        ]);

        $session->load(['vendor', 'vendorPackage', 'latestMessage']);

        return (new ChatSessionResource($session))
            ->response()
            ->setStatusCode(201);
    }

    /** Detail sesi + pesan; dukung ?after=<id> untuk polling pesan baru. */
    public function show(Request $request, string $token)
    {
        $session = $this->ownedSession($request, $token);

        $messages = ChatMessage::where('chat_session_id', $session->id)
            ->where('id', '>', (int) $request->query('after', 0))
            ->with(['adminUser:id,name', 'vendorPackage'])
            ->orderBy('id')
            ->get();

        $session->load(['vendor', 'vendorPackage']);

        return response()->json([
            'data' => [
                'session'  => new ChatSessionResource($session),
                'messages' => ChatMessageResource::collection($messages),
            ],
        ]);
    }

    /** Kirim pesan ke sesi milik sendiri. */
    public function send(Request $request, string $token)
    {
        $data = $request->validate([
            'message'    => ['required', 'string', 'max:2000'],
            'package_id' => ['nullable', 'integer', 'exists:vendor_packages,id'],
        ]);

        $session = $this->ownedSession($request, $token);

        if ($session->status !== 'open') {
            return response()->json(['message' => 'Sesi chat sudah ditutup.'], 409);
        }

        $message = ChatMessage::create([
            'chat_session_id'   => $session->id,
            'sender'            => 'guest',
            'message'           => strip_tags(trim($data['message'])),
            'vendor_package_id' => $data['package_id'] ?? null,
        ]);

        $session->touch();

        return (new ChatMessageResource($message))
            ->response()
            ->setStatusCode(201);
    }

    private function ownedSession(Request $request, string $token): ChatSession
    {
        return ChatSession::where('session_token', $token)
            ->where('user_id', $request->user()->id)
            ->where('type', 'public')
            ->firstOrFail();
    }
}
