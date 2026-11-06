<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\Vendor;
use App\Services\WeddingMetricsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PrintController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    /**
     * Render the official Editorial Master Wedding Guide (A4 Print & PDF Ready).
     */
    public function masterGuide(Request $request): View
    {
        $profile = $this->metricsService->getProfile();
        $summary = $this->metricsService->getDashboardSummary();

        $rundowns = $profile->rundowns()->get();
        $vendors = Vendor::query()->with('payments')->orderBy('category')->get();
        $guests = $profile->guests()->get();
        $budgetItems = BudgetItem::query()->with('vendor')->orderBy('category')->get();

        return view('print.master-guide', [
            'profile' => $profile,
            'summary' => $summary,
            'rundowns' => $rundowns,
            'vendors' => $vendors,
            'guests' => $guests,
            'budgetItems' => $budgetItems,
        ]);
    }
}
