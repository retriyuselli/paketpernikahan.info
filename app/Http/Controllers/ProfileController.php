<?php

namespace App\Http\Controllers;

use App\Models\VendorBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function updateName(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $request->user()->update(['name' => $data['name']]);

        return redirect()->route('dashboard', ['#setting'])
            ->with('setting_success', 'Nama berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard', ['#setting'])
            ->with('setting_success', 'Password berhasil diperbarui.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:1024'],
        ]);

        $user = $request->user();

        if ($user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar_url' => $path]);

        return redirect()->route('dashboard', ['#setting'])
            ->with('setting_success', 'Foto profil berhasil diperbarui.');
    }

    public function updateWhatsapp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'whatsapp' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\s.-]{8,30}$/'],
        ]);

        $normalized = VendorBooking::normalizeWhatsappNumber($data['whatsapp'] ?? '');
        if (filled($data['whatsapp']) && (strlen($normalized) < 10 || strlen($normalized) > 15 || !str_starts_with($normalized, '62'))) {
            return back()
                ->withErrors(['whatsapp' => 'Nomor WhatsApp tidak valid.'])
                ->withInput();
        }

        $request->user()->update([
            'whatsapp' => filled($data['whatsapp']) ? $normalized : null,
        ]);

        return redirect()->route('dashboard.pengaturan')
            ->with('setting_success', 'Nomor WhatsApp berhasil diperbarui.');
    }

    public function deleteAccount(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Delete avatar from storage
        if ($user->avatar_url && !str_starts_with($user->avatar_url, 'http')) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $user->delete();

        return redirect()->route('home')
            ->with('status', 'Akun kamu telah berhasil dihapus.');
    }
}
