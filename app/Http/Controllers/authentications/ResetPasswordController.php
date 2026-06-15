<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Show reset password form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        
        return view('content.authentications.auth-reset-password-basic', [
            'token' => $token,
            'email' => $request->email,
            'pageConfigs' => ['myLayout' => 'blank'],
        ]);
    }

    /**
     * Handle reset password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Pastikan redirect ke halaman login yang ada
            return redirect()
                ->route('auth-login-basic') // ganti dengan nama route login yang ada di projek kamu
                ->with('status', __($status));
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}
