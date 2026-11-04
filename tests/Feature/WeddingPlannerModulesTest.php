<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Enums\TimelinePhase;
use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\BudgetItem;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use Database\Seeders\WeddingPlannerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WeddingPlannerModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingPlannerSeeder::class);
    }

    public function test_dashboard_renders_executive_summary_and_seeded_couple(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Aurelia Larasati');
        $response->assertSee('Reza Mahendra');
        $response->assertSee('Rp 350.000.000');
        $response->assertSee('Distribusi Anggaran per Kategori');
    }

    public function test_dashboard_profile_can_be_updated(): void
    {
        $response = $this->patch('/profile', [
            'bride_name' => 'Marsha Larasati',
            'groom_name' => 'Irsyad Mahendra',
            'wedding_date' => now()->addDays(180)->toDateString(),
            'wedding_time' => '09:00 WIB',
            'venue_name' => 'Raffles Jakarta Grand Ballroom',
            'city' => 'Jakarta Selatan',
            'total_budget' => 420000000,
            'guest_target' => 600,
            'theme_concept' => 'Vogue Bridal Editorial',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertDatabaseHas('wedding_profiles', [
            'bride_name' => 'Marsha Larasati',
            'venue_name' => 'Raffles Jakarta Grand Ballroom',
            'total_budget' => 420000000,
        ]);
    }

    public function test_budget_planner_displays_items_and_allows_crud_and_cap_update(): void
    {
        $this->get('/budget')
            ->assertOk()
            ->assertSee('Buku Anggaran & Realisasi Biaya')
            ->assertSee('Sewa Eksklusif The Glasshouse & Courtyard Plataran');

        $storeResponse = $this->post('/budget', [
            'category' => WeddingCategory::Decoration->value,
            'title' => 'Instalasi Bunga Meja VIP Keluarga',
            'estimated_cost' => 12000000,
            'actual_cost' => 11500000,
            'paid_amount' => 5000000,
            'notes' => 'Bunga anggrek bulan putih segar',
        ]);
        $storeResponse->assertRedirect(route('budget.index'));

        $item = BudgetItem::query()->where('title', 'Instalasi Bunga Meja VIP Keluarga')->firstOrFail();
        $this->assertSame(6500000, $item->remaining_balance);

        $this->put("/budget/{$item->id}", [
            'category' => WeddingCategory::Decoration->value,
            'title' => 'Instalasi Bunga Meja VIP Keluarga',
            'estimated_cost' => 12000000,
            'actual_cost' => 11500000,
            'paid_amount' => 11500000,
            'notes' => 'Lunas',
        ])->assertRedirect(route('budget.index'));

        $this->assertSame(0, $item->fresh()->remaining_balance);

        $this->patch('/budget/cap', ['total_budget' => 400000000])
            ->assertRedirect(route('budget.index'));
        $this->assertSame(400000000, WeddingProfile::query()->firstOrFail()->total_budget);

        $this->delete("/budget/{$item->id}")->assertRedirect(route('budget.index'));
        $this->assertDatabaseMissing('budget_items', ['id' => $item->id]);
    }

    public function test_checklist_supports_filtering_creating_and_status_toggle(): void
    {
        $this->get('/checklist?category=venue')
            ->assertOk()
            ->assertSee('Daftar Tugas & Checklist Pernikahan');

        $this->post('/checklist', [
            'title' => 'Verifikasi Berkas Wali Nikah KUA',
            'description' => 'Fotokopi KTP dan KK wali nikah',
            'category' => WeddingCategory::Documents->value,
            'phase' => TimelinePhase::Months3->value,
            'due_date' => now()->addDays(15)->toDateString(),
            'status' => TaskStatus::Todo->value,
            'priority' => 'high',
            'assigned_to' => 'Reza Mahendra',
            'is_milestone' => true,
        ])->assertRedirect(route('checklist.index'));

        $task = WeddingTask::query()->where('title', 'Verifikasi Berkas Wali Nikah KUA')->firstOrFail();
        $this->assertSame(TaskStatus::Todo, $task->status);

        $this->patch("/checklist/{$task->id}/toggle")->assertRedirect();
        $this->assertSame(TaskStatus::Done, $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_timeline_renders_phases_and_updates_milestone_status(): void
    {
        $this->get('/timeline')
            ->assertOk()
            ->assertSee('Linimasa Persiapan Pernikahan')
            ->assertSee('H-12 Bulan')
            ->assertSee('Hari-H');

        $this->post('/timeline', [
            'title' => 'Simulasi Prosesi Kirab Resepsi',
            'description' => 'Gladi resik bersama keluarga dan musik',
            'category' => WeddingCategory::Entertainment->value,
            'phase' => TimelinePhase::Week1->value,
            'due_date' => now()->addDays(130)->toDateString(),
            'priority' => 'high',
            'assigned_to' => 'WO & Pasangan',
            'is_milestone' => true,
        ])->assertRedirect(route('timeline.index'));

        $task = WeddingTask::query()->where('title', 'Simulasi Prosesi Kirab Resepsi')->firstOrFail();

        $this->patch("/timeline/{$task->id}/status", [
            'status' => TaskStatus::InProgress->value,
        ])->assertRedirect(route('timeline.index'));

        $this->assertSame(TaskStatus::InProgress, $task->fresh()->status);
    }

    public function test_vendor_management_creates_updates_and_tracks_payments(): void
    {
        $this->get('/vendors')
            ->assertOk()
            ->assertSee('Manajemen Vendor & Status Pembayaran')
            ->assertSee('Plataran Menteng Heritage Venue');

        $this->post('/vendors', [
            'name' => 'Nusantara Heritage Photo Booth',
            'category' => WeddingCategory::Documentation->value,
            'contact_person' => 'Raka Pratama',
            'phone' => '081299887766',
            'email' => 'hello@nusantarabooth.id',
            'instagram' => '@nusantarabooth',
            'contract_amount' => 8500000,
            'paid_amount' => 3500000,
            'booking_status' => VendorBookingStatus::DpPaid->value,
            'payment_due_date' => now()->addDays(45)->toDateString(),
            'notes' => 'Unlimited cetak 4 jam',
        ])->assertRedirect(route('vendors.index'));

        $vendor = Vendor::query()->where('name', 'Nusantara Heritage Photo Booth')->firstOrFail();
        $this->assertSame(5000000, $vendor->remaining_balance);

        $this->patch("/vendors/{$vendor->id}/payment", [
            'paid_amount' => 8500000,
            'booking_status' => VendorBookingStatus::Paid->value,
        ])->assertRedirect(route('vendors.index'));

        $this->assertSame(0, $vendor->fresh()->remaining_balance);
        $this->assertSame(VendorBookingStatus::Paid, $vendor->fresh()->booking_status);
    }
}
