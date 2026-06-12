<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->assignDefaultRole($user);

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            report($e); // registrasi tetap berhasil walau email verifikasi gagal terkirim
        }

        return $this->tokenResponse($user, $request->input('device_name', 'ios'), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $this->tokenResponse($user, $request->input('device_name', 'ios'));
    }

    /**
     * Sign in with Apple — menerima identity token dari ASAuthorization
     * di aplikasi iOS, verifikasi terhadap kunci publik Apple, lalu
     * terbitkan token Sanctum. Logika sama dengan SocialAuthController
     * versi web, hanya hasil akhirnya token (bukan redirect).
     */
    public function apple(Request $request): JsonResponse
    {
        $request->validate([
            'identity_token' => ['required', 'string'],
            'given_name'     => ['nullable', 'string', 'max:100'],
            'family_name'    => ['nullable', 'string', 'max:100'],
            'device_name'    => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $appleKeys = Http::timeout(10)->get('https://appleid.apple.com/auth/keys')->json();
            $decoded   = JWT::decode($request->identity_token, JWK::parseKeySet($appleKeys));

            if (($decoded->iss ?? '') !== 'https://appleid.apple.com') {
                throw new \Exception('Invalid issuer');
            }
            if (($decoded->aud ?? '') !== 'id.co.paketpernikahan.app') {
                throw new \Exception('Invalid audience');
            }

            $appleId = $decoded->sub;
            $email   = $decoded->email ?? null;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Verifikasi token Apple gagal.'], 422);
        }

        $user = User::where('apple_id', $appleId)->first();

        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            if (!$email) {
                return response()->json(['message' => 'Email tidak tersedia, silakan coba lagi.'], 422);
            }
            $name = trim(($request->given_name ?? '') . ' ' . ($request->family_name ?? '')) ?: 'Apple User';
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'password'          => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
                'apple_id'          => $appleId,
            ]);
            $this->assignDefaultRole($user);
        } else {
            $updates = [];
            if (!$user->apple_id) $updates['apple_id'] = $appleId;
            if (!$user->email_verified_at) $updates['email_verified_at'] = now();
            if ($updates) $user->update($updates);
        }

        return $this->tokenResponse($user, $request->input('device_name', 'ios'));
    }

    /**
     * Sign in with Google — menerima ID token dari Google Sign-In SDK
     * di aplikasi iOS, verifikasi ke endpoint tokeninfo Google.
     */
    public function google(Request $request): JsonResponse
    {
        $request->validate([
            'id_token'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $payload = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->id_token,
        ])->json();

        $allowedAudiences = array_filter([
            config('services.google.client_id'),
            config('services.google.ios_client_id'),
        ]);

        $validIssuer = in_array($payload['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true);

        if (
            !$payload
            || !$validIssuer
            || !in_array($payload['aud'] ?? '', $allowedAudiences, true)
            || empty($payload['email'])
        ) {
            return response()->json(['message' => 'Verifikasi token Google gagal.'], 422);
        }

        $user = User::firstOrCreate(
            ['email' => $payload['email']],
            [
                'name'              => $payload['name'] ?? $payload['email'],
                'password'          => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
            ]
        );

        $user->update([
            'avatar_url'        => $payload['picture'] ?? $user->avatar_url,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        if ($user->wasRecentlyCreated) {
            $this->assignDefaultRole($user);
        }

        return $this->tokenResponse($user, $request->input('device_name', 'ios'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout.']);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    private function assignDefaultRole(User $user): void
    {
        if (!$user->hasAnyRole(['super_admin', 'admin', 'vendor', 'pengunjung'])) {
            Role::findOrCreate('pengunjung', 'web');
            $user->assignRole('pengunjung');
        }
    }

    private function tokenResponse(User $user, string $deviceName, int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => [
                'token' => $user->createToken($deviceName)->plainTextToken,
                'user'  => new UserResource($user),
            ],
        ], $status);
    }
}
