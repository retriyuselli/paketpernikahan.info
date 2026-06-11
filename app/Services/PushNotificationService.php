<?php

namespace App\Services;

use App\Models\DeviceToken;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
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
     * @return array{sent: int, failed: int}
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): array
    {
        $tokens = DeviceToken::where('user_id', $userId)->pluck('token');

        $result = ['sent' => 0, 'failed' => 0];

        foreach ($tokens as $token) {
            $this->sendToToken($token, $title, $body, $data)
                ? $result['sent']++
                : $result['failed']++;
        }

        return $result;
    }

    /**
     * Kirim notifikasi ke satu FCM token.
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        try {
            $message = CloudMessage::fromArray([
                'token'        => $token,
                'notification' => ['title' => $title, 'body' => $body],
                'data'         => array_map('strval', $data),
            ]);

            $this->messaging->send($message);

            return true;
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

            return false;
        }
    }

    /**
     * Kirim notifikasi broadcast ke semua device yang terdaftar.
     *
     * @return array{sent: int, failed: int}
     */
    public function broadcast(string $title, string $body, array $data = []): array
    {
        $result = ['sent' => 0, 'failed' => 0];

        DeviceToken::chunk(500, function ($tokens) use ($title, $body, $data, &$result) {
            foreach ($tokens as $deviceToken) {
                $this->sendToToken($deviceToken->token, $title, $body, $data)
                    ? $result['sent']++
                    : $result['failed']++;
            }
        });

        return $result;
    }

    private function isInvalidToken(string $errorMessage): bool
    {
        return str_contains($errorMessage, 'UNREGISTERED')
            || str_contains($errorMessage, 'INVALID_ARGUMENT')
            || str_contains($errorMessage, 'registration-token-not-registered')
            || str_contains($errorMessage, 'not a valid FCM registration token')
            || str_contains($errorMessage, 'Requested entity was not found');
    }
}
