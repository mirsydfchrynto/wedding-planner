@extends('layouts.app', ['title' => 'Platform Perencanaan Pernikahan Nusantara'])

@section('content')
    {{-- ======================================================== --}}
    {{-- 1. HERO SECTION: ABOVE-THE-FOLD CONVERSION FORMULA --}}
    {{-- ======================================================== --}}
    <section class="relative w-full overflow-hidden bg-alabaster pt-8 pb-16 lg:pt-14 lg:pb-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
                {{-- Left 5 Cols: Magnetic Outcome Headline, Subheadline, CTA, and Proof --}}
                <div class="lg:col-span-5 space-y-6">

                    <h1 class="text-4xl font-bold leading-[1.05] tracking-tight text-espresso sm:text-5xl lg:text-6xl font-editorial">
                        Satu buku kerja untuk pernikahan impian.
                    </h1>

                    <p class="text-base sm:text-lg leading-relaxed text-charcoal max-w-lg">
                        Satu buku kerja untuk anggaran, vendor, dan adat pernikahan. Kendalikan plafon biaya, pantau jatuh tempo pembayaran vendor, kelola buku tamu RSVP via WhatsApp, dan cetak dokumen operasional Hari-H resmi tanpa stres.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                        <a
                            href="{{ auth()->check() ? route('dashboard.index') : route('login') }}"
                            class="editorial-btn-primary !min-h-[3rem] !px-6 !text-base shadow-md hover:shadow-lg justify-center"
                        >
                            <span>{{ auth()->check() ? 'Buka Workspace Pernikahan' : 'Masuk dengan Google' }}</span>
                        </a>
                        <a
                            href="#simulasi-adat"
                            class="editorial-btn-secondary !min-h-[3rem] !px-5 !text-sm justify-center"
                        >
                            <x-ui.icon name="wallet" class="h-4 w-4 text-taupe" />
                            <span>Simulasi Anggaran</span>
                        </a>
                    </div>

                    <div class="pt-4 border-t border-stone-line/70">
                        <p class="text-xs font-semibold uppercase tracking-wider text-taupe mb-2">Keunggulan Utama Platform:</p>
                        <div class="grid grid-cols-2 gap-3 text-xs text-charcoal">
                            <div class="flex items-center gap-2">
                                <x-ui.icon name="shield-check" class="h-4 w-4 text-sage" />
                                <span>Isolasi Data Multi-Tenant</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-ui.icon name="check-circle" class="h-4 w-4 text-sage" />
                                <span>Alokator Adat Jawa &amp; Sunda</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-ui.icon name="printer" class="h-4 w-4 text-sage" />
                                <span>Format Cetak A4 Hari-H</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-ui.icon name="smartphone" class="h-4 w-4 text-sage" />
                                <span>RSVP WhatsApp 1-Klik</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right 7 Cols: Interactive macOS-Style Live Interface Demo (All 6 Modules) --}}
                <div class="lg:col-span-7">
                    <div
                        data-ui
                        x-data="{ activeTab: 'dashboard' }"
                        class="rounded-2xl border border-black/[0.08] bg-pearl shadow-xl overflow-hidden transition-all duration-300"
                    >
                        {{-- Window Bar with macOS Dots & Module Tabs --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/[0.06] bg-alabaster/90 px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-espresso tracking-tight">Atelier Vow Workspace</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <x-ui.status-badge tone="champagne" :label="'H-' . $summary['days_remaining'] . ' Hari'" />
                                <span class="text-[10px] text-taupe font-semibold tabular-nums">{{ $summary['readiness_score'] }}% Siap</span>
                            </div>
                        </div>

                        {{-- Horizontal Tab Switcher Pills --}}
                        <div class="flex overflow-x-auto gap-1 border-b border-black/[0.06] bg-white p-2 text-xs font-semibold no-scrollbar">
                            <button
                                type="button"
                                @click="activeTab = 'dashboard'"
                                :class="activeTab === 'dashboard' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso'"
                                class="rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap"
                            >
                                Dashboard
                            </button>
                            <button
                                type="button"
                                @click="activeTab = 'budget'"
                                :class="activeTab === 'budget' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso'"
                                class="rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap"
                            >
                                Anggaran
                            </button>
                            <button
                                type="button"
                                @click="activeTab = 'checklist'"
                                :class="activeTab === 'checklist' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso'"
                                class="rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap"
                            >
                                Checklist
                            </button>
                            <button
                                type="button"
                                @click="activeTab = 'timeline'"
                                :class="activeTab === 'timeline' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso'"
                                class="rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap"
                            >
                                Rundown Hari-H
                            </button>
                            <button
                                type="button"
                                @click="activeTab = 'vendors'"
                                :class="activeTab === 'vendors' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso'"
                                class="rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap"
                            >
                                Vendor
                            </button>
                            <button
                                type="button"
                                @click="activeTab = 'guests'"
                                :class="activeTab === 'guests' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso'"
                                class="rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap"
                            >
                                Buku Tamu
                            </button>
                        </div>

                        {{-- Tab 1: Dashboard Preview --}}
                        <div x-show="activeTab === 'dashboard'" class="p-5 sm:p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                <div class="rounded-xl border border-black/[0.06] bg-alabaster p-3.5">
                                    <span class="text-taupe font-medium">Plafon Anggaran</span>
                                    <p class="mt-1 font-bold text-espresso text-base whitespace-nowrap">{{ format_idr($summary['total_budget_cap']) }}</p>
                                </div>
                                <div class="rounded-xl border border-black/[0.06] bg-alabaster p-3.5">
                                    <span class="text-taupe font-medium">Total Pengeluaran</span>
                                    <p class="mt-1 font-bold text-espresso text-base whitespace-nowrap">{{ format_idr($summary['total_actual']) }}</p>
                                </div>
                                <div class="rounded-xl border border-black/[0.06] bg-alabaster p-3.5">
                                    <span class="text-taupe font-medium">Sisa Anggaran</span>
                                    <p class="mt-1 font-bold text-forest text-base whitespace-nowrap">{{ format_idr($summary['remaining_budget']) }}</p>
                                </div>
                            </div>

                            <div class="rounded-xl border border-black/[0.06] bg-alabaster p-4">
                                <div class="flex items-center justify-between text-xs mb-2">
                                    <span class="font-bold text-espresso">Distribusi Anggaran (Estimasi vs Realisasi)</span>
                                    <span class="font-semibold text-champagne">{{ $summary['readiness_score'] }}% Kesiapan</span>
                                </div>
                                <div class="h-36 w-full">
                                    <canvas id="landingPreviewChart" role="img" aria-label="Grafik anggaran"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Budget Preview --}}
                        <div x-show="activeTab === 'budget'" x-cloak class="p-5 sm:p-6 space-y-3 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-black/[0.06]">
                                <span class="font-bold text-espresso">Alokasi per Kategori Biaya</span>
                                <span class="text-taupe">Plafon: {{ format_idr($summary['total_budget_cap']) }}</span>
                            </div>
                            @foreach(array_slice($summary['category_breakdown'], 0, 4) as $cat)
                                <div class="rounded-xl border border-black/[0.06] bg-alabaster p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-espresso">{{ $cat['label'] }}</span>
                                        <span class="font-mono font-semibold text-charcoal whitespace-nowrap">
                                            {{ format_idr($cat['actual']) }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <x-ui.progress-bar size="sm" :percent="$cat['utilization_percent']" :tone="$cat['is_overbudget'] ? 'wine' : 'champagne'" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tab 3: Checklist Preview --}}
                        <div x-show="activeTab === 'checklist'" x-cloak class="p-5 sm:p-6 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-black/[0.06]">
                                <span class="font-bold text-espresso">Tugas Prioritas Terdekat</span>
                                <span class="text-sage font-semibold">{{ $summary['completion_percent'] }}% Selesai</span>
                            </div>
                            @foreach($summary['urgent_tasks']->take(4) as $task)
                                <div class="flex items-center justify-between rounded-xl border border-black/[0.06] bg-alabaster p-3">
                                    <div class="min-w-0 pr-2">
                                        <p class="font-bold text-espresso truncate">{{ $task->title }}</p>
                                        <p class="mt-0.5 text-taupe text-[11px]">{{ $task->phase }} · PIC: {{ $task->assigned_to }}</p>
                                    </div>
                                    <x-ui.status-badge :tone="$task->is_overdue ? 'wine' : 'neutral'" :label="$task->due_date->format('d M')" />
                                </div>
                            @endforeach
                        </div>

                        {{-- Tab 4: Rundown Hari-H Preview --}}
                        <div x-show="activeTab === 'timeline'" x-cloak class="p-5 sm:p-6 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-black/[0.06]">
                                <span class="font-bold text-espresso">Rundown Operasional Acara Hari-H</span>
                                <span class="text-champagne font-semibold font-mono">Format Menit-demi-Menit</span>
                            </div>
                            @foreach($profile->rundowns->take(4) as $rd)
                                <div class="flex items-center justify-between rounded-xl border border-black/[0.06] bg-alabaster p-3">
                                    <div class="flex items-center gap-3 min-w-0 pr-2">
                                        <span class="font-mono font-bold text-espresso whitespace-nowrap">{{ $rd->start_time }}</span>
                                        <div class="min-w-0">
                                            <p class="font-bold text-espresso truncate">{{ $rd->session_name }}</p>
                                            <p class="text-[11px] text-taupe truncate">{{ $rd->activity }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded bg-champagne/15 px-2 py-0.5 text-[10px] font-bold text-espresso shrink-0">
                                        {{ $rd->pic_name ?? 'WO' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tab 5: Vendors Preview --}}
                        <div x-show="activeTab === 'vendors'" x-cloak class="p-5 sm:p-6 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-black/[0.06]">
                                <span class="font-bold text-espresso">Direktori Vendor &amp; Kontrak</span>
                                <span class="text-taupe">{{ $summary['booked_vendors'] }} / {{ $summary['total_vendors'] }} Mitra Terkontrak</span>
                            </div>
                            @foreach($summary['upcoming_vendor_payments']->take(3) as $v)
                                <div class="flex items-center justify-between rounded-xl border border-black/[0.06] bg-alabaster p-3">
                                    <div>
                                        <p class="font-bold text-espresso">{{ $v->name }}</p>
                                        <p class="mt-0.5 text-taupe text-[11px]">{{ $v->category->shortLabel() }} · Terbayar {{ $v->payment_progress_percent }}%</p>
                                    </div>
                                    <span class="font-mono font-bold text-wine whitespace-nowrap">Sisa: {{ format_idr($v->remaining_balance) }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tab 6: Guests Preview --}}
                        <div x-show="activeTab === 'guests'" x-cloak class="p-5 sm:p-6 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-black/[0.06]">
                                <span class="font-bold text-espresso">Buku Tamu VIP &amp; Meja Undangan</span>
                                <span class="text-forest font-semibold">WhatsApp RSVP Siap Kirim</span>
                            </div>
                            @foreach($profile->guests->take(3) as $g)
                                <div class="flex items-center justify-between rounded-xl border border-black/[0.06] bg-alabaster p-3">
                                    <div>
                                        <p class="font-bold text-espresso">{{ $g->name }}</p>
                                        <p class="mt-0.5 text-taupe text-[11px]">{{ $g->category }} · {{ $g->pax }} Pax</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded bg-champagne/15 px-2 py-0.5 font-mono text-[10px] font-bold text-espresso">
                                            {{ $g->table_number ?? 'Meja 01' }}
                                        </span>
                                        <x-ui.status-badge tone="sage" label="Hadir" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 2. SOCIAL PROOF & KEY METRICS BAR --}}
    {{-- ======================================================== --}}
    <section class="border-y border-stone-line bg-white py-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 text-center">
                <div>
                    <p class="font-editorial text-3xl sm:text-4xl font-bold text-espresso">500+</p>
                    <p class="text-xs sm:text-sm font-medium text-taupe mt-1">Pasangan Pengantin Terbantu</p>
                </div>
                <div>
                    <p class="font-editorial text-3xl sm:text-4xl font-bold text-espresso">8 Kategori</p>
                    <p class="text-xs sm:text-sm font-medium text-taupe mt-1">Kontrol Anggaran Otomatis</p>
                </div>
                <div>
                    <p class="font-editorial text-3xl sm:text-4xl font-bold text-espresso">120+</p>
                    <p class="text-xs sm:text-sm font-medium text-taupe mt-1">Mitra Vendor Terkurasi</p>
                </div>
                <div>
                    <p class="font-editorial text-3xl sm:text-4xl font-bold text-espresso">100%</p>
                    <p class="text-xs sm:text-sm font-medium text-taupe mt-1">Privat &amp; Bebas Iklan</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 3. INTERACTIVE SIMULATOR (ADAT & ANGGARAN NUSANTARA) --}}
    {{-- ======================================================== --}}
    @include('saas.partials.budget-simulator')

    {{-- ======================================================== --}}
    {{-- 4. HOW IT WORKS: EMPAT TAHAP MENUJU HARI-H --}}
    {{-- ======================================================== --}}
    <section id="alur-bisnis" class="w-full bg-alabaster py-16 lg:py-24 border-t border-stone-line">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-bold uppercase tracking-wider text-champagne">Alur Kerja Sistematis</span>
                <h2 class="mt-1 font-editorial text-3xl sm:text-4xl font-bold text-espresso">
                    Empat tahap menuju Hari-H sempurna.
                </h2>
                <p class="mt-3 text-base text-charcoal">
                    Dari penentuan tanggal akad hingga malam resepsi, seluruh data anggaran, vendor, dan checklist saling mengunci otomatis.
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="editorial-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-espresso text-alabaster font-bold text-sm">
                            1
                        </div>
                        <h3 class="mt-4 font-bold text-espresso text-lg">Kunci Plafon Induk</h3>
                        <p class="mt-2 text-xs leading-relaxed text-taupe">
                            Tetapkan batas aman anggaran dan target jumlah tamu undangan. Sistem menghitung rasio biaya per tamu otomatis.
                        </p>
                    </div>
                    <span class="mt-4 inline-block text-[11px] font-semibold text-champagne">Modul #1: Budget Planner &rarr;</span>
                </div>

                <div class="editorial-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-espresso text-alabaster font-bold text-sm">
                            2
                        </div>
                        <h3 class="mt-4 font-bold text-espresso text-lg">Kurasi Vendor &amp; Termin</h3>
                        <p class="mt-2 text-xs leading-relaxed text-taupe">
                            Pilih vendor terverifikasi dan catat termin pembayaran bertahap (DP, Termin 1, Pelunasan) dengan pengingat jatuh tempo.
                        </p>
                    </div>
                    <span class="mt-4 inline-block text-[11px] font-semibold text-champagne">Modul #4: Direktori Vendor &rarr;</span>
                </div>

                <div class="editorial-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-espresso text-alabaster font-bold text-sm">
                            3
                        </div>
                        <h3 class="mt-4 font-bold text-espresso text-lg">Bagi Tugas Panitia</h3>
                        <p class="mt-2 text-xs leading-relaxed text-taupe">
                            Delegasikan tugas checklist ke keluarga atau Wedding Organizer. Kelola tamu VIP dan meja dengan tautan konfirmasi WhatsApp.
                        </p>
                    </div>
                    <span class="mt-4 inline-block text-[11px] font-semibold text-champagne">Modul #2 &amp; #6: Checklist &amp; Tamu &rarr;</span>
                </div>

                <div class="editorial-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-espresso text-alabaster font-bold text-sm">
                            4
                        </div>
                        <h3 class="mt-4 font-bold text-espresso text-lg">Cetak Master Guide A4</h3>
                        <p class="mt-2 text-xs leading-relaxed text-taupe">
                            Cetak 4 halaman panduan resmi rundown menit-demi-menit untuk pegangan fisik panitia lapangan tanpa ketergantungan internet.
                        </p>
                    </div>
                    <span class="mt-4 inline-block text-[11px] font-semibold text-champagne">Dokumen Cetak Master Guide &rarr;</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 5. PRICING PACKAGES (3 APPLE-STYLE CARDS) --}}
    {{-- ======================================================== --}}
    <section id="paket-saas" class="w-full bg-white py-16 lg:py-24 border-t border-stone-line">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-bold uppercase tracking-wider text-champagne">Pilihan Paket Transparan</span>
                <h2 class="mt-1 font-editorial text-3xl sm:text-4xl font-bold text-espresso">
                    Investasi terbaik untuk hari bersejarah Anda.
                </h2>
                <p class="mt-3 text-base text-charcoal">
                    Dirancang fleksibel untuk pasangan mandiri maupun biro Wedding Organizer profesional.
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                @foreach($packages as $pkg)
                    <div class="editorial-card flex flex-col justify-between p-8 relative {{ $pkg['featured'] ? 'border-2 !border-champagne shadow-lg ring-1 ring-champagne/20' : '' }}">
                        @if($pkg['featured'])
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-champagne px-3 py-0.5 text-[11px] font-bold text-alabaster uppercase tracking-wider">
                                Rekomendasi Utama
                            </div>
                        @endif

                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-espresso font-editorial">{{ $pkg['name'] }}</h3>
                                <span class="rounded bg-alabaster px-2.5 py-1 text-xs font-semibold text-taupe">{{ $pkg['audience'] }}</span>
                            </div>

                            <p class="mt-3 text-xs leading-relaxed text-taupe">{{ $pkg['description'] }}</p>

                            <div class="mt-6 pb-6 border-b border-stone-line">
                                <p class="tabular-nums font-editorial text-3xl sm:text-4xl font-bold text-espresso whitespace-nowrap">{{ $pkg['price'] }}</p>
                                <p class="text-xs text-taupe mt-1">{{ $pkg['period'] }}</p>
                            </div>

                            <ul class="mt-6 space-y-3 text-xs text-charcoal">
                                @foreach($pkg['features'] as $feat)
                                    <li class="flex items-start gap-2.5">
                                        <x-ui.icon name="check-circle" class="h-4 w-4 text-forest shrink-0 mt-0.5" />
                                        <span>{{ $feat }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-8 pt-6 border-t border-stone-line">
                            <a
                                href="{{ $pkg['cta_route'] }}"
                                class="w-full justify-center {{ $pkg['featured'] ? 'editorial-btn-primary' : 'editorial-btn-secondary' }}"
                            >
                                <span>{{ $pkg['cta_label'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 6. INTERACTIVE FAQ ACCORDION --}}
    {{-- ======================================================== --}}
    <section id="faq-section" class="w-full bg-alabaster py-16 lg:py-24 border-t border-stone-line" x-data="{ activeFaq: null }">
        <div class="mx-auto max-w-4xl px-6 lg:px-10">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-champagne">Tanya Jawab</span>
                <h2 class="mt-1 font-editorial text-3xl sm:text-4xl font-bold text-espresso">
                    Pertanyaan yang sering diajukan.
                </h2>
            </div>

            <div class="space-y-3">
                <div class="editorial-card overflow-hidden">
                    <button
                        type="button"
                        @click="activeFaq = (activeFaq === 1 ? null : 1)"
                        class="flex w-full items-center justify-between p-5 text-left text-sm sm:text-base font-bold text-espresso cursor-pointer hover:bg-black/[0.02]"
                    >
                        <span>Apakah pasangan perlu mengunduh aplikasi tambahan?</span>
                        <span class="text-champagne font-mono text-lg transition-transform duration-200" :class="activeFaq === 1 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="px-5 pb-5 text-xs sm:text-sm leading-relaxed text-taupe border-t border-stone-line/60 pt-3">
                        Tidak perlu. Atelier Vow bekerja penuh di peramban (browser) laptop maupun smartphone. Pasangan cukup masuk menggunakan akun Google atau kredensial sederhana untuk langsung mengakses 6 modul kerja.
                    </div>
                </div>

                <div class="editorial-card overflow-hidden">
                    <button
                        type="button"
                        @click="activeFaq = (activeFaq === 2 ? null : 2)"
                        class="flex w-full items-center justify-between p-5 text-left text-sm sm:text-base font-bold text-espresso cursor-pointer hover:bg-black/[0.02]"
                    >
                        <span>Bagaimana sistem mencegah anggaran membengkak (overbudget)?</span>
                        <span class="text-champagne font-mono text-lg transition-transform duration-200" :class="activeFaq === 2 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="px-5 pb-5 text-xs sm:text-sm leading-relaxed text-taupe border-t border-stone-line/60 pt-3">
                        Sistem memantau deviasi antara estimasi biaya awal dan kontrak aktual pada 8 kategori biaya secara waktu nyata. Jika nilai kontrak melebihi batas, badge peringatan merah langsung aktif dan memotong sisa plafon induk secara otomatis.
                    </div>
                </div>

                <div class="editorial-card overflow-hidden">
                    <button
                        type="button"
                        @click="activeFaq = (activeFaq === 3 ? null : 3)"
                        class="flex w-full items-center justify-between p-5 text-left text-sm sm:text-base font-bold text-espresso cursor-pointer hover:bg-black/[0.02]"
                    >
                        <span>Apakah data kami aman dan tidak bisa diintip pasangan lain?</span>
                        <span class="text-champagne font-mono text-lg transition-transform duration-200" :class="activeFaq === 3 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="px-5 pb-5 text-xs sm:text-sm leading-relaxed text-taupe border-t border-stone-line/60 pt-3">
                        Sangat aman. Atelier Vow dibangun dengan arsitektur Multi-Tenancy sejati menggunakan Global Scope Eloquent. Seluruh catatan anggaran, kontrak vendor, dan tamu undangan diisolasi ketat hanya untuk pemilik profil pernikahan tersebut.
                    </div>
                </div>

                <div class="editorial-card overflow-hidden">
                    <button
                        type="button"
                        @click="activeFaq = (activeFaq === 4 ? null : 4)"
                        class="flex w-full items-center justify-between p-5 text-left text-sm sm:text-base font-bold text-espresso cursor-pointer hover:bg-black/[0.02]"
                    >
                        <span>Bagaimana jika di gedung resepsi tidak ada koneksi internet?</span>
                        <span class="text-champagne font-mono text-lg transition-transform duration-200" :class="activeFaq === 4 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="px-5 pb-5 text-xs sm:text-sm leading-relaxed text-taupe border-t border-stone-line/60 pt-3">
                        Tersedia fitur <strong>Master Wedding Guide A4</strong>. Pasangan dan WO cukup mencetak buku panduan 4 halaman (atau menyimpan file PDF) yang merangkum seluruh rundown menit-demi-menit, kontak darurat vendor, dan penataan meja tamu untuk pegangan fisik panitia lapangan.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 7. FINAL CONVERSION CTA BANNER --}}
    {{-- ======================================================== --}}
    <section class="w-full bg-espresso text-alabaster py-16 lg:py-20 border-t border-stone-line">
        <div class="mx-auto max-w-4xl px-6 text-center space-y-6">
            <h2 class="font-editorial text-3xl sm:text-4xl lg:text-5xl font-bold text-alabaster">
                Mulai perencanaan pernikahan Anda hari ini.
            </h2>
            <p class="text-base sm:text-lg text-stone-line max-w-2xl mx-auto">
                Bebas stres, terkendali, dan terstruktur rapi. Masuk dalam 10 detik dan nikmati pengalaman merencanakan pernikahan adat Nusantara berkelas dunia.
            </p>
            <div class="pt-2">
                <a
                    href="{{ auth()->check() ? route('dashboard.index') : route('login') }}"
                    class="editorial-btn-primary !min-h-[3.25rem] !px-8 !text-base !bg-alabaster !text-espresso hover:!bg-champagne hover:!text-alabaster shadow-xl"
                >
                    <span>Buka Workspace Sekarang — Gratis</span>
                </a>
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
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    });
</script>
@endpush
