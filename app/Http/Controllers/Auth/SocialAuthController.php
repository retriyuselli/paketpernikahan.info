<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        if ($request->filled('redirect')) {
            $request->session()->put('url.intended', $request->query('redirect'));
        }
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

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

        return redirect()->intended(route('dashboard'));
    }
}
