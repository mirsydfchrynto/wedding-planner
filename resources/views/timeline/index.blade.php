@extends('layouts.app', ['title' => 'Linimasa'])

@section('content')
<div x-data="{ currentTab: '{{ $activeTab }}' }">
    <x-ui.section-header title="Linimasa & Rundown">
        <x-slot:actions>
            <a
                href="{{ route('timeline.create') }}"
                x-show="currentTab === 'timeline'"
                class="editorial-btn-primary"
            >
                + Agenda
            </a>
            <a
                href="{{ route('timeline.rundowns.create') }}"
                x-show="currentTab === 'rundown'"
                x-cloak
                class="editorial-btn-primary"
            >
                + Sesi rundown
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Tab sebagai teks bergaris bawah --}}
    <div class="flex items-baseline gap-6 border-b border-stone-line pt-5">
        <button
            type="button"
            @click="currentTab = 'timeline'"
            :class="currentTab === 'timeline' ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso'"
            class="pb-2.5 text-sm font-semibold cursor-pointer"
        >
            Fase persiapan
        </button>
        <button
            type="button"
            @click="currentTab = 'rundown'"
            :class="currentTab === 'rundown' ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso'"
            class="pb-2.5 text-sm font-semibold cursor-pointer"
        >
            Rundown hari-H ({{ $rundowns->count() }})
        </button>
    </div>

    <div x-show="currentTab === 'timeline'">
        {{-- Filter fase --}}
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line py-4 lg:flex-row lg:items-center">
            <div class="flex flex-wrap items-baseline gap-x-5 gap-y-2">
                <a
                    href="{{ route('timeline.index', ['milestone_only' => $milestoneOnly ? 1 : null, 'search' => $searchQuery ?: null, 'sort' => $selectedSort]) }}"
                    class="text-sm font-semibold {{ !$selectedPhase ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso' }}"
                >
                    Semua fase
                </a>
                @foreach($allPhases as $phase)
                    @php $activePhase = $selectedPhase === $phase->value; @endphp
                    <a
                        href="{{ route('timeline.index', ['phase' => $phase->value, 'milestone_only' => $milestoneOnly ? 1 : null, 'search' => $searchQuery ?: null, 'sort' => $selectedSort]) }}"
                        class="text-sm font-medium {{ $activePhase ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso' }}"
                    >
                        {{ $phase->value }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('timeline.index') }}" class="flex flex-wrap items-center gap-2 no-print">
                @if($selectedPhase)
                    <input type="hidden" name="phase" value="{{ $selectedPhase }}">
                @endif
                @if($milestoneOnly)
                    <input type="hidden" name="milestone_only" value="1">
                @endif

                <input
                    type="search"
                    name="search"
                    value="{{ $searchQuery ?? '' }}"
                    placeholder="Cari agenda, PIC, atau vendor..."
                    aria-label="Cari agenda atau milestone"
                    class="editorial-input !w-full sm:!w-52 !py-1.5 !text-xs"
                >

                <select name="sort" aria-label="Urutkan agenda" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="" @selected(!$selectedSort || $selectedSort === 'due_date_asc')>Tenggat terdekat</option>
                    <option value="priority_desc" @selected($selectedSort === 'priority_desc')>Prioritas tertinggi</option>
                    <option value="due_date_desc" @selected($selectedSort === 'due_date_desc')>Tenggat terjauh</option>
                </select>

                <button type="submit" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">Cari</button>

                @if($selectedPhase || $milestoneOnly || ($searchQuery ?? '') !== '' || $selectedSort)
                    <a href="{{ route('timeline.index') }}" class="text-xs font-semibold text-champagne hover:underline">Reset</a>
                @endif
            </form>
        </div>

        {{-- Fase --}}
        <div class="space-y-9 pt-8">
            @foreach($phases as $phaseGroup)
                @if(!$selectedPhase || $selectedPhase === $phaseGroup['name'])
                    <section>
                        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-3 sm:flex-row sm:items-baseline">
                            <h2 class="text-lg font-bold text-espresso">
                                <span class="mr-2 text-sm font-semibold text-champagne tabular-nums">{{ $phaseGroup['enum']->order() }}</span>
                                {{ $phaseGroup['name'] }}
                            </h2>
                            <p class="text-sm text-taupe">
                                {{ $phaseGroup['subtitle'] }} ·
                                <span class="{{ $phaseGroup['done'] === $phaseGroup['total'] && $phaseGroup['total'] > 0 ? 'font-semibold text-sage' : 'font-semibold text-charcoal' }}">
                                    {{ $phaseGroup['done'] }} dari {{ $phaseGroup['total'] }} tugas
                                </span>
                            </p>
                        </div>

                        <ul class="divide-y divide-stone-line/70 border-l border-stone-line/70 pl-5">
                            @forelse($phaseGroup['tasks'] as $task)
                                <li class="py-4">
                                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                                        <div class="min-w-0">
                                            <p class="flex flex-wrap items-baseline gap-2 text-xs">
                                                <span class="font-semibold text-champagne tabular-nums">{{ $task->due_date->format('d M Y') }}</span>
                                                <span class="text-taupe">{{ $task->category->shortLabel() }} · PIC {{ $task->assigned_to }}</span>
                                                @if($task->is_milestone)
                                                    <span class="font-semibold text-espresso">tonggak</span>
                                                @endif
                                                @if($task->is_overdue)
                                                    <span class="font-semibold text-wine">terlambat</span>
                                                @endif
                                            </p>
                                            <h3 class="mt-1 text-sm font-semibold text-espresso">{{ $task->title }}</h3>
                                            @if($task->description)
                                                <p class="mt-1 text-xs text-taupe">{{ $task->description }}</p>
                                            @endif
                                            @if($task->vendor)
                                                <p class="mt-1 text-xs text-taupe">Vendor: <span class="font-medium text-champagne">{{ $task->vendor->name }}</span></p>
                                            @endif
                                        </div>

                                        <form method="POST" action="{{ route('timeline.updateStatus', $task) }}" class="shrink-0 no-print">
                                            @csrf
                                            @method('PATCH')
                                            <select
                                                name="status"
                                                onchange="this.form.submit()"
                                                class="editorial-input !w-auto !py-1.5 !text-xs font-medium"
                                                aria-label="Ubah status agenda"
                                            >
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->value }}" @selected($task->status === $status)>
                                                        {{ $status->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-xs text-taupe">Belum ada agenda pada fase ini.</li>
                            @endforelse
                        </ul>
                    </section>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Rundown hari-H --}}
    <div x-show="currentTab === 'rundown'" x-cloak class="pt-7">
        <div class="flex flex-col justify-between gap-3 border-b border-stone-line pb-3 sm:flex-row sm:items-baseline">
            <div>
                <h2 class="text-lg font-bold text-espresso">Jadwal menit demi menit</h2>
                <p class="mt-0.5 text-xs text-taupe">Rundown resmi untuk panitia, WO, dan vendor lapangan.</p>
            </div>
            <a href="{{ route('print.masterGuide') }}" target="_blank" class="text-sm font-semibold text-champagne hover:underline no-print">
                Cetak panduan A4
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-stone-line font-semibold uppercase tracking-wider text-taupe">
                        <th scope="col" class="w-32 py-3 pr-3">Waktu (WIB)</th>
                        <th scope="col" class="w-44 px-3 py-3">Sesi</th>
                        <th scope="col" class="px-3 py-3">Aktivitas</th>
                        <th scope="col" class="w-40 px-3 py-3">Lokasi</th>
                        <th scope="col" class="w-44 py-3 pl-3">PIC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-line/70">
                    @forelse($rundowns as $rundown)
                        <tr>
                            <td class="py-3.5 pr-3 font-mono font-bold text-espresso whitespace-nowrap">
                                {{ $rundown->start_time }} – {{ $rundown->end_time }}
                            </td>
                            <td class="px-3 py-3.5 font-semibold text-espresso">{{ $rundown->session_name }}</td>
                            <td class="px-3 py-3.5 text-charcoal">
                                <p class="font-medium text-espresso">{{ $rundown->activity }}</p>
                                @if($rundown->notes)
                                    <p class="mt-0.5 text-xs text-taupe">{{ $rundown->notes }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-taupe">{{ $rundown->location ?? '—' }}</td>
                            <td class="py-3.5 pl-3 text-espresso" x-data="{ copiedPic: false }">
                                <p class="font-semibold">{{ $rundown->pic_name ?? 'Tim WO' }}</p>
                                @if($rundown->pic_phone)
                                    <div class="mt-0.5 flex flex-wrap items-center gap-2">
                                        <a href="tel:{{ $rundown->pic_phone }}" class="font-mono text-xs text-champagne hover:underline">
                                            {{ $rundown->pic_phone }}
                                        </a>
                                        <button
                                            type="button"
                                            @click="window.copyToClipboard('{{ $rundown->pic_phone }}'); copiedPic = true; setTimeout(() => copiedPic = false, 1800)"
                                            class="text-xs font-semibold text-taupe hover:text-espresso cursor-pointer no-print"
                                            title="Salin kontak PIC"
                                        >
                                            <span x-text="copiedPic ? 'Tersalin' : 'Salin'">Salin</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-taupe">Belum ada agenda rundown hari-H.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
