<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\WeddingCategory;
use App\Models\BudgetItem;
use App\Models\WeddingProfile;
use App\Support\WeddingMasterData;

/**
 * Seeds a profile's budget from the research master data.
 *
 * Estimates use the research minimum (per-pax rows multiplied by the guest
 * target); the contingency row is 5% of the subtotal. Users replace them with
 * vendor quotes.
 */
final class BudgetAllocationService
{
    private const CONTINGENCY_PERCENT = 5;

    /**
     * @return array<BudgetItem>
     */
    public function allocate(WeddingProfile $profile, string $eventType = 'akad_resepsi'): array
    {
        $items = [];
        $totalCap = (int) $profile->total_budget;
        $eligibleRows = [];
        $rawSubtotal = 0;
        $contingencyRow = null;

        foreach (WeddingMasterData::BUDGET_ITEMS as $row) {
            if ($row['category'] === WeddingCategory::Contingency->value) {
                $contingencyRow = $row;
                continue;
            }

            if ($eventType === 'akad_only' && in_array($row['category'], ['entertainment', 'souvenir'], true)) {
                continue;
            }

            $rawEstimate = $row['per_guest']
                ? $row['min'] * (int) $profile->guest_target
                : $row['min'];
            $rawSubtotal += $rawEstimate;

            $eligibleRows[] = [
                'row' => $row,
                'raw' => $rawEstimate,
            ];
        }

        // Skalakan estimasi agar proporsional dengan batas total budget pengguna
        $scaleFactor = ($totalCap > 0 && $rawSubtotal > 0)
            ? ($totalCap * 0.95) / $rawSubtotal
            : 1.0;

        $allocatedSubtotal = 0;
        foreach ($eligibleRows as $entry) {
            $row = $entry['row'];
            $estimated = (int) round(($entry['raw'] * $scaleFactor) / 50000) * 50000;
            $allocatedSubtotal += $estimated;

            $items[] = BudgetItem::query()->create([
                'category' => $row['category'],
                'title' => $row['title'],
                'estimated_cost' => max(100000, $estimated),
                'actual_cost' => 0,
                'paid_amount' => 0,
                'notes' => $this->researchNote($row),
            ]);
        }

        if ($contingencyRow !== null) {
            $contingencyAmount = $totalCap > 0
                ? max(1000000, (int) round($totalCap * 0.05))
                : (int) round($allocatedSubtotal * self::CONTINGENCY_PERCENT / 100);

            $items[] = BudgetItem::query()->create([
                'category' => $contingencyRow['category'],
                'title' => $contingencyRow['title'],
                'estimated_cost' => $contingencyAmount,
                'actual_cost' => 0,
                'paid_amount' => 0,
                'notes' => $this->researchNote($contingencyRow),
            ]);
        }

        return $items;
    }

    /**
     * @param  array{min:int,max:int,unit:string,notes:string}  $row
     */
    private function researchNote(array $row): string
    {
        $range = 'Rp'.number_format($row['min'], 0, ',', '.').' – Rp'.number_format($row['max'], 0, ',', '.');

        return 'Estimasi riset indikatif: '.$range.' '.$row['unit'].'. Ganti dengan penawaran vendor. '.$row['notes'];
    }
}
