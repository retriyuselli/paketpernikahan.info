<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha'  => ['required', 'integer'],
            'agree'    => ['accepted'],
        ]);

        // Verify CAPTCHA
        if ((int) $request->captcha !== (int) $request->captcha_answer) {
            return back()->withErrors(['captcha' => 'Jawaban CAPTCHA salah.'])->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('pengunjung');

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('email', $user->email);
    }
}
