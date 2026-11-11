@extends('layouts.app', ['title' => $item ? 'Edit Pos Anggaran' : 'Tambah Pos Anggaran'])

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-[#8C5835]">
                    <span class="inline-flex items-center rounded-md border border-[#E7E4DF] bg-[#FAF8F5] px-2 py-0.5 text-xs font-medium text-[#8C5835]">Catatan Kas</span>
                    <span>Komponen Anggaran</span>
                </div>
                <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-[#2B2520] sm:text-3xl">
                    {{ $item ? 'Edit Pos Anggaran' : 'Tambah Pos Anggaran Baru' }}
                </h1>
                <p class="text-xs text-[#6E665E] sm:text-sm mt-0.5">
                    Catat rincian komponen biaya, acuan riset estimasi, dan vendor terpilih.
                </p>
            </div>
            <div class="no-print">
                <a href="{{ route('budget.index') }}" class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    &larr; Kembali ke Buku Kas
                </a>
            </div>
        </div>

        <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ $item ? route('budget.update', $item) : route('budget.store') }}"
                class="space-y-5"
                x-data="{
                    templates: @js($templates),
                    guests: {{ (int) $guestTarget }},
                    category: @js(old('category', $item?->category?->value ?? $categories[0]->value)),
                    title: @js(old('title', $item?->title ?? '')),
                    fillEstimate() {
                        const row = (this.templates[this.category] || []).find(r => r.title === this.title);
                        if (!row) return;
                        this.$refs.estimated.value = row.min * (row.per_guest ? this.guests : 1);
                        this.$refs.estimated.dispatchEvent(new Event('input', { bubbles: true }));
                    },
                    setEstimate(val) {
                        this.$refs.estimated.value = val;
                        this.$refs.estimated.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }"
            >
                @csrf
                @if($item)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="category" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Kategori Pengeluaran</label>
                        <select id="category" name="category" required class="editorial-input" x-model="category">
                            @foreach($categories as $category)
                                <option value="{{ $category->value }}" @selected(old('category', $item?->category?->value) === $category->value)>
                                    {{ $category->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="vendor_id" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Mitra Vendor (Opsional)</label>
                        <select id="vendor_id" name="vendor_id" class="editorial-input">
                            <option value="">-- Mandiri / Belum Ada Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected((string) old('vendor_id', $item?->vendor_id) === (string) $vendor->id)>
                                    {{ $vendor->name }} ({{ $vendor->category->shortLabel() }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="title" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nama Komponen Biaya</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $item?->title) }}" list="budget-titles" placeholder="Contoh: Paket katering prasmanan" required class="editorial-input" x-model="title" @change="fillEstimate()">
                    <datalist id="budget-titles">
                        <template x-for="row in (templates[category] || [])" :key="row.title">
                            <option :value="row.title"></option>
                        </template>
                    </datalist>
                    <p class="mt-1 text-xs text-[#6E665E]">Pilih nama untuk mengisi estimasi otomatis riset pasar.</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="estimated_cost" class="block text-xs font-medium text-[#2B2520]">Biaya Estimasi Riset (Rp)</label>
                        <div class="flex items-center gap-1.5 text-xs text-[#6E665E]">
                            <span>Pilihan Cepat:</span>
                            <button type="button" @click="setEstimate(5000000)" class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition">5Jt</button>
                            <button type="button" @click="setEstimate(15000000)" class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition">15Jt</button>
                            <button type="button" @click="setEstimate(30000000)" class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition">30Jt</button>
                            <button type="button" @click="setEstimate(60000000)" class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition">60Jt</button>
                            <button type="button" @click="setEstimate(100000000)" class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition">100Jt</button>
                        </div>
                    </div>
                    <input id="estimated_cost" type="number" name="estimated_cost" x-ref="estimated" value="{{ old('estimated_cost', $item?->estimated_cost) }}" min="0" step="100000" data-rupiah required class="editorial-input tabular-nums">
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="actual_cost" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nilai Kontrak Deal (Rp)</label>
                        <input id="actual_cost" type="number" name="actual_cost" value="{{ old('actual_cost', $item?->actual_cost ?? 0) }}" min="0" step="100000" data-rupiah @required($item) class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="paid_amount" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Sudah Terbayar / DP (Rp)</label>
                        <input id="paid_amount" type="number" name="paid_amount" value="{{ old('paid_amount', $item?->paid_amount ?? 0) }}" min="0" step="100000" data-rupiah @required($item) class="editorial-input tabular-nums">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="due_date" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Tanggal Jatuh Tempo</label>
                        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $item?->due_date?->format('Y-m-d')) }}" class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="notes" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Catatan Termin / Deliverables</label>
                        <input id="notes" type="text" name="notes" value="{{ old('notes', $item?->notes) }}" placeholder="Keterangan rincian kontrak..." class="editorial-input">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#F0ECE4] pt-4">
                    <a href="{{ route('budget.index') }}" class="rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#2B2520] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        {{ $item ? 'Simpan Perubahan Pos' : 'Simpan Pos Anggaran' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
