<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TaskStatus;
use App\Enums\TimelinePhase;
use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\BudgetItem;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

final class WeddingMetricsService
{
    public function getProfile(): WeddingProfile
    {
        if ($profile = WeddingProfile::current()) {
            return $profile;
        }

        $user = Auth::user();

        // ponytail: customer mid-onboarding gets an unsaved profile so tenant data never leaks; swap for a nullable return if views need to branch.
        if ($user && ! $user->isAdmin()) {
            return new WeddingProfile();
        }

        return WeddingProfile::query()->orderBy('id')->first()
            ?? WeddingProfile::query()->create([
                'bride_name' => 'Aurelia Larasati',
                'groom_name' => 'Reza Mahendra',
                'wedding_date' => now()->addDays(145)->toDateString(),
                'wedding_time' => '08:30 WIB',
                'venue_name' => 'The Glasshouse at Plataran Menteng',
                'city' => 'Jakarta Pusat',
                'total_budget' => 350000000,
                'guest_target' => 450,
                'theme_concept' => 'Vogue Bridal Editorial — Warm Alabaster & Champagne Bronze',
            ]);
    }

    /**
     * Compute comprehensive summary metrics for Dashboard, Budget, Checklist, Timeline, and Vendors.
     *
     * @return array<string, mixed>
     */
    public function getDashboardSummary(): array
    {
        $profile = $this->getProfile();
        $budgetItems = BudgetItem::query()->with('vendor')->get();
        $tasks = WeddingTask::query()->with('vendor')->orderBy('due_date')->get();
        $vendors = Vendor::query()->orderBy('payment_due_date')->get();


        // 1. Countdown Metrics
        $weddingDate = $profile->wedding_date;

        $weddingMoment = $weddingDate
            ? Carbon::parse($weddingDate->format('Y-m-d') . ' 08:30:00')
            : null;

        $now = now();

        $totalHours = $weddingMoment !== null
            ? max(0, (int) $now->diffInHours($weddingMoment, false))
            : 0;

        $daysRemaining = (int) floor($totalHours / 24);
        $hoursRemaining = $totalHours % 24;


        // 2. Financial Metrics
        $totalBudgetCap = (int) $profile->total_budget;
        $totalEstimated = (int) $budgetItems->sum('estimated_cost');
        $totalActual = (int) $budgetItems->sum('actual_cost');
        $totalPaid = (int) $budgetItems->sum('paid_amount');
        $effectiveCommitted = $totalActual > 0 ? $totalActual : $totalEstimated;
        $remainingBudget = $totalBudgetCap - $effectiveCommitted;
        $budgetUtilizationPercent = $totalBudgetCap > 0
            ? (int) min(100, round(($effectiveCommitted / $totalBudgetCap) * 100))
            : 0;
        $varianceAmount = $totalEstimated - $totalActual;

        $categoryBreakdown = [];
        foreach (WeddingCategory::cases() as $category) {
            $catItems = $budgetItems->where('category', $category);
            $catEstimated = (int) $catItems->sum('estimated_cost');
            $catActual = (int) $catItems->sum('actual_cost');
            $catPaid = (int) $catItems->sum('paid_amount');
            $catSharePercent = $totalActual > 0
                ? (int) round(($catActual / $totalActual) * 100)
                : 0;

            $categoryBreakdown[] = [
                'case' => $category,
                'key' => $category->value,
                'label' => $category->label(),
                'short_label' => $category->shortLabel(),
                'estimated' => $catEstimated,
                'actual' => $catActual,
                'paid' => $catPaid,
                'variance' => $catEstimated - $catActual,
                'share_percent' => $catSharePercent,
                'recommended_percent' => $category->recommendedSharePercent(),
                'utilization_percent' => $catEstimated > 0
                    ? (int) min(100, round(($catActual / $catEstimated) * 100))
                    : 0,
                'is_overbudget' => $catActual > $catEstimated && $catEstimated > 0,
            ];
        }

        // 3. Preparation / Checklist & Timeline Metrics
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', TaskStatus::Done)->count();
        $inProgressTasks = $tasks->where('status', TaskStatus::InProgress)->count();
        $todoTasks = $tasks->where('status', TaskStatus::Todo)->count();
        $overdueTasksCount = $tasks->filter(fn(WeddingTask $task): bool => $task->is_overdue)->count();
        $completionPercent = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        $urgentTasks = $tasks
            ->reject(fn(WeddingTask $task): bool => $task->status === TaskStatus::Done)
            ->sortBy('due_date')
            ->take(6)
            ->values();

        $phaseProgress = [];
        foreach (TimelinePhase::cases() as $phase) {
            $phaseTasks = $tasks->where('phase', $phase->value);
            $pTotal = $phaseTasks->count();
            $pDone = $phaseTasks->where('status', TaskStatus::Done)->count();
            $phaseProgress[] = [
                'phase' => $phase->value,
                'subtitle' => $phase->subtitle(),
                'total' => $pTotal,
                'done' => $pDone,
                'percent' => $pTotal > 0 ? (int) round(($pDone / $pTotal) * 100) : 0,
            ];
        }

        // 4. Vendor Metrics
        $totalVendors = $vendors->count();
        $bookedVendors = $vendors->filter(
            fn(Vendor $vendor): bool => $vendor->booking_status->isConfirmed()
        )->count();
        $unpaidVendorBalance = (int) $vendors->sum(fn(Vendor $vendor): int => $vendor->remaining_balance);
        $upcomingVendorPayments = $vendors
            ->filter(fn(Vendor $vendor): bool => $vendor->remaining_balance > 0)
            ->sortBy('payment_due_date')
            ->take(5)
            ->values();

        $budgetPaidPercent = $effectiveCommitted > 0
            ? (int) min(100, round(($totalPaid / $effectiveCommitted) * 100))
            : 0;
        $vendorBookingPercent = $totalVendors > 0
            ? (int) round(($bookedVendors / $totalVendors) * 100)
            : 0;
        $readinessScore = (int) round(
            ($completionPercent * 0.40) + ($vendorBookingPercent * 0.35) + ($budgetPaidPercent * 0.25)
        );
        $costPerGuest = $profile->guest_target > 0
            ? (int) round($effectiveCommitted / $profile->guest_target)
            : 0;

        return [
            'profile' => $profile,
            'couple_names' => $profile->couple_names,
            'wedding_date_formatted' => $profile->wedding_date?->translatedFormat('l, d F Y') ?? 'Tanggal belum ditentukan',
            'wedding_date_iso' => $weddingMoment?->toIso8601String(),
            'days_remaining' => $daysRemaining,
            'hours_remaining' => $hoursRemaining,
            'total_budget_cap' => $totalBudgetCap,
            'total_estimated' => $totalEstimated,
            'total_actual' => $totalActual,
            'total_paid' => $totalPaid,
            'remaining_budget' => $remainingBudget,
            'budget_utilization_percent' => $budgetUtilizationPercent,
            'variance_amount' => $varianceAmount,
            'category_breakdown' => $categoryBreakdown,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'in_progress_tasks' => $inProgressTasks,
            'todo_tasks' => $todoTasks,
            'overdue_tasks_count' => $overdueTasksCount,
            'completion_percent' => $completionPercent,
            'urgent_tasks' => $urgentTasks,
            'phase_progress' => $phaseProgress,
            'total_vendors' => $totalVendors,
            'booked_vendors' => $bookedVendors,
            'unpaid_balance' => $unpaidVendorBalance,
            'upcoming_vendor_payments' => $upcomingVendorPayments,
            'budget_paid_percent' => $budgetPaidPercent,
            'vendor_booking_percent' => $vendorBookingPercent,
            'readiness_score' => $readinessScore,
            'cost_per_guest' => $costPerGuest,
        ];
    }
}
