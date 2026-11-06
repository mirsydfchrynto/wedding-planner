<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'contact_person',
        'phone',
        'email',
        'instagram',
        'contract_amount',
        'paid_amount',
        'booking_status',
        'payment_due_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'category' => WeddingCategory::class,
            'booking_status' => VendorBookingStatus::class,
            'contract_amount' => 'integer',
            'paid_amount' => 'integer',
            'payment_due_date' => 'date',
        ];
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeddingTask::class);
    }

    public function getRemainingBalanceAttribute(): int
    {
        return max(0, (int) $this->contract_amount - (int) $this->paid_amount);
    }

    public function getPaymentProgressPercentAttribute(): int
    {
        if ((int) $this->contract_amount <= 0) {
            return 0;
        }

        return (int) min(100, round(((int) $this->paid_amount / (int) $this->contract_amount) * 100));
    }

    public function getWhatsappUrlAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone) ?? '';
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return "https://wa.me/{$digits}";
    }

    public function getIsPaymentOverdueAttribute(): bool
    {
        if ($this->remaining_balance <= 0 || ! $this->payment_due_date) {
            return false;
        }

        return $this->payment_due_date->endOfDay()->isPast();
    }

    public function getDaysUntilPaymentDueAttribute(): ?int
    {
        if (! $this->payment_due_date) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($this->payment_due_date->startOfDay(), false);
    }

    public function scopeFilterAndSort(
        Builder $query,
        ?string $category = null,
        ?string $bookingStatus = null,
        ?string $search = null,
        ?string $sort = null,
    ): Builder {
        if (is_string($category) && $category !== '') {
            $query->where('category', $category);
        }

        if (is_string($bookingStatus) && $bookingStatus !== '') {
            $query->where('booking_status', $bookingStatus);
        }

        if (is_string($search) && trim($search) !== '') {
            $keyword = '%' . trim($search) . '%';
            $query->where(function (Builder $subQuery) use ($keyword): void {
                $subQuery
                    ->where('name', 'like', $keyword)
                    ->orWhere('contact_person', 'like', $keyword)
                    ->orWhere('phone', 'like', $keyword)
                    ->orWhere('notes', 'like', $keyword);
            });
        }

        return match ($sort) {
            'contract_desc' => $query->orderByDesc('contract_amount')->orderBy('name'),
            'contract_asc' => $query->orderBy('contract_amount')->orderBy('name'),
            'due_date_asc' => $query
                ->orderByRaw('CASE WHEN payment_due_date IS NULL THEN 1 ELSE 0 END ASC')
                ->orderBy('payment_due_date')
                ->orderByDesc('contract_amount'),
            'remaining_desc' => $query
                ->orderByRaw('(contract_amount - paid_amount) DESC')
                ->orderBy('name'),
            default => $query->orderBy('category')->orderBy('name'),
        };
    }
}
