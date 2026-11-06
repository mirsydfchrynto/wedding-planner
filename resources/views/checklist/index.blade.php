@extends('layouts.app', ['title' => 'Wedding Checklist'])

@section('content')
    <x-ui.section-header
        title="Checklist Persiapan"
    >
        <x-slot:actions>
            <button
                type="button"
                @click="$dispatch('open-modal', 'create-checklist-modal')"
                class="editorial-btn-primary"
            >
                + Tambah Tugas Baru
            </button>
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
                            <button
                                type="button"
                                @click="$dispatch('open-modal', 'edit-task-{{ $task->id }}')"
                                class="text-xs font-semibold text-champagne hover:underline"
                            >
                                Edit
                            </button>
                            <form method="POST" action="{{ route('checklist.destroy', $task) }}" onsubmit="return confirm('Hapus tugas persiapan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-wine hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>

                        {{-- Modal: Edit Checklist Task --}}
                        <x-ui.modal name="edit-task-{{ $task->id }}" :title="'Edit Tugas: ' . $task->title" subtitle="Perbarui status, tenggat waktu, kategori, atau penanggung jawab">
                            <form method="POST" action="{{ route('checklist.update', $task) }}" class="space-y-4 text-left">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-charcoal">Judul Tugas Persiapan</label>
                                    <input type="text" name="title" value="{{ $task->title }}" required class="editorial-input">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-charcoal">Deskripsi / Catatan Koordinasi</label>
                                    <textarea name="description" rows="2" class="editorial-input">{{ $task->description }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                                        <select name="category" required class="editorial-input">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->value }}" @selected($task->category === $category)>
                                                    {{ $category->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Fase Linimasa</label>
                                        <select name="phase" required class="editorial-input">
                                            @foreach($phases as $phase)
                                                <option value="{{ $phase->value }}" @selected($task->phase === $phase->value)>
                                                    {{ $phase->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- deslop-ignore-next-line 28 --}}
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Tenggat Waktu</label>
                                        <input type="date" name="due_date" value="{{ $task->due_date->format('Y-m-d') }}" required class="editorial-input tabular-nums">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Status</label>
                                        <select name="status" required class="editorial-input">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->value }}" @selected($task->status === $status)>
                                                    {{ $status->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Prioritas</label>
                                        <select name="priority" required class="editorial-input">
                                            <option value="high" @selected($task->priority === 'high')>Prioritas Tinggi</option>
                                            <option value="medium" @selected($task->priority === 'medium')>Penting</option>
                                            <option value="low" @selected($task->priority === 'low')>Standar</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Penanggung Jawab (PIC)</label>
                                        <input type="text" name="assigned_to" value="{{ $task->assigned_to }}" required class="editorial-input">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Mitra Vendor Terkait</label>
                                        <select name="vendor_id" class="editorial-input">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}" @selected($task->vendor_id === $vendor->id)>
                                                    {{ $vendor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <label class="inline-flex items-center gap-2 text-xs font-medium text-charcoal">
                                    <input type="checkbox" name="is_milestone" value="1" @checked($task->is_milestone)>
                                    Tandai sebagai Milestone Utama pada Linimasa
                                </label>

                                <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                                    <button type="button" @click="$dispatch('close-modal', 'edit-task-{{ $task->id }}')" class="editorial-btn-secondary">
                                        Batal
                                    </button>
                                    <button type="submit" class="editorial-btn-primary">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </x-ui.modal>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center">
                    <p class="text-sm text-taupe">Tidak ada tugas persiapan yang sesuai dengan filter.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Modal: Create New Checklist Task --}}
    <x-ui.modal name="create-checklist-modal" title="Tambah Tugas Checklist Baru" subtitle="Susun agenda persiapan pernikahan baru beserta jadwal dan PIC">
        <form method="POST" action="{{ route('checklist.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Judul Tugas Persiapan</label>
                <input type="text" name="title" placeholder="Contoh: Pengambilan Bukti Pendaftaran KUA & Konfirmasi Saksi" required class="editorial-input">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Deskripsi / Catatan Detail</label>
                <textarea name="description" rows="2" placeholder="Rincian berkas atau hal yang perlu disiapkan..." class="editorial-input"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}">{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Fase Linimasa</label>
                    <select name="phase" required class="editorial-input">
                        @foreach($phases as $phase)
                            <option value="{{ $phase->value }}">{{ $phase->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- deslop-ignore-next-line 28 --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Tenggat Waktu</label>
                    <input type="date" name="due_date" value="{{ now()->addDays(14)->format('Y-m-d') }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Status Awal</label>
                    <select name="status" required class="editorial-input">
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Prioritas</label>
                    <select name="priority" required class="editorial-input">
                        <option value="high">Prioritas Tinggi</option>
                        <option value="medium" selected>Penting</option>
                        <option value="low">Standar</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Penanggung Jawab (PIC)</label>
                    <input type="text" name="assigned_to" value="Aurelia & Reza" required class="editorial-input">
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
            </div>

            <label class="inline-flex items-center gap-2 text-xs font-medium text-charcoal">
                <input type="checkbox" name="is_milestone" value="1" checked>
                Tampilkan sebagai Milestone Utama pada Peta Linimasa
            </label>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'create-checklist-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Tugas Baru
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
