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
}
