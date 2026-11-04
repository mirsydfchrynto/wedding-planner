<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WeddingCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'estimated_cost',
        'actual_cost',
        'paid_amount',
        'vendor_id',
        'due_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'category' => WeddingCategory::class,
            'estimated_cost' => 'integer',
            'actual_cost' => 'integer',
            'paid_amount' => 'integer',
            'due_date' => 'date',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getRemainingBalanceAttribute(): int
    {
        $target = (int) $this->actual_cost > 0 ? (int) $this->actual_cost : (int) $this->estimated_cost;

        return max(0, $target - (int) $this->paid_amount);
    }

    public function getVarianceAmountAttribute(): int
    {
        return (int) $this->estimated_cost - (int) $this->actual_cost;
    }

    public function getIsOverbudgetAttribute(): bool
    {
        return (int) $this->actual_cost > (int) $this->estimated_cost && (int) $this->estimated_cost > 0;
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        $target = (int) $this->actual_cost > 0 ? (int) $this->actual_cost : (int) $this->estimated_cost;
        if ($target <= 0 || (int) $this->paid_amount <= 0) {
            return 'Belum Bayar';
        }
        if ((int) $this->paid_amount >= $target) {
            return 'Lunas';
        }

        return 'Cicilan / DP';
    }

    public function getPaymentStatusToneAttribute(): string
    {
        $target = (int) $this->actual_cost > 0 ? (int) $this->actual_cost : (int) $this->estimated_cost;
        if ($target <= 0 || (int) $this->paid_amount <= 0) {
            return 'neutral';
        }
        if ((int) $this->paid_amount >= $target) {
            return 'sage';
        }

        return 'ochre';
    }
}
