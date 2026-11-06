<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\WeddingCategory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TenancyTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        WeddingProfile::useTenancy(null);

        parent::tearDown();
    }

    public function test_customer_without_profile_cannot_reach_workspace_modules(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get('/dashboard')->assertRedirect('/onboarding');
        $this->actingAs($user)->get('/budget')->assertRedirect('/onboarding');
        $this->actingAs($user)->get('/vendors')->assertRedirect('/onboarding');
        $this->actingAs($user)->get('/print/master-guide')->assertRedirect('/onboarding');
    }

    public function test_onboarding_page_renders_the_profile_form(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/onboarding')
            ->assertOk()
            ->assertSee('Mulai Perencanaan Pernikahan')
            ->assertSee('name="bride_name"', false);
    }

    public function test_onboarding_creates_a_profile_owned_by_the_customer(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->post('/onboarding', [
            'bride_name' => 'Sekar',
            'groom_name' => 'Bagas',
            'wedding_date' => now()->addMonths(6)->toDateString(),
            'venue_name' => 'Pendopo Agung',
            'city' => 'Yogyakarta',
            'total_budget' => 150000000,
            'guest_target' => 200,
        ])->assertRedirect('/dashboard');

        $this->assertSame($user->id, $user->fresh()->weddingProfile->user_id);
    }

    public function test_new_records_are_auto_assigned_to_the_signed_in_profile(): void
    {
        $user = $this->customerWithProfile();

        $this->actingAs($user);
        $vendor = Vendor::query()->create([
            'name' => 'Vendor Milik Sendiri',
            'category' => WeddingCategory::Venue,
            'contact_person' => 'Sekar',
            'phone' => '08123456789',
        ]);

        $this->assertSame($user->weddingProfile->id, $vendor->wedding_profile_id);
    }

    public function test_vendor_data_is_isolated_per_wedding_profile(): void
    {
        $first = $this->customerWithProfile();
        $second = $this->customerWithProfile();

        $this->actingAs($first);
        Vendor::query()->create([
            'name' => 'Vendor Rahasia Pertama',
            'category' => WeddingCategory::Venue,
            'contact_person' => 'Pertama',
            'phone' => '0811111111',
        ]);

        $this->actingAs($second);
        Vendor::query()->create([
            'name' => 'Vendor Rahasia Kedua',
            'category' => WeddingCategory::Documentation,
            'contact_person' => 'Kedua',
            'phone' => '0822222222',
        ]);

        $this->actingAs($first)
            ->get('/vendors')
            ->assertOk()
            ->assertSee('Vendor Rahasia Pertama')
            ->assertDontSee('Vendor Rahasia Kedua');

        $this->actingAs($second->fresh())
            ->get('/vendors')
            ->assertOk()
            ->assertSee('Vendor Rahasia Kedua')
            ->assertDontSee('Vendor Rahasia Pertama');
    }

    private function customerWithProfile(): User
    {
        $user = User::factory()->create(['role' => 'customer']);

        $user->weddingProfile()->create([
            'bride_name' => 'Mempelai Wanita '.$user->id,
            'groom_name' => 'Mempelai Pria '.$user->id,
            'wedding_date' => now()->addMonths(4)->toDateString(),
            'wedding_time' => '08:30 WIB',
            'venue_name' => 'Venue '.$user->id,
            'city' => 'Jakarta',
            'total_budget' => 200000000,
            'guest_target' => 250,
            'theme_concept' => 'Editorial',
        ]);

        return $user->fresh();
    }
}
