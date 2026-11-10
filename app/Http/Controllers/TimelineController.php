<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\TimelinePhase;
use App\Enums\WeddingCategory;
use App\Models\Vendor;
use App\Models\WeddingRundown;
use App\Models\WeddingTask;
use App\Services\WeddingMetricsService;
use Illuminate\Database\Eloquent\Builder;
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
        $selectedPhase = is_string($request->query('phase')) ? $request->query('phase') : null;
        $milestoneOnly = $request->boolean('milestone_only');
        $searchQuery = is_string($request->query('search')) ? trim($request->query('search')) : '';
        $selectedSort = is_string($request->query('sort')) ? $request->query('sort') : null;

        $query = WeddingTask::query()->with('vendor');

        if ($selectedPhase !== null && $selectedPhase !== '') {
            $query->where('phase', $selectedPhase);
        }

        if ($milestoneOnly) {
            $query->where('is_milestone', true);
        }

        if ($searchQuery !== '') {
            $keyword = '%' . $searchQuery . '%';
            $query->where(function (Builder $subQuery) use ($keyword): void {
                $subQuery
                    ->where('title', 'like', $keyword)
                    ->orWhere('description', 'like', $keyword)
                    ->orWhere('assigned_to', 'like', $keyword)
                    ->orWhereHas('vendor', function (Builder $vendorQuery) use ($keyword): void {
                        $vendorQuery->where('name', 'like', $keyword);
                    });
            });
        }

        match ($selectedSort) {
            'due_date_desc' => $query->orderByDesc('due_date')->orderBy('id'),
            'priority_desc' => $query
                ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END ASC")
                ->orderBy('due_date')
                ->orderBy('id'),
            default => $query->orderBy('due_date')->orderBy('id'),
        };

        $tasks = $query->get();
        $summary = $this->metricsService->getDashboardSummary();
        $vendors = Vendor::query()->orderBy('name')->get();
        $rundowns = WeddingRundown::query()->orderBy('sort_order')->orderBy('start_time')->get();

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
            'searchQuery' => $searchQuery,
            'selectedSort' => $selectedSort,
            'rundowns' => $rundowns,
            'activeTab' => $request->query('tab', 'timeline'),
        ]);
    }

    public function create(): View
    {
        return view('timeline.form', [
            'task' => null,
            'categories' => WeddingCategory::cases(),
            'phases' => TimelinePhase::cases(),
            'vendors' => Vendor::query()->orderBy('name')->get(),
        ]);
    }

    public function createRundown(): View
    {
        return view('timeline.rundown');
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

    public function storeRundown(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_time' => ['required', 'string', 'max:10'],
            'end_time' => ['required', 'string', 'max:10'],
            'session_name' => ['required', 'string', 'max:80'],
            'activity' => ['required', 'string', 'max:200'],
            'location' => ['nullable', 'string', 'max:160'],
            'pic_name' => ['nullable', 'string', 'max:120'],
            'pic_phone' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string'],
        ]);

        WeddingRundown::query()->create($validated);

        return redirect()->route('timeline.index', ['tab' => 'rundown'])
            ->with('success', 'Agenda rundown Hari-H berhasil ditambahkan.');
    }
}
