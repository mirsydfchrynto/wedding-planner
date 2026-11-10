<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TimelinePhase;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use App\Support\WeddingMasterData;
use Carbon\CarbonInterface;

/**
 * Generates a profile's checklist from the research master checklist
 * (124 tasks across 10 stages), plus adat-specific tasks for the chosen tradition.
 */
final class TimelineGeneratorService
{
    /**
     * @return array<WeddingTask>
     */
    public function generate(WeddingProfile $profile, string $adatTradition = 'none'): array
    {
        $tasks = [];
        $weddingDate = $profile->wedding_date;

        foreach (WeddingMasterData::CHECKLIST_STAGES as $stageName => $stage) {
            $phase = TimelinePhase::from($stage['phase']);
            $isMilestone = str_starts_with($stageName, '10.');

            foreach ($stage['tasks'] as $task) {
                $tasks[] = $this->createTask($profile, [
                    'title' => $task['title'],
                    'description' => $task['description'],
                    'category' => $stage['category'],
                    'priority' => $this->priorityFor($task['type']),
                    'is_milestone' => $isMilestone,
                ], $phase, $weddingDate);
            }
        }

        foreach (WeddingMasterData::ADAT_TASKS[$adatTradition] ?? [] as $task) {
            $tasks[] = $this->createTask($profile, [
                'title' => $task['title'],
                'description' => 'Aktivitas adat '.$adatTradition.'.',
                'category' => 'ceremony',
                'priority' => $task['priority'],
                'is_milestone' => false,
            ], TimelinePhase::from($task['phase']), $weddingDate);
        }

        return $tasks;
    }

    private function priorityFor(string $type): string
    {
        return match ($type) {
            'Wajib sesuai ketentuan' => 'high',
            'Umum', 'Disarankan' => 'medium',
            default => 'low',
        };
    }

    /**
     * @param  array{title:string,description:string,category:string,priority:string,is_milestone:bool}  $taskData
     */
    private function createTask(
        WeddingProfile $profile,
        array $taskData,
        TimelinePhase $phase,
        CarbonInterface $weddingDate,
    ): WeddingTask {
        return WeddingTask::query()->create([
            'title' => $taskData['title'],
            'description' => $taskData['description'],
            'category' => $taskData['category'],
            'phase' => $phase->value,
            'due_date' => $this->calculateDueDate($phase, $weddingDate),
            'status' => 'todo',
            'priority' => $taskData['priority'],
            'assigned_to' => 'Pasangan & Keluarga',
            'is_milestone' => $taskData['is_milestone'],
        ]);
    }

    /**
     * Spreads the ten stages across whatever time is left, so a wedding a year
     * out and one tomorrow both produce an ordered, usable schedule.
     * shortcut: stages are evenly spaced, no per-task lead time; add offsets
     * to WeddingMasterData if tasks inside a stage need finer order.
     */
    private function calculateDueDate(TimelinePhase $phase, CarbonInterface $weddingDate): CarbonInterface
    {
        $today = now()->startOfDay();
        $target = $weddingDate->copy()->startOfDay();

        if ($phase === TimelinePhase::WeddingDay || $target->lessThanOrEqualTo($today)) {
            return $target;
        }

        $remainingDays = (int) $today->diffInDays($target);

        return $today->copy()->addDays((int) round($remainingDays * ($phase->order() - 1) / 9));
    }

}
