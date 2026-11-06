<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToWeddingProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class WeddingRundown extends Model
{
    use BelongsToWeddingProfile, HasFactory;

    protected $fillable = [
        'wedding_profile_id',
        'start_time',
        'end_time',
        'session_name',
        'activity',
        'location',
        'pic_name',
        'pic_phone',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function getTimeRangeAttribute(): string
    {
        return "{$this->start_time} - {$this->end_time} WIB";
    }
}
