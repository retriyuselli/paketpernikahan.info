<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    /**
     * Simpan / update device token dari app mobile.
     * Dipanggil saat user membuka app dan push permission di-grant.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => 'required|string|max:500',
            'platform' => 'nullable|in:ios,android',
        ]);

        $userId   = Auth::id(); // null jika belum login
        $platform = $request->input('platform', 'ios');

        DeviceToken::updateOrCreate(
            ['token' => $request->input('token')],
            ['user_id' => $userId, 'platform' => $platform],
        );

        return response()->json(['message' => 'Token berhasil disimpan.']);
    }

    /**
     * Hapus device token (misalnya saat user logout dari app).
     */
    public function unregister(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string|max:500',
        ]);

        DeviceToken::where('token', $request->input('token'))->delete();

        return response()->json(['message' => 'Token berhasil dihapus.']);
    }

    /**
     * Kirim notifikasi test ke device milik user yang sedang login.
     * Hanya untuk keperluan development/debug.
     */
    public function sendTest(Request $request, PushNotificationService $push): JsonResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'body'  => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        if (! $userId) {
            return response()->json(['message' => 'Harus login terlebih dahulu.'], 401);
        }

        $tokenCount = DeviceToken::where('user_id', $userId)->count();

        if ($tokenCount === 0) {
            return response()->json(['message' => 'Tidak ada device token untuk user ini.'], 422);
        }

        $push->sendToUser(
            userId: $userId,
            title:  $request->input('title', 'Test Notifikasi'),
            body:   $request->input('body', 'Notifikasi berhasil dikirim dari server!'),
            data:   ['type' => 'test'],
        );

        return response()->json(['message' => "Notifikasi dikirim ke {$tokenCount} device."]);
    }
}
