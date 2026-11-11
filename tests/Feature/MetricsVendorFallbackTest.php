<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\BudgetItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use App\Services\WeddingMetricsService;
use Database\Seeders\WeddingPlannerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class MetricsVendorFallbackTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private WeddingMetricsService $metrics;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingPlannerSeeder::class);
        $this->user = User::query()->where('role', 'customer')->firstOrFail();
        $this->metrics = app(WeddingMetricsService::class);
    }

    public function test_metrics_uses_vendor_contracts_when_budget_actual_is_zero(): void
    {
        $this->actingAs($this->user);

        // Pastikan budget item actual bernilai 0
        $this->assertSame(0, (int) BudgetItem::sum('actual_cost'));

        $summary = $this->metrics->getDashboardSummary();

        // Nilai deal vendor harus mencerminkan total kontrak vendor yang confirmed (> 0)
        $this->assertGreaterThan(0, $summary['total_actual']);
        $this->assertGreaterThan(0, $summary['total_paid']);

        // Nilai harus persis sama dengan jumlah kontrak vendor
        $confirmedVendors = Vendor::all()->filter(fn ($v) => $v->booking_status->isConfirmed());
        $expectedContract = (int) $confirmedVendors->sum('contract_amount');
        $expectedPaid = (int) $confirmedVendors->sum('paid_amount');

        $this->assertSame($expectedContract, $summary['total_actual']);
        $this->assertSame($expectedPaid, $summary['total_paid']);

        // Dashboard view menampilkan angka nilai deal vendor yang sinkron
        $response = $this->get(route('dashboard.index'));
        $response->assertOk();
        $response->assertSee(format_idr($expectedContract));
    }

    public function test_updating_wedding_date_shifts_task_due_dates_proportionally(): void
    {
        $this->actingAs($this->user);

        $profile = $this->metrics->getProfile();
        $firstTask = WeddingTask::query()->orderBy('due_date')->firstOrFail();
        $initialDueDate = $firstTask->due_date->copy();

        $newWeddingDate = Carbon::parse($profile->wedding_date)->addDays(30)->toDateString();

        $response = $this->patch(route('dashboard.updateProfile'), [
            'bride_name' => $profile->bride_name,
            'groom_name' => $profile->groom_name,
            'wedding_date' => $newWeddingDate,
            'wedding_time' => $profile->wedding_time,
            'venue_name' => $profile->venue_name,
            'city' => $profile->city,
            'total_budget' => $profile->total_budget,
            'guest_target' => $profile->guest_target,
            'theme_concept' => $profile->theme_concept,
        ]);

        $response->assertRedirect(route('dashboard.index'));

        // Cek bahwa due date tugas ikut maju 30 hari
        $updatedFirstTask = $firstTask->fresh();
        $this->assertSame(
            $initialDueDate->addDays(30)->toDateString(),
            $updatedFirstTask->due_date->toDateString()
        );
    }
}
