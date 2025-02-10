<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Password;
use Str;
use View;

class AuthController extends Controller
{
    // Menampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect('/');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Cek apakah checkbox "Remember Me" dicentang

        if (Auth::attempt($credentials, $remember)) {
            return redirect('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect("/login");
    }

    public function forgotPasswordView(Request $request)
    {
        return view('auth.forgot-password');
    }

    public function postForgotPassword(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            credentials: $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPasswordView(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function postResetPassword(ResetPasswordRequest $request): RedirectResponse
    {

        $status = Password::reset(
            credentials: $request->only('email', 'password', 'password_confirmation', 'token'),
            callback: function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}