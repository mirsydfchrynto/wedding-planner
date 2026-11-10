@extends('layouts.app', ['title' => $item ? 'Edit Pos Anggaran' : 'Tambah Pos Anggaran'])

@section('content')
    <x-ui.section-header
        :title="$item ? 'Edit Pos Anggaran' : 'Tambah Pos Anggaran'"
        :subtitle="$item ? 'Perbarui rincian biaya estimasi, kontrak aktual, dan nominal terbayar.' : 'Catat rencana pengeluaran baru beserta estimasi, biaya kontrak aktual, dan vendor.'"
    >
        <x-slot:actions>
            <a href="{{ route('budget.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ $item ? route('budget.update', $item) : route('budget.store') }}" class="space-y-5">
            @csrf
            @if($item)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="category" class="mb-1 block text-xs font-semibold text-charcoal">Kategori Pengeluaran</label>
                    <select id="category" name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}" @selected(old('category', $item?->category?->value) === $category->value)>
                                {{ $category->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="vendor_id" class="mb-1 block text-xs font-semibold text-charcoal">Mitra Vendor (Opsional)</label>
                    <select id="vendor_id" name="vendor_id" class="editorial-input">
                        <option value="">-- Mandiri / Belum Ada Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected((string) old('vendor_id', $item?->vendor_id) === (string) $vendor->id)>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="title" class="mb-1 block text-xs font-semibold text-charcoal">Nama Komponen Biaya</label>
                <input id="title" type="text" name="title" value="{{ old('title', $item?->title) }}" placeholder="Contoh: Paket Dekorasi Lorong & Lighting Resepsi" required class="editorial-input">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="estimated_cost" class="mb-1 block text-xs font-semibold text-charcoal">Biaya Estimasi (Rp)</label>
                    <input id="estimated_cost" type="number" name="estimated_cost" value="{{ old('estimated_cost', $item?->estimated_cost ?? 15000000) }}" min="0" step="100000" data-rupiah required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="actual_cost" class="mb-1 block text-xs font-semibold text-charcoal">Biaya Aktual (Rp)</label>
                    <input id="actual_cost" type="number" name="actual_cost" value="{{ old('actual_cost', $item?->actual_cost ?? 0) }}" min="0" step="100000" data-rupiah @required($item) class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="paid_amount" class="mb-1 block text-xs font-semibold text-charcoal">Sudah Dibayar (Rp)</label>
                    <input id="paid_amount" type="number" name="paid_amount" value="{{ old('paid_amount', $item?->paid_amount ?? 0) }}" min="0" step="100000" data-rupiah @required($item) class="editorial-input tabular-nums">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="due_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Jatuh Tempo</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $item?->due_date?->format('Y-m-d')) }}" class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="notes" class="mb-1 block text-xs font-semibold text-charcoal">Catatan Tambahan</label>
                    <input id="notes" type="text" name="notes" value="{{ old('notes', $item?->notes) }}" placeholder="Keterangan termin pembayaran..." class="editorial-input">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('budget.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">
                    {{ $item ? 'Simpan Perubahan' : 'Simpan Komponen Anggaran' }}
                </button>
            </div>
        </form>
    </section>
@endsection
