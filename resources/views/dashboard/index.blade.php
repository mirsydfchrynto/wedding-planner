@extends('layouts.app', ['title' => 'Pusat Kontrol Pengantin'])

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
        shareToFamily() {
            window.shareToFamilyWhatsApp({
                couple: '{{ addslashes($profile->bride_name) }} & {{ addslashes($profile->groom_name) }}',
                date: '{{ addslashes($summary['wedding_date_formatted']) }}',
                days: {{ $summary['days_remaining'] }},
                venue: '{{ addslashes($profile->venue_name) }}, {{ addslashes($profile->city) }}',
                budgetCap: '{{ format_idr($summary['total_budget_cap']) }}',
                remaining: '{{ format_idr($summary['remaining_budget']) }}',
                tasksDone: {{ $summary['completed_tasks'] }},
                tasksTotal: {{ $summary['total_tasks'] }},
                vendorsBooked: {{ $summary['booked_vendors'] }}
            });
        }
    }"
    class="space-y-6"
>
    {{-- Header Baris Status Cepat --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b-2 border-ink pb-3">
        <div class="flex items-center gap-2">
            <span class="stamp !bg-kunyit font-extrabold">Pusat Kontrol 5 Detik</span>
            <span class="text-xs font-bold text-taupe">{{ $profile->theme_concept ?: 'Konsep Pernikahan Indonesia' }}</span>
        </div>
        <div class="flex items-center gap-2 no-print">
            <button
                type="button"
                @click="shareToFamily()"
                class="btn-neo-wa !py-1 !px-3 !text-xs cursor-pointer flex items-center gap-1.5"
                title="Kirim pesan ringkasan rapi ke grup WhatsApp keluarga"
            >
                <x-ui.icon name="phone" class="h-3.5 w-3.5" />
                <span>Bagikan Ringkasan ke WA Keluarga</span>
            </button>
            <a href="{{ route('dashboard.editProfile') }}" class="btn-neo-secondary !py-1 !px-3 !text-xs">
                Ubah Profil
            </a>
            <a href="{{ route('print.masterGuide') }}" target="_blank" class="btn-neo-secondary !py-1 !px-3 !text-xs">
                Cetak A4
            </a>
        </div>
    </div>

    {{-- BENTO GRID UTAMA (5 Kompartemen dalam Satu Layar) --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

        {{-- BENTO 1: Hero Fokus Hari Ini (Span 7 Kolom — Kuning Kunyit) --}}
        <div class="bento-card bento-kunyit md:col-span-7 p-5 sm:p-6 flex flex-col justify-between relative overflow-hidden">
            <div class="tape"></div>
            
            <div>
                {{-- Maskot SVG + Speech Bubble Asisten --}}
                <div class="flex items-start gap-3">
                    <div class="shrink-0">
                        {{-- Maskot Kuncup Melati Ramah --}}
                        <svg class="w-11 h-11" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="56" height="56" rx="2" fill="#FAF5EC" stroke="#2B2118" stroke-width="3"/>
                            <path d="M32 14C24 22 18 32 20 44C26 48 38 48 44 44C46 32 40 22 32 14Z" fill="#CDE7D8" stroke="#2B2118" stroke-width="2.5"/>
                            <circle cx="27" cy="34" r="2.5" fill="#2B2118"/>
                            <circle cx="37" cy="34" r="2.5" fill="#2B2118"/>
                            <path d="M29 39C31 41 33 41 35 39" stroke="#2B2118" stroke-width="2" stroke-linecap="round"/>
                            <path d="M32 8L32 14" stroke="#7A4425" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="32" cy="7" r="2.5" fill="#F6D365" stroke="#2B2118" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="speech-bubble flex-1">
                        @if($summary['next_task'])
                            Fokus utama kita hari ini: segera selesaikan <strong>"{{ $summary['next_task']->title }}"</strong>.
                        @else
                            Luar biasa! Semua tugas terdekat sudah beres. Saatnya istirahat &amp; gladi bersih.
                        @endif
                    </div>
                </div>

                {{-- Tugas Prioritas Utama --}}
                @if($summary['next_task'])
                    @php
                        $nextTask = $summary['next_task'];
                        $guide = $summary['next_task_guide'];
                    @endphp
                    <div class="mt-4 pt-3 border-t-2 border-ink">
                        <div class="flex items-center gap-2">
                            <span class="stamp !bg-paper">Prioritas Hari Ini</span>
                            <span class="text-xs font-bold text-ink">
                                Tenggat: <span class="text-wine underline underline-offset-2">{{ $nextTask->due_date->translatedFormat('l, d F Y') }}</span>
                            </span>
                        </div>
                        <h2 class="mt-2 font-display text-2xl font-extrabold text-ink leading-tight sm:text-3xl">
                            {{ $nextTask->title }}
                        </h2>
                        <p class="text-xs font-semibold text-taupe mt-1">
                            Kategori: {{ $nextTask->category->label() }} · PIC: <strong class="text-ink">{{ $nextTask->assigned_to }}</strong>
                            @if($nextTask->vendor)
                                · Vendor: <strong class="text-soga">{{ $nextTask->vendor->name }}</strong>
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            {{-- Tombol Aksi 1-Klik Taktil --}}
            <div class="mt-5 pt-3 border-t-2 border-ink flex flex-wrap items-center gap-2.5 no-print">
                @if($summary['next_task'])
                    <form method="POST" action="{{ route('checklist.toggle', $summary['next_task']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-neo !bg-forest hover:!bg-[#2E5A36] !text-paper !py-1.5 !px-3.5 !text-xs cursor-pointer flex items-center gap-1.5">
                            <x-ui.icon name="check" class="h-3.5 w-3.5" />
                            <span>Tandai Selesai 1-Klik</span>
                        </button>
                    </form>
                    <button
                        type="button"
                        @click="openModal({{ Js::from($summary['next_task_guide']) }})"
                        class="btn-neo-secondary !py-1.5 !px-3 !text-xs cursor-pointer flex items-center gap-1.5"
                    >
                        <x-ui.icon name="lightbulb" class="h-3.5 w-3.5 text-soga" />
                        <span>Cara Urus &amp; WA</span>
                    </button>
                @endif
                <a href="{{ route('checklist.index') }}" class="btn-neo-secondary !py-1.5 !px-3 !text-xs ml-auto">
                    Semua Tugas &rarr;
                </a>
            </div>
        </div>

        {{-- BENTO 2: Countdown & Pasangan (Span 5 Kolom — Kertas Krem `#FAF5EC`) --}}
        <div class="bento-card bento-paper md:col-span-5 p-5 sm:p-6 flex flex-col justify-between text-center relative">
            <div>
                <span class="stamp !bg-kunyit">Hari Bahagia</span>
                <h2 class="mt-2 font-display text-3xl font-extrabold text-ink sm:text-4xl">
                    {{ $profile->bride_name }} &amp; {{ $profile->groom_name }}
                </h2>
                <p class="text-xs font-semibold text-taupe mt-1">
                    {{ $summary['wedding_date_formatted'] }} · {{ $profile->wedding_time }}
                </p>
                <p class="text-xs text-taupe mt-0.5">
                    {{ $profile->venue_name }}, {{ $profile->city }} ({{ number_format($profile->guest_target, 0, ',', '.') }} Tamu)
                </p>

                {{-- Countdown Raksasa --}}
                <div class="mt-4 bento-card bento-kunyit p-3 border-2 border-ink inline-block min-w-[200px]">
                    <span class="text-[0.68rem] font-extrabold uppercase tracking-widest text-ink block">Hitung Mundur Hari</span>
                    <span class="hand text-5xl sm:text-6xl font-black text-ink block leading-none mt-1">
                        H-{{ $summary['days_remaining'] }}
                    </span>
                    <span class="text-[0.72rem] font-bold text-taupe block mt-1">
                        {{ $summary['hours_remaining'] }} jam lagi menuju akad nikah
                    </span>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t-2 border-ink">
                <button
                    type="button"
                    @click="shareToFamily()"
                    class="btn-neo-wa w-full !py-2 !text-xs font-extrabold cursor-pointer flex items-center justify-center gap-2"
                >
                    <x-ui.icon name="phone" class="h-3.5 w-3.5" />
                    <span>Bagikan Ringkasan ke WA Keluarga</span>
                </button>
            </div>
        </div>

        {{-- BENTO 3: Radar Anggaran & Kas (Span 4 Kolom — Hijau Mint `#CDE7D8`) --}}
        <div class="bento-card bento-mint md:col-span-4 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                    <span class="font-display text-lg font-extrabold text-ink">Buku Kas &amp; Pagu</span>
                    <span class="stamp !bg-paper {{ $summary['remaining_budget'] >= 0 ? '!border-forest !text-forest' : '!border-wine !text-wine' }}">
                        {{ $summary['remaining_budget'] >= 0 ? 'Aman / Hemat' : 'Lewat Batas' }}
                    </span>
                </div>

                <div class="mt-3 space-y-2.5">
                    <div>
                        <span class="text-[0.68rem] font-extrabold uppercase tracking-wider text-taupe block">Pagu Anggaran Induk</span>
                        <span class="rupiah text-xl font-black text-ink block">{{ format_idr($summary['total_budget_cap']) }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-ink/40 text-xs">
                        <div>
                            <span class="text-[0.65rem] font-bold text-taupe block">Komitmen Vendor</span>
                            <span class="rupiah font-extrabold text-ink">{{ format_idr($summary['total_actual']) }}</span>
                        </div>
                        <div>
                            <span class="text-[0.65rem] font-bold text-taupe block">Sisa Dana Aman</span>
                            <span class="rupiah font-extrabold {{ $summary['remaining_budget'] >= 0 ? 'text-forest' : 'text-wine' }}">
                                {{ format_idr($summary['remaining_budget']) }}
                            </span>
                        </div>
                    </div>

                    {{-- Visual Progress Bar Kas --}}
                    <div class="pt-1">
                        <div class="h-3 w-full bg-paper border-2 border-ink">
                            <div class="h-full bg-soga" style="width: {{ $summary['budget_utilization_percent'] }}%"></div>
                        </div>
                        <span class="text-[0.68rem] font-bold text-taupe mt-1 block">
                            {{ $summary['budget_utilization_percent'] }}% pagu dialokasikan ({{ format_idr($summary['total_paid']) }} terbayar)
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-2 border-t-2 border-ink">
                <a href="{{ route('budget.index') }}" class="btn-neo-secondary w-full !py-1.5 !text-xs text-center font-bold">
                    Buka Buku Kas &rarr;
                </a>
            </div>
        </div>

        {{-- BENTO 4: Progres Checklist Kebutuhan (Span 4 Kolom — Kertas Krem `#FAF5EC`) --}}
        <div class="bento-card bento-paper md:col-span-4 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                    <h2 class="font-display text-lg font-extrabold text-ink">Tugas terdekat</h2>
                    <span class="stamp !bg-kunyit font-black">{{ $summary['completed_tasks'] }}/{{ $summary['total_tasks'] }}</span>
                </div>

                <div class="mt-2.5">
                    <p class="text-xs text-taupe font-semibold">
                        {{ $summary['completion_percent'] }}% kebutuhan persiapan selesai
                    </p>
                    <div class="h-2.5 w-full bg-paper-2 border-2 border-ink mt-1">
                        <div class="h-full bg-forest" style="width: {{ $summary['completion_percent'] }}%"></div>
                    </div>
                </div>

                {{-- 3 Item Kebutuhan Terdekat dengan Centang 1-Klik Instan --}}
                <ul class="mt-3 divide-y divide-ink/30 text-xs">
                    @forelse($summary['urgent_tasks']->take(3) as $task)
                        <li class="py-2 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <form method="POST" action="{{ route('checklist.toggle', $task) }}" class="shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="h-4 w-4 border-2 border-ink bg-paper flex items-center justify-center hover:bg-kunyit cursor-pointer text-transparent hover:text-ink font-bold"
                                        title="Centang selesai 1-klik"
                                    >
                                        <x-ui.icon name="check" class="h-3 w-3" />
                                    </button>
                                </form>
                                <span class="truncate font-bold text-ink">{{ $task->title }}</span>
                            </div>
                            <span class="rupiah text-[0.68rem] font-bold shrink-0 {{ $task->is_overdue ? 'text-wine' : 'text-taupe' }}">
                                {{ $task->is_overdue ? 'Terlambat' : $task->due_date->format('d M') }}
                            </span>
                        </li>
                    @empty
                        <li class="py-3 text-center text-taupe text-xs">Semua tugas beres.</li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-4 pt-2 border-t-2 border-ink">
                <a href="{{ route('checklist.index') }}" class="btn-neo-secondary w-full !py-1.5 !text-xs text-center font-bold">
                    Kelola Semua Checklist &rarr;
                </a>
            </div>
        </div>

        {{-- BENTO 5: Status Vendor & Tagihan Kritis (Span 4 Kolom — Terakota Muda `#F7C5B8`) --}}
        <div class="bento-card bento-terracotta md:col-span-4 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                    <span class="font-display text-lg font-extrabold text-ink">Mitra Vendor</span>
                    <span class="stamp !bg-paper">{{ $summary['booked_vendors'] }}/{{ $summary['total_vendors'] }} Terkontrak</span>
                </div>

                <div class="mt-3 space-y-2 text-xs">
                    <div class="flex items-baseline justify-between">
                        <span class="font-bold text-taupe">Nilai Kontrak Total:</span>
                        <span class="rupiah font-extrabold text-ink">{{ format_idr($summary['total_actual']) }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="font-bold text-taupe">Sisa Tagihan Belum Lunas:</span>
                        <span class="rupiah font-black text-wine">{{ format_idr($summary['unpaid_balance']) }}</span>
                    </div>

                    {{-- 1 Vendor Paling Mendekati Jatuh Tempo --}}
                    @if($summary['upcoming_vendor_payments']->isNotEmpty())
                        @php $urgentVendor = $summary['upcoming_vendor_payments']->first(); @endphp
                        <div class="mt-2 bento-card bento-paper p-2.5 border-2 border-ink">
                            <span class="text-[0.65rem] font-bold uppercase text-wine block">Tenggat Terdekat:</span>
                            <p class="font-bold text-ink truncate text-xs">{{ $urgentVendor->name }}</p>
                            <div class="flex items-center justify-between text-[0.7rem] mt-0.5">
                                <span class="rupiah text-wine font-extrabold">Sisa {{ format_idr($urgentVendor->remaining_balance) }}</span>
                                @if($urgentVendor->phone)
                                    <a href="{{ $urgentVendor->whatsapp_url }}" target="_blank" rel="noopener" class="text-forest font-bold hover:underline">
                                        Chat WA &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 pt-2 border-t-2 border-ink">
                <a href="{{ route('vendors.index') }}" class="btn-neo-secondary w-full !py-1.5 !text-xs text-center font-bold">
                    Buka Direktori Vendor &rarr;
                </a>
            </div>
        </div>

    </div>

    {{-- BAGIAN BAWAH: Buku Kas Kategori & Tahapan Persiapan --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Kiri: Tabel Ledger Kas Kategori --}}
        <section class="lg:col-span-7 bento-card p-5">
            <div class="flex items-baseline justify-between border-b-2 border-ink pb-2">
                <div>
                    <h2 class="font-display text-xl font-bold text-espresso">Anggaran per kategori</h2>
                    <p class="text-xs text-taupe">Rincian estimasi acuan pasar vs nilai kontrak nyata.</p>
                </div>
                <a href="{{ route('budget.index') }}" class="text-xs font-bold text-soga hover:underline">
                    Buku Besar Lengkap &rarr;
                </a>
            </div>

            <div class="mt-3 overflow-x-auto">
                <table class="ledger">
                    <thead>
                        <tr>
                            <th scope="col">Kategori</th>
                            <th scope="col" class="text-right">Estimasi</th>
                            <th scope="col" class="text-right">Deal Vendor</th>
                            <th scope="col" class="text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['category_breakdown'] as $cat)
                            @continue($cat['estimated'] === 0 && $cat['actual'] === 0)
                            <tr>
                                <td class="font-bold text-ink">{{ $cat['label'] }}</td>
                                <td class="rupiah text-right text-taupe">{{ format_idr($cat['estimated']) }}</td>
                                <td class="rupiah text-right font-black text-ink">{{ format_idr($cat['actual']) }}</td>
                                <td class="text-right">
                                    @if($cat['is_overbudget'])
                                        <span class="stamp !bg-terracotta-light !border-wine !text-wine !py-0 !text-[0.6rem]">Lewat</span>
                                    @else
                                        <span class="stamp !bg-mint-light !border-forest !text-forest !py-0 !text-[0.6rem]">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Kanan: 5 Tahapan Persiapan --}}
        <section class="lg:col-span-5 bento-card p-5">
            <div class="flex items-baseline justify-between border-b-2 border-ink pb-2">
                <div>
                    <h2 class="font-display text-xl font-bold text-espresso">Tahapan Menuju Hari H</h2>
                    <p class="text-xs text-taupe">Alur persiapan kronologis.</p>
                </div>
                <a href="{{ route('rundown.index') }}" class="text-xs font-bold text-soga hover:underline">
                    Rundown &rarr;
                </a>
            </div>

            <div class="mt-3 space-y-2.5">
                @foreach($summary['phase_progress'] as $idx => $phase)
                    <a
                        href="{{ route('rundown.index', ['phase' => $phase['phase']]) }}"
                        class="bento-card block p-2.5 hover:bg-paper-2 transition text-xs"
                    >
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-ink">Fase {{ $idx + 1 }}: {{ $phase['phase'] }}</span>
                            <span class="font-bold {{ $phase['done'] === $phase['total'] && $phase['total'] > 0 ? 'text-forest' : 'text-taupe' }}">
                                {{ $phase['done'] }}/{{ $phase['total'] }} Selesai
                            </span>
                        </div>
                        <div class="mt-1.5 h-2 w-full bg-paper-2 border border-ink">
                            <div class="h-full bg-soga" style="width: {{ $phase['percent'] }}%"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Modal Dialog Kertas: Cara Urus & Salin WhatsApp --}}
    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print"
        role="dialog"
        aria-modal="true"
        x-on:keydown.escape.window="closeModal()"
    >
        <div class="fixed inset-0 bg-ink/50" @click="closeModal()"></div>

        <div class="modal-paper relative z-10 w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between gap-3 border-b-2 border-ink pb-3">
                <div>
                    <span class="stamp !bg-kunyit">Panduan Asisten</span>
                    <h2 class="font-display text-xl font-extrabold text-ink mt-1" x-text="'Cara Urus: ' + (guideData ? guideData.target_name : '')"></h2>
                </div>
                <button type="button" @click="closeModal()" class="btn-neo-secondary !min-h-0 !p-1 text-sm font-bold cursor-pointer">✕</button>
            </div>

            <div class="mt-4" x-show="guideData">
                <p class="text-xs font-black uppercase tracking-wider text-soga">3 Langkah Eksekusi yang Disarankan:</p>
                <ol class="mt-2 space-y-2 text-xs sm:text-sm text-ink">
                    <template x-for="(step, idx) in (guideData ? guideData.steps : [])" :key="idx">
                        <li class="flex items-start gap-2">
                            <span class="hand font-bold text-soga" x-text="(idx + 1) + '.'"></span>
                            <span x-text="step"></span>
                        </li>
                    </template>
                </ol>

                <div class="mt-5 border-t-2 border-ink pt-3">
                    <p class="text-xs font-black uppercase tracking-wider text-soga">Draf Pesan WhatsApp Siap Kirim:</p>
                    <div class="bento-card bento-paper2 mt-2 p-3 font-mono text-[0.72rem] text-ink whitespace-pre-wrap leading-relaxed border-2 border-ink" x-text="guideData ? guideData.wa_text : ''"></div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                        <button
                            type="button"
                            @click="window.copyToClipboard(guideData ? guideData.wa_text : ''); copiedWa = true; setTimeout(() => copiedWa = false, 2200)"
                            class="btn-neo !py-1.5 !px-3 !text-xs cursor-pointer"
                        >
                            <span x-text="copiedWa ? 'Teks WA Berhasil Disalin!' : 'Salin Draf WhatsApp'">Salin Draf WhatsApp</span>
                        </button>

                        <template x-if="guideData && guideData.wa_url">
                            <a :href="guideData.wa_url" target="_blank" rel="noopener" class="btn-neo-wa !py-1.5 !px-3 !text-xs">
                                Buka Aplikasi WhatsApp &rarr;
                            </a>
                        </template>

                        <button type="button" @click="closeModal()" class="text-xs text-taupe hover:text-ink font-bold underline">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
