<?php

declare(strict_types=1);

namespace App\Enums;

enum WeddingCategory: string
{
    case Venue = 'venue';
    case Catering = 'catering';
    case Attire = 'attire';
    case Decoration = 'decoration';
    case Documentation = 'documentation';
    case Souvenir = 'souvenir';
    case Documents = 'documents';
    case Entertainment = 'entertainment';

    public function label(): string
    {
        return match ($this) {
            self::Venue => 'Venue & Gedung',
            self::Catering => 'Katering & Jamuan',
            self::Attire => 'Busana & Rias',
            self::Decoration => 'Dekorasi & Floral',
            self::Documentation => 'Dokumentasi Foto/Video',
            self::Souvenir => 'Souvenir & Undangan',
            self::Documents => 'Dokumen & KUA/Catatan Sipil',
            self::Entertainment => 'Musik, MC & Hiburan',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Venue => 'Venue',
            self::Catering => 'Catering',
            self::Attire => 'Busana',
            self::Decoration => 'Dekorasi',
            self::Documentation => 'Dokumentasi',
            self::Souvenir => 'Souvenir',
            self::Documents => 'Dokumen',
            self::Entertainment => 'Entertainment',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Venue => 'building-2',
            self::Catering => 'utensils-crossed',
            self::Attire => 'sparkles',
            self::Decoration => 'flower-2',
            self::Documentation => 'camera',
            self::Souvenir => 'gift',
            self::Documents => 'file-check-2',
            self::Entertainment => 'music',
        };
    }

    public function recommendedSharePercent(): int
    {
        return match ($this) {
            self::Venue => 20,
            self::Catering => 32,
            self::Attire => 10,
            self::Decoration => 14,
            self::Documentation => 8,
            self::Souvenir => 6,
            self::Documents => 4,
            self::Entertainment => 6,
        };
    }
}
