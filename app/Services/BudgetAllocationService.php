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
    public function allocate(WeddingProfile $profile): array
    {
        $items = [];
        $subtotal = 0;
        $contingencyRow = null;

        foreach (WeddingMasterData::BUDGET_ITEMS as $row) {
            if ($row['category'] === WeddingCategory::Contingency->value) {
                $contingencyRow = $row;

                continue;
            }

            $estimated = $row['per_guest']
                ? $row['min'] * (int) $profile->guest_target
                : $row['min'];
            $subtotal += $estimated;

            $items[] = BudgetItem::query()->create([
                'category' => $row['category'],
                'title' => $row['title'],
                'estimated_cost' => $estimated,
                'actual_cost' => 0,
                'paid_amount' => 0,
                'notes' => $this->researchNote($row),
            ]);
        }

        if ($contingencyRow !== null) {
            $items[] = BudgetItem::query()->create([
                'category' => $contingencyRow['category'],
                'title' => $contingencyRow['title'],
                'estimated_cost' => (int) round($subtotal * self::CONTINGENCY_PERCENT / 100),
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
