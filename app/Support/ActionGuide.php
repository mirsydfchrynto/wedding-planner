<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;

final class ActionGuide
{
    /**
     * @return array{steps: list<string>, wa_text: string, wa_url: ?string, target_name: string}
     */
    public static function forTask(WeddingTask $task, ?WeddingProfile $profile = null): array
    {
        $couple = $profile?->couple_names ?? 'Pengantin';
        $weddingDate = $profile?->wedding_date?->translatedFormat('d F Y') ?? 'hari bahagia';
        $venue = $profile?->venue_name ?? 'venue pernikahan';
        $titleLower = mb_strtolower($task->title);
        $catLower = mb_strtolower($task->category->value);

        $steps = match (true) {
            str_contains($titleLower, 'venue') || str_contains($titleLower, 'gedung') => [
                'Survei minimal 3 lokasi dan pastikan ketersediaan tanggal '.$weddingDate.'.',
                'Cek rincian harga sewa: apakah sudah termasuk listrik 10.000W, ruang rias AC, dan izin keramaian.',
                'Kunci tanggal dengan DP 20-30% serta minta draft kontrak resmi tertulis.',
            ],
            str_contains($titleLower, 'katering') || str_contains($titleLower, 'catering') || str_contains($titleLower, 'menu') => [
                'Jadwalkan sesi food tasting berdua beserta orang tua/wali.',
                'Hitung proporsi porsi: buffet utama 100% dan gubukan/food stall 4-5 macam (masing-masing 40-50% porsi).',
                'Minta jaminan kebersihan, waiters berseragam, dan kepastian pengemasan sisa makanan.',
            ],
            str_contains($titleLower, 'busana') || str_contains($titleLower, 'fitting') || str_contains($titleLower, 'kebaya') || str_contains($titleLower, 'jas') => [
                'Tentukan tema warna keluarga inti dan pastikan fitting pertama dilakukan minimal H-60.',
                'Jadwalkan final fitting H-14 untuk antisipasi fluktuasi berat badan menjelang hari H.',
                'Siapkan tim khusus (bestman/bridesmaid) yang bertanggung jawab atas penjemputan dan pengembalian busana sewa.',
            ],
            str_contains($titleLower, 'foto') || str_contains($titleLower, 'video') || str_contains($titleLower, 'dokumentasi') => [
                'Tinjau portofolio full-event (bukan hanya highlight 1 menit di media sosial).',
                'Sepakati deliverables: jumlah album kolase, durasi cinematic video, dan estimasi waktu selesai (maksimal 30 hari kerja).',
                'Buat shot-list wajib keluarga inti dan serahkan ke fotografer lead minimal H-7.',
            ],
            str_contains($titleLower, 'dekor') || str_contains($titleLower, 'bunga') => [
                'Bawa denah layout venue dan diskusikan titik fokus: pelaminan, lorong masuk, photobooth, dan area katering.',
                'Pastikan pencahayaan (lighting ambiance) sudah selaras dengan kebutuhan tim foto/video.',
                'Sepakati waktu loading masuk gedung (minimal H-1 malam) dan waktu teardown selesai.',
            ],
            str_contains($titleLower, 'undangan') || str_contains($titleLower, 'tamu') => [
                'Finalisasi daftar nama tamu di Excel/Buku Tamu dan kelompokkan per kategori (VIP, keluarga, rekan kerja).',
                'Sebarkan Save the Date digital di H-60 dan link undangan resmi interaktif di H-21.',
                'Pantau konfirmasi RSVP aktif di H-7 untuk mengunci pesanan porsi katering final.',
            ],
            str_contains($titleLower, 'kua') || str_contains($titleLower, 'surat') || str_contains($titleLower, 'dokumen') || str_contains($titleLower, 'legalitas') => [
                'Urus surat pengantar RT/RW dan kelurahan (model N1 s/d N4) di domisili masing-masing calon mempelai.',
                'Daftar ke KUA kecamatan via simkah.kemenag.go.id minimal 30 hari kerja sebelum tanggal akad.',
                'Lakukan pemeriksaan kesehatan pranikah (sertifikat Elsimil / Puskesmas) dan ikuti bimbingan perkawinan.',
            ],
            str_contains($titleLower, 'mahar') || str_contains($titleLower, 'cincin') || str_contains($titleLower, 'seserahan') => [
                'Beli cincin kawin minimal H-45 untuk estimasi grafir nama dan penyesuaian ukuran.',
                'Tentukan rincian mahar (uang tunai, logam mulia, alat sholat) dan koordinasikan dengan penghulu.',
                'Kemas seserahan dalam boks mika/akrilik dan serahkan ke penanggung jawab keluarga di H-2.',
            ],
            str_contains($titleLower, 'rundown') || str_contains($titleLower, 'gladi') || str_contains($titleLower, 'rapat') => [
                'Adakan rapat koordinasi keluarga inti dan seluruh vendor (Technical Meeting) di venue H-14.',
                'Bagikan susunan acara final menit demi menit beserta kontak darurat PIC masing-masing.',
                'Lakukan gladi bersih posisi prosesi akad dan resepsi agar seluruh pengisi acara tenang.',
            ],
            default => [
                'Pahami batas tanggung jawab dan kebutuhan rincian tugas "'.$task->title.'".',
                'Hubungi PIC penanggung jawab ('.$task->assigned_to.') untuk menyamakan tenggat waktu.',
                'Catat progres nyata dan tandai selesai di buku catatan begitu tugas tuntas.',
            ],
        };

        $targetName = $task->vendor?->name ?? $task->assigned_to;
        $phone = $task->vendor?->phone;

        $waText = "Halo {$targetName},\n\nKami dari pihak {$couple} ingin berkoordinasi mengenai persiapan pernikahan kami tanggal {$weddingDate} di {$venue}.\n\nPerihal: {$task->title}\nTenggat koordinasi: {$task->due_date->translatedFormat('d F Y')}\n\nMohon konfirmasi dan arahan langkah selanjutnya ya. Terima kasih banyak.";

        $waUrl = $phone ? 'https://wa.me/'.preg_replace('/\D+/', '', $phone).'?text='.rawurlencode($waText) : null;

        return [
            'steps' => $steps,
            'wa_text' => $waText,
            'wa_url' => $waUrl,
            'target_name' => $targetName,
        ];
    }

