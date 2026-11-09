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
            $phone = '62' . substr($phone, 1);
        }

        $greeting = urlencode("Halo {$this->name}, kami dari tim panitia pernikahan ingin mengonfirmasi kehadiran Anda pada acara kami.");

        return "https://wa.me/{$phone}?text={$greeting}";
    }


    public function whatsappUrlForTemplate(string $template): ?string
    {
        if (! $this->whatsapp_number) {
            return null;
        }

        // Membersihkan nomor WhatsApp dari spasi, tanda +, dan karakter lainnya.
        $phone = preg_replace('/\D+/', '', $this->whatsapp_number) ?? '';

        if ($phone === '') {
            return null;
        }

        // Mengubah format nomor Indonesia menjadi format internasional 62.
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        // Menentukan isi pesan berdasarkan template yang dipilih.
        $message = match ($template) {
            'formal' => "Yth. Bapak/Ibu {$this->name},\n\nDengan hormat, kami ingin mengonfirmasi kehadiran Bapak/Ibu pada acara pernikahan kami. Mohon berkenan menginformasikan apakah Bapak/Ibu dapat hadir.\n\nTerima kasih atas perhatian dan waktunya.\n\nHormat kami,\nKeluarga mempelai.",

            'casual' => "Halo {$this->name}! 😊\n\nApa kabar? Kami ingin memastikan apakah kamu bisa hadir di acara pernikahan kami. Kalau sudah tahu, boleh kabari kami ya!\n\nTerima kasih. Sampai bertemu di hari bahagia kami! ❤️",

            default => null,
        };

        if ($message === null) {
            return null;
        }

        return "https://wa.me/{$phone}?text=" . rawurlencode($message);
    }
}
