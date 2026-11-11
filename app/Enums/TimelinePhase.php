<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Work stages, not calendar offsets: due dates are spread across whatever time
 * is left before the wedding, so a profile created a year out and one created
 * tomorrow both get a usable sequence.
 */
enum TimelinePhase: string
{
    case Planning = 'Perencanaan';
    case Administration = 'Administrasi';
    case Venue = 'Venue';
    case Vendor = 'Vendor';
    case Attire = 'Busana';
    case Gifts = 'Mahar';
    case Invitations = 'Undangan';
    case Ceremony = 'Rangkaian';
    case Finalization = 'Finalisasi';
    case WeddingDay = 'Hari Pernikahan';

    public function order(): int
    {
        return match ($this) {
            self::Planning => 1,
            self::Administration => 2,
            self::Venue => 3,
            self::Vendor => 4,
            self::Attire => 5,
            self::Gifts => 6,
            self::Invitations => 7,
            self::Ceremony => 8,
            self::Finalization => 9,
            self::WeddingDay => 10,
        };
    }

    public function subtitle(): string
    {
        return match ($this) {
            self::Planning => 'Konsep, anggaran induk, tanggal, dan pembagian tugas keluarga.',
            self::Administration => 'Berkas KUA/Catatan Sipil, dokumen identitas, dan legalitas.',
            self::Venue => 'Pemilihan dan penguncian lokasi akad serta resepsi.',
            self::Vendor => 'Kurasi katering, dekorasi, dokumentasi, hiburan, dan kontrak vendor.',
            self::Attire => 'Busana pengantin, fitting, dan penampilan keluarga.',
            self::Gifts => 'Mahar, cincin, seserahan, dan perlengkapan adat.',
            self::Invitations => 'Daftar tamu, undangan, souvenir, dan distribusi.',
            self::Ceremony => 'Urutan prosesi, susunan acara, gladi, dan kebutuhan pendukung.',
            self::Finalization => 'Pelunasan, technical meeting, dan pemeriksaan akhir.',
            self::WeddingDay => 'Akad, prosesi adat, dan resepsi.',
        };
    }
}
