<?php

declare(strict_types=1);

namespace App\Enums;

enum VendorBookingStatus: string
{
    case Researching = 'researching';
    case Contacted = 'contacted';
    case Booked = 'booked';
    case DpPaid = 'dp_paid';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Researching => 'Riset & Kurasi',
            self::Contacted => 'Negosiasi / Survey',
            self::Booked => 'Kontrak Terkunci',
            self::DpPaid => 'DP Terbayar',
            self::Paid => 'Lunas (Paid)',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::Researching => 'neutral',
            self::Contacted => 'champagne',
            self::Booked => 'ochre',
            self::DpPaid => 'ochre',
            self::Paid => 'sage',
            self::Cancelled => 'wine',
        };
    }

    public function isConfirmed(): bool
    {
        return in_array($this, [self::Booked, self::DpPaid, self::Paid], true);
    }
}
