<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\WeddingProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Isolates every wedding record to the signed-in couple's wedding profile.
 * With no resolved profile (console, seeder, admin console) no filter is applied.
 */
trait BelongsToWeddingProfile
{
    protected static function bootBelongsToWeddingProfile(): void
    {
        static::addGlobalScope('wedding_profile', function (Builder $query): void {
            $profileId = WeddingProfile::currentId();

            if ($profileId !== null) {
                $query->where($query->getModel()->getTable().'.wedding_profile_id', $profileId);
            }
        });

        static::creating(function (Model $model): void {
            if ($model->getAttribute('wedding_profile_id') === null) {
                $model->setAttribute('wedding_profile_id', WeddingProfile::currentId());
            }
        });
    }

    public function weddingProfile(): BelongsTo
    {
        return $this->belongsTo(WeddingProfile::class);
    }
}
