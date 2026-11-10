@extends('layouts.app', ['title' => 'Jadwalkan Milestone'])

@section('content')
    <x-ui.section-header
        title="Jadwalkan Milestone Linimasa"
        subtitle="Tambahkan agenda penting ke dalam peta persiapan H-12 Bulan hingga Hari-H."
    >
        <x-slot:actions>
            <a href="{{ route('timeline.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ route('timeline.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="mb-1 block text-xs font-semibold text-charcoal">Nama Agenda / Milestone</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Gladi Bersih Prosesi Kirab & Soundcheck Quartet" required class="editorial-input">
            </div>

            <div>
                <label for="description" class="mb-1 block text-xs font-semibold text-charcoal">Keterangan Pelaksanaan</label>
                <textarea id="description" name="description" rows="3" placeholder="Catatan waktu dan koordinasi pihak terkait..." class="editorial-input">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="phase" class="mb-1 block text-xs font-semibold text-charcoal">Fase Persiapan</label>
                    <select id="phase" name="phase" required class="editorial-input">
                        @foreach($phases as $phase)
                            <option value="{{ $phase->value }}" @selected(old('phase') === $phase->value)>{{ $phase->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="category" class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select id="category" name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}" @selected(old('category') === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="due_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Target</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', now()->addDays(21)->format('Y-m-d')) }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="priority" class="mb-1 block text-xs font-semibold text-charcoal">Prioritas</label>
                    <select id="priority" name="priority" required class="editorial-input">
                        @foreach(['high' => 'Prioritas Tinggi', 'medium' => 'Penting', 'low' => 'Standar'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', 'high') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="assigned_to" class="mb-1 block text-xs font-semibold text-charcoal">PIC</label>
                    <input id="assigned_to" type="text" name="assigned_to" value="{{ old('assigned_to', 'WO & Pasangan') }}" required class="editorial-input">
                </div>
            </div>

            <div>
                <label for="vendor_id" class="mb-1 block text-xs font-semibold text-charcoal">Mitra Vendor Terkait (Opsional)</label>
                <select id="vendor_id" name="vendor_id" class="editorial-input">
                    <option value="">-- Tidak Ada --</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" @selected((string) old('vendor_id') === (string) $vendor->id)>{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="is_milestone" value="1">

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('timeline.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">Simpan ke Linimasa</button>
            </div>
        </form>
    </section>
@endsection
