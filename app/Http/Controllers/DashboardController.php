<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(): View
    {
        $summary = $this->metricsService->getDashboardSummary();

        return view('dashboard.index', [
            'summary' => $summary,
            'profile' => $summary['profile'],
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bride_name' => ['required', 'string', 'max:120'],
            'groom_name' => ['required', 'string', 'max:120'],
            'wedding_date' => ['required', 'date'],
            'wedding_time' => ['required', 'string', 'max:80'],
            'venue_name' => ['required', 'string', 'max:160'],
            'city' => ['required', 'string', 'max:80'],
            'total_budget' => ['required', 'integer', 'min:1000000'],
            'guest_target' => ['required', 'integer', 'min:10'],
            'theme_concept' => ['required', 'string', 'max:200'],
        ]);

        $profile = $this->metricsService->getProfile();
        $profile->update($validated);

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Profil pernikahan & target anggaran induk berhasil diperbarui.');
    }
}
