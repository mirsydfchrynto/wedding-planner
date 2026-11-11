<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\BudgetItem;
use App\Models\User;
use App\Models\WeddingTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OnboardingWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_onboarding_wizard_filters_tasks_and_budget_for_akad_only(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->post('/onboarding', [
            'event_type' => 'akad_only',
            'wedding_date' => now()->addMonths(5)->toDateString(),
            'city' => 'Surakarta',
            'venue_name' => 'Masjid Raya Sheikh Zayed',
            'venue_type' => 'Masjid',
            'wedding_time' => '08:00 – 11:00 WIB',
            'total_budget' => 150000000,
            'guest_target' => 100,
            'bride_name' => 'Larasati',
            'groom_name' => 'Dharma',
            'helper_mode' => 'family',
        ]);

        $response->assertRedirect('/dashboard');

        $profile = $user->fresh()->weddingProfile;
        $this->assertNotNull($profile);
        $this->assertSame('akad_only', $profile->event_type);
        $this->assertSame('family', $profile->helper_mode);

        // Budget items untuk akad_only tidak mengandung pos entertainment/souvenir
        $entertainmentBudget = BudgetItem::query()
            ->where('wedding_profile_id', $profile->id)
            ->where('category', 'entertainment')
            ->count();
        $this->assertSame(0, $entertainmentBudget);

        // Task checklist terfilter dan PIC diset ke Keluarga Inti
        $tasks = WeddingTask::query()->where('wedding_profile_id', $profile->id)->get();
        $this->assertGreaterThan(30, $tasks->count());
        $this->assertLessThan(124, $tasks->count());
        $this->assertStringContainsString('Keluarga', $tasks->first()->assigned_to);
    }

    public function test_onboarding_wizard_appends_jawa_adat_tasks(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->post('/onboarding', [
            'event_type' => 'jawa_inti',
            'wedding_date' => now()->addMonths(6)->toDateString(),
            'city' => 'Yogyakarta',
            'venue_name' => 'Ndalem Ngabean',
            'venue_type' => 'Pendopo',
            'wedding_time' => '09:00 – 13:00 WIB',
            'total_budget' => 350000000,
            'guest_target' => 300,
            'bride_name' => 'Sekar',
            'groom_name' => 'Bagas',
            'helper_mode' => 'wo',
        ]);

        $response->assertRedirect('/dashboard');

        $profile = $user->fresh()->weddingProfile;
        $this->assertNotNull($profile);

        // Memiliki 124 tugas standar + 4 tugas adat Jawa = 128 tugas
        $taskCount = WeddingTask::query()->where('wedding_profile_id', $profile->id)->count();
        $this->assertSame(128, $taskCount);
    }
}
