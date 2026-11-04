<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
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
}
