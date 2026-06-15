<?php

namespace App\Http\Controllers\authentications;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class AuthController extends Controller
{
    // 🟢 REGISTER USER BARU
    // 🟢 REGISTER USER BARU
public function register(Request $request)
{
    // Validasi form biasa + captcha Mews
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:operator,admin',
        'nip' => 'required|string|max:20|unique:users',
        'captcha' => 'required|captcha' // <-- Mews captcha validasi
    ]);

    // ✅ Buat user baru
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'nip' => $request->nip,
        'password' => Hash::make($request->password),
        'role' => $request->role ?? 'operator',
    ]);

    Auth::login($user);

    return redirect()->route('dashboard-analytics-pages')
        ->with('success', 'Register sukses, selamat datang!');
}


    // 🟡 LOGIN USER
    public function login(Request $request)
    {
        // Validasi input awal
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ]);

        // // ✅ Verifikasi reCAPTCHA manual
        // $token = $request->input('g-recaptcha-response');
        // $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret'   => config('captcha.secret'),
        //     'response' => $token,
        //     'remoteip' => $request->ip(),
        // ])->json();

        // if (!($verify['success'] ?? false)) {
        //     return back()->withErrors([
        //         'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal, coba lagi ya.',
        //     ])->withInput();
        // }

        // ✅ Cek kredensial login
        $credentials = $request->only('nip', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (in_array($user->role, ['admin', 'operator'])) {
                return redirect()->route('dashboard-analytics-pages');
            } else {
                Auth::logout();
                return back()->withErrors(['role' => 'Role tidak dikenali.']);
            }
        }

        return back()->withErrors(['nip' => 'NIP atau password salah.']);
    }

    // 🔴 LOGOUT USER
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth-login-basic');
    }

    // 📄 HALAMAN REGISTER
    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-register-basic', compact('pageConfigs'));
    }

    // 📄 HALAMAN LOGIN
    public function showLoginForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-login-basic', compact('pageConfigs'));
    }
}
