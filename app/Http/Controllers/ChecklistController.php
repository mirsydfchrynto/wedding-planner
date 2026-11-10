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

final class ChecklistController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');
        $selectedStatus = $request->query('status');
        $selectedPic = $request->query('assigned_to');

        // Search and priority filters added for the Dinda checklist module.
        $selectedSearch = $request->query('search', '');
        $selectedSearch = is_string($selectedSearch) ? trim($selectedSearch) : '';

        $selectedPriority = $request->query('priority', '');
        $allowedPriorities = ['high', 'medium', 'low'];
        $selectedPriority = is_string($selectedPriority)
            && in_array($selectedPriority, $allowedPriorities, true)
                ? $selectedPriority
                : '';

        $query = WeddingTask::query()->with('vendor')->orderBy('due_date')->orderBy('id');

        if (is_string($selectedCategory) && $selectedCategory !== '') {
            $query->where('category', $selectedCategory);
        }

        if (is_string($selectedStatus) && $selectedStatus !== '') {
            $query->where('status', $selectedStatus);
        }

        if (is_string($selectedPic) && $selectedPic !== '') {
            $query->where('assigned_to', 'like', '%' . $selectedPic . '%');
        }

        if ($selectedSearch !== '') {
            $query->where('title', 'like', '%' . $selectedSearch . '%');
        }

        if ($selectedPriority !== '') {
            $query->where('priority', $selectedPriority);
        }

        $tasks = $query->get();
        $summary = $this->metricsService->getDashboardSummary();
        $vendors = Vendor::query()->orderBy('name')->get();
        $assignees = WeddingTask::query()->select('assigned_to')->distinct()->pluck('assigned_to');

        return view('checklist.index', [
            'summary' => $summary,
            'tasks' => $tasks,
            'categories' => WeddingCategory::cases(),
            'statuses' => TaskStatus::cases(),
            'phases' => TimelinePhase::cases(),
            'vendors' => $vendors,
            'assignees' => $assignees,
            'selectedCategory' => $selectedCategory,
            'selectedStatus' => $selectedStatus,
            'selectedPic' => $selectedPic,
            'selectedSearch' => $selectedSearch,
            'selectedPriority' => $selectedPriority,
        ]);
    }

    public function create(): View
    {
        return view('checklist.form', [
            'task' => null,
            'categories' => WeddingCategory::cases(),
            'statuses' => TaskStatus::cases(),
            'phases' => TimelinePhase::cases(),
            'vendors' => Vendor::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(WeddingTask $weddingTask): View
    {
        return view('checklist.form', [
            'task' => $weddingTask,
            'categories' => WeddingCategory::cases(),
            'statuses' => TaskStatus::cases(),
            'phases' => TimelinePhase::cases(),
            'vendors' => Vendor::query()->orderBy('name')->get(),
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
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'assigned_to' => ['required', 'string', 'max:64'],
            'is_milestone' => ['nullable', 'boolean'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
        ]);

        $validated['is_milestone'] = $request->boolean('is_milestone');
        $validated['completed_at'] = $validated['status'] === TaskStatus::Done->value ? now() : null;

        WeddingTask::query()->create($validated);

        return redirect()
            ->route('checklist.index')
            ->with('success', 'Daftar tugas persiapan baru berhasil ditambahkan.');
    }

    public function update(Request $request, WeddingTask $weddingTask): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['required', Rule::enum(WeddingCategory::class)],
            'phase' => ['required', Rule::enum(TimelinePhase::class)],
            'due_date' => ['required', 'date'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'assigned_to' => ['required', 'string', 'max:64'],
            'is_milestone' => ['nullable', 'boolean'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
        ]);

        $validated['is_milestone'] = $request->boolean('is_milestone');
        $validated['completed_at'] = $validated['status'] === TaskStatus::Done->value
            ? ($weddingTask->completed_at ?? now())
            : null;

        $weddingTask->update($validated);

        return redirect()
            ->route('checklist.index')
            ->with('success', 'Tugas "' . $weddingTask->title . '" berhasil diperbarui.');
    }

    public function toggle(WeddingTask $weddingTask): RedirectResponse
    {
        $nextStatus = $weddingTask->status === TaskStatus::Done
            ? TaskStatus::Todo
            : TaskStatus::Done;

        $weddingTask->update([
            'status' => $nextStatus,
            'completed_at' => $nextStatus === TaskStatus::Done ? now() : null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status tugas "' . $weddingTask->title . '" diubah menjadi ' . $nextStatus->label() . '.');
    }

    public function destroy(WeddingTask $weddingTask): RedirectResponse
    {
        $title = $weddingTask->title;
        $weddingTask->delete();

        return redirect()
            ->route('checklist.index')
            ->with('success', 'Tugas "' . $title . '" telah dihapus dari daftar checklist.');
    }
}
