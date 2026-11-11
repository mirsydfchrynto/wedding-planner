@extends('layouts.app', ['title' => 'Checklist & Timeline Persiapan'])

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

    <div class="flex flex-col gap-3 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold text-[#8C5835]">Jadwal &amp; Kebutuhan Terpadu</p>
            <h1 class="mt-1.5 text-2xl font-bold tracking-tight text-[#2B2520] sm:text-3xl">
                Checklist &amp; Timeline Persiapan
            </h1>
            <p class="text-xs text-[#6E665E] sm:text-sm mt-0.5">
                Daftar kebutuhan pernikahan berurut otomatis dari deadline terdekat. Centang status tugas dan koordinasi langsung dengan PIC atau vendor.
            </p>
        </div>
        <div class="flex items-center gap-2 no-print">
            <a href="{{ route('checklist.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                + Tambah Kebutuhan
            </a>
        </div>
    </div>

    {{-- Visual SVG Task Pipeline Flow Diagram --}}
    <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm overflow-x-auto">
        <div class="flex items-center justify-between pb-3 border-b border-[#F0ECE4] text-xs">
            <span class="font-semibold text-[#2B2520]">Pipeline Status Persiapan</span>
            <span class="text-[#6E665E]">Total {{ $summary['total_tasks'] }} Kebutuhan Terjadwal</span>
        </div>
        <div class="pt-3">
            <svg viewBox="0 0 760 80" class="w-full min-w-[620px] h-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Connecting Flow Lines -->
                <line x1="120" y1="40" x2="380" y2="40" stroke="#E7E4DF" stroke-width="2" />
                <line x1="380" y1="40" x2="640" y2="40" stroke="#E7E4DF" stroke-width="2" />

                <!-- Stage 1: Beres -->
                <circle cx="120" cy="40" r="24" fill="{{ $summary['completed_tasks'] > 0 ? '#EAF4EE' : '#FAF8F5' }}" stroke="#2E5E3E" stroke-width="2" />
                <text x="120" y="45" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="700" fill="#2E5E3E">{{ $summary['completed_tasks'] }}</text>
                <text x="120" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="600" fill="#2E5E3E">Selesai (Beres)</text>

                <!-- Stage 2: Sedang Diurus -->
                <circle cx="380" cy="40" r="24" fill="{{ $summary['in_progress_tasks'] > 0 ? '#FEF7EC' : '#FAF8F5' }}" stroke="#A06414" stroke-width="2" />
                <text x="380" y="45" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="700" fill="#A06414">{{ $summary['in_progress_tasks'] }}</text>
                <text x="380" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="600" fill="#A06414">Sedang Diproses</text>

                <!-- Stage 3: Belum Mulai -->
                <circle cx="640" cy="40" r="24" fill="#FAF8F5" stroke="#6E665E" stroke-width="2" />
                <text x="640" y="45" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="700" fill="#6E665E">{{ $summary['todo_tasks'] }}</text>
                <text x="640" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="600" fill="#6E665E">Belum Dimulai</text>
            </svg>
        </div>
    </div>

    <section aria-label="Ringkasan status tugas" class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Beres</p>
            <p class="text-xl sm:text-3xl font-semibold text-[#2E5E3E] tabular-nums">
                {{ $summary['completed_tasks'] }} <span class="text-xs font-normal text-[#6E665E]">/ {{ $summary['total_tasks'] }}</span>
            </p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Sedang Diurus</p>
            <p class="text-xl sm:text-3xl font-semibold text-[#A06414] tabular-nums">
                {{ $summary['in_progress_tasks'] }} <span class="text-xs font-normal text-[#6E665E]">tugas</span>
            </p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Belum Mulai</p>
            <p class="text-xl sm:text-3xl font-semibold text-[#6E665E] tabular-nums">
                {{ $summary['todo_tasks'] }} <span class="text-xs font-normal text-[#6E665E]">tugas</span>
            </p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Terlambat</p>
            <p class="text-xl sm:text-3xl font-semibold tabular-nums {{ $summary['overdue_tasks_count'] > 0 ? 'text-[#9E352B]' : 'text-[#6E665E]' }}">
                {{ $summary['overdue_tasks_count'] }} <span class="text-xs font-normal text-[#6E665E]">tugas</span>
            </p>
        </div>
    </section>

    <div class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm space-y-4 no-print">
        <div class="flex items-center justify-between border-b border-[#F0ECE4] pb-3">
            <span class="text-xs font-medium text-[#8C5835]">Kategori Kebutuhan Inti:</span>
            @if($selectedCategory || $selectedStatus || $selectedPic || $selectedSearch !== '' || $selectedPriority !== '')
                <a href="{{ route('checklist.index') }}" class="text-xs font-medium text-[#9E352B] hover:underline">Reset Semua Filter</a>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('checklist.index') }}" class="rounded-md border px-3 py-1.5 text-xs font-medium transition cursor-pointer {{ !$selectedCategory ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]' }}">
                Semua Kategori ({{ $summary['total_tasks'] }})
            </a>
            @foreach($categories as $cat)
                @php $isActive = $selectedCategory === $cat->value; @endphp
                <a
                    href="{{ route('checklist.index', ['category' => $isActive ? null : $cat->value, 'status' => $selectedStatus, 'priority' => $selectedPriority]) }}"
                    class="rounded-md border px-3 py-1.5 text-xs font-medium transition cursor-pointer {{ $isActive ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]' }}"
                >
                    {{ $cat->label() }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('checklist.index') }}" class="flex flex-wrap items-center gap-2.5 pt-3 border-t border-[#F0ECE4] text-xs">
            @if($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif

            <input
                type="search"
                name="search"
                value="{{ $selectedSearch }}"
                placeholder="Cari nama tugas..."
                aria-label="Cari judul tugas"
                class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs text-[#2B2520] placeholder-[#9E968D] focus:border-[#8C5835] focus:outline-none w-full sm:w-52"
            >

            <select name="status" aria-label="Filter status" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="priority" aria-label="Filter prioritas" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                <option value="">Semua Prioritas</option>
                <option value="high" @selected($selectedPriority === 'high')>Tinggi (Wajib)</option>
                <option value="medium" @selected($selectedPriority === 'medium')>Penting</option>
                <option value="low" @selected($selectedPriority === 'low')>Standar</option>
            </select>

            <select name="assigned_to" aria-label="Filter PIC" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                <option value="">Semua PIC</option>
                @foreach($assignees as $pic)
                    <option value="{{ $pic }}" @selected($selectedPic === $pic)>{{ $pic }}</option>
                @endforeach
            </select>

            <button type="submit" class="rounded-md border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer">Filter</button>
            <span class="ml-auto text-[#6E665E] text-xs tabular-nums">{{ $tasks->count() }} tugas tercatat</span>
        </form>
    </div>

    <ul class="rounded-xl border border-[#E7E4DF] bg-white shadow-sm divide-y divide-[#F0ECE4] overflow-hidden">
        @forelse($tasks as $task)
            @php
                $isDone = $task->status->value === 'done';
                $isInProgress = $task->status->value === 'in_progress';
                $guide = \App\Support\ActionGuide::forTask($task, $summary['profile']);
            @endphp
            <li
                x-data="{ expanded: false }"
                class="p-4 transition {{ $isDone ? 'bg-[#F2F8F4]' : 'hover:bg-[#FAF8F5]' }}"
            >
                <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3 min-w-0">
                        <form method="POST" action="{{ route('checklist.toggle', $task) }}" class="mt-0.5 shrink-0">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="flex h-5 w-5 items-center justify-center rounded-md border transition {{ $isDone ? 'border-[#2E5E3E] bg-[#2E5E3E] text-white' : 'border-[#E7E4DF] bg-white text-transparent hover:border-[#2E5E3E] hover:text-[#2E5E3E]' }} cursor-pointer"
                                title="Ubah status selesai 1-klik"
                            >
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                </svg>
                            </button>
                        </form>

                        <div class="min-w-0 space-y-0.5">
                            <div class="flex flex-wrap items-baseline gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold {{ $isDone ? 'text-[#6E665E] line-through' : 'text-[#2B2520]' }}">
                                    {{ $task->title }}
                                </h3>
                                @if($isDone)
                                    <span class="inline-flex items-center rounded border border-[#D3E6DA] bg-[#EAF4EE] px-2 py-0.5 text-xs font-medium text-[#2E5E3E]">Beres</span>
                                @elseif($isInProgress)
                                    <span class="inline-flex items-center rounded border border-[#F6E6CC] bg-[#FEF7EC] px-2 py-0.5 text-xs font-medium text-[#A06414]">Sedang Diurus</span>
                                @else
                                    <span class="inline-flex items-center rounded border border-[#E7E4DF] bg-[#FAF8F5] px-2 py-0.5 text-xs font-medium text-[#6E665E]">Belum Mulai</span>
                                @endif

                                @if($task->is_overdue)
                                    <span class="inline-flex items-center rounded border border-[#F3D5D0] bg-[#FDF2F0] px-2 py-0.5 text-xs font-medium text-[#9E352B]">Terlambat</span>
                                @endif
                            </div>
                            <p class="text-xs text-[#6E665E]">
                                {{ $task->category->label() }} · {{ $task->phase }} · PIC: <strong class="text-[#2B2520] font-medium">{{ $task->assigned_to }}</strong>
                                @if($task->vendor)
                                    · Vendor: <strong class="text-[#8C5835] font-medium">{{ $task->vendor->name }}</strong>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pl-8 sm:pl-0 shrink-0 no-print">
                        <span class="text-xs tabular-nums {{ $task->is_overdue ? 'text-[#9E352B] font-semibold' : 'text-[#6E665E]' }}">
                            {{ $task->due_date->translatedFormat('d M Y') }}
                        </span>

                        <button
                            type="button"
                            @click="openModal({{ Js::from($guide) }})"
                            class="inline-flex items-center gap-1 rounded-lg border border-[#E7E4DF] bg-white px-2.5 py-1 text-xs font-medium text-[#8C5835] hover:bg-[#F5F2EB] transition cursor-pointer"
                        >
                            <x-ui.icon name="lightbulb" class="h-3 w-3 text-[#8C5835]" />
                            <span>Cara Urus</span>
                        </button>
                        <button
                            type="button"
                            @click="expanded = !expanded"
                            class="rounded-lg border border-[#E7E4DF] bg-white px-2 py-1 text-xs text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer"
                            title="Buka rincian"
                        >
                            <span x-text="expanded ? '▲' : '▼'">▼</span>
                        </button>
                    </div>
                </div>

                <div x-show="expanded" x-cloak class="mt-3 ml-8 border-l-2 border-[#E7E4DF] pl-3 py-1 text-xs text-[#2B2520] space-y-2">
                    @if($task->description)
                        <p class="text-[#6E665E] text-xs leading-relaxed">{{ $task->description }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-4 text-xs text-[#6E665E]">
                        <span>Prioritas: <strong class="text-[#2B2520] font-medium">{{ $task->priority_label }}</strong></span>
                        <span>Status: <strong class="text-[#2B2520] font-medium">{{ $task->status->label() }}</strong></span>
                        @if($task->completed_at)
                            <span>Selesai pada: {{ $task->completed_at->translatedFormat('d M Y H:i') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 pt-1 no-print text-xs">
                        <a href="{{ route('checklist.edit', $task) }}" class="font-medium text-[#8C5835] hover:underline">
                            Ubah Tugas
                        </a>
                        <form method="POST" action="{{ route('checklist.destroy', $task) }}" onsubmit="return confirm('Hapus tugas persiapan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-[#9E352B] hover:underline cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="py-12 px-4 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#FAF8F5] border border-[#E7E4DF] text-[#8C5835] mb-3">
                    <x-ui.icon name="check-circle" class="h-6 w-6" />
                </div>
                <h3 class="text-sm font-semibold text-[#2B2520]">Belum ada kebutuhan tercatat</h3>
                <p class="mt-1 text-xs text-[#6E665E] max-w-sm mx-auto">
                    Mulai susun kebutuhan pernikahan Anda. Setiap tugas otomatis memiliki tanggal tenggat dan terintegrasi ke linimasa persiapan.
                </p>
                <div class="mt-4">
                    <a href="{{ route('checklist.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        + Tambah Kebutuhan Baru
                    </a>
                </div>
            </li>
        @endforelse
    </ul>

    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print"
        role="dialog"
        aria-modal="true"
        x-on:keydown.escape.window="closeModal()"
    >
        <div class="fixed inset-0 bg-[#2B2520]/40 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative z-10 w-full max-w-lg rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between gap-3 border-b border-[#F0ECE4] pb-3">
                <div>
                    <span class="inline-flex items-center rounded-md border border-[#E7E4DF] bg-[#FAF8F5] px-2 py-0.5 text-xs font-medium text-[#8C5835]">Panduan 3 Langkah</span>
                    <h2 class="text-lg font-semibold text-[#2B2520] mt-1" x-text="'Cara Urus: ' + (guideData ? guideData.target_name : '')"></h2>
                </div>
                <button type="button" @click="closeModal()" class="rounded border border-[#E7E4DF] bg-white p-1 text-sm font-semibold text-[#6E665E] hover:text-[#2B2520] cursor-pointer">✕</button>
            </div>

            <div class="mt-4" x-show="guideData">
                <p class="text-xs font-semibold text-[#8C5835]">3 Langkah Pelaksanaan:</p>
                <ol class="mt-2 space-y-2 text-xs sm:text-sm text-[#2B2520]">
                    <template x-for="(step, idx) in (guideData ? guideData.steps : [])" :key="idx">
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-[#8C5835]" x-text="(idx + 1) + '.'"></span>
                            <span x-text="step"></span>
                        </li>
                    </template>
                </ol>

                <div class="mt-5 border-t border-[#F0ECE4] pt-4">
                    <p class="text-xs font-semibold text-[#8C5835]">Draf Pesan WhatsApp Siap Kirim:</p>
                    <div class="mt-2 rounded-lg border border-[#E7E4DF] bg-[#FAF8F5] p-3.5 font-mono text-xs text-[#2B2520] whitespace-pre-wrap leading-relaxed" x-text="guideData ? guideData.wa_text : ''"></div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                        <button
                            type="button"
                            @click="window.copyToClipboard(guideData ? guideData.wa_text : ''); copiedWa = true; setTimeout(() => copiedWa = false, 2200)"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2B2520] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815] cursor-pointer"
                        >
                            <span x-text="copiedWa ? 'Teks WhatsApp Berhasil Disalin!' : 'Salin Draf WhatsApp'">Salin Draf WhatsApp</span>
                        </button>

                        <template x-if="guideData && guideData.wa_url">
                            <a :href="guideData.wa_url" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-lg bg-[#2E5E3E] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#254F34]">
                                Buka Aplikasi WhatsApp &rarr;
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
