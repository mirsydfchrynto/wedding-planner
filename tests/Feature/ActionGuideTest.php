<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use App\Support\ActionGuide;
use Database\Seeders\WeddingPlannerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ActionGuideTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingPlannerSeeder::class);
    }

    public function test_action_guide_generates_three_steps_and_wa_for_task(): void
    {
        $profile = WeddingProfile::query()->firstOrFail();
        $task = WeddingTask::query()->where('title', 'like', '%venue%')->first()
            ?? WeddingTask::query()->firstOrFail();

        $guide = ActionGuide::forTask($task, $profile);

        $this->assertCount(3, $guide['steps']);
        $this->assertNotEmpty($guide['wa_text']);
        $this->assertStringContainsString($profile->bride_name, $guide['wa_text']);
        $this->assertStringContainsString($task->title, $guide['wa_text']);
    }

    public function test_action_guide_generates_steps_and_wa_for_vendor(): void
    {
        $profile = WeddingProfile::query()->firstOrFail();
        $vendor = Vendor::query()->firstOrFail();

        $guide = ActionGuide::forVendor($vendor, $profile);

        $this->assertCount(3, $guide['steps']);
        $this->assertNotEmpty($guide['wa_text']);
        $this->assertStringContainsString($vendor->name, $guide['wa_text']);
        $this->assertStringContainsString(number_format($vendor->contract_amount, 0, ',', '.'), $guide['wa_text']);
    }
}
