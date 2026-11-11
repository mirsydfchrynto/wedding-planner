<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class AdminController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            // Jika sedang login sebagai pengguna biasa, akhiri sesi agar form login admin dapat terbuka:
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (! $user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                abort(403, 'Akun ini bukan administrator sistem.');
            }

            $request->session()->regenerate();

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Selamat datang di Konsol Administrator Tatanikah.');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi administrator salah.',
        ])->onlyInput('email');
    }

    public function dashboard(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['Akses khusus Administrator. Silakan login menggunakan akun staf admin.']);
        }

        abort_unless(Auth::user()->isAdmin(), 403, 'Akses terbatas khusus Administrator.');
        $totalUsers = User::query()->where('role', 'customer')->count();
        $totalProfiles = WeddingProfile::query()->count();
        $totalBudgetManaged = (int) WeddingProfile::query()->sum('total_budget');
        $totalVendors = Vendor::query()->count();
        $totalTasks = WeddingTask::query()->count();

        $profiles = WeddingProfile::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalProfiles' => $totalProfiles,
            'totalBudgetManaged' => $totalBudgetManaged,
            'totalVendors' => $totalVendors,
            'totalTasks' => $totalTasks,
            'profiles' => $profiles,
        ]);
    }

    public function destroyProfile(WeddingProfile $profile): RedirectResponse
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $names = $profile->couple_names;
        $profile->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Buku kerja pengantin "' . $names . '" berhasil dihapus dari platform.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Sesi Administrator telah diakhiri.');
    }
}
