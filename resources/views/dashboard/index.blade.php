@extends('layouts.app', ['title' => 'Dashboard Pengantin'])

@section('content')
<div
    x-data="{
        modalOpen: false,
        guideData: null,
        copiedWa: false,
        openModal(data) {
            this.guideData = data;
            this.copiedWa = false;
            this.modalOpen = true;
        },
        closeModal() {
            this.modalOpen = false;
        },
    }"
    class="space-y-6"
>
    {{-- 1. HEADER RINGKASAN PROFIL & HITUNG MUNDUR HARI-H --}}
    <div class="rounded-2xl border border-[#E7E4DF] bg-white p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-xs font-semibold text-[#8C5835]">Buku Kerja Pernikahan</span>
            <h1 class="text-2xl font-bold tracking-tight text-[#2B2520] sm:text-3xl mt-0.5">
                {{ $profile->bride_name }} &amp; {{ $profile->groom_name }}
            </h1>
            <p class="text-xs text-[#6E665E] mt-1">
                {{ $summary['wedding_date_formatted'] }} · {{ $profile->city }} ({{ number_format($profile->guest_target, 0, ',', '.') }} Tamu Undangan)
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="rounded-xl border border-[#D3E6DA] bg-[#EAF4EE] px-4 py-2.5 text-center">
                <span class="block text-[11px] font-semibold text-[#2E5E3E]">Hitung Mundur</span>
                <span class="text-xl font-bold text-[#2E5E3E] tabular-nums">
                    {{ $summary['days_remaining'] > 0 ? $summary['days_remaining'] . ' Hari Lagi' : 'Hari Pernikahan!' }}
                </span>
            </div>
            <a href="{{ route('dashboard.editProfile') }}" class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3 py-2 text-xs font-semibold text-[#2B2520] hover:bg-[#F5F2EB] transition no-print">
                Ubah
            </a>
        </div>
    </div>

    {{-- 2. KARTU FOKUS UTAMA: LANGKAH PRIORITAS ANDA HARI INI (TUNGGAL & TO-THE-POINT) --}}
    <section class="rounded-2xl border-2 border-[#8C5835]/30 bg-gradient-to-br from-white to-[#FAF8F5] p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-[#8C5835]"></span>
                <span class="font-semibold text-xs text-[#8C5835]">Langkah Prioritas Hari Ini</span>
            </div>
            @if($summary['next_task'])
                <span class="text-xs text-[#6E665E]">
                    Tenggat: <strong class="text-[#9E352B]">{{ $summary['next_task']->due_date->translatedFormat('d M Y') }}</strong>
                </span>
            @endif
        </div>

        @if($summary['next_task'])
            @php
                $nextTask = $summary['next_task'];
                $guide = $summary['next_task_guide'];
            @endphp
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-[#2B2520] leading-snug">
                    {{ $nextTask->title }}
                </h2>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-[#6E665E]">
                    <span class="rounded-md border border-[#E7E4DF] bg-white px-2.5 py-0.5 font-medium text-[#2B2520]">
                        {{ $nextTask->category->label() }}
                    </span>
                    <span>Penanggung Jawab: <strong class="text-[#2B2520] font-semibold">{{ $nextTask->assigned_to }}</strong></span>
                    @if($nextTask->vendor)
                        <span>· Vendor: <strong class="text-[#8C5835] font-semibold">{{ $nextTask->vendor->name }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="pt-3 border-t border-[#F0ECE4] flex flex-wrap items-center gap-3 no-print">
                <form method="POST" action="{{ route('checklist.toggle', $nextTask) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2E5E3E] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#254F34] cursor-pointer shadow-xs">
                        <x-ui.icon name="check" class="h-4 w-4" />
                        <span>Tandai Selesai 1-Klik</span>
                    </button>
                </form>

                <button
                    type="button"
                    @click="openModal({{ Js::from($guide) }})"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-[#E7E4DF] bg-white px-4 py-2 text-xs font-semibold text-[#8C5835] hover:bg-[#F5F2EB] transition cursor-pointer"
                >
                    <x-ui.icon name="lightbulb" class="h-4 w-4 text-[#8C5835]" />
                    <span>Petunjuk &amp; Draf Chat WA</span>
                </button>

                <a href="{{ route('checklist.index') }}" class="ml-auto text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] transition">
                    Lihat Semua Jadwal &rarr;
                </a>
            </div>
        @else
            <div class="py-4 text-center space-y-1">
                <h3 class="text-lg font-bold text-[#2B2520]">Semua Tugas Terdekat Sudah Beres</h3>
                <p class="text-xs text-[#6E665E]">Tidak ada kebutuhan mendesak saat ini. Persiapan berjalan sesuai rencana.</p>
                <div class="pt-2">
                    <a href="{{ route('checklist.index') }}" class="inline-flex items-center gap-1 rounded-lg bg-[#2B2520] px-3.5 py-1.5 text-xs font-semibold text-white">
                        Buka Seluruh Checklist
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- 3. VISUAL UTAMA: 2 METRIK KUNCI (ANGGARAN VS KESIAPAN) TANPA DOBEL DATA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- VISUAL 1: ANGGARAN TERPAKAI VS SISA AMAN (DONUT / STACKED BAR VISUAL) --}}
        <section class="rounded-2xl border border-[#E7E4DF] bg-white p-6 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between border-b border-[#F0ECE4] pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="wallet" class="h-4 w-4 text-[#8C5835]" />
                    <h2 class="text-base font-bold text-[#2B2520]">Kondisi Anggaran</h2>
                </div>
                <span class="rounded-md px-2 py-0.5 text-xs font-semibold {{ $summary['remaining_budget'] >= 0 ? 'bg-[#EAF4EE] text-[#2E5E3E]' : 'bg-[#FDF2F0] text-[#9E352B]' }}">
                    {{ $summary['remaining_budget'] >= 0 ? 'Terkendali' : 'Defisit / Lewat Budget' }}
                </span>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="text-xs text-[#6E665E] block">Batas Total Anggaran:</span>
                    <span class="text-2xl font-bold text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap']) }}</span>
                </div>

                {{-- Visual Progress Bar Anggaran --}}
                <div class="space-y-1.5">
                    <div class="h-4 w-full rounded-full bg-[#FAF8F5] overflow-hidden flex border border-[#E7E4DF]">
                        <div class="bg-[#8C5835] transition-all duration-500" style="width: {{ min(100, $summary['budget_utilization_percent']) }}%"></div>
                        <div class="bg-[#2E5E3E] transition-all duration-500" style="width: {{ max(0, 100 - min(100, $summary['budget_utilization_percent'])) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs pt-1">
                        <div>
                            <span class="flex items-center gap-1.5 text-[#6E665E]">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#8C5835]"></span>
                                Terpakai / Deal:
                            </span>
                            <span class="font-bold text-[#2B2520] tabular-nums block pl-4">{{ format_idr($summary['total_actual']) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="flex items-center justify-end gap-1.5 text-[#2E5E3E]">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#2E5E3E]"></span>
                                Sisa Anggaran Aman:
                            </span>
                            <span class="font-bold text-[#2E5E3E] tabular-nums block pr-1">{{ format_idr($summary['remaining_budget']) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-[#E7E4DF] bg-[#FAF8F5] p-3 text-xs flex justify-between items-center text-[#6E665E]">
                    <span>Total dibayarkan ke vendor saat ini:</span>
                    <strong class="text-[#2B2520] tabular-nums font-semibold">{{ format_idr($summary['total_paid']) }}</strong>
                </div>
            </div>

            <div class="pt-3 border-t border-[#F0ECE4]">
                <a href="{{ route('budget.index') }}" class="inline-flex w-full items-center justify-center rounded-lg border border-[#E7E4DF] bg-white py-2 text-xs font-semibold text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    Kelola Rincian Anggaran &rarr;
                </a>
            </div>
        </section>

        {{-- VISUAL 2: KESIAPAN PERSIAPAN (PROGRESS GAUGE CHECKLIST) --}}
        <section class="rounded-2xl border border-[#E7E4DF] bg-white p-6 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between border-b border-[#F0ECE4] pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="check-circle" class="h-4 w-4 text-[#2E5E3E]" />
                    <h2 class="text-base font-bold text-[#2B2520]">Progres Kesiapan</h2>
                </div>
                <span class="text-xs font-semibold text-[#6E665E] tabular-nums">
                    {{ $summary['completed_tasks'] }} / {{ $summary['total_tasks'] }} Selesai
                </span>
            </div>

            <div class="space-y-4">
                {{-- Circular Gauge & Status Text --}}
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 shrink-0 flex items-center justify-center">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <path stroke="#FAF8F5" stroke-width="3.5" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path stroke="#2E5E3E" stroke-width="3.5" stroke-dasharray="{{ $summary['completion_percent'] }}, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                        <span class="absolute text-sm font-bold text-[#2E5E3E] tabular-nums">{{ $summary['completion_percent'] }}%</span>
                    </div>
                    <div>
                        <span class="text-base font-bold text-[#2B2520] block">Kebutuhan Terpenuhi</span>
                        <p class="text-xs text-[#6E665E] mt-0.5 leading-relaxed">
                            {{ $summary['completed_tasks'] }} tugas rampung, {{ $summary['total_tasks'] - $summary['completed_tasks'] }} dalam proses persiapan.
                        </p>
                    </div>
                </div>

                {{-- Preview 3 Tugas Terdekat --}}
                <div class="space-y-1.5 pt-1">
                    <span class="text-xs font-semibold text-[#6E665E] block">Tugas terdekat:</span>
                    <div class="space-y-1.5 text-xs">
                        @forelse($summary['urgent_tasks']->take(3) as $task)
                            <div class="flex items-center justify-between p-2 rounded-lg bg-[#FAF8F5] border border-[#E7E4DF]">
                                <span class="truncate font-medium text-[#2B2520]">{{ $task->title }}</span>
                                <span class="text-[11px] font-semibold tabular-nums shrink-0 ml-2 {{ $task->is_overdue ? 'text-[#9E352B]' : 'text-[#8C5835]' }}">
                                    {{ $task->due_date->format('d M') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-[#6E665E] py-2 text-center">Seluruh kebutuhan telah terpenuhi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#F0ECE4]">
                <a href="{{ route('checklist.index') }}" class="inline-flex w-full items-center justify-center rounded-lg border border-[#E7E4DF] bg-white py-2 text-xs font-semibold text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    Buka Checklist &amp; Timeline &rarr;
                </a>
            </div>
        </section>

    </div>

    {{-- MODAL ACTION GUIDE (PETUNJUK KOORDINASI) --}}
    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print"
        role="dialog"
        aria-modal="true"
        x-on:keydown.escape.window="closeModal()"
    >
        <div class="fixed inset-0 bg-[#2B2520]/40 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative z-10 w-full max-w-lg rounded-2xl border border-[#E7E4DF] bg-white p-6 shadow-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between gap-3 border-b border-[#F0ECE4] pb-3">
                <h3 class="text-base font-bold text-[#2B2520]" x-text="'Cara Koordinasi: ' + (guideData ? guideData.target_name : '')"></h3>
                <button type="button" @click="closeModal()" class="rounded border border-[#E7E4DF] bg-white p-1 text-sm font-semibold text-[#6E665E] hover:text-[#2B2520] cursor-pointer">✕</button>
            </div>

            <div class="mt-4" x-show="guideData">
                <p class="text-xs font-semibold text-[#8C5835]">Langkah Praktis:</p>
                <ol class="mt-2 space-y-2 text-xs text-[#2B2520]">
                    <template x-for="(step, idx) in (guideData ? guideData.steps : [])" :key="idx">
                        <li class="flex items-start gap-2">
                            <span class="font-bold text-[#8C5835]" x-text="(idx + 1) + '.'"></span>
                            <span x-text="step"></span>
                        </li>
                    </template>
                </ol>

                <div class="mt-5 border-t border-[#F0ECE4] pt-4">
                    <p class="text-xs font-semibold text-[#8C5835]">Draf Pesan WhatsApp:</p>
                    <div class="mt-2 rounded-lg border border-[#E7E4DF] bg-[#FAF8F5] p-3 text-xs text-[#2B2520] whitespace-pre-wrap leading-relaxed font-mono" x-text="guideData ? guideData.wa_text : ''"></div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                        <button
                            type="button"
                            @click="window.copyToClipboard(guideData ? guideData.wa_text : ''); copiedWa = true; setTimeout(() => copiedWa = false, 2000)"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2B2520] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815] cursor-pointer"
                        >
                            <span x-text="copiedWa ? 'Tersalin ke Clipboard!' : 'Salin Teks WhatsApp'">Salin Teks WhatsApp</span>
                        </button>

                        <template x-if="guideData && guideData.wa_url">
                            <a :href="guideData.wa_url" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-lg bg-[#2E5E3E] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#254F34]">
                                Buka WhatsApp &rarr;
                            </a>
                        </template>

                        <button type="button" @click="closeModal()" class="text-xs text-[#6E665E] hover:text-[#2B2520] transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