    /**
     * @return array{steps: list<string>, wa_text: string, wa_url: ?string, target_name: string}
     */
    public static function forVendor(Vendor $vendor, ?WeddingProfile $profile = null): array
    {
        $couple = $profile?->couple_names ?? 'Pengantin';
        $weddingDate = $profile?->wedding_date?->translatedFormat('d F Y') ?? 'hari pernikahan';
        $venue = $profile?->venue_name ?? 'lokasi acara';

        $steps = [
            'Konfirmasi ulang paket kontrak dan deliverables yang disepakati untuk tanggal '.$weddingDate.'.',
            'Jadwalkan technical meeting lapangan bersama tim WO/panitia keluarga di '.$venue.'.',
            'Verifikasi termin pembayaran berikutnya dan pastikan kwitansi serta surat tanda terima tercatat rapi.',
        ];

        $waText = "Halo Kak {$vendor->contact_person} ({$vendor->name}),\n\nKami dari {$couple} ingin follow-up koordinasi vendor {$vendor->category->label()} untuk tanggal {$weddingDate}.\n\nStatus saat ini:\n- Nilai Kontrak: Rp".number_format($vendor->contract_amount, 0, ',', '.')."\n- Terbayar: Rp".number_format($vendor->paid_amount, 0, ',', '.')."\n- Sisa Tagihan: Rp".number_format($vendor->remaining_balance, 0, ',', '.')."\n\nBoleh mohon informasi jadwal koordinasi dan persiapan teknis selanjutnya? Terima kasih.";

        $waUrl = $vendor->phone ? 'https://wa.me/'.preg_replace('/\D+/', '', $vendor->phone).'?text='.rawurlencode($waText) : null;

        return [
            'steps' => $steps,
            'wa_text' => $waText,
            'wa_url' => $waUrl,
            'target_name' => $vendor->contact_person ?: $vendor->name,
        ];
    }
}
