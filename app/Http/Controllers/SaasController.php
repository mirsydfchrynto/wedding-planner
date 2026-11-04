<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WeddingMetricsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class SaasController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function landing(): View
    {
        $summary = $this->metricsService->getDashboardSummary();
        $targetRoute = Auth::check() ? route('dashboard.index') : route('login');

        $packages = [
            [
                'name' => 'Akad Intimate',
                'audience' => 'Pasangan Mandiri (B2C)',
                'price' => 'Rp 0',
                'period' => 'Selamanya untuk 1 pernikahan',
                'description' => 'Buku kerja digital untuk pasangan yang menyusun anggaran, daftar tugas, dan jadwal akad secara mandiri.',
                'features' => [
                    '5 Modul Inti (Dashboard, Anggaran, Checklist, Linimasa, Vendor)',
                    'Kalkulator alokasi 8 kategori pernikahan Indonesia',
                    'Ekspor & cetak ringkasan eksekutif untuk rapat keluarga',
                ],
                'cta_label' => 'Mulai Gratis dengan Google',
                'cta_route' => $targetRoute,
                'featured' => false,
            ],
            [
                'name' => 'Keraton Royal',
                'audience' => 'Pasangan & Keluarga Besar',
                'price' => 'Rp 490.000',
                'period' => 'Sekali bayar untuk 12 bulan persiapan',
                'description' => 'Dirancang untuk pernikahan adat Nusantara (Jawa, Sunda, Minang, Nasional) dengan koordinasi keluarga & vendor.',
                'features' => [
                    'Seluruh fitur Akad Intimate + Template Prosesi Adat Jawa (Siraman, Midodareni, Panggih)',
                    'Pelacakan kontrak, termin DP, dan pengingat jatuh tempo WhatsApp vendor',
                    'Audit deviasi anggaran otomatis per kategori dan simulasi biaya per tamu',
                ],
                'cta_label' => 'Pilih Paket Keraton Royal',
                'cta_route' => $targetRoute,
                'featured' => true,
            ],
            [
                'name' => 'Atelier Enterprise WO',
                'audience' => 'Wedding Organizer & Venue (B2B)',
                'price' => 'Rp 1.850.000',
                'period' => 'Per bulan · hingga 25 pasangan aktif',
                'description' => 'Ruang kerja terkelola bagi biro Wedding Organizer untuk memantau seluruh klien, SLA vendor, dan arus kas.',
                'features' => [
                    'Workspace multi-pasangan & verifikasi status kontrak vendor',
                    'Eskalasi satu-klik untuk tugas yang melewati tenggat waktu (overdue)',
                    'Arsitektur Twelve-Factor siap pakai (SQLite/MySQL, CI/CD, tanpa vendor lock-in)',
                ],
                'cta_label' => 'Masuk Akun Mitra WO',
                'cta_route' => route('login'),
                'featured' => false,
            ],
        ];

        return view('saas.landing', [
            'summary' => $summary,
            'profile' => $summary['profile'],
            'packages' => $packages,
        ]);
    }
}
