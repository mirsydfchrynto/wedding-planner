<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

final class BudgetSimulator extends Component
{
    public int $guests = 450;

    public string $adatScale = 'keraton';

    public function setScale(string $scale): void
    {
        $this->adatScale = $scale;
    }

    public function render(): View
    {
        $baseAdat = match ($this->adatScale) {
            'intimate' => 45000000,
            'grand' => 135000000,
            default => 95000000,
        };

        $total = ($this->guests * 650000) + $baseAdat;

        $allocations = [
            ['label' => 'Katering & Jamuan', 'percent' => 35, 'amount' => (int) round($total * 0.35), 'icon' => 'utensils'],
            ['label' => 'Venue & Gedung', 'percent' => 22, 'amount' => (int) round($total * 0.22), 'icon' => 'building'],
            ['label' => 'Dekorasi & Adat', 'percent' => 23, 'amount' => (int) round($total * 0.23), 'icon' => 'flower'],
            ['label' => 'Busana, Foto & WO', 'percent' => 20, 'amount' => (int) round($total * 0.20), 'icon' => 'camera'],
        ];

        return view('livewire.budget-simulator', [
            'total' => $total,
            'allocations' => $allocations,
        ]);
    }
}
