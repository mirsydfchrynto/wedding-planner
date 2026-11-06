<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return self::$tenancyOverride ?? Auth::user()?->weddingProfile?->id;
    }

    public static function current(): ?self
    {
        return Auth::user()?->weddingProfile;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
