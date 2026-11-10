@extends('layouts.app', ['title' => 'Ringkasan'])

@section('content')
    {{-- Kepala: pasangan, tanggal, hitung mundur --}}
    <section class="border-b border-stone-line pb-7">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <div class="min-w-0">
                <h1 class="text-3xl font-bold tracking-tight text-espresso sm:text-4xl">
                    {{ $profile->bride_name }} &amp; {{ $profile->groom_name }}
                </h1>
                <p class="mt-3 text-sm text-taupe">
                    {{ $summary['wedding_date_formatted'] }} · {{ $profile->wedding_time }}
                </p>
                <p class="mt-1 text-sm text-taupe">
                    {{ $profile->venue_name }}, {{ $profile->city }} · {{ number_format($profile->guest_target, 0, ',', '.') }} tamu
                </p>
                <div class="mt-5 flex flex-wrap items-center gap-5 no-print">
                    <a href="{{ route('dashboard.editProfile') }}" class="editorial-btn-primary">
                        <x-ui.icon name="sliders" class="h-3.5 w-3.5" />
                        <span>Ubah profil</span>
                    </a>
                    <a href="{{ route('print.masterGuide') }}" target="_blank" class="text-sm font-semibold text-espresso underline decoration-stone-strong underline-offset-4 hover:decoration-espresso">
                        Cetak panduan A4
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
                class="shrink-0"
            >
                <p class="text-xs font-semibold uppercase tracking-wider text-taupe">Menuju hari-H</p>
                <p class="mt-1 text-5xl font-bold tracking-tight text-espresso tabular-nums">
                    <span x-text="days">{{ $summary['days_remaining'] }}</span><span class="ml-2 text-lg font-semibold text-taupe">hari</span>
                </p>
                <p class="mt-1 text-sm text-taupe tabular-nums">
                    <span x-text="hours">{{ $summary['hours_remaining'] }}</span> jam
                    <span x-text="minutes">0</span> menit lagi
                </p>
                <p class="mt-3 text-sm text-charcoal">
                    {{ $summary['completed_tasks'] }} dari {{ $summary['total_tasks'] }} tugas selesai
                    @if($summary['overdue_tasks_count'] > 0)
                        <span class="text-wine">· {{ $summary['overdue_tasks_count'] }} terlambat</span>
                    @endif
                </p>
            </div>
        </div>
    </section>

    {{-- Angka pokok: anggaran, tugas, vendor --}}
    <section aria-label="Angka pokok" class="grid grid-cols-2 gap-x-8 gap-y-7 border-b border-stone-line py-7 lg:grid-cols-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-taupe">Plafon anggaran</p>
            <p class="mt-2 text-xl font-bold text-espresso tabular-nums">{{ format_idr($summary['total_budget_cap']) }}</p>
            <p class="mt-1 text-xs text-taupe">
                Terpakai {{ format_idr($summary['total_budget_cap'] - $summary['remaining_budget']) }} ·
                {{ $summary['remaining_budget'] >= 0 ? 'sisa ' . format_idr($summary['remaining_budget']) : 'lewat ' . format_idr(abs($summary['remaining_budget'])) }}
            </p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-taupe">Nilai deal vendor</p>
            <p class="mt-2 text-xl font-bold text-espresso tabular-nums">{{ format_idr($summary['total_actual']) }}</p>
            <p class="mt-1 text-xs text-taupe">
                Terbayar {{ format_idr($summary['total_paid']) }} ·
                sisa tagihan {{ format_idr(max(0, $summary['total_actual'] - $summary['total_paid'])) }}
            </p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-taupe">Tugas selesai</p>
            <p class="mt-2 text-xl font-bold text-espresso tabular-nums">{{ $summary['completed_tasks'] }} / {{ $summary['total_tasks'] }}</p>
            <p class="mt-1 text-xs text-taupe">
                {{ $summary['in_progress_tasks'] }} dikerjakan · {{ $summary['todo_tasks'] }} belum mulai
            </p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-taupe">Vendor terkontrak</p>
            <p class="mt-2 text-xl font-bold text-espresso tabular-nums">{{ $summary['booked_vendors'] }} / {{ $summary['total_vendors'] }}</p>
            <p class="mt-1 text-xs text-taupe">
                {{ format_idr($summary['cost_per_guest']) }} per tamu ·
                sisa pelunasan {{ format_idr($summary['unpaid_balance']) }}
            </p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-x-10 gap-y-10 pt-9 lg:grid-cols-12">
        {{-- Anggaran per kategori --}}
        <section class="lg:col-span-7">
            <div class="flex items-baseline justify-between border-b border-stone-line pb-3">
                <h2 class="text-lg font-bold text-espresso">Anggaran per kategori</h2>
                <a href="{{ route('budget.index') }}" class="text-sm font-semibold text-champagne hover:underline">Buku besar</a>
            </div>

            <dl class="divide-y divide-stone-line/70">
                @foreach($summary['category_breakdown'] as $cat)
                    @continue($cat['estimated'] === 0 && $cat['actual'] === 0)
                    <div class="flex items-baseline justify-between gap-4 py-3">
                        <dt class="text-sm text-charcoal">
                            {{ $cat['short_label'] }}
                            @if($cat['is_overbudget'])
                                <span class="text-xs font-semibold text-wine">lewat estimasi</span>
                            @endif
                        </dt>
                        <dd class="shrink-0 text-right">
                            <span class="text-sm font-semibold text-espresso tabular-nums">{{ format_idr($cat['actual']) }}</span>
                            <span class="block text-xs text-taupe tabular-nums">estimasi {{ format_idr($cat['estimated']) }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <div class="space-y-10 lg:col-span-5">
            {{-- Tugas terdekat --}}
            <section>
                <div class="flex items-baseline justify-between border-b border-stone-line pb-3">
                    <h2 class="text-lg font-bold text-espresso">Tugas terdekat</h2>
                    <a href="{{ route('checklist.index') }}" class="text-sm font-semibold text-champagne hover:underline">Semua tugas</a>
                </div>
                <ul class="divide-y divide-stone-line/70">
                    @forelse($summary['urgent_tasks']->take(4) as $task)
                        <li class="flex items-center justify-between gap-3 py-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <form method="POST" action="{{ route('checklist.toggle', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="flex h-4 w-4 shrink-0 items-center justify-center border border-stone-strong text-transparent hover:border-espresso cursor-pointer"
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
                            <span class="shrink-0 text-xs tabular-nums {{ $task->is_overdue ? 'font-semibold text-wine' : 'text-taupe' }}">
                                {{ $task->is_overdue ? 'Terlambat' : $task->due_date->format('d M') }}
                            </span>
                        </li>
                    @empty
                        <li class="py-4 text-sm text-taupe">Seluruh tugas sudah selesai.</li>
                    @endforelse
                </ul>
            </section>

            {{-- Sisa pelunasan vendor --}}
            <section>
                <div class="flex items-baseline justify-between border-b border-stone-line pb-3">
                    <h2 class="text-lg font-bold text-espresso">Sisa pelunasan vendor</h2>
                    <a href="{{ route('vendors.index') }}" class="text-sm font-semibold text-champagne hover:underline">Direktori</a>
                </div>
                <ul class="divide-y divide-stone-line/70">
                    @forelse($summary['upcoming_vendor_payments']->take(4) as $vendor)
                        <li class="flex items-baseline justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-espresso">{{ $vendor->name }}</p>
                                <p class="text-xs text-taupe">
                                    {{ $vendor->category->shortLabel() }} · terbayar {{ format_idr($vendor->paid_amount) }} dari {{ format_idr($vendor->contract_amount) }}
                                </p>
                            </div>
                            <span class="shrink-0 text-sm font-semibold text-wine tabular-nums">{{ format_idr($vendor->remaining_balance) }}</span>
                        </li>
                    @empty
                        <li class="py-4 text-sm text-taupe">Semua kontrak vendor sudah lunas.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>

    {{-- Fase persiapan --}}
    <section class="mt-10">
        <div class="flex items-baseline justify-between border-b border-stone-line pb-3">
            <h2 class="text-lg font-bold text-espresso">Fase persiapan</h2>
            <a href="{{ route('timeline.index') }}" class="text-sm font-semibold text-champagne hover:underline">Linimasa penuh</a>
        </div>
        <ol class="divide-y divide-stone-line/70">
            @foreach($summary['phase_progress'] as $phase)
                <li>
                    <a href="{{ route('timeline.index', ['phase' => $phase['phase']]) }}" class="flex items-baseline justify-between gap-4 py-3 hover:text-champagne">
                        <span class="text-sm font-semibold text-espresso">{{ $phase['phase'] }}</span>
                        <span class="shrink-0 text-sm tabular-nums {{ $phase['done'] === $phase['total'] && $phase['total'] > 0 ? 'text-sage' : 'text-taupe' }}">
                            {{ $phase['done'] }} dari {{ $phase['total'] }} tugas
                        </span>
                    </a>
                </li>
            @endforeach
        </ol>
    </section>
@endsection
