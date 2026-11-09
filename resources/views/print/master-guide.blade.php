<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Panduan Pernikahan — {{ $profile->couple_names }} | Atelier Vow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #1a1a1a !important; font-size: 11pt; }
            .page-break { break-after: page; page-break-after: always; }
            .avoid-break { break-inside: avoid; page-break-inside: avoid; }
            .print-border { border: 1px solid #d1d5db !important; }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
        .font-serif-editorial { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>
<body class="bg-pearl font-sans text-charcoal antialiased print:bg-white">

    {{-- Top Action Toolbar (Hidden during print) --}}
    <header class="no-print sticky top-0 z-50 border-b border-stone-line bg-white/95 px-6 py-3.5 backdrop-blur shadow-sm">
        <div class="mx-auto flex max-w-5xl items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.index') }}" class="inline-flex items-center gap-2 rounded-md border border-stone-line bg-alabaster px-3 py-1.5 text-xs font-semibold text-charcoal hover:bg-pearl transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Workspace</span>
                </a>
                <span class="rounded bg-champagne/15 px-2.5 py-1 text-[11px] font-semibold text-espresso">
                    Format Dokumen Cetak A4 &amp; PDF Resmi
                </span>
            </div>
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-md bg-espresso px-4 py-2 text-xs font-bold text-alabaster shadow hover:bg-charcoal transition cursor-pointer"
                >
                    <svg class="h-4 w-4 text-champagne" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak / Unduh PDF (Ctrl+P)</span>
                </button>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-4xl p-6 sm:p-10 print:max-w-none print:p-0">

        {{-- ======================================================== --}}
        {{-- BAGIAN 1: COVER DOKUMEN & RINGKASAN EKSEKUTIF --}}
        {{-- ======================================================== --}}
        <article class="page-break mb-10 rounded-xl border border-stone-line bg-white p-8 sm:p-12 shadow-sm print:border-none print:p-0 print:shadow-none">
            {{-- Header Badge --}}
            <div class="flex items-center justify-between border-b border-stone-line pb-6">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-champagne">Atelier Vow Nusantara &bull; Master Wedding Guide</span>
                    <h1 class="mt-1 font-serif-editorial text-3xl sm:text-4xl font-bold text-espresso">{{ $profile->couple_names }}</h1>
                    <p class="mt-1 text-sm text-taupe font-medium">Buku Kerja &amp; Panduan Operasional Resmi Hari Pernikahan</p>
                </div>
                <div class="text-right">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-espresso text-base font-black text-alabaster">
                        AV
                    </div>
                    <p class="mt-1 text-[10px] text-taupe">ID Profil: #WP-{{ str_pad((string)$profile->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            {{-- Detail Inti Pernikahan --}}
            <div class="mt-8 grid grid-cols-2 gap-6 sm:grid-cols-4 border-b border-stone-line pb-8">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-taupe">Tanggal Pernikahan</p>
                    <p class="mt-1 text-sm font-bold text-espresso">{{ $summary['wedding_date_formatted'] }}</p>
                    <p class="text-xs text-taupe">{{ $profile->wedding_time }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-taupe">Lokasi &amp; Venue</p>
                    <p class="mt-1 text-sm font-bold text-espresso">{{ $profile->venue_name }}</p>
                    <p class="text-xs text-taupe">{{ $profile->city }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-taupe">Target Undangan</p>
                    <p class="mt-1 text-sm font-bold text-espresso">{{ number_format($profile->guest_target) }} Tamu</p>
                    <p class="text-xs text-taupe">{{ $guests->count() }} Kontak Terdata</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-taupe">Konsep &amp; Adat</p>
                    <p class="mt-1 text-sm font-bold text-espresso truncate">{{ $profile->theme_concept }}</p>
                    <p class="text-xs text-taupe">Kesiapan: {{ $summary['readiness_score'] }}%</p>
                </div>
            </div>

            {{-- Executive Financial & Readiness Metrics --}}
            <div class="mt-8">
                <h2 class="text-xs font-bold uppercase tracking-wider text-espresso">Ringkasan Anggaran &amp; Kesiapan Tim</h2>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="rounded-lg border border-stone-line bg-pearl/60 p-4">
                        <p class="text-[10px] font-bold uppercase text-taupe">Plafon Anggaran</p>
                        <p class="mt-1 text-lg font-bold text-espresso whitespace-nowrap">Rp {{ number_format($summary['total_budget_cap'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-stone-line bg-pearl/60 p-4">
                        <p class="text-[10px] font-bold uppercase text-taupe">Total Komitmen</p>
                        <p class="mt-1 text-lg font-bold text-espresso whitespace-nowrap">Rp {{ number_format($summary['total_estimated'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-stone-line bg-pearl/60 p-4">
                        <p class="text-[10px] font-bold uppercase text-taupe">Sudah Terbayar</p>
                        <p class="mt-1 text-lg font-bold text-forest whitespace-nowrap">Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-stone-line bg-pearl/60 p-4">
                        <p class="text-[10px] font-bold uppercase text-taupe">Sisa Tagihan Vendor</p>
                        <p class="mt-1 text-lg font-bold text-wine whitespace-nowrap">Rp {{ number_format($summary['unpaid_balance'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Instruksi Penting Hari-H --}}
            <div class="mt-8 rounded-lg border border-champagne/40 bg-champagne/10 p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-espresso">Protokol Komunikasi Panitia &amp; Keluarga</h3>
                <ul class="mt-2.5 list-disc space-y-1.5 pl-5 text-xs text-charcoal">
                    <li>Seluruh koordinasi teknis vendor Hari-H dipimpin oleh Lead WO (tidak membebani pengantin &amp; orang tua).</li>
                    <li>Saksi nikah dan keluarga inti wajib tiba di lokasi minimal 45 menit sebelum prosesi Ijab Qabul dimulai.</li>
                    <li>Pelunasan vendor yang jatuh tempo pada Hari-H divalidasi oleh bendahara keluarga bersama kwitansi resmi.</li>
                </ul>
            </div>
        </article>

        {{-- ======================================================== --}}
        {{-- BAGIAN 2: RUNDOWN ACARA RESMI HARI-H (MINUTE-BY-MINUTE) --}}
        {{-- ======================================================== --}}
        <article class="page-break mb-10 rounded-xl border border-stone-line bg-white p-8 sm:p-12 shadow-sm print:border-none print:p-0 print:shadow-none">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-champagne">Operasional Hari-H</span>
                    <h2 class="font-serif-editorial text-2xl font-bold text-espresso">Rundown Acara Menit-demi-Menit</h2>
                </div>
                <div class="text-right text-xs text-taupe">
                    Total Sesi: {{ $rundowns->count() }} Kegiatan
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="border-b-2 border-espresso text-[10px] font-bold uppercase tracking-wider text-espresso">
                            <th scope="col" class="py-2.5 pr-3 w-28">Waktu (WIB)</th>
                            <th scope="col" class="px-3 py-2.5 w-40">Sesi &amp; Acara</th>
                            <th scope="col" class="px-3 py-2.5">Aktivitas &amp; Deskripsi Detail</th>
                            <th scope="col" class="px-3 py-2.5 w-36">Lokasi</th>
                            <th scope="col" class="pl-3 py-2.5 w-44">Penanggung Jawab (PIC)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-line">
                        @forelse($rundowns as $rundown)
                            <tr class="avoid-break hover:bg-pearl/40">
                                <td class="py-3 pr-3 font-mono font-bold text-espresso whitespace-nowrap">
                                    {{ $rundown->start_time }} - {{ $rundown->end_time }}
                                </td>
                                <td class="px-3 py-3 font-semibold text-espresso">
                                    {{ $rundown->session_name }}
                                </td>
                                <td class="px-3 py-3 text-charcoal">
                                    <p class="font-medium text-espresso">{{ $rundown->activity }}</p>
                                    @if($rundown->notes)
                                        <p class="mt-0.5 text-[11px] text-taupe italic">{{ $rundown->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-taupe">
                                    {{ $rundown->location ?? '-' }}
                                </td>
                                <td class="pl-3 py-3 text-espresso">
                                    <p class="font-semibold">{{ $rundown->pic_name ?? 'Tim WO' }}</p>
                                    @if($rundown->pic_phone)
                                        <p class="font-mono text-[10px] text-taupe">{{ $rundown->pic_phone }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-taupe">Belum ada item rundown tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        {{-- ======================================================== --}}
        {{-- BAGIAN 3: DIREKTORI KONTAK VENDOR & STATUS PEMBAYARAN --}}
        {{-- ======================================================== --}}
        <article class="page-break mb-10 rounded-xl border border-stone-line bg-white p-8 sm:p-12 shadow-sm print:border-none print:p-0 print:shadow-none">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-champagne">Mitra Kerja Pernikahan</span>
                    <h2 class="font-serif-editorial text-2xl font-bold text-espresso">Direktori Kontak Vendor &amp; Rekap Pembayaran</h2>
                </div>
                <div class="text-right text-xs text-taupe">
                    {{ $vendors->count() }} Vendor Terdaftar
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="border-b-2 border-espresso text-[10px] font-bold uppercase tracking-wider text-espresso">
                            <th scope="col" class="py-2.5 pr-3">Mitra Vendor &amp; Kategori</th>
                            <th scope="col" class="px-3 py-2.5">Kontak PIC / WhatsApp</th>
                            <th scope="col" class="px-3 py-2.5 text-right">Nilai Kontrak</th>
                            <th scope="col" class="px-3 py-2.5 text-right">Terbayar</th>
                            <th scope="col" class="px-3 py-2.5 text-right">Sisa Tagihan</th>
                            <th scope="col" class="pl-3 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-line">
                        @foreach($vendors as $vendor)
                            <tr class="avoid-break hover:bg-pearl/40">
                                <td class="py-3 pr-3">
                                    <p class="font-bold text-espresso">{{ $vendor->name }}</p>
                                    <span class="inline-block mt-0.5 rounded bg-pearl px-1.5 py-0.5 text-[10px] font-semibold text-taupe">
                                        {{ $vendor->category->label() }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-charcoal">
                                    <p class="font-medium text-espresso">{{ $vendor->contact_person }}</p>
                                    <p class="font-mono text-[11px] text-taupe">{{ $vendor->phone }}</p>
                                </td>
                                <td class="px-3 py-3 text-right font-mono font-medium text-espresso whitespace-nowrap">
                                    Rp {{ number_format($vendor->contract_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right font-mono font-medium text-forest whitespace-nowrap">
                                    Rp {{ number_format($vendor->paid_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right font-mono font-bold whitespace-nowrap {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-taupe' }}">
                                    Rp {{ number_format($vendor->remaining_balance, 0, ',', '.') }}
                                </td>
                                <td class="pl-3 py-3 text-center">
                                    <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold {{ $vendor->booking_status->isConfirmed() ? 'bg-forest/10 text-forest' : 'bg-ochre/15 text-ochre' }}">
                                        {{ $vendor->booking_status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Riwayat Termin Pembayaran Terbaru --}}
            <div class="mt-8 pt-6 border-t border-stone-line">
                <h3 class="text-xs font-bold uppercase tracking-wider text-espresso">Catatan Riwayat Pembayaran Termin (Validasi Pembukuan)</h3>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    @php
                        $allPayments = $vendors->flatMap->payments->sortByDesc('paid_date')->take(6);
                    @endphp
                    @foreach($allPayments as $payment)
                        <div class="rounded border border-stone-line bg-alabaster p-2.5">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-espresso">{{ $payment->vendor->name }}</span>
                                <span class="font-mono font-bold text-forest">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-1 flex items-center justify-between text-[11px] text-taupe">
                                <span>{{ $payment->payment_stage }} (Ref: {{ $payment->receipt_ref ?? '-' }})</span>
                                <span>{{ $payment->paid_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </article>

        {{-- ======================================================== --}}
        {{-- BAGIAN 4: BUKU TAMU VIP & DAFTAR MEJA KEHORMATAN --}}
        {{-- ======================================================== --}}
        <article class="mb-10 rounded-xl border border-stone-line bg-white p-8 sm:p-12 shadow-sm print:border-none print:p-0 print:shadow-none">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-champagne">Protokoler &amp; Hospitality</span>
                    <h2 class="font-serif-editorial text-2xl font-bold text-espresso">Daftar Tamu Kehormatan (VIP) &amp; Penataan Meja</h2>
                </div>
                <div class="text-right text-xs text-taupe">
                    Target Kapasitas: {{ $profile->guest_target }} Pax
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="border-b-2 border-espresso text-[10px] font-bold uppercase tracking-wider text-espresso">
                            <th scope="col" class="py-2.5 pr-3">Nama Tamu Undangan</th>
                            <th scope="col" class="px-3 py-2.5">Kategori Tamu</th>
                            <th scope="col" class="px-3 py-2.5 text-center">Pax</th>
                            <th scope="col" class="px-3 py-2.5">Sesi Kehadiran</th>
                            <th scope="col" class="px-3 py-2.5 text-center">Nomor Meja</th>
                            <th scope="col" class="pl-3 py-2.5 text-center">Status RSVP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-line">
                        @forelse($guests as $guest)
                            <tr class="avoid-break hover:bg-pearl/40">
                                <td class="py-3 pr-3 font-semibold text-espresso">
                                    {{ $guest->name }}
                                    @if($guest->notes)
                                        <p class="mt-0.5 text-[10px] text-taupe font-normal">{{ $guest->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-taupe font-medium">
                                    {{ $guest->category }}
                                </td>
                                <td class="px-3 py-3 text-center font-bold text-espresso">
                                    {{ $guest->pax }}
                                </td>
                                <td class="px-3 py-3 text-charcoal">
                                    {{ $guest->session }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono font-bold text-espresso">
                                    <span class="rounded bg-champagne/20 px-2 py-0.5 text-[11px] text-espresso">
                                        {{ $guest->table_number ?? '-' }}
                                    </span>
                                </td>
                                <td class="pl-3 py-3 text-center">
                                    @php
                                        $statusClass = match($guest->rsvp_status) {
                                            'confirmed' => 'bg-forest/10 text-forest',
                                            'declined' => 'bg-wine/10 text-wine',
                                            default => 'bg-ochre/15 text-ochre',
                                        };
                                        $statusLabel = match($guest->rsvp_status) {
                                            'confirmed' => 'Konfirmasi Hadir',
                                            'declined' => 'Berhalangan',
                                            default => 'Menunggu RSVP',
                                        };
                                    @endphp
                                    <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-taupe">Belum ada daftar tamu tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Signature & Approval Panitia --}}
            <div class="mt-12 pt-8 border-t border-stone-line">
                <div class="grid grid-cols-3 gap-6 text-center text-xs">
                    <div>
                        <p class="text-taupe">Calon Pengantin</p>
                        <div class="mt-16 border-b border-stone-line pb-1">
                            <p class="font-bold text-espresso">{{ $profile->bride_name }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-taupe">Perwakilan Keluarga Besar</p>
                        <div class="mt-16 border-b border-stone-line pb-1">
                            <p class="font-bold text-espresso">Ketua Panitia Pernikahan</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-taupe">Lead Wedding Organizer</p>
                        <div class="mt-16 border-b border-stone-line pb-1">
                            <p class="font-bold text-espresso">Atelier Vow Management</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center text-[10px] text-taupe">
                    Dicetak otomatis dari Sistem SaaS Atelier Vow Nusantara pada {{ now()->translatedFormat('l, d F Y H:i:s') }} WIB. Dokumen ini sah sebagai panduan kerja lapangan.
                </div>
            </div>
        </article>

    </main>

</body>
</html>
