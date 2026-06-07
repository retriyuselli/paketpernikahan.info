<?php

namespace App\Services;

use App\Models\DeviceToken;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    public function __construct(private Messaging $messaging) {}

    /**
     * Kirim notifikasi ke semua device milik satu user.
     *
     * @param  int    $userId
     * @param  string $title
     * @param  string $body
     * @param  array  $data  Payload tambahan (url, type, id, dll)
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = DeviceToken::where('user_id', $userId)->pluck('token');

        if ($tokens->isEmpty()) {
            return;
        }

        foreach ($tokens as $token) {
            $this->sendToToken($token, $title, $body, $data);
        }
    }

    /**
     * Kirim notifikasi ke satu FCM token.
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body))
                ->withData(array_map('strval', $data));

            $this->messaging->send($message);
        } catch (\Throwable $e) {
            Log::error('[Push] Gagal kirim ke token', [
                'token' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage(),
            ]);

            // Hapus token yang tidak valid agar tidak menumpuk
            if ($this->isInvalidToken($e->getMessage())) {
                DeviceToken::where('token', $token)->delete();
                Log::info('[Push] Token tidak valid dihapus.', ['token' => substr($token, 0, 20) . '...']);
            }
        }
    }

    /**
     * Kirim notifikasi broadcast ke semua device yang terdaftar.
     */
    public function broadcast(string $title, string $body, array $data = []): void
    {
        DeviceToken::chunk(500, function ($tokens) use ($title, $body, $data) {
            foreach ($tokens as $deviceToken) {
                $this->sendToToken($deviceToken->token, $title, $body, $data);
            }
        });
    }

    private function isInvalidToken(string $errorMessage): bool
    {
        return str_contains($errorMessage, 'UNREGISTERED')
            || str_contains($errorMessage, 'INVALID_ARGUMENT')
            || str_contains($errorMessage, 'registration-token-not-registered');
    }
}
