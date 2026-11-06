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

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingPlannerSeeder::class);

        $this->customerUser = User::query()->where('role', 'customer')->firstOrFail();
        $this->adminUser = User::query()->where('role', 'admin')->firstOrFail();
    }

    public function test_public_root_renders_saas_branding_landing_page_and_hides_admin_console(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Satu buku kerja untuk anggaran, vendor, dan adat pernikahan.');
        $response->assertSee('Simulasi Interaktif Anggaran & Adat Nusantara');
        $response->assertSee('Keraton Royal');
        $response->assertSee('Masuk dengan Google');
        $response->assertDontSee('Konsol Admin Privat');
    }

    public function test_guest_is_redirected_to_login_when_accessing_customer_or_admin_routes(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/budget')->assertRedirect('/login');
        $this->get('/checklist')->assertRedirect('/login');
        $this->get('/timeline')->assertRedirect('/login');
        $this->get('/vendors')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
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
        $response->assertSee('Tata Upacara Adat Jawa & Perhitungan Weton Neptu');
        $response->assertDontSee('Konsol Admin Privat');
    }

    public function test_google_sign_in_aborts_when_attempting_to_use_admin_account(): void
    {
        $response = $this->post('/auth/google', [
            'google_email' => $this->adminUser->email,
        ]);

        $response->assertForbidden();
    }

    public function test_google_sign_in_aborts_when_demo_mode_is_disabled(): void
    {
        config(['services.google.demo_login' => false]);

        $response = $this->post('/auth/google', [
            'google_email' => 'aurelia.reza@gmail.com',
        ]);

        $response->assertForbidden();
    }

    public function test_customer_cannot_access_private_admin_console(): void
    {
        $this->actingAs($this->customerUser)
            ->get('/admin')
            ->assertForbidden();
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
            ->assertSee('Sewa Eksklusif The Glasshouse & Courtyard Plataran');

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
            ->assertSee('Checklist Persiapan');
        $this->actingAs($this->customerUser)->post('/checklist', [
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

        $this->actingAs($this->customerUser)->patch("/checklist/{$task->id}/toggle")->assertRedirect();
        $this->assertSame(TaskStatus::Done, $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_timeline_renders_phases_and_updates_milestone_status(): void
    {
        $this->actingAs($this->customerUser)
            ->get('/timeline')
            ->assertOk()
            ->assertSee('Linimasa &amp; Jadwal', false)
            ->assertSee('H-12 Bulan')
            ->assertSee('Hari-H');

        $this->actingAs($this->customerUser)->post('/timeline', [
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

        $this->actingAs($this->customerUser)->patch("/timeline/{$task->id}/status", [
            'status' => TaskStatus::InProgress->value,
        ])->assertRedirect(route('timeline.index'));

        $this->assertSame(TaskStatus::InProgress, $task->fresh()->status);
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

        $this->actingAs($this->customerUser)->patch("/vendors/{$vendor->id}/payment", [
            'paid_amount' => 8500000,
            'booking_status' => VendorBookingStatus::Paid->value,
        ])->assertRedirect(route('vendors.index'));

        $this->assertSame(0, $vendor->fresh()->remaining_balance);
        $this->assertSame(VendorBookingStatus::Paid, $vendor->fresh()->booking_status);
    }

    public function test_private_admin_can_login_and_execute_admin_console_operations(): void
    {
        $this->post('/auth/admin', [
            'email' => 'irsyad@ateliervow.id',
            'password' => 'AtelierAdmin2026!',
        ])->assertRedirect(route('admin.index'));

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Konsol Admin & Operasional Wedding Organizer')
            ->assertSee('Matriks Kepatuhan Arsitektur Twelve-Factor SaaS')
            ->assertSee('12 / 12 Faktor Terverifikasi');

        $this->post('/admin/escalate-overdue')->assertRedirect(route('admin.index'));

        $vendor = Vendor::query()->where('booking_status', VendorBookingStatus::Contacted->value)->firstOrFail();
        $this->patch("/admin/vendors/{$vendor->id}/verify")->assertRedirect(route('admin.index'));
        $this->assertSame(VendorBookingStatus::Booked, $vendor->fresh()->booking_status);
    }

    public function test_master_guide_print_view_renders_rundown_and_vendor_directory(): void
    {
        $response = $this->actingAs($this->customerUser)->get('/print/master-guide');

        $response->assertOk();
        $response->assertSee('Buku Kerja &amp; Panduan Operasional Resmi Hari Pernikahan', false);
        $response->assertSee('Rundown Acara Menit-demi-Menit');
        $response->assertSee('Plataran Menteng Heritage Venue');
        $response->assertSee('Daftar Tamu Kehormatan (VIP) &amp; Penataan Meja', false);
    }
}
