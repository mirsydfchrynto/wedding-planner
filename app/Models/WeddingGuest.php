<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToWeddingProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class WeddingGuest extends Model
{
    use BelongsToWeddingProfile, HasFactory;

    protected $fillable = [
        'wedding_profile_id',
        'name',
        'category',
        'pax',
        'session',
        'rsvp_status',
        'table_number',
        'whatsapp_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'pax' => 'integer',
        ];
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (! $this->whatsapp_number) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $greeting = urlencode("Halo {$this->name}, kami dari tim panitia pernikahan ingin mengonfirmasi kehadiran Anda pada acara kami.");

        return "https://wa.me/{$phone}?text={$greeting}";
    }
}
