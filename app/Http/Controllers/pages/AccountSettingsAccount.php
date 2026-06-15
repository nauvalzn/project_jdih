<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AccountSettingsAccount extends Controller
{
    public function index()
    {
        return view('content.pages.pages-account-settings-account');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Hapus avatar lama kalau ada
        if ($user->avatar && Storage::exists('public/avatars/'.$user->avatar)) {
            Storage::delete('public/avatars/'.$user->avatar);
        }

        // Simpan avatar baru
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = basename($path);
        $user->save();

        return redirect()->back()->with('success', 'Avatar berhasil diupdate!');
    }

    public function update(Request $request)
{
    $user = auth()->user();

    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->kontak = $request->input('kontak');
    $user->alamat = $request->input('alamat');
    // tambahin field lain kalau ada

    $user->save();

    return redirect()->route('pages-account-settings-account')->with('success', 'Profile updated!');
}
}