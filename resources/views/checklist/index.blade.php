@extends('layouts.app', ['title' => 'Daftar Tugas'])

@section('content')
    <x-ui.section-header
        title="Daftar Tugas Persiapan"
        subtitle="Tugas diurutkan menurut tenggat. Centang untuk menandai selesai."
    >
        <x-slot:actions>
            <a href="{{ route('checklist.create') }}" class="editorial-btn-primary">
                + Tugas baru
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Angka pokok --}}
    <section aria-label="Ringkasan tugas" class="grid grid-cols-2 gap-x-8 gap-y-7 border-b border-stone-line py-7 lg:grid-cols-4">
        <x-ui.stat-card
            label="Selesai"
            :value="$summary['completed_tasks'] . ' / ' . $summary['total_tasks']"
            subtitle="Total tugas persiapan"
        />
        <x-ui.stat-card
            label="Dikerjakan"
            :value="$summary['in_progress_tasks'] . ' tugas'"
            subtitle="Sedang berjalan"
        />
        <x-ui.stat-card
            label="Belum mulai"
            :value="$summary['todo_tasks'] . ' tugas'"
            subtitle="Menunggu jadwal"
        />
        <x-ui.stat-card
            label="Terlambat"
            :value="$summary['overdue_tasks_count'] . ' tugas'"
            subtitle="Lewat tenggat"
            :badgeTone="$summary['overdue_tasks_count'] > 0 ? 'wine' : 'sage'"
        />
    </section>

    {{-- Filter --}}
    <section class="pt-7">
        <div class="flex flex-wrap items-baseline gap-x-5 gap-y-2 border-b border-stone-line pb-3">
            <a
                href="{{ route('checklist.index', ['status' => $selectedStatus, 'assigned_to' => $selectedPic, 'search' => $selectedSearch, 'priority' => $selectedPriority]) }}"
                class="text-sm font-semibold {{ !$selectedCategory ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso' }}"
            >
                Semua kategori
            </a>
            @foreach($categories as $category)
                @php $activeCat = $selectedCategory === $category->value; @endphp
                <a
                    href="{{ route('checklist.index', ['category' => $category->value, 'status' => $selectedStatus, 'assigned_to' => $selectedPic, 'search' => $selectedSearch, 'priority' => $selectedPriority]) }}"
                    class="text-sm font-medium {{ $activeCat ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso' }}"
                >
                    {{ $category->shortLabel() }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('checklist.index') }}" class="flex flex-wrap items-center gap-2 border-b border-stone-line py-4 no-print">
            @if($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif

            <input
                type="search"
                name="search"
                value="{{ $selectedSearch }}"
                placeholder="Cari judul tugas..."
                aria-label="Cari judul tugas"
                class="editorial-input !w-full sm:!w-56 !py-1.5 !text-xs"
            >

            <select name="priority" aria-label="Filter prioritas" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                <option value="">Semua prioritas</option>
                <option value="high" @selected($selectedPriority === 'high')>Tinggi</option>
                <option value="medium" @selected($selectedPriority === 'medium')>Penting</option>
                <option value="low" @selected($selectedPriority === 'low')>Standar</option>
            </select>

            <select name="status" aria-label="Filter status" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                <option value="">Semua status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="assigned_to" aria-label="Filter penanggung jawab" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                <option value="">Semua PIC</option>
                @foreach($assignees as $pic)
                    <option value="{{ $pic }}" @selected($selectedPic === $pic)>{{ $pic }}</option>
                @endforeach
            </select>

            <button type="submit" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">Cari</button>

            @if($selectedCategory || $selectedStatus || $selectedPic || $selectedSearch !== '' || $selectedPriority !== '')
                <a href="{{ route('checklist.index') }}" class="text-xs font-semibold text-champagne hover:underline">Reset</a>
            @endif
        </form>

        {{-- Daftar tugas --}}
        <ul class="divide-y divide-stone-line/70">
            @forelse($tasks as $task)
                @php $isDone = $task->status->value === 'done'; @endphp
                <li class="flex flex-col justify-between gap-4 py-4 sm:flex-row sm:items-center">
                    <div class="flex items-start gap-3.5">
                        <form method="POST" action="{{ route('checklist.toggle', $task) }}" class="mt-0.5">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="flex h-5 w-5 items-center justify-center border transition {{ $isDone ? 'border-sage bg-sage text-pearl' : 'border-stone-strong bg-transparent text-transparent hover:border-espresso' }}"
                                title="Ubah status selesai"
                            >
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                </svg>
                            </button>
                        </form>

                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold {{ $isDone ? 'text-taupe line-through' : 'text-espresso' }}">
                                {{ $task->title }}
                                @if($task->is_milestone)
                                    <span class="ml-1 text-xs font-semibold text-champagne">· tonggak utama</span>
                                @endif
                            </h3>

                            @if($task->description)
                                <p class="mt-1 text-xs text-taupe">{{ $task->description }}</p>
                            @endif

                            <p class="mt-1.5 text-xs text-taupe">
                                {{ $task->category->label() }}
                                · {{ $task->phase }}
                                · PIC {{ $task->assigned_to }}
                                @if($task->vendor)
                                    · {{ $task->vendor->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pl-9 sm:justify-end sm:pl-0">
                        <span class="text-xs tabular-nums {{ $task->is_overdue ? 'font-semibold text-wine' : 'text-taupe' }}">
                            {{ $task->due_date->format('d M Y') }}
                        </span>
                        <x-ui.status-badge :tone="$task->priority_tone" :label="$task->priority_label" />
                        <x-ui.status-badge :tone="$task->status->badgeTone()" :label="$task->status->label()" />

                        <div class="flex items-center gap-3 no-print">
                            <a href="{{ route('checklist.edit', $task) }}" class="text-xs font-semibold text-champagne hover:underline">Ubah</a>
                            <form method="POST" action="{{ route('checklist.destroy', $task) }}" onsubmit="return confirm('Hapus tugas persiapan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-wine hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-10 text-sm text-taupe">Tidak ada tugas yang cocok dengan filter ini.</li>
            @endforelse
        </ul>
    </section>
@endsection
