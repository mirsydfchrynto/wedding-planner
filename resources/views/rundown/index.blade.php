@extends('layouts.app', ['title' => 'Susunan Acara & Rundown'])

@section('content')
<div x-data="{ currentTab: '{{ $activeTab }}' }" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 border-b-2 border-ink pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-soga">
                <span class="stamp !bg-kunyit font-black">Pita Kalender Waktu</span>
                <span>Alur Persiapan &amp; Prosesi Hari H</span>
            </div>
            <h1 class="mt-1 font-display text-2xl font-black tracking-tight text-espresso sm:text-4xl">
                Susunan Acara &amp; Rundown
            </h1>
            <p class="text-xs text-taupe font-semibold sm:text-sm">
                Dua lembar kerja: Peta Waktu Kronologis sebelum acara, dan jadwal menit demi menit di hari pernikahan.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 no-print">
            <a
                href="{{ route('rundown.create') }}"
                x-show="currentTab === 'pra'"
                class="btn-neo !py-1.5 !px-3.5 !text-xs font-black"
            >
                + Agenda Persiapan
            </a>
            <a
                href="{{ route('rundown.sessions.create') }}"
                x-show="currentTab === 'hari'"
                x-cloak
                class="btn-neo !py-1.5 !px-3.5 !text-xs font-black"
            >
                + Tambah Sesi Acara
            </a>
            <a href="{{ route('print.masterGuide') }}" target="_blank" class="btn-neo-secondary !py-1.5 !px-3.5 !text-xs font-bold">
                Cetak Panduan A4
            </a>
        </div>
    </div>

    {{-- Tab Pilihan Folder --}}
    <div class="flex items-baseline gap-2 border-b-2 border-ink pt-2">
        <button
            type="button"
            @click="currentTab = 'pra'"
            class="index-tab cursor-pointer"
            :class="currentTab === 'pra' ? 'index-tab-active font-black text-ink' : 'text-taupe'"
        >
            <span>Persiapan</span>
            <span class="text-[0.7rem] tabular-nums font-mono font-bold">({{ $summary['total_tasks'] }})</span>
        </button>
        <button
            type="button"
            @click="currentTab = 'hari'"
            class="index-tab cursor-pointer"
            :class="currentTab === 'hari' ? 'index-tab-active font-black text-ink' : 'text-taupe'"
        >
            <span>Hari pernikahan (Rundown)</span>
            <span class="text-[0.7rem] tabular-nums font-mono font-bold">({{ $rundowns->count() }})</span>
        </button>
    </div>

    {{-- Lembar 1: Agenda Persiapan Kronologis (Pita Kalender 4 Blok Waktu) --}}
    <div x-show="currentTab === 'pra'" class="space-y-6 pt-2">

        {{-- 4 Blok Pita Waktu Kronologis --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 text-xs text-center font-bold">
            <div class="bento-card bento-paper2 p-2">
                <span class="text-[0.65rem] text-taupe uppercase block">Blok 1 (6–3 Bulan)</span>
                <span class="text-ink">Dokumen, Venue, Katering</span>
            </div>
            <div class="bento-card bento-kunyit p-2">
                <span class="text-[0.65rem] text-taupe uppercase block">Blok 2 (2–1 Bulan)</span>
                <span class="text-ink">Busana, Undangan, Pelunasan</span>
            </div>
            <div class="bento-card bento-terracotta p-2">
                <span class="text-[0.65rem] text-taupe uppercase block">Blok 3 (H-7 s/d H-1)</span>
                <span class="text-ink">Technical Meeting, Gladi</span>
            </div>
            <div class="bento-card bento-mint p-2">
                <span class="text-[0.65rem] text-taupe uppercase block">Blok 4 (Hari H)</span>
                <span class="text-ink">Akad, Panggih &amp; Resepsi</span>
            </div>
        </div>

        {{-- Filter Ringkas --}}
        <div class="bento-card bento-paper p-3 flex flex-col justify-between gap-3 lg:flex-row lg:items-center no-print">
            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                <a
                    href="{{ route('rundown.index', ['milestone_only' => $milestoneOnly ? 1 : null, 'search' => $searchQuery ?: null, 'sort' => $selectedSort]) }}"
                    class="chip {{ !$selectedPhase ? 'chip-active' : '' }}"
                >
                    Semua Fase
                </a>
                @foreach($allPhases as $phase)
                    @php $activePhase = $selectedPhase === $phase->value; @endphp
                    <a
                        href="{{ route('rundown.index', ['phase' => $phase->value, 'milestone_only' => $milestoneOnly ? 1 : null, 'search' => $searchQuery ?: null, 'sort' => $selectedSort]) }}"
                        class="chip {{ $activePhase ? 'chip-active' : '' }}"
                    >
                        {{ $phase->value }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('rundown.index') }}" class="flex items-center gap-2 text-xs">
                @if($selectedPhase)
                    <input type="hidden" name="phase" value="{{ $selectedPhase }}">
                @endif
                <input
                    type="search"
                    name="search"
                    value="{{ $searchQuery }}"
                    placeholder="Cari agenda atau vendor..."
                    class="editorial-input !w-44 !py-1 !text-xs font-bold"
                >
                <button type="submit" class="btn-neo-secondary !min-h-0 !py-1 !px-2.5 !text-xs font-bold">Cari</button>
                @if($selectedPhase || $searchQuery !== '')
                    <a href="{{ route('rundown.index') }}" class="text-xs text-soga hover:underline ml-1 font-bold">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tampilan Garis Pita Vertikal Berfase --}}
        <div class="space-y-6 pt-2">
            @foreach($phases as $idx => $phaseGroup)
                @if(!$selectedPhase || $selectedPhase === $phaseGroup['name'])
                    <section
                        x-data="{ phaseOpen: {{ $idx === 0 || $selectedPhase ? 'true' : 'false' }} }"
                        class="bento-card bento-paper p-4 relative"
                    >
                        <div class="flex items-center justify-between border-b-2 border-ink pb-2.5 cursor-pointer" @click="phaseOpen = !phaseOpen">
                            <div class="flex items-baseline gap-2.5">
                                <span class="stamp !bg-kunyit font-black">#{{ $idx + 1 }}</span>
                                <div>
                                    <h2 class="font-display text-lg font-black text-ink">{{ $phaseGroup['name'] }}</h2>
                                    <p class="text-[0.72rem] text-taupe font-semibold">{{ $phaseGroup['subtitle'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold {{ $phaseGroup['done'] === $phaseGroup['total'] && $phaseGroup['total'] > 0 ? 'text-forest' : 'text-taupe' }}">
                                    {{ $phaseGroup['done'] }} / {{ $phaseGroup['total'] }} tugas rampung
                                </span>
                                <span class="btn-neo-secondary !min-h-0 !p-1 text-xs" x-text="phaseOpen ? '▲' : '▼'">▼</span>
                            </div>
                        </div>

                        <div x-show="phaseOpen" class="mt-3 divide-y-2 divide-ink/20">
                            @forelse($phaseGroup['tasks'] as $task)
                                <div class="flex flex-col justify-between gap-2 py-2.5 sm:flex-row sm:items-center">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="rupiah text-[0.72rem] font-black text-soga">{{ $task->due_date->translatedFormat('d M Y') }}</span>
                                            <span class="text-taupe text-[0.7rem] font-semibold">· {{ $task->category->shortLabel() }}</span>

                                            @if($task->is_milestone)
                                                <span class="stamp !bg-mint !border-forest !text-forest !text-[0.6rem] !py-0 !px-1 font-bold">Tonggak</span>
                                            @endif

                                            {{-- Stempel Deadline Mencolok --}}
                                            @if($task->is_overdue)
                                                <span class="stamp !bg-terracotta-light !border-wine !text-wine !text-[0.6rem] !py-0 !px-1.5 font-black stamp-slam">
                                                    [Terlambat]
                                                </span>
                                            @elseif($task->status->value !== 'done' && $task->due_date->diffInDays(now()) <= 7)
                                                <span class="stamp !bg-kunyit !border-ink !text-ink !text-[0.6rem] !py-0 !px-1.5 font-extrabold">
                                                    [Mendekati Deadline]
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-xs sm:text-sm font-extrabold text-ink mt-0.5">{{ $task->title }}</h3>
                                        <p class="text-[0.7rem] text-taupe font-semibold">
                                            PIC: <strong class="text-ink">{{ $task->assigned_to }}</strong>
                                            @if($task->vendor)
                                                · Vendor: <strong class="text-soga">{{ $task->vendor->name }}</strong>
                                            @endif
                                        </p>
                                    </div>

                                    <form method="POST" action="{{ route('rundown.updateStatus', $task) }}" class="shrink-0 no-print">
                                        @csrf
                                        @method('PATCH')
                                        <select
                                            name="status"
                                            onchange="this.form.submit()"
                                            class="editorial-input !w-auto !py-1 !text-[0.72rem] font-bold"
                                        >
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->value }}" @selected($task->status === $status)>
                                                    {{ $status->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            @empty
                                <p class="py-3 text-xs text-taupe text-center font-semibold">Belum ada agenda pada tahapan ini.</p>
                            @endforelse
                        </div>
                    </section>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Lembar 2: Jadwal Menit demi Menit Hari Pernikahan --}}
    <div x-show="currentTab === 'hari'" x-cloak class="space-y-4 pt-2">
        <div class="bento-card bento-paper p-4 flex items-center justify-between">
            <div>
                <h2 class="font-display text-xl font-black text-ink">Jadwal Menit demi Menit Hari H</h2>
                <p class="text-xs text-taupe font-semibold">Urutan susunan acara resmi untuk panitia keluarga, MC, WO, dan vendor lapangan.</p>
            </div>
            <a href="{{ route('rundown.sessions.create') }}" class="btn-neo !py-1.5 !px-3 !text-xs font-black no-print">
                + Tambah Sesi
            </a>
        </div>

        {{-- Desktop: Tabel Ledger --}}
        <div class="hidden sm:block bento-card bento-paper p-4 overflow-x-auto">
            <table class="ledger">
                <thead>
                    <tr>
                        <th scope="col" class="w-32">Waktu (WIB)</th>
                        <th scope="col" class="w-48">Sesi Acara</th>
                        <th scope="col">Rincian Aktivitas</th>
                        <th scope="col" class="w-40">Lokasi</th>
                        <th scope="col" class="w-48">Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rundowns as $rundown)
                        <tr>
                            <td class="rupiah font-black text-soga whitespace-nowrap">
                                {{ $rundown->start_time }} – {{ $rundown->end_time }}
                            </td>
                            <td class="font-extrabold text-ink font-display text-sm">{{ $rundown->session_name }}</td>
                            <td class="text-ink">
                                <p class="font-semibold">{{ $rundown->activity }}</p>
                                @if($rundown->notes)
                                    <p class="mt-0.5 text-[0.7rem] text-taupe font-mono">{{ $rundown->notes }}</p>
                                @endif
                            </td>
                            <td class="text-taupe text-xs font-semibold">{{ $rundown->location ?? '—' }}</td>
                            <td class="text-ink" x-data="{ copiedPic: false }">
                                <p class="font-bold text-xs">{{ $rundown->pic_name ?? 'Tim WO' }}</p>
                                @if($rundown->pic_phone)
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $rundown->pic_phone) }}" target="_blank" rel="noopener" class="rupiah text-[0.7rem] font-bold text-forest hover:underline">
                                            {{ $rundown->pic_phone }}
                                        </a>
                                        <button
                                            type="button"
                                            @click="window.copyToClipboard('{{ $rundown->pic_phone }}'); copiedPic = true; setTimeout(() => copiedPic = false, 1800)"
                                            class="text-[0.65rem] text-taupe hover:text-ink cursor-pointer underline no-print font-bold"
                                        >
                                            <span x-text="copiedPic ? 'Tersalin' : 'Salin'">Salin</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-xs text-taupe font-bold">Belum ada sesi acara tercatat. Klik tombol "+ Tambah Sesi" di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: Kartu Waktu Bertumpuk --}}
        <div class="sm:hidden space-y-3">
            @forelse($rundowns as $rundown)
                <div class="bento-card bento-paper p-3.5 space-y-2" x-data="{ copiedMobile: false }">
                    <div class="flex items-baseline justify-between border-b-2 border-ink pb-1.5">
                        <span class="rupiah font-black text-soga text-sm">{{ $rundown->start_time }} – {{ $rundown->end_time }}</span>
                        <span class="text-[0.7rem] text-taupe font-bold">{{ $rundown->location ?? 'Venue' }}</span>
                    </div>
                    <div>
                        <h3 class="font-display text-base font-extrabold text-ink">{{ $rundown->session_name }}</h3>
                        <p class="text-xs text-ink mt-0.5 font-semibold">{{ $rundown->activity }}</p>
                        @if($rundown->notes)
                            <p class="mt-1 text-[0.7rem] text-taupe font-mono">{{ $rundown->notes }}</p>
                        @endif
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t-2 border-ink text-[0.72rem] text-taupe">
                        <span>PIC: <strong class="text-ink">{{ $rundown->pic_name ?? 'Tim WO' }}</strong></span>
                        @if($rundown->pic_phone)
                            <button
                                type="button"
                                @click="window.copyToClipboard('{{ $rundown->pic_phone }}'); copiedMobile = true; setTimeout(() => copiedMobile = false, 1800)"
                                class="text-forest font-bold hover:underline cursor-pointer"
                            >
                                <span x-text="copiedMobile ? 'Nomor Tersalin' : 'Salin Kontak'">Salin Kontak</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-xs text-taupe font-bold">Belum ada sesi acara hari H.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
