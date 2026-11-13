<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        if (! config('services.google.client_id')) {
            return redirect()->route('login')->with('info', 'Google OAuth credentials belum terpasang. Gunakan Akses Instan.');
        }

        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['Gagal terhubung dengan server Google: ' . $e->getMessage()]);
        }

        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?? 'Calon Pengantin';

        $existingUser = User::query()->where('email', $email)->first();
        if ($existingUser !== null && $existingUser->role !== 'customer') {
            abort(403, 'Gunakan email akun pengantin untuk masuk ke workspace.');
        }

        $user = $existingUser ?? User::query()->create([
            'email' => $email,
            'name' => $name,
            'password' => Hash::make(Str::random(24)),
            'role' => 'customer',
            'google_id' => $googleUser->getId() ?: 'google-oauth-'.substr(sha1($email), 0, 10),
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()
            ->intended($user->weddingProfile()->exists() ? route('dashboard.index') : route('onboarding.index'))
            ->with('success', 'Berhasil masuk dengan akun Google (' . $user->email . ').');
    }

    /**
     * Authenticate customer via Google OAuth flow (supports real Google OAuth credentials or instant Google account sign-in).
     */
    public function loginWithGoogle(Request $request): RedirectResponse
    {
        abort_unless(config('services.google.demo_login'), 403, 'Google Sign-In belum dikonfigurasi pada lingkungan ini.');

        $validated = $request->validate([
            'google_email' => ['nullable', 'email', 'max:140'],
            'google_name' => ['nullable', 'string', 'max:140'],
        ]);

        $email = $validated['google_email'] ?? 'aurelia.reza@gmail.com';
        $name = $validated['google_name'] ?? 'Aurelia Larasati & Reza Mahendra';

        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser !== null && $existingUser->role !== 'customer') {
            abort(403, 'Gunakan email akun pengantin untuk masuk ke workspace.');
        }

        $user = $existingUser ?? User::query()->create([
            'email' => $email,
            'name' => $name,
            'password' => Hash::make(Str::random(24)),
            'role' => 'customer',
            'google_id' => 'google-oauth-'.substr(sha1($email), 0, 10),
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()
            ->intended(route('dashboard.index'))
            ->with('success', 'Berhasil masuk dengan akun Google ('.$user->email.'). Selamat datang di Portal Pengantin.');
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Sesi Anda telah diakhiri.');
    }
}
