<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
