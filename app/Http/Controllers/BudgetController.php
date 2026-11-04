<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\WeddingCategory;
use App\Http\Requests\StoreBudgetItemRequest;
use App\Http\Requests\UpdateBudgetItemRequest;
use App\Models\BudgetItem;
use App\Models\Vendor;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class BudgetController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');
        $selectedStatus = $request->query('status');

        $query = BudgetItem::query()->with('vendor')->orderBy('category')->orderBy('id');

        if (is_string($selectedCategory) && $selectedCategory !== '') {
            $query->where('category', $selectedCategory);
        }

        $items = $query->get();

        if (is_string($selectedStatus) && $selectedStatus !== '') {
            $items = $items->filter(function (BudgetItem $item) use ($selectedStatus): bool {
                return match ($selectedStatus) {
                    'overbudget' => $item->is_overbudget,
                    'unpaid' => $item->remaining_balance > 0,
                    'paid' => $item->remaining_balance === 0 && ($item->actual_cost > 0 || $item->estimated_cost > 0),
                    default => true,
                };
            })->values();
        }

        $summary = $this->metricsService->getDashboardSummary();
        $vendors = Vendor::query()->orderBy('name')->get();

        return view('budget.index', [
            'summary' => $summary,
            'profile' => $summary['profile'],
            'items' => $items,
            'categories' => WeddingCategory::cases(),
            'vendors' => $vendors,
            'selectedCategory' => $selectedCategory,
            'selectedStatus' => $selectedStatus,
        ]);
    }

    public function store(StoreBudgetItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['actual_cost'] = (int) ($validated['actual_cost'] ?? 0);
        $validated['paid_amount'] = (int) ($validated['paid_amount'] ?? 0);

        BudgetItem::query()->create($validated);

        return redirect()
            ->route('budget.index')
            ->with('success', 'Komponen anggaran berhasil ditambahkan ke buku anggaran.');
    }

    public function update(UpdateBudgetItemRequest $request, BudgetItem $budgetItem): RedirectResponse
    {
        $budgetItem->update($request->validated());

        return redirect()
            ->route('budget.index')
            ->with('success', 'Rincian pos anggaran "' . $budgetItem->title . '" berhasil diperbarui.');
    }

    public function updateCap(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'total_budget' => ['required', 'integer', 'min:1000000'],
        ]);

        $profile = $this->metricsService->getProfile();
        $profile->update([
            'total_budget' => (int) $validated['total_budget'],
        ]);

        return redirect()
            ->route('budget.index')
            ->with('success', 'Plafon total anggaran pernikahan berhasil diperbarui.');
    }

    public function destroy(BudgetItem $budgetItem): RedirectResponse
    {
        $title = $budgetItem->title;
        $budgetItem->delete();

        return redirect()
            ->route('budget.index')
            ->with('success', 'Pos anggaran "' . $title . '" telah dihapus.');
    }
}
