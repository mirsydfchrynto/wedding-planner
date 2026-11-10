@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    {{-- 1. HERO: COUPLE, COUNTDOWN, ACTIONS --}}
    <section class="editorial-card batik-kawung-bg relative rounded-lg p-6" data-reveal>
        <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-espresso">
                    {{ $profile->bride_name }} &amp; {{ $profile->groom_name }}
                </h1>
                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-sm text-taupe">
                    <span class="inline-flex items-center gap-1.5 font-medium text-charcoal">
                        <x-ui.icon name="calendar" class="h-3.5 w-3.5 text-champagne" />
                        <span>{{ $summary['wedding_date_formatted'] }} · {{ $profile->wedding_time }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-ui.icon name="building" class="h-3.5 w-3.5 text-champagne" />
                        <span>{{ $profile->venue_name }}, {{ $profile->city }}</span>
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2 no-print">
                    <a href="{{ route('dashboard.editProfile') }}" class="editorial-btn-primary">
                        <x-ui.icon name="sliders" class="h-3.5 w-3.5" />
                        <span>Edit Profil</span>
                    </a>
                    <a href="{{ route('print.masterGuide') }}" target="_blank" class="editorial-btn-secondary">
                        <x-ui.icon name="printer" class="h-3.5 w-3.5" />
                        <span>Cetak Panduan</span>
                    </a>
                </div>
            </div>

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
                        <span>Menuju hari-H</span>
                    </span>
                    <span class="tabular-nums font-bold text-champagne">{{ $summary['readiness_score'] }}% siap</span>
                </div>
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

    {{-- 2. KPI CARDS --}}
    <section class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            icon="wallet"
            label="Plafon Anggaran"
            :value="format_idr($summary['total_budget_cap'])"
            :subtitle="'Sisa: ' . format_idr($summary['remaining_budget'])"
            :badge="$summary['remaining_budget'] >= 0 ? 'Aman' : 'Over'"
            :badgeTone="$summary['remaining_budget'] >= 0 ? 'sage' : 'wine'"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['budget_utilization_percent']" :tone="$summary['remaining_budget'] >= 0 ? 'champagne' : 'wine'" />
        </x-ui.stat-card>

        <x-ui.stat-card
            icon="trending-up"
            label="Deal Vendor"
            :value="format_idr($summary['total_actual'])"
            :subtitle="'Terbayar: ' . format_idr($summary['total_paid'])"
            :badge="$summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Over'"
            :badgeTone="$summary['variance_amount'] >= 0 ? 'sage' : 'wine'"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['budget_paid_percent']" tone="sage" />
        </x-ui.stat-card>

        <x-ui.stat-card
            icon="check-circle"
            label="Checklist"
            :value="$summary['completion_percent'] . '%'"
            :subtitle="$summary['completed_tasks'] . ' dari ' . $summary['total_tasks'] . ' tugas'"
            :badge="$summary['overdue_tasks_count'] > 0 ? $summary['overdue_tasks_count'] . ' Terlambat' : 'Tepat Waktu'"
            :badgeTone="$summary['overdue_tasks_count'] > 0 ? 'wine' : 'sage'"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['completion_percent']" tone="sage" />
        </x-ui.stat-card>

        <x-ui.stat-card
            icon="building"
            label="Vendor"
            :value="$summary['booked_vendors'] . ' / ' . $summary['total_vendors'] . ' Mitra'"
            :subtitle="'Sisa tagihan: ' . format_idr($summary['unpaid_balance'])"
            :badge="format_idr($summary['cost_per_guest']) . '/pax'"
            badgeTone="champagne"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['vendor_booking_percent']" tone="champagne" />
        </x-ui.stat-card>
    </section>

    {{-- 3. BUDGET: 3-LAYER BAR + CATEGORY BARS, TASKS & VENDOR PAYMENTS --}}
    <section class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="editorial-card rounded-lg p-5 lg:col-span-7" data-reveal>
            <div class="flex items-center justify-between border-b border-stone-line pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="wallet" class="h-4 w-4 text-champagne" />
                    <h2 class="text-xl font-bold text-espresso">Anggaran</h2>
                </div>
                <a href="{{ route('budget.index') }}" class="text-xs font-semibold text-champagne hover:underline">Detail</a>
            </div>

            @php
                $cap = max(1, $summary['total_budget_cap']);
                $dealPct = min(100, (int) round($summary['total_actual'] / $cap * 100));
                $paidPct = min(100, (int) round($summary['total_paid'] / $cap * 100));
            @endphp

            <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
                <div>
                    <span class="text-xs text-taupe">Rencana</span>
                    <p class="tabular-nums font-bold text-espresso whitespace-nowrap">{{ format_idr($summary['total_budget_cap']) }}</p>
                </div>
                <div>
                    <span class="text-xs text-taupe">Deal</span>
                    <p class="tabular-nums font-bold text-espresso whitespace-nowrap">{{ format_idr($summary['total_actual']) }}</p>
                </div>
                <div>
                    <span class="text-xs text-taupe">Terbayar</span>
                    <p class="tabular-nums font-bold text-sage whitespace-nowrap">{{ format_idr($summary['total_paid']) }}</p>
                </div>
            </div>

            <div class="mt-3 flex h-2.5 w-full overflow-hidden rounded-full bg-stone-line" role="img" aria-label="Porsi terbayar dan deal terhadap plafon anggaran">
                <div class="h-full bg-sage" style="width: {{ $paidPct }}%"></div>
                <div class="h-full bg-champagne" style="width: {{ max(0, $dealPct - $paidPct) }}%"></div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                @foreach($summary['category_breakdown'] as $cat)
                    @continue($cat['estimated'] === 0 && $cat['actual'] === 0)
                    <div class="rounded border border-stone-line bg-alabaster p-3 text-sm">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 font-semibold text-espresso">
                                <x-ui.icon :name="$cat['case']->icon()" class="h-3.5 w-3.5 text-champagne" />
                                <span>{{ $cat['short_label'] }}</span>
                            </span>
                            <span class="tabular-nums font-semibold text-charcoal whitespace-nowrap">{{ format_idr($cat['actual']) }}</span>
                        </div>
                        <div class="mt-1.5">
                            <x-ui.progress-bar size="sm" :percent="$cat['utilization_percent']" :tone="$cat['is_overbudget'] ? 'wine' : 'champagne'" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

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
                            <span class="tabular-nums font-bold text-wine shrink-0 whitespace-nowrap">{{ format_idr($vendor->remaining_balance) }}</span>
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-taupe">Semua kontrak lunas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- 4. 7-PHASE TIMELINE STRIP --}}
    <section class="mt-6 editorial-card rounded-lg p-5" data-reveal>
        <div class="flex items-center justify-between border-b border-stone-line pb-3">
            <div class="flex items-center gap-2">
                <x-ui.icon name="calendar" class="h-4 w-4 text-champagne" />
                <h2 class="text-xl font-bold text-espresso">Linimasa 7 Fase</h2>
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
@endsection
