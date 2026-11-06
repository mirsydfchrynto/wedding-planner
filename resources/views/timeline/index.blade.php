@extends('layouts.app', ['title' => 'Wedding Timeline'])

@section('content')
<div x-data="{ currentTab: '{{ $activeTab }}' }">
    <x-ui.section-header
        title="Linimasa & Jadwal"
    >
        <x-slot:actions>
            <div class="inline-flex rounded-xl bg-pearl border border-black/[0.06] p-1 shadow-sm">
                <button
                    type="button"
                    @click="currentTab = 'timeline'"
                    :class="currentTab === 'timeline' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:text-espresso'"
                    class="rounded-lg px-3 py-1.5 text-xs font-semibold transition cursor-pointer"
                >
                    Linimasa Persiapan
                </button>
                <button
                    type="button"
                    @click="currentTab = 'rundown'"
                    :class="currentTab === 'rundown' ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:text-espresso'"
                    class="rounded-lg px-3 py-1.5 text-xs font-semibold transition cursor-pointer"
                >
                    Rundown Hari-H ({{ $rundowns->count() }})
                </button>
            </div>
            <button
                type="button"
                x-show="currentTab === 'timeline'"
                @click="$dispatch('open-modal', 'create-timeline-modal')"
                class="editorial-btn-primary"
            >
                + Agenda Linimasa
            </button>
            <button
                type="button"
                x-show="currentTab === 'rundown'"
                x-cloak
                @click="$dispatch('open-modal', 'create-rundown-modal')"
                class="editorial-btn-primary"
            >
                + Sesi Rundown
            </button>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Phase Selector Strip & Search/Sort Controls --}}
    <div x-show="currentTab === 'timeline'">
    <section class="mt-6 editorial-card rounded-lg p-5">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-4 lg:flex-row lg:items-center">
            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ route('timeline.index', ['milestone_only' => $milestoneOnly ? 1 : null, 'search' => $searchQuery ?: null, 'sort' => $selectedSort]) }}"
                    class="rounded-md px-3.5 py-1.5 text-xs font-semibold transition {{ !$selectedPhase ? 'bg-espresso text-alabaster' : 'border border-stone-line bg-pearl text-charcoal hover:border-champagne' }}"
                >
                    Seluruh Fase (H-12 s/d Hari-H)
                </a>
                @foreach($allPhases as $phase)
                    @php $activePhase = $selectedPhase === $phase->value; @endphp
                    <a
                        href="{{ route('timeline.index', ['phase' => $phase->value, 'milestone_only' => $milestoneOnly ? 1 : null, 'search' => $searchQuery ?: null, 'sort' => $selectedSort]) }}"
                        class="rounded-md px-3.5 py-1.5 text-xs font-medium transition {{ $activePhase ? 'bg-champagne text-pearl' : 'border border-stone-line bg-pearl text-charcoal hover:border-champagne' }}"
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
                    aria-label="Cari Agenda atau Milestone Linimasa"
                    class="editorial-input !w-full sm:!w-52 !py-1.5 !text-xs"
                >

                <select name="sort" aria-label="Urutkan Agenda Linimasa" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="" @selected(!$selectedSort || $selectedSort === 'due_date_asc')>Terdekat Jatuh Tempo</option>
                    <option value="priority_desc" @selected($selectedSort === 'priority_desc')>Prioritas Tertinggi</option>
                    <option value="due_date_desc" @selected($selectedSort === 'due_date_desc')>Tenggat Paling Akhir</option>
                </select>

                <button type="submit" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">
                    Cari
                </button>

                @if($selectedPhase || $milestoneOnly || ($searchQuery ?? '') !== '' || $selectedSort)
                    <a href="{{ route('timeline.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- Chronological Vertical Roadmap --}}
    <section class="mt-8 space-y-6">
        @foreach($phases as $phaseGroup)
            @if(!$selectedPhase || $selectedPhase === $phaseGroup['name'])
                <div class="editorial-card rounded-lg p-6">
                    <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-champagne-light font-editorial text-sm font-bold text-champagne">
                                    {{ $phaseGroup['enum']->order() }}
                                </span>
                                <h2 class="font-editorial text-2xl font-semibold text-espresso">
                                    Fase {{ $phaseGroup['name'] }}
                                </h2>
                                <x-ui.status-badge
                                    :tone="$phaseGroup['percent'] === 100 ? 'sage' : ($phaseGroup['percent'] > 0 ? 'ochre' : 'neutral')"
                                    :label="$phaseGroup['done'] . '/' . $phaseGroup['total'] . ' Selesai (' . $phaseGroup['percent'] . '%)'"
                                />
                            </div>
                            <p class="mt-1 text-xs text-taupe">{{ $phaseGroup['subtitle'] }}</p>
                        </div>

                        <div class="w-full sm:w-48">
                            <x-ui.progress-bar
                                size="sm"
                                :percent="$phaseGroup['percent']"
                                :tone="$phaseGroup['percent'] === 100 ? 'sage' : 'champagne'"
                            />
                        </div>
                    </div>

                    {{-- Tasks within this Phase --}}
                    <div class="mt-5 relative border-l-2 border-stone-line ml-3.5 pl-6 space-y-4">
                        @forelse($phaseGroup['tasks'] as $task)
                            @php
                                $dotColor = match ($task->status->value) {
                                    'done' => 'bg-sage border-sage',
                                    'in_progress' => 'bg-ochre border-ochre',
                                    default => 'bg-pearl border-champagne',
                                };
                            @endphp
                            <div class="relative rounded border border-stone-line bg-alabaster/70 p-4 transition hover:bg-pearl">
                                {{-- deslop-ignore-next-line 19 --}}
                                <span class="absolute -left-[31px] top-5 h-3.5 w-3.5 rounded-full border-2 {{ $dotColor }}"></span>

                                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="tabular-nums text-xs font-semibold text-champagne">
                                                {{ $task->due_date->format('d M Y') }}
                                            </span>
                                            @if($task->is_milestone)
                                                <span class="rounded bg-champagne-light px-2 py-0.5 text-[10px] font-semibold text-champagne">
                                                    Milestone
                                                </span>
                                            @endif
                                            @if($task->is_overdue)
                                                <x-ui.status-badge tone="wine" label="Melewati Tenggat" />
                                            @endif
                                        </div>

                                        <h3 class="mt-1 text-sm font-semibold text-espresso">
                                            {{ $task->title }}
                                        </h3>

                                        @if($task->description)
                                            <p class="mt-1 text-xs text-taupe">{{ $task->description }}</p>
                                        @endif

                                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-taupe">
                                            <span>Kategori: <strong class="text-charcoal">{{ $task->category->shortLabel() }}</strong></span>
                                            <span>·</span>
                                            <span>PIC: <strong class="text-charcoal">{{ $task->assigned_to }}</strong></span>
                                            @if($task->vendor)
                                                <span>·</span>
                                                <span>Mitra: <strong class="text-champagne">{{ $task->vendor->name }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 no-print">
                                        <form method="POST" action="{{ route('timeline.updateStatus', $task) }}">
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
                                </div>
                            </div>
                        @empty
                            <p class="py-3 text-xs text-taupe">Belum ada agenda pada fase ini.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        @endforeach
    </section>
    </div>

    {{-- Rundown Hari-H Tab Content --}}
    <div x-show="currentTab === 'rundown'" x-cloak class="mt-6">
        <section class="editorial-card p-6">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <div>
                    <h2 class="font-editorial text-2xl font-semibold text-espresso">Jadwal Menit-demi-Menit Hari Pernikahan</h2>
                    <p class="text-xs text-taupe">Rundown operasional resmi yang digunakan oleh panitia, WO, dan vendor lapangan</p>
                </div>
                <a href="{{ route('print.masterGuide') }}" target="_blank" class="editorial-btn-secondary !text-xs !py-1.5">
                    <x-ui.icon name="printer" class="h-3.5 w-3.5" />
                    <span>Format Cetak A4</span>
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-line text-taupe font-semibold">
                            <th scope="col" class="py-3 pr-3 w-32">Waktu (WIB)</th>
                            <th scope="col" class="px-3 py-3 w-44">Sesi Acara</th>
                            <th scope="col" class="px-3 py-3">Aktivitas &amp; Detail</th>
                            <th scope="col" class="px-3 py-3 w-40">Lokasi</th>
                            <th scope="col" class="pl-3 py-3 w-44">PIC &amp; Kontak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-line/70">
                        @forelse($rundowns as $rundown)
                            <tr class="hover:bg-alabaster/60">
                                <td class="py-3.5 pr-3 font-mono font-bold text-espresso whitespace-nowrap">
                                    {{ $rundown->start_time }} - {{ $rundown->end_time }}
                                </td>
                                <td class="px-3 py-3.5 font-semibold text-espresso">
                                    {{ $rundown->session_name }}
                                </td>
                                <td class="px-3 py-3.5 text-charcoal">
                                    <p class="font-medium text-espresso">{{ $rundown->activity }}</p>
                                    @if($rundown->notes)
                                        <p class="mt-0.5 text-[11px] text-taupe">{{ $rundown->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-taupe">
                                    {{ $rundown->location ?? '-' }}
                                </td>
                                <td class="pl-3 py-3.5 text-espresso">
                                    <p class="font-semibold">{{ $rundown->pic_name ?? 'Tim WO' }}</p>
                                    @if($rundown->pic_phone)
                                        <a href="tel:{{ $rundown->pic_phone }}" class="font-mono text-[11px] text-champagne hover:underline">
                                            {{ $rundown->pic_phone }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-taupe">Belum ada agenda rundown Hari-H.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

    {{-- Modal Tambah Sesi Rundown Hari-H --}}
    <x-ui.modal name="create-rundown-modal" title="Tambah Sesi Rundown Hari-H">
        <form method="POST" action="{{ route('timeline.storeRundown') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Jam Mulai (WIB)</label>
                    <input type="text" name="start_time" placeholder="Contoh: 08:00" required class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Jam Selesai (WIB)</label>
                    <input type="text" name="end_time" placeholder="Contoh: 09:30" required class="editorial-input">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Sesi Acara</label>
                <input type="text" name="session_name" placeholder="Contoh: Akad Nikah / Kirab Pengantin" required class="editorial-input">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Rincian Aktivitas</label>
                <textarea name="activity" rows="2" placeholder="Deskripsi tahapan acara..." required class="editorial-input !min-h-0"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Lokasi / Ruangan</label>
                    <input type="text" name="location" placeholder="Contoh: Pendopo Utama" class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama PIC</label>
                    <input type="text" name="pic_name" placeholder="Contoh: Lead WO Irsyad" class="editorial-input">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Nomor Telepon PIC (WhatsApp)</label>
                <input type="text" name="pic_phone" placeholder="Contoh: 08123456789" class="editorial-input">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Catatan Khusus (Opsional)</label>
                <input type="text" name="notes" placeholder="Contoh: Gamelan standby 15 menit sebelum kirab" class="editorial-input">
            </div>
            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'create-rundown-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Rundown
                </button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal: Schedule Timeline Milestone --}}
    <x-ui.modal name="create-timeline-modal" title="Jadwalkan Milestone Linimasa Baru" subtitle="Tambahkan agenda penting ke dalam peta persiapan H-12 Bulan hingga Hari-H">
        <form method="POST" action="{{ route('timeline.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Agenda / Milestone</label>
                <input type="text" name="title" placeholder="Contoh: Gladi Bersih Prosesi Kirab & Soundcheck Quartet" required class="editorial-input">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Keterangan Pelaksanaan</label>
                <textarea name="description" rows="2" placeholder="Catatan waktu dan koordinasi pihak terkait..." class="editorial-input"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Fase Persiapan</label>
                    <select name="phase" required class="editorial-input">
                        @foreach($allPhases as $phase)
                            <option value="{{ $phase->value }}">{{ $phase->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}">{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- deslop-ignore-next-line 28 --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Target</label>
                    <input type="date" name="due_date" value="{{ now()->addDays(21)->format('Y-m-d') }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Prioritas</label>
                    <select name="priority" required class="editorial-input">
                        <option value="high" selected>Prioritas Tinggi</option>
                        <option value="medium">Penting</option>
                        <option value="low">Standar</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">PIC</label>
                    <input type="text" name="assigned_to" value="WO & Pasangan" required class="editorial-input">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Mitra Vendor Terkait (Opsional)</label>
                <select name="vendor_id" class="editorial-input">
                    <option value="">-- Tidak Ada --</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="is_milestone" value="1">

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'create-timeline-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan ke Linimasa
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
