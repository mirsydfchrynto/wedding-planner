<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\WeddingProfile;
use Database\Seeders\WeddingPlannerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminConsoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingPlannerSeeder::class);
    }

    public function test_guest_can_view_admin_login_page(): void
    {
        $response = $this->get('/admin/login');
        $response->assertOk();
        $response->assertSee('Konsol Administrator');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@tatanikah.id',
            'password' => 'admin',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::query()->where('role', 'customer')->firstOrFail();

        $response = $this->actingAs($customer)->get('/admin');
        $response->assertForbidden();
    }

    public function test_admin_dashboard_displays_platform_monitoring_metrics(): void
    {
        $admin = User::query()->where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertOk();
        $response->assertSee('Platform Monitoring');
        $response->assertSee('Total Pengantin Terdaftar');
        $response->assertSee('Total Anggaran Dikelola');
        $response->assertSee('Aurelia Larasati');
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::query()->where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->post('/admin/logout');
        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_customer_opening_admin_login_page_can_see_form_and_switches_session(): void
    {
        $customer = User::query()->where('role', 'customer')->firstOrFail();

        $response = $this->actingAs($customer)->get('/admin/login');
        $response->assertOk();
        $response->assertSee('Konsol Administrator');
    }

    public function test_admin_can_delete_wedding_profile(): void
    {
        $admin = User::query()->where('role', 'admin')->firstOrFail();
        $profile = WeddingProfile::query()->firstOrFail();
        $profileId = $profile->id;

        $response = $this->actingAs($admin)->delete("/admin/profiles/{$profileId}");
        $response->assertRedirect('/admin');
        $this->assertDatabaseMissing('wedding_profiles', ['id' => $profileId]);
    }

    public function test_admin_can_impersonate_couple_and_inspect_workspace(): void
    {
        $admin = User::query()->where('role', 'admin')->firstOrFail();
        $profile = WeddingProfile::query()->firstOrFail();

        $response = $this->actingAs($admin)->post("/admin/profiles/{$profile->id}/impersonate");
        $response->assertRedirect('/dashboard');

        $dashboardResponse = $this->actingAs($admin)->get('/dashboard');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Mode Inspeksi Admin');
        $dashboardResponse->assertSee($profile->couple_names);
    }

    public function test_admin_can_stop_impersonation_and_return_to_admin(): void
    {
        $admin = User::query()->where('role', 'admin')->firstOrFail();
        $profile = WeddingProfile::query()->firstOrFail();

        $this->actingAs($admin)->post("/admin/profiles/{$profile->id}/impersonate");
        $response = $this->actingAs($admin)->post('/admin/stop-impersonate');
        $response->assertRedirect('/admin');
        $this->assertFalse(session()->has('impersonated_profile_id'));
    }

    public function test_admin_can_export_platform_data_to_csv(): void
    {
        $admin = User::query()->where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/export');
        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
    }
}
