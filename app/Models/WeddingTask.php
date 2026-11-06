<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\WeddingCategory;
use App\Models\Concerns\BelongsToWeddingProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WeddingTask extends Model
{
    use BelongsToWeddingProfile, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'phase',
        'due_date',
        'status',
        'priority',
        'assigned_to',
        'is_milestone',
        'vendor_id',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'category' => WeddingCategory::class,
            'status' => TaskStatus::class,
            'due_date' => 'date',
            'is_milestone' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === TaskStatus::Done || ! $this->due_date) {
            return false;
        }

        return $this->due_date->endOfDay()->isPast();
    }

    public function getDaysUntilDueAttribute(): int
    {
        if (! $this->due_date) {
            return 0;
        }

        return (int) now()->startOfDay()->diffInDays($this->due_date->startOfDay(), false);
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'Prioritas Tinggi',
            'low' => 'Standar',
            default => 'Penting',
        };
    }

    public function getPriorityToneAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'wine',
            'low' => 'neutral',
            default => 'champagne',
        };
    }
}
