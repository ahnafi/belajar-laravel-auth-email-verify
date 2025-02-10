<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\AuthController;

// Route untuk tampilan
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Route untuk proses
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk halaman yang dilindungi auth
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    });
});

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

// Route untuk menangani verifikasi email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Route untuk mengirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $user = Auth::user();
    $user->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware("guest")->group(function () {
    Route::get('/forgot-password', [AuthController::class, "forgotPasswordView"])->name('password.request');

    Route::post('/forgot-password', [AuthController::class, "postForgotPassword"])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, "resetPasswordView"])->name('password.reset');

    Route::post('/reset-password', [AuthController::class, "postResetPassword"])->name('password.update');
});