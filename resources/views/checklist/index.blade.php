@extends('layouts.app', ['title' => 'Catatan Tugas Persiapan'])

@section('content')
<div x-data="{
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
    }
}" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 border-b-2 border-ink pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-soga">
                <span class="stamp !bg-kunyit">Anti-Terlewat</span>
                <span>8 Kategori Kebutuhan Pernikahan</span>
            </div>
            <h1 class="mt-1 font-display text-2xl font-black tracking-tight text-espresso sm:text-4xl">
                Daftar Tugas Persiapan
            </h1>
            <p class="text-xs text-taupe font-semibold sm:text-sm">
                Centang kotak saat kebutuhan rampung. Klik tombol "Cara Urus" untuk 3 langkah praktis dan draf WhatsApp koordinasi.
            </p>
        </div>
        <div class="flex items-center gap-2 no-print">
            <a href="{{ route('checklist.create') }}" class="btn-neo !py-1.5 !px-3.5 !text-xs font-black">
                + Tambah Tugas Baru
            </a>
        </div>
    </div>

    {{-- 4 Kotak Ringkasan Status Bento --}}
    <section aria-label="Ringkasan status tugas" class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
        <div class="bento-card bento-mint p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Beres</p>
            <p class="font-display text-xl font-black text-forest sm:text-3xl">
                {{ $summary['completed_tasks'] }} <span class="text-xs font-semibold text-taupe">/ {{ $summary['total_tasks'] }}</span>
            </p>
        </div>
        <div class="bento-card bento-kunyit p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Sedang Diurus</p>
            <p class="font-display text-xl font-black text-ink sm:text-3xl">
                {{ $summary['in_progress_tasks'] }} <span class="text-xs font-semibold text-taupe">tugas</span>
            </p>
        </div>
        <div class="bento-card bento-paper p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Belum Mulai</p>
            <p class="font-display text-xl font-black text-ink sm:text-3xl">
                {{ $summary['todo_tasks'] }} <span class="text-xs font-semibold text-taupe">tugas</span>
            </p>
        </div>
        <div class="bento-card bento-terracotta p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Terlambat</p>
            <p class="font-display text-xl font-black {{ $summary['overdue_tasks_count'] > 0 ? 'text-wine' : 'text-taupe' }} sm:text-3xl">
                {{ $summary['overdue_tasks_count'] }} <span class="text-xs font-semibold text-taupe">tugas</span>
            </p>
        </div>
    </section>

    {{-- Filter Bar & 8 Kategori Inti --}}
    <div class="bento-card bento-paper p-4 space-y-3 no-print">
        <div class="flex items-center justify-between border-b border-ink/30 pb-2">
            <span class="text-xs font-black uppercase tracking-wider text-soga">Pilih Kategori Kebutuhan Inti:</span>
            @if($selectedCategory || $selectedStatus || $selectedPic || $selectedSearch !== '' || $selectedPriority !== '')
                <a href="{{ route('checklist.index') }}" class="text-xs font-bold text-wine hover:underline">Reset Semua Filter</a>
            @endif
        </div>

        {{-- 8 Kategori Inti Chips --}}
        <div class="flex flex-wrap items-center gap-1.5 text-xs">
            <a href="{{ route('checklist.index') }}" class="chip {{ !$selectedCategory ? 'chip-active' : '' }}">
                Semua Kategori ({{ $summary['total_tasks'] }})
            </a>
            @foreach($categories as $cat)
                @php $isActive = $selectedCategory === $cat->value; @endphp
                <a
                    href="{{ route('checklist.index', ['category' => $isActive ? null : $cat->value, 'status' => $selectedStatus, 'priority' => $selectedPriority]) }}"
                    class="chip {{ $isActive ? 'chip-active' : '' }}"
                >
                    {{ $cat->label() }}
                </a>
            @endforeach
        </div>

        {{-- Pencarian & Filter Dropdown --}}
        <form method="GET" action="{{ route('checklist.index') }}" class="flex flex-wrap items-center gap-2 pt-2 border-t border-ink/20 text-xs">
            @if($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif

            <input
                type="search"
                name="search"
                value="{{ $selectedSearch }}"
                placeholder="Cari nama tugas..."
                aria-label="Cari judul tugas"
                class="editorial-input !w-full sm:!w-48 !py-1 !text-xs font-bold"
            >

            <select name="status" aria-label="Filter status" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="priority" aria-label="Filter prioritas" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                <option value="">Semua Prioritas</option>
                <option value="high" @selected($selectedPriority === 'high')>Tinggi (Wajib)</option>
                <option value="medium" @selected($selectedPriority === 'medium')>Penting</option>
                <option value="low" @selected($selectedPriority === 'low')>Standar</option>
            </select>

            <select name="assigned_to" aria-label="Filter PIC" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                <option value="">Semua PIC Keluarga</option>
                @foreach($assignees as $pic)
                    <option value="{{ $pic }}" @selected($selectedPic === $pic)>{{ $pic }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-neo-secondary !min-h-0 !py-1 !px-3 !text-xs font-bold">Filter</button>
            <span class="ml-auto text-taupe font-bold text-[0.72rem]">{{ $tasks->count() }} tugas tercatat</span>
        </form>
    </div>

    {{-- Daftar Tugas Padat dengan Accordion --}}
    <ul class="bento-card bento-paper divide-y-2 divide-ink">
        @forelse($tasks as $task)
            @php
                $isDone = $task->status->value === 'done';
                $isInProgress = $task->status->value === 'in_progress';
                $guide = \App\Support\ActionGuide::forTask($task, $summary['profile']);
            @endphp
            <li
                x-data="{ expanded: false }"
                class="p-3 transition {{ $isDone ? 'checked-row' : 'hover:bg-paper-2' }}"
            >
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    {{-- Checkbox + Judul --}}
                    <div class="flex items-start gap-3 min-w-0">
                        <form method="POST" action="{{ route('checklist.toggle', $task) }}" class="mt-0.5 shrink-0">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="flex h-5 w-5 items-center justify-center border-2 border-ink transition {{ $isDone ? 'bg-forest text-paper shadow-none' : 'bg-paper text-transparent hover:bg-kunyit shadow-[2px_2px_0px_#2B2118]' }} cursor-pointer"
                                title="Ubah status selesai 1-klik"
                            >
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                </svg>
                            </button>
                        </form>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-baseline gap-1.5">
                                <h3 class="text-xs sm:text-sm font-extrabold {{ $isDone ? 'text-taupe line-through' : 'text-ink' }}">
                                    {{ $task->title }}
                                </h3>
                                @if($isDone)
                                    <span class="stamp !bg-mint !border-forest !text-forest !py-0 !px-1.5 font-black">BERES</span>
                                @elseif($isInProgress)
                                    <span class="stamp !bg-kunyit !border-ink !text-ink !py-0 !px-1.5 font-bold">Sedang Diurus</span>
                                @else
                                    <span class="stamp !bg-paper-2 !border-ink/50 !text-taupe !py-0 !px-1 font-semibold">Belum Mulai</span>
                                @endif

                                @if($task->is_overdue)
                                    <span class="stamp !bg-terracotta-light !border-wine !text-wine !py-0 !px-1 font-black">Terlambat</span>
                                @endif
                            </div>
                            <p class="text-[0.68rem] text-taupe font-semibold mt-0.5">
                                {{ $task->category->label() }} · {{ $task->phase }} · PIC: <strong class="text-ink">{{ $task->assigned_to }}</strong>
                                @if($task->vendor)
                                    · Vendor: <strong class="text-soga">{{ $task->vendor->name }}</strong>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Aksi + Tenggat --}}
                    <div class="flex items-center gap-2.5 pl-8 sm:pl-0 shrink-0 no-print">
                        <span class="rupiah text-[0.72rem] font-bold {{ $task->is_overdue ? 'text-wine' : 'text-taupe' }}">
                            {{ $task->due_date->translatedFormat('d M Y') }}
                        </span>

                        <button
                            type="button"
                            @click="openModal({{ Js::from($guide) }})"
                            class="btn-neo-secondary !min-h-0 !py-1 !px-2.5 !text-[0.7rem] font-extrabold text-soga hover:text-ink cursor-pointer flex items-center gap-1"
                        >
                            <x-ui.icon name="lightbulb" class="h-3 w-3 text-soga" />
                            <span>Cara Urus</span>
                        </button>
                        <button
                            type="button"
                            @click="expanded = !expanded"
                            class="btn-neo-secondary !min-h-0 !p-1 text-[0.7rem] cursor-pointer"
                            title="Buka rincian"
                        >
                            <span x-text="expanded ? '▲' : '▼'">▼</span>
                        </button>
                    </div>
                </div>

                {{-- Accordion detail (default tertutup) --}}
                <div x-show="expanded" x-cloak class="mt-2.5 ml-8 border-l-2 border-ink pl-3 py-1.5 text-xs text-ink space-y-2">
                    @if($task->description)
                        <p class="text-taupe text-[0.75rem] font-semibold leading-relaxed">{{ $task->description }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-4 text-[0.7rem] text-taupe font-bold">
                        <span>Prioritas: <strong class="text-ink uppercase">{{ $task->priority_label }}</strong></span>
                        <span>Status: <strong class="text-ink">{{ $task->status->label() }}</strong></span>
                        @if($task->completed_at)
                            <span>Selesai pada: {{ $task->completed_at->translatedFormat('d M Y H:i') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 pt-1 no-print text-xs">
                        <a href="{{ route('checklist.edit', $task) }}" class="font-bold text-soga hover:underline">
                            Ubah Tugas
                        </a>
                        <form method="POST" action="{{ route('checklist.destroy', $task) }}" onsubmit="return confirm('Hapus tugas persiapan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-bold text-wine hover:underline cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="py-8 text-center text-xs text-taupe font-bold">
                Tidak ada tugas yang sesuai dengan filter pencarian ini.
            </li>
        @endforelse
    </ul>

    {{-- Modal Dialog Kertas Neo-Brutalist: Cara Urus & Salin WhatsApp --}}
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
                    <span class="stamp !bg-kunyit font-black">Panduan 3 Langkah</span>
                    <h2 class="font-display text-xl font-extrabold text-ink mt-1" x-text="'Cara Urus: ' + (guideData ? guideData.target_name : '')"></h2>
                </div>
                <button type="button" @click="closeModal()" class="btn-neo-secondary !min-h-0 !p-1 text-sm font-bold cursor-pointer">✕</button>
            </div>

            <div class="mt-4" x-show="guideData">
                <p class="text-xs font-black uppercase tracking-wider text-soga">3 Langkah yang Harus Dijalankan:</p>
                <ol class="mt-2 space-y-2 text-xs sm:text-sm text-ink">
                    <template x-for="(step, idx) in (guideData ? guideData.steps : [])" :key="idx">
                        <li class="flex items-start gap-2">
                            <span class="hand font-bold text-soga" x-text="(idx + 1) + '.'"></span>
                            <span class="font-semibold" x-text="step"></span>
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
                            <span x-text="copiedWa ? 'Teks WhatsApp Berhasil Disalin!' : 'Salin Draf WhatsApp'">Salin Draf WhatsApp</span>
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
