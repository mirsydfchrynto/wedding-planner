<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

final class WeddingProfile extends Model
{
    use HasFactory;

    private static ?int $tenancyOverride = null;

    public static function useTenancy(?int $profileId): void
    {
        self::$tenancyOverride = $profileId;
    }

    public static function currentId(): ?int
    {
        if (self::$tenancyOverride !== null) {
            return self::$tenancyOverride;
        }

        if (Auth::user()?->isAdmin() && session()->has('impersonated_profile_id')) {
            return (int) session('impersonated_profile_id');
        }

        return Auth::user()?->weddingProfile?->id;
    }

    public static function current(): ?self
    {
        if (self::$tenancyOverride !== null) {
            return self::query()->find(self::$tenancyOverride);
        }

        if (Auth::user()?->isAdmin() && session()->has('impersonated_profile_id')) {
            return self::query()->find(session('impersonated_profile_id'));
        }

        return Auth::user()?->weddingProfile;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendorPayments(): HasMany
    {
        return $this->hasMany(VendorPayment::class)->latest('paid_date');
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeddingTask::class);
    }

    protected $fillable = [
        'user_id',
        'groom_name',
        'bride_name',
        'wedding_date',
        'wedding_time',
        'venue_name',
        'city',
        'total_budget',
        'guest_target',
        'theme_concept',
        'event_type',
        'helper_mode',
        'venue_type',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'total_budget' => 'integer',
            'guest_target' => 'integer',
        ];
    }

    public function getCoupleNamesAttribute(): string
    {
        return "{$this->bride_name} & {$this->groom_name}";
    }

    public function getDaysRemainingAttribute(): int
    {
        if (! $this->wedding_date) {
            return 0;
        }

        $days = (int) now()->startOfDay()->diffInDays($this->wedding_date->copy()->startOfDay(), false);

        return max(0, $days);
    }
}
