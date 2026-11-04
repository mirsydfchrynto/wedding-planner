@extends('layouts.app', ['title' => 'Platform Perencanaan Pernikahan Nusantara'])

@section('content')
    {{-- CHAPTER 1: HERO — FRAGMENT ON A SHAPED STAGE (Flat Stone Ground #F4F4F2) --}}
    <section class="w-full bg-alabaster text-espresso">
        <div class="mx-auto grid min-h-[86svh] max-w-7xl grid-cols-1 gap-10 px-6 py-12 lg:grid-cols-12 lg:items-center lg:px-10 lg:py-20">
            {{-- Left Columns 1–5: Short 2-Line Headline + 18px Supporting Sentence + Google CTA --}}
            <div class="lg:col-span-5">
                {{-- deslop-ignore-next-line 11 --}}
                <h1 class="lines text-4xl font-bold leading-[1.02] tracking-tight text-espresso sm:text-6xl">
                    <span class="line"><span data-line style="--i:0">Buku kerja</span></span>
                    <span class="line"><span data-line style="--i:1">pernikahan.</span></span>
                </h1>

                <p class="mt-6 max-w-md text-lg leading-relaxed text-charcoal" data-load style="--d: 260ms">
                    Satu buku kerja untuk anggaran, vendor, dan adat pernikahan. Kelola plafon 8 kategori biaya, pantau jatuh tempo DP vendor, dan jadwalkan persiapan H-12 bulan hingga Hari-H.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4" data-load style="--d: 360ms">
                    <a
                        href="{{ auth()->check() ? route('dashboard.index') : route('login') }}"
                        class="editorial-btn-primary"
                    >
                        <span class="flex h-5 w-5 items-center justify-center rounded-sm bg-pearl p-0.5">
                            <x-ui.google-icon class="h-3.5 w-3.5" />
                        </span>
                        <span>{{ auth()->check() ? 'Buka Dashboard Pernikahan' : 'Masuk dengan Google' }}</span>
                    </a>
                    <a href="#simulasi-adat" class="editorial-btn-secondary">
                        <span>Kalkulator Anggaran</span>
                    </a>
                </div>
            </div>

            {{-- Right Columns 6–12: Shaped Stage with Live Interface Fragment #1 (data-ui) --}}
            <div class="lg:col-span-7" data-open>
                <div class="rounded-lg bg-ivory p-5 sm:p-8">
                    <div data-ui x-data="{ previewTab: 'dashboard' }" class="rounded-lg bg-pearl p-5">
                        <div class="flex flex-wrap items-center justify-between gap-2 pb-4">
                            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                                <button
                                    type="button"
                                    @click="previewTab = 'dashboard'"
                                    :class="previewTab === 'dashboard' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal'"
                                    class="rounded px-3 py-1.5 font-semibold cursor-pointer"
                                >
                                    Dashboard
                                </button>
                                <button
                                    type="button"
                                    @click="previewTab = 'budget'"
                                    :class="previewTab === 'budget' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal'"
                                    class="rounded px-3 py-1.5 font-semibold cursor-pointer"
                                >
                                    Anggaran
                                </button>
                                <button
                                    type="button"
                                    @click="previewTab = 'checklist'"
                                    :class="previewTab === 'checklist' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal'"
                                    class="rounded px-3 py-1.5 font-semibold cursor-pointer"
                                >
                                    Checklist
                                </button>
                                <button
                                    type="button"
                                    @click="previewTab = 'vendors'"
                                    :class="previewTab === 'vendors' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal'"
                                    class="rounded px-3 py-1.5 font-semibold cursor-pointer"
                                >
                                    Vendor
                                </button>
                            </div>
                            <x-ui.status-badge tone="champagne" :label="'H-' . $summary['days_remaining'] . ' Hari'" />
                        </div>

                        <div x-show="previewTab === 'dashboard'">
                            {{-- deslop-ignore-next-line 28 --}}
                            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3 text-xs">
                                <div class="rounded bg-alabaster p-3">
                                    <span class="text-taupe">Plafon Induk</span>
                                    <p class="mt-1 tabular-nums text-base font-bold text-espresso">{{ format_idr($summary['total_budget_cap']) }}</p>
                                </div>
                                <div class="rounded bg-alabaster p-3">
                                    <span class="text-taupe">Realisasi Aktual</span>
                                    <p class="mt-1 tabular-nums text-base font-bold text-espresso">{{ format_idr($summary['total_actual']) }}</p>
                                </div>
                                <div class="rounded bg-alabaster p-3">
                                    <span class="text-taupe">Sisa Anggaran</span>
                                    <p class="mt-1 tabular-nums text-base font-bold text-sage">{{ format_idr($summary['remaining_budget']) }}</p>
                                </div>
                            </div>

                            <div class="mt-3.5 rounded bg-alabaster p-3.5">
                                <div class="flex items-center justify-between text-xs mb-2">
                                    <span class="font-semibold text-espresso">Serapan 5 Kategori Terbesar</span>
                                    <span class="tabular-nums font-semibold text-champagne">{{ $summary['readiness_score'] }}% Siap</span>
                                </div>
                                <div class="h-36 w-full">
                                    <canvas id="landingPreviewChart" role="img" aria-label="Pratinjau grafik anggaran"></canvas>
                                </div>
                            </div>
                        </div>

                        <div x-show="previewTab === 'budget'" x-cloak class="space-y-2.5 text-xs">
                            @foreach(array_slice($summary['category_breakdown'], 0, 4) as $cat)
                                <div class="rounded bg-alabaster p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-espresso">{{ $cat['label'] }}</span>
                                        <span class="tabular-nums font-semibold text-charcoal">
                                            Aktual {{ format_idr($cat['actual']) }} / Est {{ format_idr($cat['estimated']) }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <x-ui.progress-bar size="sm" :percent="$cat['utilization_percent']" :tone="$cat['is_overbudget'] ? 'wine' : 'sage'" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div x-show="previewTab === 'checklist'" x-cloak class="space-y-2 text-xs">
                            @foreach($summary['urgent_tasks']->take(4) as $task)
                                <div class="flex items-center justify-between rounded bg-alabaster p-3">
                                    <div>
                                        <p class="font-bold text-espresso">{{ $task->title }}</p>
                                        <p class="mt-0.5 text-taupe">{{ $task->phase }} · PIC: {{ $task->assigned_to }}</p>
                                    </div>
                                    <x-ui.status-badge :tone="$task->is_overdue ? 'wine' : 'ochre'" :label="$task->due_date->format('d M Y')" />
                                </div>
                            @endforeach
                        </div>

                        <div x-show="previewTab === 'vendors'" x-cloak class="space-y-2 text-xs">
                            @foreach($summary['upcoming_vendor_payments']->take(4) as $v)
                                <div class="flex items-center justify-between rounded bg-alabaster p-3">
                                    <div>
                                        <p class="font-bold text-espresso">{{ $v->name }}</p>
                                        <p class="mt-0.5 text-taupe">{{ $v->category->shortLabel() }} · Terbayar {{ $v->payment_progress_percent }}%</p>
                                    </div>
                                    <span class="tabular-nums font-bold text-wine">Sisa {{ format_idr($v->remaining_balance) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CHAPTER 2: SHOWCASE SPLIT — INPUT HALF, OUTPUT HALF --}}
    <livewire:budget-simulator />

    {{-- CHAPTER 3: HOW IT WORKS — EMPAT TAHAP MENUJU HARI-H --}}
    <section id="alur-bisnis" class="w-full bg-ivory text-espresso">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-10 lg:py-24">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-start">
                <div class="lg:col-span-5" data-reveal>
                    <h2 class="text-3xl font-bold leading-tight text-espresso sm:text-4xl">
                        Empat tahap menuju Hari-H.
                    </h2>
                    <p class="mt-4 text-lg leading-relaxed text-charcoal">
                        Begitu masuk dengan akun Google, seluruh catatan anggaran, kontrak vendor, checklist keluarga, dan tata adat Jawa saling mengunci dalam satu alur kerja.
                    </p>

                    <div class="mt-8 space-y-5 text-base text-charcoal">
                        <div data-reveal style="--delay: 60ms">
                            <p class="font-bold text-espresso">Pertama, kunci plafon dan profil akad.</p>
                            <p class="mt-1 text-taupe">Tetapkan tanggal pernikahan, lokasi venue, jumlah tamu, dan plafon anggaran induk sebagai batas aman.</p>
                        </div>
                        <div data-reveal style="--delay: 120ms">
                            <p class="font-bold text-espresso">Kedua, bagi ke delapan kategori biaya.</p>
                            <p class="mt-1 text-taupe">Bandingkan biaya estimasi terhadap kontrak aktual pada Venue, Katering, Busana, Dekorasi, dan Dokumentasi.</p>
                        </div>
                        <div data-reveal style="--delay: 180ms">
                            <p class="font-bold text-espresso">Ketiga, pantau termin DP dan pelunasan vendor.</p>
                            <p class="mt-1 text-taupe">Catat nilai kontrak mitra, sisa tagihan belum lunas, dan hubungi PIC vendor langsung melalui WhatsApp.</p>
                        </div>
                        <div data-reveal style="--delay: 240ms">
                            <p class="font-bold text-espresso">Keempat, jalankan linimasa H-12 hingga Hari-H.</p>
                            <p class="mt-1 text-taupe">Delegasikan tugas ke keluarga atau WO, hitung kecocokan Weton Jawa, dan cetak laporan rapat keluarga.</p>
                        </div>
                    </div>
                </div>

                {{-- Two Live Product Interface Fragments (#3 Checklist/Timeline & #4 Vendor Ledger) + Atmosphere Photographs --}}
                <div class="space-y-6 lg:col-span-7">
                    <div data-ui data-reveal style="--delay: 100ms" class="rounded-lg bg-pearl p-5">
                        <div class="flex items-center justify-between pb-3 text-xs">
                            <span class="font-bold text-espresso">Fragmen Modul #2 &amp; #3 · Linimasa &amp; Checklist PIC</span>
                            <x-ui.status-badge tone="sage" :label="$summary['completion_percent'] . '% Selesai'" />
                        </div>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 text-xs">
                            @foreach(array_slice($summary['phase_progress'], 0, 4) as $phase)
                                <div class="rounded bg-alabaster p-2.5">
                                    <div class="flex items-center justify-between font-bold text-espresso">
                                        <span>{{ $phase['phase'] }}</span>
                                        <span class="tabular-nums text-champagne">{{ $phase['percent'] }}%</span>
                                    </div>
                                    <div class="mt-1.5">
                                        <x-ui.progress-bar size="sm" :percent="$phase['percent']" tone="champagne" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div data-ui data-reveal style="--delay: 180ms" class="rounded-lg bg-pearl p-5">
                        <div class="flex items-center justify-between pb-3 text-xs">
                            <span class="font-bold text-espresso">Fragmen Modul #4 · Buku Kontrak &amp; Pelunasan Vendor</span>
                            <x-ui.status-badge tone="ochre" :label="$summary['booked_vendors'] . '/' . $summary['total_vendors'] . ' Mitra Aktif'" />
                        </div>
                        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 text-xs">
                            @foreach($summary['upcoming_vendor_payments']->take(2) as $vendor)
                                <div class="rounded bg-alabaster p-3">
                                    <p class="font-bold text-espresso">{{ $vendor->name }}</p>
                                    <p class="mt-0.5 text-taupe">{{ $vendor->contact_person }} · Terbayar {{ $vendor->payment_progress_percent }}%</p>
                                    <p class="mt-1.5 tabular-nums font-bold text-wine">Sisa: {{ format_idr($vendor->remaining_balance) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4" data-reveal style="--delay: 240ms">
                        <img
                            src="https://picsum.photos/id/302/600/450?grayscale"
                            width="600"
                            height="450"
                            alt="Suasana tenang persiapan acara di tepi air"
                            class="h-32 w-full object-cover"
                        >
                        <video
                            src="/media/atelier-atmosphere.mp4"
                            poster="/media/atelier-poster.jpg"
                            autoplay
                            loop
                            muted
                            playsinline
                            class="h-32 w-full object-cover"
                        ></video>
                        <img
                            src="https://picsum.photos/id/255/600/450?grayscale"
                            width="600"
                            height="450"
                            loading="lazy"
                            alt="Jalan setapak menuju lokasi perhelatan"
                            class="h-32 w-full object-cover"
                        >
                        <img
                            src="https://picsum.photos/id/269/600/450?grayscale"
                            width="600"
                            height="450"
                            loading="lazy"
                            alt="Cahaya sore untuk jamuan resepsi"
                            class="h-32 w-full object-cover"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CHAPTER 4: PRICING STATEMENT — PAKET LAYANAN SAAS (Full-Bleed Alternating Bands) --}}
    <section id="paket-saas" class="w-full bg-sage text-alabaster">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-10">
            <div class="max-w-3xl" data-reveal>
                <h2 class="text-3xl font-bold leading-tight text-alabaster sm:text-4xl">
                    Paket layanan SaaS.
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-sage-bg">
                    Strategi Kampanye Pemasaran &amp; Positioning Produk: dirancang untuk pasangan mandiri (B2C) maupun biro Wedding Organizer profesional (B2B) yang mengelola banyak pernikahan sekaligus.
                </p>
            </div>
        </div>

        @foreach($packages as $idx => $pkg)
            <div class="w-full {{ $pkg['featured'] ? 'bg-alabaster text-espresso' : ($idx === 0 ? 'bg-espresso text-alabaster' : 'bg-charcoal text-alabaster') }}">
                <div
                    data-reveal
                    style="--delay: {{ $idx * 90 }}ms"
                    class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-6 py-10 lg:grid-cols-12 lg:items-center lg:px-10"
                >
                    <div class="lg:col-span-4">
                        <h3 class="text-2xl font-bold">{{ $pkg['name'] }}</h3>
                        <p class="mt-1 text-base font-medium {{ $pkg['featured'] ? 'text-champagne' : 'text-stone-strong' }}">
                            {{ $pkg['audience'] }} · {{ $pkg['period'] }}
                        </p>
                    </div>

                    <div class="lg:col-span-5">
                        <p class="text-base leading-relaxed {{ $pkg['featured'] ? 'text-charcoal' : 'text-stone-line' }}">
                            {{ $pkg['description'] }}
                        </p>
                    </div>

                    <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center lg:col-span-3 lg:justify-end">
                        <p class="tabular-nums text-2xl font-bold">{{ $pkg['price'] }}</p>
                        <a
                            href="{{ $pkg['cta_route'] }}"
                            class="inline-flex items-center justify-center rounded px-4 py-2.5 text-sm font-bold transition {{ $pkg['featured'] ? 'bg-espresso text-alabaster hover:bg-champagne' : 'bg-alabaster text-espresso hover:bg-ivory' }}"
                        >
                            {{ $pkg['cta_label'] }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- CHAPTER 5: FAQ BANDS — A BAND PER QUESTION --}}
    <section id="faq-section" class="w-full bg-alabaster text-espresso">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-10">
            <h2 class="text-3xl font-bold leading-tight text-espresso sm:text-4xl" data-reveal>
                Pertanyaan umum.
            </h2>
        </div>

        <div class="w-full bg-ivory">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-6 py-12 lg:grid-cols-12 lg:px-10" data-reveal>
                <h3 class="text-xl font-bold text-espresso lg:col-span-5">
                    Apakah pasangan perlu menginstal aplikasi tambahan?
                </h3>
                <p class="text-lg leading-relaxed text-charcoal lg:col-span-7">
                    Tidak perlu. Atelier Vow bekerja langsung di peramban laptop maupun ponsel. Pasangan cukup masuk menggunakan akun Google untuk membuka Dashboard, Budget Planner, Checklist, Timeline, dan Direktori Vendor.
                </p>
            </div>
        </div>

        <div class="w-full bg-alabaster">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-6 py-12 lg:grid-cols-12 lg:px-10" data-reveal>
                <h3 class="text-xl font-bold text-espresso lg:col-span-5">
                    Bagaimana sistem mencegah anggaran membengkak di H-30?
                </h3>
                <p class="text-lg leading-relaxed text-charcoal lg:col-span-7">
                    Setiap pengeluaran mencatat biaya estimasi, nilai kontrak aktual, dan nominal yang sudah dibayarkan. Begitu biaya aktual melampaui estimasi pada salah satu dari delapan kategori, sistem langsung memberi peringatan selisih dan memotong sisa plafon induk.
                </p>
            </div>
        </div>

        <div class="w-full bg-ivory">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-6 py-12 lg:grid-cols-12 lg:px-10" data-reveal>
                <h3 class="text-xl font-bold text-espresso lg:col-span-5">
                    Bagaimana pemisahan akses antara pengantin dan Admin WO?
                </h3>
                <p class="text-lg leading-relaxed text-charcoal lg:col-span-7">
                    Calon pengantin yang masuk dengan akun Google hanya melihat ruang kerja pernikahan mereka sendiri. Konsol Operasional Admin dan Lead WO diproteksi oleh middleware khusus dan disembunyikan sepenuhnya dari navigasi publik.
                </p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('landingPreviewChart');
        if (!canvas || typeof window.Chart === 'undefined') return;

        const breakdown = @json(array_slice($summary['category_breakdown'], 0, 5));
        new window.Chart(canvas, {
            type: 'bar',
            data: {
                labels: breakdown.map(i => i.short_label),
                datasets: [
                    {
                        label: 'Estimasi (Jt)',
                        data: breakdown.map(i => Math.round(i.estimated / 1000000)),
                        backgroundColor: '#DCDCD7',
                        borderRadius: 4
                    },
                    {
                        label: 'Aktual (Jt)',
                        data: breakdown.map(i => Math.round(i.actual / 1000000)),
                        backgroundColor: '#6E5434',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, display: false },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });
    });
</script>
@endpush
