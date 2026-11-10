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
    case Makeup = 'makeup';
    case Transport = 'transport';
    case Jewelry = 'jewelry';
    case Contingency = 'contingency';
    case Planning = 'planning';
    case Vendor = 'vendor';
    case Ceremony = 'ceremony';

    /**
     * Categories that hold budget rows (the 12 research categories).
     *
     * @return array<self>
     */
    public static function budgetCases(): array
    {
        return [
            self::Documents,
            self::Venue,
            self::Catering,
            self::Decoration,
            self::Attire,
            self::Makeup,
            self::Documentation,
            self::Entertainment,
            self::Souvenir,
            self::Transport,
            self::Jewelry,
            self::Contingency,
        ];
    }

    /**
     * Categories a vendor can be booked for.
     *
     * @return array<self>
     */
    public static function vendorCases(): array
    {
        return [
            self::Venue,
            self::Catering,
            self::Attire,
            self::Makeup,
            self::Decoration,
            self::Documentation,
            self::Entertainment,
            self::Souvenir,
            self::Transport,
            self::Jewelry,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::Venue => 'Venue & Gedung',
            self::Catering => 'Katering & Jamuan',
            self::Attire => 'Busana',
            self::Decoration => 'Dekorasi',
            self::Documentation => 'Dokumentasi',
            self::Souvenir => 'Undangan & Souvenir',
            self::Documents => 'Administrasi & Legalitas',
            self::Entertainment => 'Hiburan & Acara',
            self::Makeup => 'Makeup & Hairdo',
            self::Transport => 'Transportasi',
            self::Jewelry => 'Perhiasan & Seremonial',
            self::Contingency => 'Dana Cadangan',
            self::Planning => 'Perencanaan',
            self::Vendor => 'Vendor & WO',
            self::Ceremony => 'Upacara & Adat',
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
            self::Documents => 'Administrasi',
            self::Entertainment => 'Hiburan',
            self::Makeup => 'Makeup',
            self::Transport => 'Transportasi',
            self::Jewelry => 'Perhiasan',
            self::Contingency => 'Dana Cadangan',
            self::Planning => 'Perencanaan',
            self::Vendor => 'Vendor',
            self::Ceremony => 'Upacara',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Venue => 'building-2',
            self::Catering => 'utensils-crossed',
            self::Attire => 'users',
            self::Decoration => 'flower-2',
            self::Documentation => 'camera',
            self::Souvenir => 'gift',
            self::Documents => 'file-check-2',
            self::Entertainment => 'music',
            self::Makeup => 'flower-2',
            self::Transport => 'arrow-right',
            self::Jewelry => 'gift',
            self::Contingency => 'shield-check',
            self::Planning => 'calendar',
            self::Vendor => 'users',
            self::Ceremony => 'shield-check',
        };
    }

    /**
     * Share of the total budget, in percent. Budget categories sum to 100.
     * Resepsi group (venue, catering, decoration, contingency) = 60%, per the research report.
     */
    public function recommendedSharePercent(): int
    {
        return match ($this) {
            self::Documents => 2,
            self::Venue => 15,
            self::Catering => 30,
            self::Decoration => 10,
            self::Attire => 10,
            self::Makeup => 3,
            self::Documentation => 8,
            self::Entertainment => 6,
            self::Souvenir => 5,
            self::Transport => 3,
            self::Jewelry => 3,
            self::Contingency => 5,
            self::Planning, self::Vendor, self::Ceremony => 0,
        };
    }
}
