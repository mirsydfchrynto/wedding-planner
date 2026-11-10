@extends('layouts.app', ['title' => 'Catat Termin: ' . $vendor->name])

@section('content')
    <x-ui.section-header
        :title="'Catat Termin: ' . $vendor->name"
        :subtitle="'Sisa tagihan saat ini: ' . format_idr($vendor->remaining_balance)"
    >
        <x-slot:actions>
            <a href="{{ route('vendors.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ route('vendors.storePayment', $vendor) }}" class="space-y-5">
            @csrf

            <div>
                <label for="payment_stage" class="mb-1 block text-xs font-semibold text-charcoal">Tahap Termin</label>
                <input id="payment_stage" type="text" name="payment_stage" value="{{ old('payment_stage') }}" placeholder="Contoh: DP 30% / Termin 2 / Pelunasan" required class="editorial-input">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="amount" class="mb-1 block text-xs font-semibold text-charcoal">Nominal Dibayar (Rp)</label>
                    <input id="amount" type="number" name="amount" value="{{ old('amount') }}" min="1000" data-rupiah required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="paid_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Bayar</label>
                    <input id="paid_date" type="date" name="paid_date" value="{{ old('paid_date', now()->toDateString()) }}" required class="editorial-input tabular-nums">
                </div>
            </div>

            <div>
                <label for="receipt_ref" class="mb-1 block text-xs font-semibold text-charcoal">Nomor Referensi Kwitansi / Transfer</label>
                <input id="receipt_ref" type="text" name="receipt_ref" value="{{ old('receipt_ref') }}" placeholder="Contoh: BCA-TRF-99018" class="editorial-input">
            </div>

            <div>
                <label for="notes" class="mb-1 block text-xs font-semibold text-charcoal">Catatan (Opsional)</label>
                <input id="notes" type="text" name="notes" value="{{ old('notes') }}" class="editorial-input">
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('vendors.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">Simpan Termin</button>
            </div>
        </form>
    </section>
@endsection
