<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Spatie\Permission\Models\Role;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        if ($request->filled('redirect')) {
            $request->session()->put('url.intended', $request->query('redirect'));
        }
        if ($request->query('source') === 'app') {
            $request->session()->put('oauth_source', 'app');
        }
        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            return redirect()
                ->route('auth.google')
                ->with('login_error', 'Sesi login Google tidak valid / sudah kedaluwarsa. Silakan coba lagi.');
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName(),
                'password'          => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
            ]
        );

        // Always sync avatar and verify email on Google login
        $user->update([
            'avatar_url'        => $googleUser->getAvatar(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        // Assign default role for new users
        if ($user->wasRecentlyCreated && !$user->hasAnyRole(['super_admin', 'admin', 'vendor', 'pengunjung'])) {
            Role::findOrCreate('pengunjung', 'web');
            $user->assignRole('pengunjung');
        }

        Auth::login($user, true);

        if (session()->pull('oauth_source') === 'app') {
            $token = \Illuminate\Support\Str::random(64);
            cache()->put('app_login_token_' . $token, $user->id, now()->addMinutes(5));
            return redirect()->to('paketpernikahan://auth-success?token=' . $token);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function appleSignIn(Request $request)
    {
        $identityToken = $request->input('identity_token');
        $givenName     = $request->input('given_name');
        $familyName    = $request->input('family_name');

        if (!$identityToken) {
            return response()->json(['error' => 'Token tidak valid'], 422);
        }

        try {
            $appleKeys = Http::timeout(10)->get('https://appleid.apple.com/auth/keys')->json();
            $decoded   = JWT::decode($identityToken, JWK::parseKeySet($appleKeys));

            if (($decoded->iss ?? '') !== 'https://appleid.apple.com') {
                throw new \Exception('Invalid issuer');
            }
            if (($decoded->aud ?? '') !== 'id.co.paketpernikahan.app') {
                throw new \Exception('Invalid audience');
            }

            $appleId = $decoded->sub;
            $email   = $decoded->email ?? null;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Verifikasi token gagal: ' . $e->getMessage()], 422);
        }

        // Find user by apple_id, then by email
        $user = User::where('apple_id', $appleId)->first();

        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            if (!$email) {
                return response()->json(['error' => 'Email tidak tersedia, silakan coba lagi.'], 422);
            }
            $name = trim(($givenName ?? '') . ' ' . ($familyName ?? '')) ?: 'Apple User';
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'password'          => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
                'apple_id'          => $appleId,
            ]);
            Role::findOrCreate('pengunjung', 'web');
            $user->assignRole('pengunjung');
        } else {
            $updates = [];
            if (!$user->apple_id) $updates['apple_id'] = $appleId;
            if (!$user->email_verified_at) $updates['email_verified_at'] = now();
            if ($updates) $user->update($updates);
        }

        $token = Str::random(64);
        cache()->put('app_login_token_' . $token, $user->id, now()->addMinutes(5));

        return response()->json(['redirect' => route('auth.app.token') . '?token=' . $token]);
    }

    public function loginWithAppToken(\Illuminate\Http\Request $request)
    {
        $token = $request->query('token');
        $userId = $token ? cache()->pull('app_login_token_' . $token) : null;

        if (!$userId) {
            return redirect()->route('login')->with('login_error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        Auth::login($user, true);
        return redirect()->intended(route('dashboard'));
    }
}
