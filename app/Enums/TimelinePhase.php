<?php

declare(strict_types=1);

namespace App\Enums;

enum TimelinePhase: string
{
    case Months12 = 'H-12 Bulan';
    case Months9 = 'H-9 Bulan';
    case Months6 = 'H-6 Bulan';
    case Months3 = 'H-3 Bulan';
    case Month1 = 'H-1 Bulan';
    case Week1 = 'H-7 Hari';
    case WeddingDay = 'Hari-H';

    public function order(): int
    {
        return match ($this) {
            self::Months12 => 1,
            self::Months9 => 2,
            self::Months6 => 3,
            self::Months3 => 4,
            self::Month1 => 5,
            self::Week1 => 6,
            self::WeddingDay => 7,
        };
    }

    public function subtitle(): string
    {
        return match ($this) {
            self::Months12 => 'Penentuan konsep, anggaran induk, tanggal akad, dan penguncian venue utama.',
            self::Months9 => 'Kurasi katering utama, perancang busana pengantin, dan rumah dokumentasi.',
            self::Months6 => 'Pendaftaran berkas KUA/Catatan Sipil, desainer dekorasi floral, dan cincin kawin.',
            self::Months3 => 'Cetak undangan, pemesanan souvenir, food tasting final, dan fitting busana I.',
            self::Month1 => 'Pelunasan vendor utama, technical meeting seluruh vendor, dan distribusi undangan.',
            self::Week1 => 'Fitting akhir, gladi bersih prosesi akad, dan serah terima rundown ke WO.',
            self::WeddingDay => 'Pelaksanaan Akad Nikah, prosesi adat, dan resepsi puncak.',
        };
    }
}
