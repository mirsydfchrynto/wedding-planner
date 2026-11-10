@extends('layouts.app', ['title' => 'Wedding Checklist'])

@section('content')
    <x-ui.section-header
        title="Checklist Persiapan"
    >
        <x-slot:actions>
            <a href="{{ route('checklist.create') }}" class="editorial-btn-primary">
                + Tambah Tugas Baru
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Checklist KPI Cards --}}
    <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Penyelesaian"
            :value="$summary['completion_percent'] . '%'"
            :subtitle="$summary['completed_tasks'] . ' dari ' . $summary['total_tasks'] . ' selesai'"
            badge="Target 100%"
            badgeTone="sage"
        >
            <x-ui.progress-bar size="sm" :percent="$summary['completion_percent']" tone="sage" />
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Sedang Berjalan"
            :value="$summary['in_progress_tasks'] . ' Tugas'"
            subtitle="Dalam proses pengerjaan"
            badge="Aktif"
            badgeTone="ochre"
        />

        <x-ui.stat-card
            label="Belum Dimulai"
            :value="$summary['todo_tasks'] . ' Tugas'"
            subtitle="Menunggu jadwal"
            badge="Antrean"
            badgeTone="neutral"
        />

        <x-ui.stat-card
            label="Tenggat Terlewat"
            :value="$summary['overdue_tasks_count'] . ' Tugas'"
            subtitle="Melewati target"
            :badge="$summary['overdue_tasks_count'] > 0 ? 'Perhatian' : 'Tepat Waktu'"
            :badgeTone="$summary['overdue_tasks_count'] > 0 ? 'wine' : 'sage'"
        />
    </section>

    {{-- Category Quick Filter Pills & Search Controls --}}
    <section class="mt-6 editorial-card rounded-lg p-6">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-4 lg:flex-row lg:items-center">
            <div class="flex flex-wrap items-center gap-1.5">
                <a
                    href="{{ route('checklist.index', ['status' => $selectedStatus, 'assigned_to' => $selectedPic, 'search' => $selectedSearch, 'priority' => $selectedPriority]) }}"
                    class="rounded-md px-3 py-1.5 text-xs font-semibold transition {{ !$selectedCategory ? 'bg-espresso text-alabaster' : 'bg-ivory text-charcoal hover:bg-stone-line' }}"
                >
                    Semua Kategori
                </a>
                @foreach($categories as $category)
                    @php $activeCat = $selectedCategory === $category->value; @endphp
                    <a
                        href="{{ route('checklist.index', ['category' => $category->value, 'status' => $selectedStatus, 'assigned_to' => $selectedPic, 'search' => $selectedSearch, 'priority' => $selectedPriority]) }}"
                        class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $activeCat ? 'bg-champagne text-pearl' : 'bg-alabaster border border-stone-line text-charcoal hover:border-champagne' }}"
                    >
                        {{ $category->shortLabel() }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('checklist.index') }}" class="flex flex-wrap items-center gap-2 no-print">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif

                <input
                    type="search"
                    name="search"
                    value="{{ $selectedSearch }}"
                    placeholder="Cari judul tugas..."
                    aria-label="Cari judul tugas"
                    class="editorial-input !w-48 !py-1.5 !text-xs"
                >

                <button type="submit" class="editorial-btn-secondary !py-1.5 !text-xs">
                    Cari
                </button>

                <select name="priority" aria-label="Filter Prioritas Tugas" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua Prioritas</option>
                    <option value="high" @selected($selectedPriority === 'high')>Prioritas Tinggi</option>
                    <option value="medium" @selected($selectedPriority === 'medium')>Penting</option>
                    <option value="low" @selected($selectedPriority === 'low')>Standar</option>
                </select>

                <select name="status" aria-label="Filter Status Tugas" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="assigned_to" aria-label="Filter Penanggung Jawab PIC" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua PIC</option>
                    @foreach($assignees as $pic)
                        <option value="{{ $pic }}" @selected($selectedPic === $pic)>{{ $pic }}</option>
                    @endforeach
                </select>

                @if($selectedCategory || $selectedStatus || $selectedPic || $selectedSearch !== '' || $selectedPriority !== '')
                    <a href="{{ route('checklist.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Checklist Items List --}}
        <div class="mt-4 divide-y divide-stone-line/80">
            @forelse($tasks as $task)
                @php $isDone = $task->status->value === 'done'; @endphp
                <div class="flex flex-col justify-between gap-4 py-4 sm:flex-row sm:items-center">
                    <div class="flex items-start gap-3.5">
                        <form method="POST" action="{{ route('checklist.toggle', $task) }}" class="mt-0.5">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="flex h-5 w-5 items-center justify-center rounded border transition {{ $isDone ? 'border-sage bg-sage text-pearl' : 'border-stone-strong bg-alabaster text-transparent hover:border-champagne' }}"
                                title="Ubah status selesai"
                            >
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                </svg>
                            </button>
                        </form>

                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- deslop-ignore-next-line 09 --}}
                                <h3 class="text-sm font-semibold {{ $isDone ? 'line-through text-taupe' : 'text-espresso' }}">
                                    {{ $task->title }}
                                </h3>
                                @if($task->is_milestone)
                                    <span class="rounded border border-champagne/40 bg-champagne-light px-2 py-0.5 text-[10px] font-semibold text-champagne">
                                        Milestone Utama
                                    </span>
                                @endif
                            </div>

                            @if($task->description)
                                <p class="mt-1 text-xs text-taupe">{{ $task->description }}</p>
                            @endif

                            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-taupe">
                                <span class="font-medium text-charcoal">{{ $task->category->label() }}</span>
                                <span>·</span>
                                <span>Fase: <strong class="font-medium text-charcoal">{{ $task->phase }}</strong></span>
                                <span>·</span>
                                <span>PIC: <strong class="font-medium text-charcoal">{{ $task->assigned_to }}</strong></span>
                                @if($task->vendor)
                                    <span>·</span>
                                    <span>Vendor: <strong class="font-medium text-champagne">{{ $task->vendor->name }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-end">
                        <div class="flex items-center gap-2">
                            <x-ui.status-badge :tone="$task->priority_tone" :label="$task->priority_label" />
                            <x-ui.status-badge :tone="$task->status->badgeTone()" :label="$task->status->label()" />
                            <span class="tabular-nums text-xs font-medium {{ $task->is_overdue ? 'text-wine font-semibold' : 'text-taupe' }}">
                                {{ $task->due_date->format('d M Y') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 no-print">
                            <a href="{{ route('checklist.edit', $task) }}" class="text-xs font-semibold text-champagne hover:underline">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('checklist.destroy', $task) }}" onsubmit="return confirm('Hapus tugas persiapan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-wine hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="py-10 text-center">
                    <p class="text-sm text-taupe">Tidak ada tugas persiapan yang sesuai dengan filter.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
