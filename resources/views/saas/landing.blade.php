@extends('layouts.app', ['title' => 'Platform Perencanaan Pernikahan Nusantara'])

@section('content')
    {{-- 1. HERO SECTION: ABOVE-THE-FOLD OUTCOME + INTERACTIVE 4-TAB PRODUCT EXPLORER --}}
    <section class="editorial-card batik-kawung-bg relative rounded-lg p-6 sm:p-10">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-6">
                <div class="inline-flex items-center gap-2 rounded-md border border-stone-line bg-alabaster px-3 py-1 text-xs font-semibold text-champagne">
                    <x-ui.icon name="sparkles" class="h-3.5 w-3.5" />
                    <span>B2C Pasangan &amp; B2B Wedding Organizer SaaS</span>
                </div>

                {{-- deslop-ignore-next-line 11 --}}
                <h1 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-espresso sm:text-4xl">
                    Satu buku kerja untuk anggaran, vendor, dan adat pernikahan.
                </h1>
                <p class="mt-3 max-w-lg text-sm leading-relaxed text-taupe">
                    Hentikan kebocoran biaya di spreadsheet yang terpisah. Kelola plafon 8 kategori, pantau jatuh tempo DP vendor, dan jadwalkan persiapan H-12 bulan hingga Hari-H dalam satu ruang kerja terhubung.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a
                        href="{{ auth()->check() ? route('dashboard.index') : route('login') }}"
                        class="inline-flex items-center gap-2.5 rounded-md bg-espresso px-5 py-3 text-xs font-bold text-alabaster transition hover:bg-champagne"
                    >
                        <span class="flex h-5 w-5 items-center justify-center rounded-sm bg-pearl p-0.5">
                            <x-ui.google-icon class="h-3.5 w-3.5" />
                        </span>
                        <span>{{ auth()->check() ? 'Buka Dashboard Pernikahan' : 'Masuk dengan Google — Gratis' }}</span>
                    </a>
                    <a href="#simulasi-adat" class="editorial-btn-secondary !py-3 !px-4">
                        <x-ui.icon name="sliders" class="h-4 w-4 text-champagne" />
                        <span>Coba Kalkulator Anggaran</span>
                    </a>
                </div>

                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-7 grid grid-cols-3 gap-3 border-t border-stone-line pt-5 text-xs">
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="wallet" class="h-4 w-4 text-champagne" />
                        <div>
                            <p class="tabular-nums font-bold text-espresso">Rp 350 Jt+</p>
                            <p class="text-[11px] text-taupe">Simulasi Terukur</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="layout-dashboard" class="h-4 w-4 text-champagne" />
                        <div>
                            <p class="font-bold text-espresso">5 Modul Inti</p>
                            <p class="text-[11px] text-taupe">Saling Terhubung</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="shield-check" class="h-4 w-4 text-sage" />
                        <div>
                            <p class="font-bold text-espresso">Google SSO</p>
                            <p class="text-[11px] text-taupe">Akses 1-Klik</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Interactive Multi-Tab Product Preview Card --}}
            <div class="lg:col-span-6" data-ui x-data="{ previewTab: 'dashboard' }">
                <div class="rounded-lg border border-stone-strong bg-pearl p-5">
                    {{-- Interactive Preview Tabs --}}
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-stone-line pb-3">
                        <div class="flex flex-wrap items-center gap-1 text-xs">
                            <button
                                type="button"
                                @click="previewTab = 'dashboard'"
                                :class="previewTab === 'dashboard' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal hover:bg-ivory'"
                                class="inline-flex items-center gap-1.5 rounded px-2.5 py-1 font-semibold transition cursor-pointer"
                            >
                                <x-ui.icon name="layout-dashboard" class="h-3.5 w-3.5" />
                                <span>Dashboard</span>
                            </button>
                            <button
                                type="button"
                                @click="previewTab = 'budget'"
                                :class="previewTab === 'budget' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal hover:bg-ivory'"
                                class="inline-flex items-center gap-1.5 rounded px-2.5 py-1 font-semibold transition cursor-pointer"
                            >
                                <x-ui.icon name="wallet" class="h-3.5 w-3.5" />
                                <span>Anggaran</span>
                            </button>
                            <button
                                type="button"
                                @click="previewTab = 'checklist'"
                                :class="previewTab === 'checklist' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal hover:bg-ivory'"
                                class="inline-flex items-center gap-1.5 rounded px-2.5 py-1 font-semibold transition cursor-pointer"
                            >
                                <x-ui.icon name="check-circle" class="h-3.5 w-3.5" />
                                <span>Checklist</span>
                            </button>
                            <button
                                type="button"
                                @click="previewTab = 'vendors'"
                                :class="previewTab === 'vendors' ? 'bg-espresso text-alabaster' : 'bg-alabaster text-charcoal hover:bg-ivory'"
                                class="inline-flex items-center gap-1.5 rounded px-2.5 py-1 font-semibold transition cursor-pointer"
                            >
                                <x-ui.icon name="building" class="h-3.5 w-3.5" />
                                <span>Vendor</span>
                            </button>
                        </div>
                        <x-ui.status-badge tone="champagne" :label="'H-' . $summary['days_remaining'] . ' Hari'" />
                    </div>

                    {{-- Tab 1: Dashboard Preview --}}
                    <div x-show="previewTab === 'dashboard'" class="mt-4">
                        {{-- deslop-ignore-next-line 28 --}}
                        <div class="grid grid-cols-3 gap-2.5 text-xs">
                            <div class="rounded border border-stone-line bg-alabaster p-3">
                                <span class="text-[11px] text-taupe">Plafon Induk</span>
                                <p class="mt-1 tabular-nums text-sm font-bold text-espresso">{{ format_idr($summary['total_budget_cap']) }}</p>
                            </div>
                            <div class="rounded border border-stone-line bg-alabaster p-3">
                                <span class="text-[11px] text-taupe">Realisasi Aktual</span>
                                <p class="mt-1 tabular-nums text-sm font-bold text-espresso">{{ format_idr($summary['total_actual']) }}</p>
                            </div>
                            <div class="rounded border border-stone-line bg-alabaster p-3">
                                <span class="text-[11px] text-taupe">Sisa Anggaran</span>
                                <p class="mt-1 tabular-nums text-sm font-bold text-sage">{{ format_idr($summary['remaining_budget']) }}</p>
                            </div>
                        </div>

                        <div class="mt-3.5 rounded border border-stone-line bg-alabaster p-3.5">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="font-semibold text-espresso">Grafik Serapan 5 Kategori Terbesar</span>
                                <span class="tabular-nums font-semibold text-champagne">{{ $summary['readiness_score'] }}% Siap</span>
                            </div>
                            <div class="h-36 w-full">
                                <canvas id="landingPreviewChart" role="img" aria-label="Pratinjau grafik anggaran"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Budget Preview --}}
                    <div x-show="previewTab === 'budget'" x-cloak class="mt-4 space-y-2.5 text-xs">
                        @foreach(array_slice($summary['category_breakdown'], 0, 4) as $cat)
                            <div class="rounded border border-stone-line bg-alabaster p-3">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-espresso">
                                        <x-ui.icon :name="$cat['case']->icon()" class="h-3.5 w-3.5 text-champagne" />
                                        <span>{{ $cat['label'] }}</span>
                                    </span>
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

                    {{-- Tab 3: Checklist Preview --}}
                    <div x-show="previewTab === 'checklist'" x-cloak class="mt-4 space-y-2 text-xs">
                        @foreach($summary['urgent_tasks']->take(4) as $task)
                            <div class="flex items-center justify-between rounded border border-stone-line bg-alabaster p-3">
                                <div>
                                    <p class="font-bold text-espresso">{{ $task->title }}</p>
                                    <p class="mt-0.5 text-[11px] text-taupe">{{ $task->phase }} · PIC: {{ $task->assigned_to }}</p>
                                </div>
                                <x-ui.status-badge :tone="$task->is_overdue ? 'wine' : 'ochre'" :label="$task->due_date->format('d M Y')" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Tab 4: Vendors Preview --}}
                    <div x-show="previewTab === 'vendors'" x-cloak class="mt-4 space-y-2 text-xs">
                        @foreach($summary['upcoming_vendor_payments']->take(4) as $v)
                            <div class="flex items-center justify-between rounded border border-stone-line bg-alabaster p-3">
                                <div>
                                    <p class="font-bold text-espresso">{{ $v->name }}</p>
                                    <p class="mt-0.5 text-[11px] text-taupe">{{ $v->category->shortLabel() }} · Terbayar {{ $v->payment_progress_percent }}%</p>
                                </div>
                                <span class="tabular-nums font-bold text-wine">Sisa {{ format_idr($v->remaining_balance) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3.5 flex items-center justify-between border-t border-stone-line pt-3 text-xs text-taupe">
                        <span>Klik tab di atas untuk mencoba pratinjau modul</span>
                        <a href="{{ auth()->check() ? route('dashboard.index') : route('login') }}" class="inline-flex items-center gap-1 font-bold text-champagne hover:underline">
                            <span>Buka Aplikasi Penuh</span>
                            <x-ui.icon name="arrow-right" class="h-3.5 w-3.5" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. PROBLEM VS SOLUTION: MENGAPA HARUS ATELIER VOW? --}}
    <section id="masalah-solusi" class="mt-12 grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div class="editorial-card rounded-lg p-6">
            <div class="flex items-center justify-between border-b border-stone-line pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="alert-triangle" class="h-4 w-4 text-wine" />
                    <h2 class="text-xl font-bold text-espresso">Masalah Cara Manual (Excel &amp; Grup Chat)</h2>
                </div>
                <x-ui.status-badge tone="wine" label="Rawan Bengkak" />
            </div>
            <ul class="mt-4 space-y-3 text-xs text-charcoal">
                <li class="flex items-start gap-2.5">
                    <span class="font-bold text-wine">1.</span>
                    <span><strong>Kebocoran Biaya H-30:</strong> Selisih Rp 2–5 juta pada katering, dekorasi, dan seragam tidak terhubung ke plafon induk sehingga anggaran membengkak tanpa disadari.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="font-bold text-wine">2.</span>
                    <span><strong>Jadwal DP Vendor Tercecer:</strong> Bukti transfer dan tanggal jatuh tempo pelunasan venue atau dokumentasi tenggelam di riwayat chat WhatsApp.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="font-bold text-wine">3.</span>
                    <span><strong>Koordinasi Keluarga Tumpang Tindih:</strong> Tidak ada kejelasan siapa PIC untuk berkas KUA, cincin kawin, suvenir, maupun prosesi Siraman/Midodareni.</span>
                </li>
            </ul>
        </div>

        <div class="editorial-card rounded-lg border-champagne bg-champagne-light/20 p-6">
            <div class="flex items-center justify-between border-b border-stone-line pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="check-circle" class="h-4 w-4 text-sage" />
                    <h2 class="text-xl font-bold text-espresso">Solusi Terpadu Atelier Vow SaaS</h2>
                </div>
                <x-ui.status-badge tone="sage" label="Terkendali Real-Time" />
            </div>
            <ul class="mt-4 space-y-3 text-xs text-charcoal">
                <li class="flex items-start gap-2.5">
                    <span class="font-bold text-sage">1.</span>
                    <span><strong>Deteksi Deviasi Otomatis:</strong> Setiap pos biaya langsung membandingkan Estimasi vs Aktual dan memberi penanda <em>Hemat</em> atau <em>Overbudget</em> secara instan.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="font-bold text-sage">2.</span>
                    <span><strong>Buku Kontrak &amp; Termin Vendor:</strong> Nilai kontrak, nominal DP terbayar, sisa tagihan, dan tombol WhatsApp PIC tersimpan rapi dalam satu layar.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="font-bold text-sage">3.</span>
                    <span><strong>Siap Rapat Keluarga &amp; WO:</strong> Dilengkapi fitur cetak ringkasan 1-klik, peta linimasa 7 fase, serta kalkulator Weton Jawa.</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- 3. 4-STEP BUSINESS & USER WORKFLOW (ALUR BISNIS END-TO-END) --}}
    <section id="alur-bisnis" class="mt-12 editorial-card rounded-lg p-6 sm:p-8">
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-xl font-bold text-espresso">Alur Kerja Platform: Dari Login Google hingga Hari-H</h2>
                <p class="text-xs text-taupe">Empat langkah terstruktur untuk mengelola persiapan pernikahan tanpa stres</p>
            </div>
            <a href="{{ route('login') }}" class="text-xs font-bold text-champagne hover:underline">Mulai Langkah 1 →</a>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded border border-stone-line bg-alabaster p-4">
                <div class="flex items-center justify-between">
                    <span class="flex h-8 w-8 items-center justify-center rounded bg-pearl border border-stone-line">
                        <x-ui.google-icon class="h-4 w-4" />
                    </span>
                    <span class="text-xs font-bold text-champagne">Langkah 01</span>
                </div>
                <h3 class="mt-3 text-sm font-bold text-espresso">Login Google &amp; Profil Akad</h3>
                <p class="mt-1 text-xs leading-relaxed text-taupe">
                    Masuk dengan akun Google, tentukan tanggal akad, lokasi venue, target jumlah tamu, dan plafon anggaran induk.
                </p>
            </div>

            <div class="rounded border border-stone-line bg-alabaster p-4">
                <div class="flex items-center justify-between">
                    <span class="flex h-8 w-8 items-center justify-center rounded bg-pearl border border-stone-line text-champagne">
                        <x-ui.icon name="wallet" class="h-4 w-4" />
                    </span>
                    <span class="text-xs font-bold text-champagne">Langkah 02</span>
                </div>
                <h3 class="mt-3 text-sm font-bold text-espresso">Alokasi 8 Kategori Biaya</h3>
                <p class="mt-1 text-xs leading-relaxed text-taupe">
                    Bagi anggaran ke Venue, Katering, Busana, Dekorasi, Dokumentasi, Souvenir, Dokumen KUA, dan Hiburan.
                </p>
            </div>

            <div class="rounded border border-stone-line bg-alabaster p-4">
                <div class="flex items-center justify-between">
                    <span class="flex h-8 w-8 items-center justify-center rounded bg-pearl border border-stone-line text-champagne">
                        <x-ui.icon name="building" class="h-4 w-4" />
                    </span>
                    <span class="text-xs font-bold text-champagne">Langkah 03</span>
                </div>
                <h3 class="mt-3 text-sm font-bold text-espresso">Kunci Vendor &amp; Pantau DP</h3>
                <p class="mt-1 text-xs leading-relaxed text-taupe">
                    Catat nilai kontrak mitra vendor, pantau progres pelunasan, dan hubungi PIC vendor langsung via WhatsApp.
                </p>
            </div>

            <div class="rounded border border-stone-line bg-alabaster p-4">
                <div class="flex items-center justify-between">
                    <span class="flex h-8 w-8 items-center justify-center rounded bg-pearl border border-stone-line text-sage">
                        <x-ui.icon name="printer" class="h-4 w-4" />
                    </span>
                    <span class="text-xs font-bold text-sage">Langkah 04</span>
                </div>
                <h3 class="mt-3 text-sm font-bold text-espresso">Eksekusi Linimasa &amp; Hari-H</h3>
                <p class="mt-1 text-xs leading-relaxed text-taupe">
                    Centang checklist per PIC dari H-12 bulan hingga Hari-H dan cetak laporan eksekutif untuk rapat keluarga.
                </p>
            </div>
        </div>
    </section>

    {{-- 4. ARSITEKTUR 5 MODUL TERPADU --}}
    <section id="fitur-platform" class="mt-12">
        <div class="flex items-center justify-between border-b border-stone-line pb-3">
            <h2 class="text-xl font-bold text-espresso">5 Modul Produk dalam Satu Langganan SaaS</h2>
            <span class="text-xs text-taupe">Terintegrasi penuh untuk Pasangan (B2C) &amp; Biro WO (B2B)</span>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            @php
                $modules = [
                    ['icon' => 'layout-dashboard', 'num' => 'Modul 01', 'title' => 'Executive Dashboard', 'desc' => 'Countdown akad, grafik serapan biaya, & kalkulator Weton Jawa.'],
                    ['icon' => 'wallet', 'num' => 'Modul 02', 'title' => 'Budget Planner', 'desc' => 'Plafon induk, 8 kategori biaya, estimasi vs aktual, & sisa tagihan.'],
                    ['icon' => 'check-circle', 'num' => 'Modul 03', 'title' => 'Wedding Checklist', 'desc' => 'Filter tugas per kategori, PIC keluarga, prioritas, & centang selesai.'],
                    ['icon' => 'calendar', 'num' => 'Modul 04', 'title' => 'Wedding Timeline', 'desc' => 'Peta persiapan kronologis 7 fase dari H-12 Bulan s/d Hari-H.'],
                    ['icon' => 'building', 'num' => 'Modul 05', 'title' => 'Vendor Management', 'desc' => 'Direktori kontrak mitra, cicilan DP/lunas, & tombol WhatsApp PIC.'],
                ];
            @endphp

            @foreach($modules as $mod)
                <div class="editorial-card rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-champagne-light text-champagne">
                            <x-ui.icon :name="$mod['icon']" class="h-4 w-4" />
                        </div>
                        <span class="text-[11px] font-semibold text-taupe">{{ $mod['num'] }}</span>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-espresso">{{ $mod['title'] }}</h3>
                    <p class="mt-1 text-xs leading-relaxed text-taupe">{{ $mod['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 5. LIVEWIRE REACTIVE BUDGET & ADAT SIMULATOR --}}
    <livewire:budget-simulator />

    {{-- 6. SAAS PRICING PLANS --}}
    <section id="paket-saas" class="mt-12">
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-3 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-xl font-bold text-espresso">Paket Layanan Software-as-a-Service (SaaS)</h2>
                <p class="text-xs text-taupe">Strategi Kampanye Pemasaran &amp; Positioning Produk untuk B2C Pasangan dan B2B Wedding Organizer</p>
            </div>
            <x-ui.status-badge tone="sage" label="Aktivasi Instan via Google" />
        </div>

        {{-- deslop-ignore-next-line 28 --}}
        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
            @foreach($packages as $pkg)
                <article class="editorial-card flex flex-col justify-between rounded-lg p-6 {{ $pkg['featured'] ? 'border-champagne bg-champagne-light/30' : '' }}">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-champagne">{{ $pkg['audience'] }}</span>
                            @if($pkg['featured'])
                                <x-ui.status-badge tone="champagne" label="Pilihan Terbaik" />
                            @endif
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-espresso">{{ $pkg['name'] }}</h3>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="tabular-nums text-2xl font-bold text-espresso">{{ $pkg['price'] }}</span>
                            <span class="text-xs text-taupe">{{ $pkg['period'] }}</span>
                        </div>
                        <p class="mt-2 text-xs leading-relaxed text-taupe">{{ $pkg['description'] }}</p>

                        <ul class="mt-4 space-y-2 border-t border-stone-line pt-3.5 text-xs text-charcoal">
                            @foreach($pkg['features'] as $feat)
                                <li class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-sage" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                    </svg>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-5 pt-3 border-t border-stone-line">
                        <a
                            href="{{ $pkg['cta_route'] }}"
                            class="w-full {{ $pkg['featured'] ? 'editorial-btn-primary' : 'editorial-btn-secondary' }}"
                        >
                            <x-ui.google-icon class="h-3.5 w-3.5" />
                            <span>{{ $pkg['cta_label'] }}</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- 7. INTERACTIVE ACCORDION FAQ --}}
    <section id="faq-section" class="mt-12 editorial-card rounded-lg p-6 sm:p-8" x-data="{ openFaq: 1 }">
        <div class="border-b border-stone-line pb-3">
            <h2 class="text-xl font-bold text-espresso">Pertanyaan Umum (FAQ) Layanan SaaS</h2>
            <p class="text-xs text-taupe">Jawaban langsung mengenai cara kerja akun Google, pengelolaan biaya, dan kolaborasi WO</p>
        </div>

        <div class="mt-4 divide-y divide-stone-line text-xs">
            <div class="py-3.5">
                <button type="button" @click="openFaq = openFaq === 1 ? 0 : 1" class="flex w-full items-center justify-between text-left font-bold text-espresso cursor-pointer">
                    <span>1. Apakah calon pengantin harus menginstal aplikasi tambahan untuk menggunakan Atelier Vow?</span>
                    <span class="text-champagne" x-text="openFaq === 1 ? '−' : '+'"></span>
                </button>
                <p x-show="openFaq === 1" class="mt-2 leading-relaxed text-taupe">
                    Tidak perlu. Atelier Vow berbasis web cloud (SaaS). Pasangan cukup klik tombol Masuk dengan Google dari laptop maupun ponsel untuk langsung mengakses ke-5 modul persiapan pernikahan.
                </p>
            </div>

            <div class="py-3.5">
                <button type="button" @click="openFaq = openFaq === 2 ? 0 : 2" class="flex w-full items-center justify-between text-left font-bold text-espresso cursor-pointer">
                    <span>2. Bagaimana sistem mencegah anggaran pernikahan membengkak (overbudget)?</span>
                    <span class="text-champagne" x-text="openFaq === 2 ? '−' : '+'"></span>
                </button>
                <p x-show="openFaq === 2" x-cloak class="mt-2 leading-relaxed text-taupe">
                    Setiap komponen biaya di Budget Planner mencatat Biaya Estimasi, Biaya Aktual Kontrak, dan Nominal Terbayar. Sistem otomatis menghitung selisih per kategori serta sisa plafon induk secara real-time.
                </p>
            </div>

            <div class="py-3.5">
                <button type="button" @click="openFaq = openFaq === 3 ? 0 : 3" class="flex w-full items-center justify-between text-left font-bold text-espresso cursor-pointer">
                    <span>3. Apakah data aman dan terpisah antara tampilan pelanggan dengan panel admin biro WO?</span>
                    <span class="text-champagne" x-text="openFaq === 3 ? '−' : '+'"></span>
                </button>
                <p x-show="openFaq === 3" x-cloak class="mt-2 leading-relaxed text-taupe">
                    Ya. Pelanggan yang login melalui Google hanya melihat workspace pernikahan mereka, sedangkan Konsol Operasional Admin/WO diproteksi oleh middleware khusus dan tersembunyi dari publik.
                </p>
            </div>
        </div>
    </section>

    {{-- 8. FINAL CONVERSION BANNER --}}
    <section class="mt-12 rounded-lg border border-stone-strong bg-pearl p-6 sm:p-8 text-center">
        <h2 class="text-2xl font-bold text-espresso">Siap Menyusun Rencana Pernikahan Anda?</h2>
        <p class="mx-auto mt-2 max-w-xl text-xs leading-relaxed text-taupe">
            Masuk dengan akun Google sekarang dan dapatkan akses langsung ke simulasi anggaran Rp 350 Juta, 8 vendor kurasi, dan 18 agenda linimasa H-12 bulan hingga Hari-H.
        </p>
        <div class="mt-5 flex justify-center">
            <a
                href="{{ auth()->check() ? route('dashboard.index') : route('login') }}"
                class="inline-flex items-center gap-2.5 rounded-md bg-espresso px-6 py-3 text-xs font-bold text-alabaster transition hover:bg-champagne"
            >
                <span class="flex h-5 w-5 items-center justify-center rounded-sm bg-pearl p-0.5">
                    <x-ui.google-icon class="h-3.5 w-3.5" />
                </span>
                <span>{{ auth()->check() ? 'Buka Workspace Pernikahan' : 'Masuk dengan Akun Google Sekarang' }}</span>
            </a>
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
                        backgroundColor: '#E6E0D6',
                        borderRadius: 4
                    },
                    {
                        label: 'Aktual (Jt)',
                        data: breakdown.map(i => Math.round(i.actual / 1000000)),
                        backgroundColor: '#735736',
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
