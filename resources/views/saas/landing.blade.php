@extends('layouts.app', ['title' => 'Platform Perencanaan Pernikahan Nusantara'])

@section('content')
    {{-- 1. MINIMALIST STARTUP HERO + VISUAL 3D APP PREVIEW --}}
    <section class="editorial-card batik-kawung-bg relative rounded-lg p-6 sm:p-10">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-6">
                {{-- deslop-ignore-next-line 11 --}}
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-espresso sm:text-4xl">
                    Satu buku kerja untuk anggaran, vendor, dan adat pernikahan.
                </h1>
                <p class="mt-3 max-w-lg text-sm leading-relaxed text-taupe">
                    Kelola plafon 8 kategori biaya, pantau jatuh tempo DP vendor, dan jadwalkan persiapan H-12 bulan hingga Hari-H dalam satu dashboard visual.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard.index') : route('login') }}" class="editorial-btn-primary">
                        <x-ui.icon name="sparkles" class="h-4 w-4" />
                        <span>{{ auth()->check() ? 'Buka Dashboard Pernikahan' : 'Masuk dengan Google' }}</span>
                    </a>
                    <a href="#simulasi-adat" class="editorial-btn-secondary">
                        <x-ui.icon name="sliders" class="h-4 w-4" />
                        <span>Kalkulator Anggaran</span>
                    </a>
                </div>

                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-7 grid grid-cols-3 gap-3 border-t border-stone-line pt-5 text-xs">
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="layout-dashboard" class="h-4 w-4 text-champagne" />
                        <div>
                            <p class="font-bold text-espresso">5 Modul</p>
                            <p class="text-[11px] text-taupe">Terintegrasi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="wallet" class="h-4 w-4 text-champagne" />
                        <div>
                            <p class="font-bold text-espresso">8 Kategori</p>
                            <p class="text-[11px] text-taupe">Standar WO</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="shield-check" class="h-4 w-4 text-sage" />
                        <div>
                            <p class="font-bold text-espresso">Google Auth</p>
                            <p class="text-[11px] text-taupe">Akses Aman</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Visual 3D App Dashboard Preview Card --}}
            <div class="stage-3d lg:col-span-6" data-ui>
                <div
                    x-data="spatialTilt(4)"
                    @mousemove="handleMove($event)"
                    @mouseleave="handleLeave()"
                    :style="{ transform: transformStyle }"
                    class="tilt-card-3d rounded-lg border border-stone-strong bg-pearl p-5"
                >
                    <div class="flex items-center justify-between border-b border-stone-line pb-3 text-xs">
                        <div class="flex items-center gap-2">
                            <x-ui.icon name="users" class="h-4 w-4 text-champagne" />
                            <span class="font-bold text-espresso">{{ $profile->couple_names }}</span>
                        </div>
                        <x-ui.status-badge tone="champagne" :label="'H-' . $summary['days_remaining'] . ' Hari'" />
                    </div>

                    {{-- deslop-ignore-next-line 28 --}}
                    <div class="mt-4 grid grid-cols-3 gap-2.5 text-xs">
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

                    <div class="mt-4 rounded border border-stone-line bg-alabaster p-3.5">
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="font-semibold text-espresso">Visualisasi Serapan 4 Kategori Utama</span>
                            <span class="tabular-nums font-semibold text-champagne">{{ $summary['readiness_score'] }}% Siap</span>
                        </div>
                        <div class="h-32 w-full">
                            <canvas id="landingPreviewChart" role="img" aria-label="Pratinjau grafik anggaran"></canvas>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between text-xs text-taupe">
                        <span>{{ $summary['booked_vendors'] }}/{{ $summary['total_vendors'] }} Vendor · {{ $summary['completed_tasks'] }}/{{ $summary['total_tasks'] }} Tugas</span>
                        <a href="{{ auth()->check() ? route('dashboard.index') : route('login') }}" class="inline-flex items-center gap-1 font-semibold text-champagne hover:underline">
                            <span>Masuk ke Workspace</span>
                            <x-ui.icon name="arrow-right" class="h-3.5 w-3.5" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. VISUAL 5-MODULE OVERVIEW (Icon-First, Minimal Text) --}}
    <section id="fitur-platform" class="mt-10">
        <div class="flex items-center justify-between border-b border-stone-line pb-3">
            <h2 class="text-xl font-bold text-espresso">Arsitektur 5 Modul Terpadu</h2>
            <span class="text-xs text-taupe">Terbuka otomatis setelah login Google</span>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            @php
                $modules = [
                    ['icon' => 'layout-dashboard', 'num' => 'Modul 01', 'title' => 'Executive Dashboard', 'desc' => 'Countdown akad, grafik biaya, & kalkulator Weton Jawa.'],
                    ['icon' => 'wallet', 'num' => 'Modul 02', 'title' => 'Budget Planner', 'desc' => 'Plafon anggaran, 8 kategori biaya, estimasi vs aktual.'],
                    ['icon' => 'check-circle', 'num' => 'Modul 03', 'title' => 'Wedding Checklist', 'desc' => 'Filter tugas per kategori, PIC keluarga, & toggle selesai.'],
                    ['icon' => 'calendar', 'num' => 'Modul 04', 'title' => 'Wedding Timeline', 'desc' => 'Peta persiapan kronologis fase H-12 Bulan s/d Hari-H.'],
                    ['icon' => 'building', 'num' => 'Modul 05', 'title' => 'Vendor Management', 'desc' => 'Kontrak mitra, cicilan DP/lunas, & tombol WhatsApp PIC.'],
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

    {{-- 3. LIVEWIRE REACTIVE BUDGET & ADAT SIMULATOR --}}
    <livewire:budget-simulator />

    {{-- 4. SAAS PRICING PLANS --}}
    <section id="paket-saas" class="mt-10">
        <div class="flex items-center justify-between border-b border-stone-line pb-3">
            <h2 class="text-xl font-bold text-espresso">Paket Layanan Software-as-a-Service (SaaS)</h2>
            <span class="text-xs text-taupe">Strategi Kampanye Pemasaran &amp; Positioning Produk</span>
        </div>

        {{-- deslop-ignore-next-line 28 --}}
        <div class="mt-4 grid grid-cols-1 gap-5 lg:grid-cols-3">
            @foreach($packages as $pkg)
                <article class="editorial-card flex flex-col justify-between rounded-lg p-5 {{ $pkg['featured'] ? 'border-champagne bg-champagne-light/30' : '' }}">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-champagne">{{ $pkg['audience'] }}</span>
                            @if($pkg['featured'])
                                <x-ui.status-badge tone="champagne" label="Populer" />
                            @endif
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-espresso">{{ $pkg['name'] }}</h3>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="tabular-nums text-2xl font-bold text-espresso">{{ $pkg['price'] }}</span>
                            <span class="text-xs text-taupe">{{ $pkg['period'] }}</span>
                        </div>

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
                            {{ $pkg['cta_label'] }}
                        </a>
                    </div>
                </article>
            @endforeach
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
