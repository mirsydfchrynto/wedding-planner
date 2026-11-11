<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Enums\TimelinePhase;
use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\BudgetItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use Database\Seeders\WeddingPlannerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WeddingPlannerModulesTest extends TestCase
{
    use RefreshDatabase;

    private User $customerUser;


    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingPlannerSeeder::class);

        $this->customerUser = User::query()->where('role', 'customer')->firstOrFail();
    }

    public function test_root_shows_landing_to_guest_and_redirects_customer_to_dashboard(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Rencana pernikahan yang jelas', false)
            ->assertSee('Gratis, dan tetap gratis', false);

        $this->actingAs($this->customerUser)
            ->get('/')
            ->assertRedirect(route('dashboard.index'));
    }

    public function test_module_form_pages_render_for_authenticated_customer(): void
    {
        $this->actingAs($this->customerUser);

        $vendor = Vendor::query()->firstOrFail();
        $task = WeddingTask::query()->firstOrFail();
        $item = BudgetItem::query()->firstOrFail();

        $this->get('/profile/edit')->assertOk();
        $this->get('/budget/create')->assertOk();
        $this->get("/budget/{$item->id}/edit")->assertOk();
        $this->get('/checklist/create')->assertOk();
        $this->get("/checklist/{$task->id}/edit")->assertOk();
        $this->get('/vendors/create')->assertOk();
        $this->get("/vendors/{$vendor->id}/edit")->assertOk();
        $this->get("/vendors/{$vendor->id}/payments/create")->assertOk();
    }

    public function test_guest_is_redirected_to_login_when_accessing_customer_or_admin_routes(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/budget')->assertRedirect('/login');
        $this->get('/checklist')->assertRedirect('/login');
        $this->get('/timeline')->assertRedirect('/login');
        $this->get('/vendors')->assertRedirect('/login');
    }

    public function test_customer_can_sign_in_with_google_and_access_dashboard(): void
    {
        $loginResponse = $this->post('/auth/google', [
            'google_email' => 'aurelia.reza@gmail.com',
            'google_name' => 'Aurelia Larasati & Reza Mahendra',
        ]);

        $loginResponse->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticated();

        $response = $this->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Aurelia Larasati & Reza Mahendra');
        $response->assertSee('Rp 350.000.000');
        $response->assertSee('Tugas terdekat');
        $response->assertDontSee('Konsol Admin Privat');
    }


    public function test_google_sign_in_aborts_when_demo_mode_is_disabled(): void
    {
        config(['services.google.demo_login' => false]);

        $response = $this->post('/auth/google', [
            'google_email' => 'aurelia.reza@gmail.com',
        ]);

        $response->assertForbidden();
    }


    public function test_dashboard_profile_can_be_updated_by_authenticated_customer(): void
    {
        $response = $this->actingAs($this->customerUser)->patch('/profile', [
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
        $this->actingAs($this->customerUser)
            ->get('/budget')
            ->assertOk()
            ->assertSee('Anggaran Pernikahan')
            ->assertSee('Gedung/ballroom');

        $storeResponse = $this->actingAs($this->customerUser)->post('/budget', [
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

        $this->actingAs($this->customerUser)->put("/budget/{$item->id}", [
            'category' => WeddingCategory::Decoration->value,
            'title' => 'Instalasi Bunga Meja VIP Keluarga',
            'estimated_cost' => 12000000,
            'actual_cost' => 11500000,
            'paid_amount' => 11500000,
            'notes' => 'Lunas',
        ])->assertRedirect(route('budget.index'));

        $this->assertSame(0, $item->fresh()->remaining_balance);

        $this->actingAs($this->customerUser)->patch('/budget/cap', ['total_budget' => 400000000])
            ->assertRedirect(route('budget.index'));
        $this->assertSame(400000000, WeddingProfile::query()->firstOrFail()->total_budget);

        $this->actingAs($this->customerUser)->delete("/budget/{$item->id}")->assertRedirect(route('budget.index'));
        $this->assertDatabaseMissing('budget_items', ['id' => $item->id]);
    }

    public function test_checklist_supports_filtering_creating_and_status_toggle(): void
    {
        $this->actingAs($this->customerUser)
            ->get('/checklist?category=venue')
            ->assertOk()
            ->assertSee('Checklist &amp; Timeline Persiapan', false);
        $this->actingAs($this->customerUser)->post('/checklist', [
            'title' => 'Verifikasi Berkas Wali Nikah KUA',
            'description' => 'Fotokopi KTP dan KK wali nikah',
            'category' => WeddingCategory::Documents->value,
            'phase' => TimelinePhase::Finalization->value,
            'due_date' => now()->addDays(15)->toDateString(),
            'status' => TaskStatus::Todo->value,
            'priority' => 'high',
            'assigned_to' => 'Reza Mahendra',
            'is_milestone' => true,
        ])->assertRedirect(route('checklist.index'));

        $task = WeddingTask::query()->where('title', 'Verifikasi Berkas Wali Nikah KUA')->firstOrFail();
        $this->assertSame(TaskStatus::Todo, $task->status);

        $this->actingAs($this->customerUser)->patch("/checklist/{$task->id}/toggle")->assertRedirect();
        $this->assertSame(TaskStatus::Done, $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_timeline_redirects_to_integrated_checklist_and_timeline(): void
    {
        $this->actingAs($this->customerUser)
            ->get('/timeline')
            ->assertRedirect(route('checklist.index'));

        $this->actingAs($this->customerUser)
            ->get('/checklist')
            ->assertOk()
            ->assertSee('Checklist &amp; Timeline Persiapan', false);
    }

    public function test_vendor_management_creates_updates_and_tracks_payments(): void
    {
        $this->actingAs($this->customerUser)
            ->get('/vendors')
            ->assertOk()
            ->assertSee('Mitra Vendor')
            ->assertSee('Plataran Menteng Heritage Venue');

        $this->actingAs($this->customerUser)->post('/vendors', [
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

        $this->actingAs($this->customerUser)->post("/vendors/{$vendor->id}/payments", [
            'payment_stage' => 'Pelunasan',
            'amount' => 5000000,
            'paid_date' => now()->toDateString(),
            'receipt_ref' => 'KW-FINAL-01',
            'notes' => 'Pelunasan lunas',
        ])->assertRedirect(route('vendors.index'));

        $this->assertSame(0, $vendor->fresh()->remaining_balance);
        $this->assertSame(VendorBookingStatus::Paid, $vendor->fresh()->booking_status);
    }


}
