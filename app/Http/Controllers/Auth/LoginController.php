<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Honour ?redirect= param stored in session or query string
            $redirectTo = $request->session()->pull('url.intended',
                $request->query('redirect', route('dashboard'))
            );

            // Validate redirect URL stays on same host (prevent open redirect)
            $host = parse_url($redirectTo, PHP_URL_HOST);
            if ($host && $host !== $request->getHost()) {
                $redirectTo = route('dashboard');
            }

            return redirect($redirectTo);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
