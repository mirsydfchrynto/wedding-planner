<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\WeddingCategory;
use App\Models\User;
use App\Models\BudgetItem;
use App\Models\WeddingTask;
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
            'venue_type' => 'Pendopo Agung',
            'city' => 'Yogyakarta',
            'akad_time' => '08:00',
            'resepsi_time' => '11:00',
            'total_budget' => 150000000,
            'guest_target' => 200,
        ])->assertRedirect('/dashboard');

        $this->assertSame($user->id, $user->fresh()->weddingProfile->user_id);

        $profile = $user->fresh()->weddingProfile;

        $this->assertSame(29, BudgetItem::query()->where('wedding_profile_id', $profile->id)->count());
        $this->assertSame(124, WeddingTask::query()->where('wedding_profile_id', $profile->id)->count());
    }

    public function test_onboarding_appends_adat_tasks_and_rejects_unknown_traditions(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->post('/onboarding', [
            'bride_name' => 'Sekar',
            'groom_name' => 'Bagas',
            'wedding_date' => now()->addMonths(6)->toDateString(),
            'venue_type' => 'Pendopo Agung',
            'city' => 'Yogyakarta',
            'akad_time' => '08:00',
            'resepsi_time' => '11:00',
            'total_budget' => 150000000,
            'guest_target' => 200,
            'adat_tradition' => 'jawa',
        ])->assertRedirect('/dashboard');

        $profile = $user->fresh()->weddingProfile;

        $this->assertSame(128, WeddingTask::query()->where('wedding_profile_id', $profile->id)->count());
        $this->assertDatabaseHas('wedding_tasks', ['wedding_profile_id' => $profile->id, 'title' => 'Siraman (pembersihan lahir batin)']);

        $this->actingAs(User::factory()->create(['role' => 'customer']))->post('/onboarding', [
            'bride_name' => 'Ayu',
            'groom_name' => 'Dimas',
            'wedding_date' => now()->addMonths(6)->toDateString(),
            'venue_type' => 'Balai Kartini',
            'city' => 'Jakarta',
            'akad_time' => '08:00',
            'resepsi_time' => '11:00',
            'total_budget' => 150000000,
            'guest_target' => 200,
            'adat_tradition' => 'bali',
        ])->assertSessionHasErrors('adat_tradition');
    }

    public function test_checklist_due_dates_fit_the_time_left_before_the_wedding(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $weddingDate = now()->addWeek();

        $this->actingAs($user)->post('/onboarding', [
            'bride_name' => 'Sekar',
            'groom_name' => 'Bagas',
            'wedding_date' => $weddingDate->toDateString(),
            'venue_type' => 'Pendopo Agung',
            'city' => 'Yogyakarta',
            'akad_time' => '08:00',
            'resepsi_time' => '11:00',
            'total_budget' => 150000000,
            'guest_target' => 200,
        ])->assertRedirect('/dashboard');

        $profile = $user->fresh()->weddingProfile;
        $tasks = WeddingTask::query()->where('wedding_profile_id', $profile->id)->get();

        $this->assertTrue($tasks->min('due_date')->isSameDay(now()));
        $this->assertTrue($tasks->max('due_date')->isSameDay($weddingDate));
        $this->assertTrue($tasks->every(fn (WeddingTask $task): bool => $task->due_date->betweenIncluded(now()->startOfDay(), $weddingDate->copy()->startOfDay())));
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
    public function test_dashboard_profile_and_summary_are_isolated_per_wedding_profile(): void
    {
        $first = $this->customerWithProfile();
        $second = $this->customerWithProfile();

        $firstName = $first->weddingProfile->couple_names;
        $secondName = $second->weddingProfile->couple_names;

        $this->actingAs($first)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($firstName)
            ->assertDontSee($secondName);

        $this->actingAs($second)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($secondName)
            ->assertDontSee($firstName);

        // Verify updateProfile targets acting user's profile
        $this->actingAs($second)
            ->patch('/profile', [
                'bride_name' => 'Pengantin Baru Wanita',
                'groom_name' => 'Pengantin Baru Pria',
                'wedding_date' => now()->addMonths(5)->toDateString(),
                'wedding_time' => '09:00 WIB',
                'venue_name' => 'Venue Baru',
                'city' => 'Bandung',
                'total_budget' => 180000000,
                'guest_target' => 300,
                'theme_concept' => 'Modern Minimalis',
            ])
            ->assertRedirect('/dashboard');

        $this->assertSame('Pengantin Baru Wanita & Pengantin Baru Pria', $second->fresh()->weddingProfile->couple_names);
        $this->assertSame($firstName, $first->fresh()->weddingProfile->couple_names);
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
