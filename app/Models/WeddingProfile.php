<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class WeddingProfile extends Model
{
    use HasFactory;

    protected $fillable = [
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
        $days = (int) now()->startOfDay()->diffInDays($this->wedding_date->startOfDay(), false);

        return max(0, $days);
    }
}
