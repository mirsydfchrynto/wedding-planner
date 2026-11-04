@extends('layouts.app', ['title' => 'Executive Dashboard'])

@section('content')
    {{-- Editorial Hero: Couple Vow & Live Countdown --}}
    <section class="editorial-card batik-kawung-bg relative rounded-lg p-6 sm:p-8 lg:p-10">
        <x-ui.batik-ornament variant="gunungan" class="absolute right-4 bottom-4 h-44 w-32 opacity-30" />
        <div class="relative z-10 flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
            <div class="max-w-2xl">
                {{-- deslop-ignore-next-line 11 --}}
                <h1 class="font-editorial text-4xl font-semibold text-espresso sm:text-5xl">
                    {{ $profile->bride_name }} &amp; {{ $profile->groom_name }}
                </h1>
                <p class="mt-2.5 text-sm text-charcoal sm:text-base">
                    {{ $summary['wedding_date_formatted'] }} · {{ $profile->wedding_time }}
                </p>
                <p class="mt-1 text-xs text-taupe">
                    Lokasi: <strong class="font-semibold text-charcoal">{{ $profile->venue_name }}, {{ $profile->city }}</strong> · Target Undangan: <strong class="tabular-nums font-semibold text-charcoal">{{ number_format($profile->guest_target, 0, ',', '.') }} Tamu</strong> · {{ $profile->theme_concept }}
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-2.5 no-print">
                    <button
                        type="button"
                        @click="$dispatch('open-modal', 'edit-profile-modal')"
                        class="editorial-btn-primary"
                    >
                        Perbarui Profil &amp; Tanggal Akad
                    </button>
                    <a href="{{ route('budget.index') }}" class="editorial-btn-secondary">
                        Kelola Buku Anggaran
                    </a>
                    <a href="{{ route('checklist.index') }}" class="editorial-btn-secondary">
                        Daftar Tugas ({{ $summary['completed_tasks'] }}/{{ $summary['total_tasks'] }})
                    </a>
                    <button
                        type="button"
                        onclick="window.print()"
                        class="editorial-btn-secondary"
                    >
                        Cetak Ringkasan Eksekutif
                    </button>
                </div>

                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-5 grid grid-cols-3 gap-3 border-t border-stone-line pt-4 text-xs">
                    <div>
                        <span class="text-taupe">Indeks Kesiapan Hari-H</span>
                        <p class="mt-0.5 tabular-nums font-semibold text-espresso">{{ $summary['readiness_score'] }}% Terukur</p>
                    </div>
                    <div>
                        <span class="text-taupe">Progres Pembayaran</span>
                        <p class="mt-0.5 tabular-nums font-semibold text-sage">{{ $summary['budget_paid_percent'] }}% Terbayar</p>
                    </div>
                    <div>
                        <span class="text-taupe">Estimasi Biaya per Tamu</span>
                        <p class="mt-0.5 tabular-nums font-semibold text-champagne">{{ format_idr($summary['cost_per_guest']) }}/pax</p>
                    </div>
                </div>
            </div>

            {{-- Live Countdown Block --}}
            <div
                x-data="{
                    ...spatialTilt(3),
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
                @mousemove="handleMove($event)"
                @mouseleave="handleLeave()"
                :style="{ transform: transformStyle }"
                class="tilt-card-3d rounded-lg border border-stone-line bg-alabaster p-5 sm:p-6 lg:min-w-[330px]"
            >
                <p class="text-xs font-medium text-taupe">Hitung Mundur Menuju Hari-H</p>
                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-3 grid grid-cols-3 gap-3 text-center">
                    <div class="rounded border border-stone-line bg-pearl px-3 py-3.5">
                        <span class="font-editorial tabular-nums text-3xl font-bold text-espresso sm:text-4xl" x-text="days">{{ $summary['days_remaining'] }}</span>
                        <span class="mt-1 block text-[11px] font-medium text-taupe">Hari</span>
                    </div>
                    <div class="rounded border border-stone-line bg-pearl px-3 py-3.5">
                        <span class="font-editorial tabular-nums text-3xl font-bold text-espresso sm:text-4xl" x-text="hours">{{ $summary['hours_remaining'] }}</span>
                        <span class="mt-1 block text-[11px] font-medium text-taupe">Jam</span>
                    </div>
                    <div class="rounded border border-stone-line bg-pearl px-3 py-3.5">
                        <span class="font-editorial tabular-nums text-3xl font-bold text-champagne sm:text-4xl" x-text="minutes">0</span>
                        <span class="mt-1 block text-[11px] font-medium text-taupe">Menit</span>
                    </div>
                </div>
                <div class="mt-4">
                    <x-ui.progress-bar
                        :percent="$summary['completion_percent']"
                        tone="champagne"
                        label="Kesiapan Keseluruhan"
                        :meta="$summary['completed_tasks'] . ' dari ' . $summary['total_tasks'] . ' tugas selesai'"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- 4 KPI Executive Summary Cards --}}
    <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Plafon Total Anggaran"
            :value="format_idr($summary['total_budget_cap'])"
            :subtitle="'Sisa anggaran: ' . format_idr($summary['remaining_budget'])"
            :badge="$summary['remaining_budget'] >= 0 ? 'Dalam Plafon' : 'Melebihi Plafon'"
            :badgeTone="$summary['remaining_budget'] >= 0 ? 'sage' : 'wine'"
        >
            <x-ui.progress-bar
                size="sm"
                :percent="$summary['budget_utilization_percent']"
                :tone="$summary['remaining_budget'] >= 0 ? 'champagne' : 'wine'"
                label="Serapan Anggaran"
            />
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Realisasi Pengeluaran & Terbayar"
            :value="format_idr($summary['total_actual'])"
            :subtitle="'Sudah dibayarkan: ' . format_idr($summary['total_paid'])"
            :badge="'Estimasi: ' . format_idr($summary['total_estimated'])"
            badgeTone="champagne"
        >
            <div class="flex items-center justify-between text-xs">
                <span class="text-taupe">Selisih Estimasi vs Aktual</span>
                <span class="tabular-nums font-semibold {{ $summary['variance_amount'] >= 0 ? 'text-sage' : 'text-wine' }}">
                    {{ $summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Over ' . format_idr(abs($summary['variance_amount'])) }}
                </span>
            </div>
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Progres Daftar Tugas (Checklist)"
            :value="$summary['completion_percent'] . '%'"
            :subtitle="$summary['completed_tasks'] . ' Selesai · ' . $summary['in_progress_tasks'] . ' Berjalan · ' . $summary['todo_tasks'] . ' Belum'"
            :badge="$summary['overdue_tasks_count'] > 0 ? $summary['overdue_tasks_count'] . ' Tugas Terlambat' : 'Jadwal Aman'"
            :badgeTone="$summary['overdue_tasks_count'] > 0 ? 'wine' : 'sage'"
        >
            <x-ui.progress-bar
                size="sm"
                :percent="$summary['completion_percent']"
                tone="sage"
                label="Penyelesaian Checklist"
            />
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Mitra Vendor Terkonfirmasi"
            :value="$summary['booked_vendors'] . ' / ' . $summary['total_vendors'] . ' Vendor'"
            :subtitle="'Sisa tagihan vendor: ' . format_idr($summary['unpaid_balance'])"
            badge="Kontrak Aktif"
            badgeTone="ochre"
        >
            <div class="flex items-center justify-between text-xs">
                <span class="text-taupe">Direktori Mitra</span>
                <a href="{{ route('vendors.index') }}" class="font-semibold text-champagne hover:underline">Lihat Semua Vendor</a>
            </div>
        </x-ui.stat-card>
    </section>

    {{-- Main Two-Column Analytical Grid: Budget Allocation vs Urgent Tasks --}}
    <section class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left 7 Cols: Budget vs Actual Visualization & Category Breakdown --}}
        <div class="editorial-card rounded-lg p-6 lg:col-span-7" x-data="{ chartMode: 'bar' }">
            <div class="flex flex-col justify-between gap-3 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="font-editorial text-2xl font-semibold text-espresso">Distribusi Anggaran per Kategori</h2>
                    <p class="text-xs text-taupe">Perbandingan estimasi awal terhadap pengeluaran aktual pada 8 kategori utama</p>
                </div>
                <div class="flex items-center gap-1.5 no-print">
                    <button
                        type="button"
                        @click="chartMode = 'bar'"
                        :class="chartMode === 'bar' ? 'bg-espresso text-alabaster' : 'bg-ivory text-charcoal'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition cursor-pointer"
                    >
                         Estimasi vs Aktual
                    </button>
                    <button
                        type="button"
                        @click="chartMode = 'doughnut'"
                        :class="chartMode === 'doughnut' ? 'bg-espresso text-alabaster' : 'bg-ivory text-charcoal'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition cursor-pointer"
                    >
                        Proporsi Kategori
                    </button>
                </div>
            </div>

            <div class="mt-5 h-64 w-full" x-show="chartMode === 'bar'">
                <canvas id="budgetCategoryChart" aria-label="Grafik perbandingan estimasi dan aktual anggaran per kategori" role="img"></canvas>
            </div>

            <div class="mt-5 h-64 w-full" x-show="chartMode === 'doughnut'" x-cloak>
                <canvas id="budgetShareDoughnutChart" aria-label="Grafik proporsi anggaran per kategori" role="img"></canvas>
            </div>
            <div class="mt-6 divide-y divide-stone-line/70">
                @foreach($summary['category_breakdown'] as $cat)
                    <div class="py-3 first:pt-0 last:pb-0">
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-espresso">{{ $cat['label'] }}</span>
                                <span class="tabular-nums text-taupe">({{ $cat['share_percent'] }}% dari total aktual)</span>
                            </div>
                            <div class="tabular-nums font-medium text-charcoal">
                                Aktual <strong class="text-espresso">{{ format_idr($cat['actual']) }}</strong>
                                <span class="text-taupe">/ Estimasi {{ format_idr($cat['estimated']) }}</span>
                            </div>
                        </div>
                        <div class="mt-1.5">
                            <x-ui.progress-bar
                                size="sm"
                                :percent="$cat['utilization_percent']"
                                :tone="$cat['is_overbudget'] ? 'wine' : 'champagne'"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right 5 Cols: Urgent Tasks & Upcoming Vendor Payments --}}
        <div class="flex flex-col gap-6 lg:col-span-5">
            {{-- Urgent Tasks Card --}}
            <div class="editorial-card rounded-lg p-6">
                <div class="flex items-center justify-between border-b border-stone-line pb-4">
                    <div>
                        <h2 class="font-editorial text-2xl font-semibold text-espresso">Prioritas &amp; Tenggat Terdekat</h2>
                        <p class="text-xs text-taupe">Tugas persiapan yang memerlukan tindakan segera</p>
                    </div>
                    <a href="{{ route('checklist.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                        Semua Tugas
                    </a>
                </div>

                <div class="mt-4 divide-y divide-stone-line/70">
                    @forelse($summary['urgent_tasks'] as $task)
                        <div class="flex items-start justify-between gap-3 py-3.5 first:pt-0 last:pb-0">
                            <div class="flex items-start gap-3">
                                <form method="POST" action="{{ route('checklist.toggle', $task) }}" class="mt-0.5">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="flex h-5 w-5 items-center justify-center rounded border border-stone-strong bg-alabaster text-transparent hover:border-champagne"
                                        title="Tandai selesai"
                                    >
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                        </svg>
                                    </button>
                                </form>
                                <div>
                                    <p class="text-sm font-medium text-espresso">{{ $task->title }}</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-taupe">
                                        <span>{{ $task->category->shortLabel() }}</span>
                                        <span>·</span>
                                        <span>{{ $task->phase }}</span>
                                        <span>·</span>
                                        <span>PIC: {{ $task->assigned_to }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <x-ui.status-badge
                                    :tone="$task->is_overdue ? 'wine' : ($task->priority === 'high' ? 'ochre' : 'neutral')"
                                    :label="$task->is_overdue ? 'Terlambat' : $task->due_date->format('d M Y')"
                                />
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-taupe">Seluruh tugas persiapan telah selesai.</p>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Vendor Payments Card --}}
            <div class="editorial-card rounded-lg p-6">
                <div class="flex items-center justify-between border-b border-stone-line pb-4">
                    <div>
                        <h2 class="font-editorial text-2xl font-semibold text-espresso">Jadwal Pelunasan Vendor</h2>
                        <p class="text-xs text-taupe">Mitra dengan sisa tagihan kontrak yang belum lunas</p>
                    </div>
                    <a href="{{ route('vendors.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                        Buku Vendor
                    </a>
                </div>

                <div class="mt-4 divide-y divide-stone-line/70">
                    @forelse($summary['upcoming_vendor_payments'] as $vendor)
                        <div class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-espresso">{{ $vendor->name }}</p>
                                <p class="text-xs text-taupe">
                                    {{ $vendor->category->shortLabel() }} · Jatuh tempo: {{ $vendor->payment_due_date ? $vendor->payment_due_date->format('d M Y') : 'Fleksibel' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="tabular-nums text-sm font-semibold text-wine">{{ format_idr($vendor->remaining_balance) }}</p>
                                <p class="tabular-nums text-[11px] text-taupe">Terbayar {{ $vendor->payment_progress_percent }}%</p>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-taupe">Seluruh kontrak vendor telah lunas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Timeline Phase Health Strip --}}
    <section class="mt-8 editorial-card rounded-lg p-6">
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-editorial text-2xl font-semibold text-espresso">Peta Linimasa Persiapan (H-12 Bulan — Hari-H)</h2>
                <p class="text-xs text-taupe">Ringkasan capaian setiap fase menuju hari pernikahan</p>
            </div>
            <a href="{{ route('timeline.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                Buka Linimasa Lengkap
            </a>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-7">
            @foreach($summary['phase_progress'] as $phase)
                <a
                    href="{{ route('timeline.index', ['phase' => $phase['phase']]) }}"
                    class="group rounded border border-stone-line bg-alabaster p-3.5 transition hover:border-champagne hover:bg-pearl"
                >
                    <div class="flex items-center justify-between">
                        <span class="font-editorial text-lg font-semibold text-espresso group-hover:text-champagne">{{ $phase['phase'] }}</span>
                        <span class="tabular-nums text-xs font-semibold {{ $phase['percent'] === 100 ? 'text-sage' : 'text-taupe' }}">{{ $phase['percent'] }}%</span>
                    </div>
                    <p class="mt-1 text-[11px] text-taupe tabular-nums">{{ $phase['done'] }}/{{ $phase['total'] }} agenda selesai</p>
                    <div class="mt-2.5">
                        <x-ui.progress-bar size="sm" :percent="$phase['percent']" :tone="$phase['percent'] === 100 ? 'sage' : 'champagne'" />
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Nusantara Cultural Module: Prosesi Adat Jawa & Kalkulator Weton Neptu Pasangan --}}
    <section
        class="mt-8 editorial-card rounded-lg p-6"
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
                    1: { nama: 'Pegat / Waspada Komunikasi', ket: 'Disarankan memilih jam akad pagi (08:00–09:30 WIB) dan doa selamatan keluarga.' },
                    2: { nama: 'Ratu (Kemuliaan & Harmoni)', ket: 'Jodoh yang dihormati lingkungan, rukun, dan membawa ketenteraman rumah tangga.' },
                    3: { nama: 'Jodoh (Selaras & Saling Melengkapi)', ket: 'Pasangan yang serasi dan saling menguatkan sejak awal berumah tangga.' },
                    4: { nama: 'Topo (Tangguh & Berkah)', ket: 'Rumah tangga semakin matang, teduh, dan sejahtera seiring perjalanan waktu.' },
                    5: { nama: 'Tinari (Murah Rezeki & Bahagia)', ket: 'Diberkahi kemudahan rezeki dan suasana keluarga yang hangat.' },
                    6: { nama: 'Padu (Dinamis)', ket: 'Perlu musyawarah terbuka dalam pengelolaan anggaran dan keputusan keluarga.' },
                    7: { nama: 'Sujanan (Saling Menjaga)', ket: 'Penting menjaga kepercayaan dan komunikasi antar keluarga besar.' },
                    0: { nama: 'Pesthi (Tentram & Langgeng)', ket: 'Rumah tangga yang rukun, damai, dan harmonis hingga hari tua.' }
                };
                return map[mod] || map[3];
            }
        }"
    >
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-editorial text-2xl font-semibold text-espresso">Tata Upacara Adat Jawa &amp; Perhitungan Weton Neptu</h2>
                <p class="text-xs text-taupe">Panduan kesiapan 4 prosesi sakral Keraton dan perhitungan kecocokan Neptu penanggalan Jawa</p>
            </div>
            <x-ui.status-badge tone="champagne" label="Warisan Budaya Nusantara" />
        </div>

        <div class="mt-5 grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- 4 Tahapan Prosesi Adat Jawa --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:col-span-7">
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-champagne">H-1 Pagi (09:00 WIB)</span>
                        <span class="font-medium text-sage">Siap</span>
                    </div>
                    <h3 class="mt-1 font-editorial text-xl font-semibold text-espresso">1. Prosesi Siraman &amp; Sungkeman</h3>
                    <p class="mt-1 text-xs leading-relaxed text-taupe">
                        Pembersihan diri dengan air 7 sumber mata air (Tirta Perwitasari), potong rikmo, dan permohonan restu kedua orang tua.
                    </p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-champagne">H-1 Malam (19:00 WIB)</span>
                        <span class="font-medium text-sage">Siap</span>
                    </div>
                    <h3 class="mt-1 font-editorial text-xl font-semibold text-espresso">2. Malam Midodareni &amp; Jonggolan</h3>
                    <p class="mt-1 text-xs leading-relaxed text-taupe">
                        Silaturahmi keluarga mempelai pria, penyerahan peningset, dan doa bersama menjelang hari akad nikah.
                    </p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-champagne">Hari-H Pagi (08:30 WIB)</span>
                        <span class="font-medium text-ochre">Terjadwal</span>
                    </div>
                    <h3 class="mt-1 font-editorial text-xl font-semibold text-espresso">3. Ijab Qabul Akad Nikah</h3>
                    <p class="mt-1 text-xs leading-relaxed text-taupe">
                        Prosesi sakral janji suci pernikahan dipimpin penghulu KUA Menteng, penyerahan mahar, dan penandatanganan buku nikah.
                    </p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-champagne">Hari-H Malam (18:30 WIB)</span>
                        <span class="font-medium text-ochre">Terjadwal</span>
                    </div>
                    <h3 class="mt-1 font-editorial text-xl font-semibold text-espresso">4. Upacara Panggih &amp; Resepsi</h3>
                    <p class="mt-1 text-xs leading-relaxed text-taupe">
                        Balangan gantal, wiji dadi, sinduran, bobot timbang, kacar-kucur, dulangan, hingga kirab resepsi puncak.
                    </p>
                </div>
            </div>

            {{-- Kalkulator Weton & Neptu Jawa --}}
            <div class="rounded border border-stone-line bg-alabaster p-5 lg:col-span-5">
                <h3 class="font-editorial text-xl font-semibold text-espresso">Kalkulator Neptu Weton Mempelai</h3>
                <p class="mt-0.5 text-xs text-taupe">Pilih hari lahir dan pasaran Jawa kedua mempelai</p>

                <div class="mt-3 grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="mb-1 block font-semibold text-charcoal">Hari Mempelai Wanita</label>
                        <select x-model="hariWanita" class="editorial-input !py-1.5 !text-xs">
                            <option value="5">Minggu (5)</option>
                            <option value="4">Senin (4)</option>
                            <option value="3">Selasa (3)</option>
                            <option value="7">Rabu (7)</option>
                            <option value="8">Kamis (8)</option>
                            <option value="6">Jumat (6)</option>
                            <option value="9">Sabtu (9)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block font-semibold text-charcoal">Pasaran Wanita</label>
                        <select x-model="pasaranWanita" class="editorial-input !py-1.5 !text-xs">
                            <option value="5">Legi (5)</option>
                            <option value="9">Pahing (9)</option>
                            <option value="7">Pon (7)</option>
                            <option value="4">Wage (4)</option>
                            <option value="8">Kliwon (8)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block font-semibold text-charcoal">Hari Mempelai Pria</label>
                        <select x-model="hariPria" class="editorial-input !py-1.5 !text-xs">
                            <option value="5">Minggu (5)</option>
                            <option value="4">Senin (4)</option>
                            <option value="3">Selasa (3)</option>
                            <option value="7">Rabu (7)</option>
                            <option value="8">Kamis (8)</option>
                            <option value="6">Jumat (6)</option>
                            <option value="9">Sabtu (9)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block font-semibold text-charcoal">Pasaran Pria</label>
                        <select x-model="pasaranPria" class="editorial-input !py-1.5 !text-xs">
                            <option value="5">Legi (5)</option>
                            <option value="9">Pahing (9)</option>
                            <option value="7">Pon (7)</option>
                            <option value="4">Wage (4)</option>
                            <option value="8">Kliwon (8)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 rounded border border-stone-line bg-pearl p-3.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-taupe">Total Neptu Gabungan</span>
                        <span class="font-editorial tabular-nums text-xl font-bold text-champagne" x-text="totalNeptu() + ' Neptu'">26 Neptu</span>
                    </div>
                    <p class="mt-1 font-semibold text-espresso" x-text="maknaWeton().nama"></p>
                    <p class="mt-0.5 text-taupe leading-relaxed" x-text="maknaWeton().ket"></p>
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
                <label class="mb-1 block text-xs font-semibold text-charcoal">Tema &amp; Konsep Estetika</label>
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
                            borderColor: '#78716C',
                            borderWidth: 1,
                            borderRadius: 6,
                        },
                        {
                            label: 'Aktual (Juta Rp)',
                            data: actualData,
                            backgroundColor: '#8C6D46',
                            borderColor: '#181512',
                            borderWidth: 1,
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }
                            }
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
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }
                            },
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
                            '#181512', '#8C6D46', '#2E5A44', '#B45309',
                            '#78716C', '#D4C9B8', '#6E5332', '#991B1B'
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
                            labels: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
