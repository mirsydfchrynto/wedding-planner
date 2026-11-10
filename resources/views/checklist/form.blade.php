@extends('layouts.app', ['title' => $task ? 'Edit Tugas' : 'Tambah Tugas'])

@section('content')
    <x-ui.section-header
        :title="$task ? 'Edit Tugas Checklist' : 'Tambah Tugas Checklist'"
        subtitle="Susun agenda persiapan pernikahan beserta jadwal, fase, dan PIC."
    >
        <x-slot:actions>
            <a href="{{ route('checklist.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ $task ? route('checklist.update', $task) : route('checklist.store') }}" class="space-y-5">
            @csrf
            @if($task)
                @method('PUT')
            @endif

            <div>
                <label for="title" class="mb-1 block text-xs font-semibold text-charcoal">Judul Tugas Persiapan</label>
                <input id="title" type="text" name="title" value="{{ old('title', $task?->title) }}" placeholder="Contoh: Pengambilan Bukti Pendaftaran KUA & Konfirmasi Saksi" required class="editorial-input">
            </div>

            <div>
                <label for="description" class="mb-1 block text-xs font-semibold text-charcoal">Deskripsi / Catatan Detail</label>
                <textarea id="description" name="description" rows="3" placeholder="Rincian berkas atau hal yang perlu disiapkan..." class="editorial-input">{{ old('description', $task?->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="category" class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select id="category" name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}" @selected(old('category', $task?->category?->value) === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="phase" class="mb-1 block text-xs font-semibold text-charcoal">Fase Linimasa</label>
                    <select id="phase" name="phase" required class="editorial-input">
                        @foreach($phases as $phase)
                            <option value="{{ $phase->value }}" @selected(old('phase', $task?->phase) === $phase->value)>{{ $phase->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="due_date" class="mb-1 block text-xs font-semibold text-charcoal">Tenggat Waktu</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $task?->due_date?->format('Y-m-d') ?? now()->addDays(14)->format('Y-m-d')) }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="status" class="mb-1 block text-xs font-semibold text-charcoal">Status</label>
                    <select id="status" name="status" required class="editorial-input">
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $task?->status?->value) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="mb-1 block text-xs font-semibold text-charcoal">Prioritas</label>
                    <select id="priority" name="priority" required class="editorial-input">
                        @foreach(['high' => 'Prioritas Tinggi', 'medium' => 'Penting', 'low' => 'Standar'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', $task?->priority ?? 'medium') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="assigned_to" class="mb-1 block text-xs font-semibold text-charcoal">Penanggung Jawab (PIC)</label>
                    <input id="assigned_to" type="text" name="assigned_to" value="{{ old('assigned_to', $task?->assigned_to ?? 'Aurelia & Reza') }}" required class="editorial-input">
                </div>
                <div>
                    <label for="vendor_id" class="mb-1 block text-xs font-semibold text-charcoal">Mitra Vendor Terkait (Opsional)</label>
                    <select id="vendor_id" name="vendor_id" class="editorial-input">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected((string) old('vendor_id', $task?->vendor_id) === (string) $vendor->id)>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-charcoal">
                <input type="checkbox" name="is_milestone" value="1" @checked(old('is_milestone', $task?->is_milestone)) class="rounded border-stone-line">
                <span>Tandai sebagai milestone penting</span>
            </label>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('checklist.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">{{ $task ? 'Simpan Perubahan' : 'Simpan Tugas' }}</button>
            </div>
        </form>
    </section>
@endsection
