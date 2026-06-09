<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserBlockController extends Controller
{
    public function block(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Tidak dapat memblokir diri sendiri.');
        }

        UserBlock::firstOrCreate([
            'blocker_id' => $request->user()->id,
            'blocked_id' => $user->id,
        ]);

        return back()->with('block_success', 'Pengguna telah diblokir dan kontennya disembunyikan.');
    }

    public function unblock(Request $request, User $user): RedirectResponse
    {
        UserBlock::where('blocker_id', $request->user()->id)
            ->where('blocked_id', $user->id)
            ->delete();

        return back()->with('block_success', 'Blokir pengguna telah dicabut.');
    }
}
