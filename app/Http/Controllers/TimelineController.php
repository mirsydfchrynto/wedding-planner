<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\TimelinePhase;
use App\Enums\WeddingCategory;
use App\Models\Vendor;
use App\Models\WeddingTask;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class TimelineController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(Request $request): View
    {
        $selectedPhase = $request->query('phase');
        $milestoneOnly = $request->boolean('milestone_only');

        $query = WeddingTask::query()->with('vendor')->orderBy('due_date')->orderBy('id');

        if (is_string($selectedPhase) && $selectedPhase !== '') {
            $query->where('phase', $selectedPhase);
        }

        if ($milestoneOnly) {
            $query->where('is_milestone', true);
        }

        $tasks = $query->get();
        $summary = $this->metricsService->getDashboardSummary();
        $vendors = Vendor::query()->orderBy('name')->get();

        $phases = collect(TimelinePhase::cases())->map(function (TimelinePhase $phase) use ($tasks): array {
            $phaseTasks = $tasks->where('phase', $phase->value)->values();
            $total = $phaseTasks->count();
            $done = $phaseTasks->where('status', TaskStatus::Done)->count();

            return [
                'enum' => $phase,
                'name' => $phase->value,
                'subtitle' => $phase->subtitle(),
                'tasks' => $phaseTasks,
                'total' => $total,
                'done' => $done,
                'percent' => $total > 0 ? (int) round(($done / $total) * 100) : 0,
            ];
        });

        return view('timeline.index', [
            'summary' => $summary,
            'phases' => $phases,
            'allPhases' => TimelinePhase::cases(),
            'categories' => WeddingCategory::cases(),
            'statuses' => TaskStatus::cases(),
            'vendors' => $vendors,
            'selectedPhase' => $selectedPhase,
            'milestoneOnly' => $milestoneOnly,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['required', Rule::enum(WeddingCategory::class)],
            'phase' => ['required', Rule::enum(TimelinePhase::class)],
            'due_date' => ['required', 'date'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'assigned_to' => ['required', 'string', 'max:64'],
            'is_milestone' => ['nullable', 'boolean'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
        ]);

        $validated['status'] = TaskStatus::Todo->value;
        $validated['is_milestone'] = $request->boolean('is_milestone', true);

        WeddingTask::query()->create($validated);

        return redirect()
            ->route('timeline.index')
            ->with('success', 'Milestone linimasa persiapan berhasil dijadwalkan.');
    }

    public function updateStatus(Request $request, WeddingTask $weddingTask): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ]);

        $weddingTask->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === TaskStatus::Done->value ? now() : null,
        ]);

        return redirect()
            ->route('timeline.index')
            ->with('success', 'Progres linimasa "' . $weddingTask->title . '" berhasil diperbarui.');
    }
}
