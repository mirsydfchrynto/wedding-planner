@extends('layouts.app', ['title' => $task ? 'Edit Tugas' : 'Tambah Tugas'])

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-[#8C5835]">
                    <span class="inline-flex items-center rounded-md border border-[#E7E4DF] bg-[#FAF8F5] px-2.5 py-0.5 text-xs font-medium text-[#8C5835]">Checklist</span>
                    <span>Pengelolaan Tugas</span>
                </div>
                <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-[#2B2520] sm:text-3xl">
                    {{ $task ? 'Edit Tugas Checklist' : 'Tambah Tugas Checklist' }}
                </h1>
                <p class="text-xs text-[#6E665E] sm:text-sm mt-0.5">
                    Susun agenda persiapan pernikahan beserta jadwal, fase, dan penanggung jawab.
                </p>
            </div>
            <div class="no-print">
                <a href="{{ route('checklist.index') }}" class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    &larr; Kembali ke Checklist
                </a>
            </div>
        </div>

        <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ $task ? route('checklist.update', $task) : route('checklist.store') }}"
                class="space-y-5"
                x-data="{
                    templates: @js($templates),
                    pick() {
                        const tpl = this.templates.find(t => t.title === this.$refs.title.value);
                        if (!tpl) return;
                        if (!this.$refs.description.value) this.$refs.description.value = tpl.description;
                        this.$refs.category.value = tpl.category;
                        this.$refs.phase.value = tpl.phase;
                    }
                }"
            >
                @csrf
                @if($task)
                    @method('PUT')
                @endif

                <div>
                    <label for="title" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Judul Tugas Persiapan</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $task?->title) }}" list="task-templates" placeholder="Contoh: Konfirmasi tanggal akad dengan KUA" required class="editorial-input" x-ref="title" @change="pick()">
                    <datalist id="task-templates">
                        @foreach($templates as $template)
                            <option value="{{ $template['title'] }}"></option>
                        @endforeach
                    </datalist>
                    <p class="mt-1.5 text-xs text-[#6E665E]">Pilih judul dari daftar untuk mengisi deskripsi, kategori, dan tahap otomatis.</p>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Deskripsi / Catatan Detail</label>
                    <textarea id="description" name="description" rows="3" placeholder="Rincian berkas atau hal yang perlu disiapkan..." class="editorial-input" x-ref="description">{{ old('description', $task?->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="category" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Kategori</label>
                        <select id="category" name="category" required class="editorial-input" x-ref="category">
                            @foreach($categories as $category)
                                <option value="{{ $category->value }}" @selected(old('category', $task?->category?->value) === $category->value)>{{ $category->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="phase" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Tahap Persiapan</label>
                        <select id="phase" name="phase" required class="editorial-input" x-ref="phase">
                            @foreach($phases as $phase)
                                <option value="{{ $phase->value }}" @selected(old('phase', $task?->phase) === $phase->value)>{{ $phase->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="due_date" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Tenggat Waktu</label>
                        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $task?->due_date?->format('Y-m-d') ?? now()->addDays(14)->format('Y-m-d')) }}" required class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="status" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Status</label>
                        <select id="status" name="status" required class="editorial-input">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $task?->status?->value) === $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Prioritas</label>
                        <select id="priority" name="priority" required class="editorial-input">
                            @foreach(['high' => 'Prioritas Tinggi', 'medium' => 'Penting', 'low' => 'Standar'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', $task?->priority ?? 'medium') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="assigned_to" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Penanggung Jawab (PIC)</label>
                        <input id="assigned_to" type="text" name="assigned_to" list="pic-presets" value="{{ old('assigned_to', $task?->assigned_to ?? 'Pengantin Berdua') }}" required class="editorial-input">
                        <datalist id="pic-presets">
                            <option value="Pengantin Berdua"></option>
                            <option value="Mempelai Pria"></option>
                            <option value="Mempelai Wanita"></option>
                            <option value="Keluarga Inti"></option>
                            <option value="Wedding Organizer"></option>
                        </datalist>
                    <div>
                        <label for="vendor_id" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Mitra Vendor Terkait (Opsional)</label>
                        <select id="vendor_id" name="vendor_id" class="editorial-input">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected((string) old('vendor_id', $task?->vendor_id) === (string) $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-xs font-medium text-[#2B2520] cursor-pointer">
                    <input type="checkbox" name="is_milestone" value="1" @checked(old('is_milestone', $task?->is_milestone)) class="rounded border-[#E7E4DF] text-[#8C5835] focus:ring-0">
                    <span>Tandai sebagai milestone penting</span>
                </label>

                <div class="flex items-center justify-end gap-3 border-t border-[#F0ECE4] pt-4">
                    <a href="{{ route('checklist.index') }}" class="rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#2B2520] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        {{ $task ? 'Simpan Perubahan' : 'Simpan Tugas' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
