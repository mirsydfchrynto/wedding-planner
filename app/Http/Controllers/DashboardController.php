<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingTask;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function editProfile(): View
    {
        $summary = $this->metricsService->getDashboardSummary();

        return view('dashboard.profile', [
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
        $oldDate = $profile->wedding_date ? Carbon::parse($profile->wedding_date) : null;
        $newDate = Carbon::parse($validated['wedding_date']);

        $profile->update($validated);

        // Recalculate task due dates proportionally so H-X stays relative to the new date
        if ($oldDate && ! $oldDate->isSameDay($newDate)) {
            $diffDays = (int) $oldDate->startOfDay()->diffInDays($newDate->startOfDay(), false);
            if ($diffDays !== 0) {
                $tasks = WeddingTask::query()->get();
                foreach ($tasks as $task) {
                    $task->update([
                        'due_date' => $task->due_date->copy()->addDays($diffDays),
                    ]);
                }
            }
        }

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Profil pernikahan & jadwal relatif tugas berhasil diperbarui.');
    }
}
