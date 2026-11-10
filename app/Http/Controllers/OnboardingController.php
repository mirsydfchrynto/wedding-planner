<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingProfile;
use App\Services\BudgetAllocationService;
use App\Services\TimelineGeneratorService;
use App\Support\WeddingMasterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class OnboardingController extends Controller
{
    public function __construct(
        private readonly BudgetAllocationService $budget,
        private readonly TimelineGeneratorService $timeline,
    ) {}

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
            'adat_tradition' => ['nullable', Rule::in(WeddingMasterData::WEDDING_TRADITIONS)],
        ]);

        $profile = DB::transaction(function () use ($request, $validated): WeddingProfile {
            $profile = $request->user()->weddingProfile()->create([
                'bride_name' => $validated['bride_name'],
                'groom_name' => $validated['groom_name'],
                'wedding_date' => $validated['wedding_date'],
                'venue_name' => $validated['venue_name'],
                'city' => $validated['city'],
                'total_budget' => $validated['total_budget'],
                'guest_target' => $validated['guest_target'],
                'wedding_time' => '08:30 WIB (Akad) & 18:30 WIB (Resepsi)',
                'theme_concept' => 'Vogue Bridal Editorial — Warm Alabaster & Champagne Bronze',
            ]);

            WeddingProfile::useTenancy($profile->id);

            try {
                $this->budget->allocate($profile);
                $this->timeline->generate($profile, $validated['adat_tradition'] ?? 'none');
            } finally {
                WeddingProfile::useTenancy(null);
            }

            return $profile;
        });

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Profil pernikahan dibuat: '.$profile->budgetItems()->count().' pos anggaran dan '.$profile->tasks()->count().' tugas checklist siap diubah.');
    }
}
