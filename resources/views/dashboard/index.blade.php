@extends('layouts.app', ['title' => 'Executive Dashboard'])

@section('content')
    {{-- 1. COMPACT VISUAL HERO & LIVE COUNTDOWN --}}
    <section class="editorial-card batik-kawung-bg relative rounded-lg p-6" data-reveal>
        <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
            <div>
                {{-- deslop-ignore-next-line 11 --}}
                <h1 class="text-2xl font-bold tracking-tight text-espresso">
                    {{ $profile->bride_name }} &amp; {{ $profile->groom_name }}
                </h1>
                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-sm text-taupe">
                    <span class="inline-flex items-center gap-1.5 font-medium text-charcoal">
                        <x-ui.icon name="calendar" class="h-3.5 w-3.5 text-champagne" />
                        <span>{{ $summary['wedding_date_formatted'] }} ({{ $profile->wedding_time }})</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-ui.icon name="building" class="h-3.5 w-3.5 text-champagne" />
                        <span>{{ $profile->venue_name }}, {{ $profile->city }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 tabular-nums">
                        <x-ui.icon name="users" class="h-3.5 w-3.5 text-champagne" />
                        <span>{{ number_format($profile->guest_target, 0, ',', '.') }} Tamu</span>
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2 no-print">
                    <button
                        type="button"
                        @click="$dispatch('open-modal', 'edit-profile-modal')"
                        class="editorial-btn-primary"
                    >
                        <x-ui.icon name="sliders" class="h-3.5 w-3.5" />
                        <span>Edit Profil &amp; Plafon</span>
                    </button>
                    <a
                        href="{{ route('print.masterGuide') }}"
                        target="_blank"
                        class="editorial-btn-secondary"
                        title="Buka Dokumen Panduan Master Pernikahan Siap Cetak A4 / PDF"
                    >
                        <x-ui.icon name="printer" class="h-3.5 w-3.5" />
                        <span>Buku Panduan &amp; Cetak</span>
                    </a>
                </div>
            </div>

            {{-- Compact Live Countdown + Readiness Meter --}}
            <div
                x-data="{
                    target: new Date('{{ $summary['wedding_date_iso'] }}').getTime(),
                    days: {{ $summary['days_remaining'] }},
                    hours: {{ $summary['hours_remaining'] }},
                    minutes: 0,
                    updateClock() {
                        const diff = Math.max(0, this.target - Date.now());
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                        this.minutes = Math.floor((diff / 1000 / 60) % 60);
                    }
                }"
                x-init="updateClock(); setInterval(() => updateClock(), 30000)"
                class="rounded-lg border border-stone-line bg-alabaster p-4 lg:min-w-[320px]"
            >
                <div class="flex items-center justify-between text-sm">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-charcoal">
                        <x-ui.icon name="clock" class="h-3.5 w-3.5 text-champagne" />
                        <span>Menuju Akad Nikah</span>
                    </span>
                    <span class="tabular-nums font-bold text-champagne">{{ $summary['readiness_score'] }}% Siap</span>
                </div>
                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-2.5 grid grid-cols-3 gap-2 text-center">
                    <div class="rounded border border-stone-line bg-pearl py-2">
                        <span class="tabular-nums text-2xl font-bold text-espresso" x-text="days">{{ $summary['days_remaining'] }}</span>
                        <span class="block text-xs font-medium text-taupe">Hari</span>
                    </div>
                    <div class="rounded border border-stone-line bg-pearl py-2">
                        <span class="tabular-nums text-2xl font-bold text-espresso" x-text="hours">{{ $summary['hours_remaining'] }}</span>
                        <span class="block text-xs font-medium text-taupe">Jam</span>
                    </div>
                    <div class="rounded border border-stone-line bg-pearl py-2">
                        <span class="tabular-nums text-2xl font-bold text-champagne" x-text="minutes">0</span>
                        <span class="block text-xs font-medium text-taupe">Menit</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. FOUR KPI CARDS WITH SVG ICONS --}}
    <section class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            icon="wallet"
            label="Plafon Total Anggaran"
            :value="format_idr($summary['total_budget_cap'])"
            :subtitle="'Sisa plafon: ' . format_idr($summary['remaining_budget'])"
            :badge="$summary['remaining_budget'] >= 0 ? 'Aman' : 'Over'"
            :badgeTone="$summary['remaining_budget'] >= 0 ? 'sage' : 'wine'"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['budget_utilization_percent']" :tone="$summary['remaining_budget'] >= 0 ? 'champagne' : 'wine'" />
        </x-ui.stat-card>

        <x-ui.stat-card
            icon="trending-up"
            label="Realisasi Pengeluaran"
            :value="format_idr($summary['total_actual'])"
            :subtitle="'Terbayar: ' . format_idr($summary['total_paid']) . ' (' . $summary['budget_paid_percent'] . '%)'"
            :badge="$summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Over'"
            :badgeTone="$summary['variance_amount'] >= 0 ? 'sage' : 'wine'"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['budget_paid_percent']" tone="sage" />
        </x-ui.stat-card>

        <x-ui.stat-card
            icon="check-circle"
            label="Progres Tugas Checklist"
            :value="$summary['completion_percent'] . '%'"
            :subtitle="$summary['completed_tasks'] . '/' . $summary['total_tasks'] . ' tugas selesai'"
            :badge="$summary['overdue_tasks_count'] > 0 ? $summary['overdue_tasks_count'] . ' Terlambat' : 'Tepat Waktu'"
            :badgeTone="$summary['overdue_tasks_count'] > 0 ? 'wine' : 'sage'"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['completion_percent']" tone="sage" />
        </x-ui.stat-card>

        <x-ui.stat-card
            icon="building"
            label="Kontrak Mitra Vendor"
            :value="$summary['booked_vendors'] . ' / ' . $summary['total_vendors'] . ' Mitra'"
            :subtitle="'Sisa tagihan: ' . format_idr($summary['unpaid_balance'])"
            :badge="format_idr($summary['cost_per_guest']) . '/pax'"
            badgeTone="champagne"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['vendor_booking_percent']" tone="champagne" />
        </x-ui.stat-card>
    </section>

    {{-- 3. MAIN VISUALIZATION GRID: CHARTS + URGENT ACTIONS --}}
    <section class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        {{-- Left 7 Cols: Interactive Bar & Doughnut Chart + Compact Category Bars --}}
        <div class="editorial-card rounded-lg p-5 lg:col-span-7" x-data="{ chartMode: 'bar' }" data-reveal>
            <div class="flex items-center justify-between border-b border-stone-line pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="wallet" class="h-4 w-4 text-champagne" />
                    <h2 class="text-xl font-bold text-espresso">Distribusi Anggaran per Kategori</h2>
                </div>
                <div class="flex items-center gap-1 no-print">
                    <button
                        type="button"
                        @click="chartMode = 'bar'"
                        :class="chartMode === 'bar' ? 'bg-espresso text-alabaster' : 'bg-ivory text-charcoal'"
                        class="rounded px-2.5 py-1 text-xs font-semibold cursor-pointer"
                    >
                        Batang
                    </button>
                    <button
                        type="button"
                        @click="chartMode = 'doughnut'"
                        :class="chartMode === 'doughnut' ? 'bg-espresso text-alabaster' : 'bg-ivory text-charcoal'"
                        class="rounded px-2.5 py-1 text-xs font-semibold cursor-pointer"
                    >
                        Donat
                    </button>
                </div>
            </div>

            <div class="mt-4 h-60 w-full" x-show="chartMode === 'bar'">
                <canvas id="budgetCategoryChart" aria-label="Grafik perbandingan estimasi dan aktual anggaran per kategori" role="img"></canvas>
            </div>

            <div class="mt-4 h-60 w-full" x-show="chartMode === 'doughnut'" x-cloak>
                <canvas id="budgetShareDoughnutChart" aria-label="Grafik proporsi anggaran per kategori" role="img"></canvas>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                @foreach($summary['category_breakdown'] as $cat)
                    <div class="rounded border border-stone-line bg-alabaster p-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 font-semibold text-espresso">
                                <x-ui.icon :name="$cat['case']->icon()" class="h-3.5 w-3.5 text-champagne" />
                                <span>{{ $cat['short_label'] }}</span>
                            </span>
                            <span class="tabular-nums font-semibold text-charcoal">{{ format_idr($cat['actual']) }}</span>
                        </div>
                        <div class="mt-1.5">
                            <x-ui.progress-bar size="sm" :percent="$cat['utilization_percent']" :tone="$cat['is_overbudget'] ? 'wine' : 'champagne'" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right 5 Cols: Urgent Tasks & Vendor Payments --}}
        <div class="flex flex-col gap-6 lg:col-span-5">
            <div class="editorial-card rounded-lg p-5" data-reveal style="--delay: 80ms">
                <div class="flex items-center justify-between border-b border-stone-line pb-3">
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="check-circle" class="h-4 w-4 text-champagne" />
                        <h2 class="text-xl font-bold text-espresso">Tugas Terdekat</h2>
                    </div>
                    <a href="{{ route('checklist.index') }}" class="text-xs font-semibold text-champagne hover:underline">Semua</a>
                </div>

                <div class="mt-3 divide-y divide-stone-line/70">
                    @forelse($summary['urgent_tasks']->take(4) as $task)
                        <div class="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <form method="POST" action="{{ route('checklist.toggle', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="flex h-4 w-4 shrink-0 items-center justify-center rounded border border-stone-strong bg-alabaster text-transparent hover:border-champagne cursor-pointer"
                                        title="Tandai selesai"
                                    >
                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                        </svg>
                                    </button>
                                </form>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-espresso">{{ $task->title }}</p>
                                    <p class="text-xs text-taupe">{{ $task->phase }} · {{ $task->assigned_to }}</p>
                                </div>
                            </div>
                            <x-ui.status-badge
                                :tone="$task->is_overdue ? 'wine' : 'neutral'"
                                :label="$task->is_overdue ? 'Terlambat' : $task->due_date->format('d M')"
                            />
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-taupe">Seluruh tugas selesai.</p>
                    @endforelse
                </div>
            </div>

            <div class="editorial-card rounded-lg p-5" data-reveal style="--delay: 140ms">
                <div class="flex items-center justify-between border-b border-stone-line pb-3">
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="building" class="h-4 w-4 text-champagne" />
                        <h2 class="text-xl font-bold text-espresso">Sisa Pelunasan Vendor</h2>
                    </div>
                    <a href="{{ route('vendors.index') }}" class="text-xs font-semibold text-champagne hover:underline">Direktori</a>
                </div>

                <div class="mt-3 divide-y divide-stone-line/70">
                    @forelse($summary['upcoming_vendor_payments']->take(4) as $vendor)
                        <div class="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0 text-sm">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-espresso">{{ $vendor->name }}</p>
                                <p class="text-xs text-taupe">{{ $vendor->category->shortLabel() }} · Terbayar {{ $vendor->payment_progress_percent }}%</p>
                            </div>
                            <span class="tabular-nums font-bold text-wine shrink-0">{{ format_idr($vendor->remaining_balance) }}</span>
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-taupe">Semua kontrak lunas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- 4. COMPACT 7-PHASE TIMELINE STRIP --}}
    <section class="mt-6 editorial-card rounded-lg p-5" data-reveal>
        <div class="flex items-center justify-between border-b border-stone-line pb-3">
            <div class="flex items-center gap-2">
                <x-ui.icon name="calendar" class="h-4 w-4 text-champagne" />
                <h2 class="text-xl font-bold text-espresso">Progres 7 Fase Linimasa (H-12 Bulan s/d Hari-H)</h2>
            </div>
            <a href="{{ route('timeline.index') }}" class="text-xs font-semibold text-champagne hover:underline">Buka Timeline</a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2.5 sm:grid-cols-4 lg:grid-cols-7">
            @foreach($summary['phase_progress'] as $phase)
                <a
                    href="{{ route('timeline.index', ['phase' => $phase['phase']]) }}"
                    class="rounded border border-stone-line bg-alabaster p-3 transition hover:border-champagne hover:bg-pearl"
                >
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-bold text-espresso">{{ $phase['phase'] }}</span>
                        <span class="tabular-nums font-semibold {{ $phase['percent'] === 100 ? 'text-sage' : 'text-taupe' }}">{{ $phase['percent'] }}%</span>
                    </div>
                    <div class="mt-2">
                        <x-ui.progress-bar size="sm" :percent="$phase['percent']" :tone="$phase['percent'] === 100 ? 'sage' : 'champagne'" />
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- 5. NUSANTARA ADAT & WETON CALCULATOR (Compact Visual Layout) --}}
    <section
        class="mt-6 editorial-card rounded-lg p-5"
        data-reveal
        x-data="{
            hariWanita: 5,
            pasaranWanita: 8,
            hariPria: 4,
            pasaranPria: 9,
            totalNeptu() {
                return Number(this.hariWanita) + Number(this.pasaranWanita) + Number(this.hariPria) + Number(this.pasaranPria);
            },
            maknaWeton() {
                const mod = this.totalNeptu() % 8;
                const map = {
                    1: { nama: 'Pegat', ket: 'Disarankan akad pagi 08:00 WIB & doa selamatan keluarga.' },
                    2: { nama: 'Ratu (Harmoni)', ket: 'Dihormati lingkungan & membawa ketenteraman.' },
                    3: { nama: 'Jodoh (Selaras)', ket: 'Pasangan serasi & saling menguatkan.' },
                    4: { nama: 'Topo (Tangguh)', ket: 'Rumah tangga semakin matang & berkah.' },
                    5: { nama: 'Tinari (Murah Rezeki)', ket: 'Diberkahi kemudahan rezeki & kebahagiaan.' },
                    6: { nama: 'Padu (Musyawarah)', ket: 'Utamakan komunikasi terbuka dalam keluarga.' },
                    7: { nama: 'Sujanan (Saling Jaga)', ket: 'Jaga kepercayaan kedua keluarga besar.' },
                    0: { nama: 'Pesthi (Tentram)', ket: 'Rukun, damai, & harmonis hingga tua.' }
                };
                return map[mod] || map[3];
            }
        }"
    >
        <div class="flex items-center justify-between border-b border-stone-line pb-3">
            <div class="flex items-center gap-2">
                <x-ui.icon name="sparkles" class="h-4 w-4 text-champagne" />
                <h2 class="text-xl font-bold text-espresso">Tata Upacara Adat Jawa &amp; Perhitungan Weton Neptu</h2>
            </div>
            <x-ui.status-badge tone="champagne" label="Adat Nusantara" />
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-12">
            <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-4 lg:col-span-7 text-sm">
                <div class="rounded border border-stone-line bg-alabaster p-3">
                    <span class="text-xs font-semibold text-sage">1. H-1 Pagi</span>
                    <p class="mt-1 font-bold text-espresso">Siraman</p>
                    <p class="mt-0.5 text-xs text-taupe">Air 7 sumber &amp; sungkeman</p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-3">
                    <span class="text-xs font-semibold text-sage">2. H-1 Malam</span>
                    <p class="mt-1 font-bold text-espresso">Midodareni</p>
                    <p class="mt-0.5 text-xs text-taupe">Seserahan &amp; doa keluarga</p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-3">
                    <span class="text-xs font-semibold text-champagne">3. Hari-H 08:30</span>
                    <p class="mt-1 font-bold text-espresso">Ijab Qabul</p>
                    <p class="mt-0.5 text-xs text-taupe">Akad nikah &amp; mahar</p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-3">
                    <span class="text-xs font-semibold text-champagne">4. Hari-H 18:30</span>
                    <p class="mt-1 font-bold text-espresso">Panggih</p>
                    <p class="mt-0.5 text-xs text-taupe">Adat panggih &amp; resepsi</p>
                </div>
            </div>

            <div class="flex flex-col justify-between gap-3 rounded border border-stone-line bg-alabaster p-3.5 lg:col-span-5 text-xs">
                <div class="grid grid-cols-4 gap-2">
                    <select x-model="hariWanita" class="editorial-input !py-1 !px-2 !text-xs" aria-label="Hari Wanita">
                        <option value="5">Minggu</option><option value="4">Senin</option><option value="3">Selasa</option><option value="7">Rabu</option><option value="8">Kamis</option><option value="6">Jumat</option><option value="9">Sabtu</option>
                    </select>
                    <select x-model="pasaranWanita" class="editorial-input !py-1 !px-2 !text-xs" aria-label="Pasaran Wanita">
                        <option value="5">Legi</option><option value="9">Pahing</option><option value="7">Pon</option><option value="4">Wage</option><option value="8">Kliwon</option>
                    </select>
                    <select x-model="hariPria" class="editorial-input !py-1 !px-2 !text-xs" aria-label="Hari Pria">
                        <option value="5">Minggu</option><option value="4">Senin</option><option value="3">Selasa</option><option value="7">Rabu</option><option value="8">Kamis</option><option value="6">Jumat</option><option value="9">Sabtu</option>
                    </select>
                    <select x-model="pasaranPria" class="editorial-input !py-1 !px-2 !text-xs" aria-label="Pasaran Pria">
                        <option value="5">Legi</option><option value="9">Pahing</option><option value="7">Pon</option><option value="4">Wage</option><option value="8">Kliwon</option>
                    </select>
                </div>
                <div class="flex items-center justify-between rounded border border-stone-line bg-pearl px-3 py-2">
                    <div>
                        <span class="font-bold text-espresso" x-text="maknaWeton().nama"></span>
                        <span class="ml-1.5 text-[11px] text-taupe" x-text="maknaWeton().ket"></span>
                    </div>
                    <span class="tabular-nums font-bold text-champagne shrink-0" x-text="totalNeptu() + ' Neptu'">26 Neptu</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal: Edit Couple Profile & Budget Cap --}}
    <x-ui.modal name="edit-profile-modal" title="Pengaturan Profil Pernikahan" subtitle="Perbarui identitas pasangan, jadwal akad, lokasi venue, dan plafon anggaran induk">
        <form method="POST" action="{{ route('dashboard.updateProfile') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Wanita</label>
                    <input type="text" name="bride_name" value="{{ old('bride_name', $profile->bride_name) }}" required class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Pria</label>
                    <input type="text" name="groom_name" value="{{ old('groom_name', $profile->groom_name) }}" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Pernikahan (Hari-H)</label>
                    <input type="date" name="wedding_date" value="{{ old('wedding_date', $profile->wedding_date->format('Y-m-d')) }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Waktu Prosesi</label>
                    <input type="text" name="wedding_time" value="{{ old('wedding_time', $profile->wedding_time) }}" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Venue Utama</label>
                    <input type="text" name="venue_name" value="{{ old('venue_name', $profile->venue_name) }}" required class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $profile->city) }}" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Plafon Total Anggaran (Rp)</label>
                    <input type="number" name="total_budget" value="{{ old('total_budget', $profile->total_budget) }}" min="1000000" step="500000" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Target Jumlah Tamu</label>
                    <input type="number" name="guest_target" value="{{ old('guest_target', $profile->guest_target) }}" min="10" required class="editorial-input tabular-nums">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Tema Konsep</label>
                <input type="text" name="theme_concept" value="{{ old('theme_concept', $profile->theme_concept) }}" required class="editorial-input">
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'edit-profile-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Chart === 'undefined') return;

        const breakdown = @json($summary['category_breakdown']);
        const labels = breakdown.map(item => item.short_label);
        const estimatedData = breakdown.map(item => Math.round(item.estimated / 1000000));
        const actualData = breakdown.map(item => Math.round(item.actual / 1000000));

        const barCanvas = document.getElementById('budgetCategoryChart');
        if (barCanvas) {
            new window.Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Estimasi (Juta Rp)',
                            data: estimatedData,
                            backgroundColor: '#E6E0D6',
                            borderRadius: 4,
                        },
                        {
                            label: 'Aktual (Juta Rp)',
                            data: actualData,
                            backgroundColor: '#735736',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 12, font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) { return 'Rp ' + value + ' Jt'; },
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }
                            },
                            grid: { color: '#F3EFEA' }
                        },
                        x: {
                            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        const doughnutCanvas = document.getElementById('budgetShareDoughnutChart');
        if (doughnutCanvas) {
            new window.Chart(doughnutCanvas, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: actualData,
                        backgroundColor: [
                            '#181512', '#735736', '#2E5A44', '#B45309',
                            '#57504A', '#D4C9B8', '#5C4428', '#991B1B'
                        ],
                        borderColor: '#FFFFFF',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { boxWidth: 12, font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
