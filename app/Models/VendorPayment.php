<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToWeddingProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class VendorPayment extends Model
{
    use BelongsToWeddingProfile, HasFactory;

    protected $fillable = [
        'wedding_profile_id',
        'vendor_id',
        'payment_stage',
        'amount',
        'paid_date',
        'receipt_ref',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_date' => 'date',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
