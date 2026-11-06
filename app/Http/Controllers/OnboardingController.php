<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OnboardingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()->weddingProfile()->exists()) {
            return redirect()->route('dashboard.index');
        }

        return view('onboarding.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bride_name' => ['required', 'string', 'max:120'],
            'groom_name' => ['required', 'string', 'max:120'],
            'wedding_date' => ['required', 'date', 'after:today'],
            'venue_name' => ['required', 'string', 'max:160'],
            'city' => ['required', 'string', 'max:80'],
            'total_budget' => ['required', 'integer', 'min:1000000'],
            'guest_target' => ['required', 'integer', 'min:10'],
        ]);

        $request->user()->weddingProfile()->create($validated + [
            'wedding_time' => '08:30 WIB (Akad) & 18:30 WIB (Resepsi)',
            'theme_concept' => 'Vogue Bridal Editorial — Warm Alabaster & Champagne Bronze',
        ]);

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Profil pernikahan berhasil dibuat. Selamat memulai perencanaan!');
    }
}
