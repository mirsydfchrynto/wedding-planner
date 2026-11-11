@extends('layouts.app', ['title' => 'Catat Termin: ' . $vendor->name])

@section('content')
    <div class="flex flex-col gap-2 border-b border-sketch pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-soga">
                <span class="stamp">Kwitansi Pembayaran</span>
                <span>Pencatatan Termin Vendor</span>
            </div>
            <h1 class="mt-1 font-display text-2xl font-bold tracking-tight text-espresso sm:text-4xl">
                Catat Termin: {{ $vendor->name }}
            </h1>
            <p class="text-xs text-taupe sm:text-sm">
                Sisa tagihan kontrak saat ini: <strong class="rupiah text-wine">{{ format_idr($vendor->remaining_balance) }}</strong>
                dari total kontrak <span class="rupiah text-espresso font-semibold">{{ format_idr($vendor->contract_amount) }}</span>.
            </p>
        </div>
        <div class="no-print">
            <a href="{{ route('vendors.index') }}" class="editorial-btn-secondary !py-1.5 !px-3.5 !text-xs">
                &larr; Kembali ke Kwitansi Vendor
            </a>
        </div>
    </div>

    <section class="mt-6 paper-card border border-sketch p-6" x-data="{
        remaining: {{ (int) $vendor->remaining_balance }},
        contract: {{ (int) $vendor->contract_amount }},
        setAmount(val, stageName) {
            this.$refs.amountInput.value = val;
            this.$refs.amountInput.dispatchEvent(new Event('input', { bubbles: true }));
            if (stageName) {
                this.$refs.stageInput.value = stageName;
            }
        },
        setDate(d) {
            this.$refs.dateInput.value = d;
        }
    }">
        <form method="POST" action="{{ route('vendors.storePayment', $vendor) }}" class="space-y-5">
            @csrf

            <div>
                <label for="payment_stage" class="mb-1 block text-xs font-bold text-charcoal uppercase tracking-wider">Tahap Termin</label>
                <input
                    id="payment_stage"
                    type="text"
                    name="payment_stage"
                    x-ref="stageInput"
                    value="{{ old('payment_stage', 'Termin Pembayaran') }}"
                    placeholder="Contoh: DP 30% / Termin 2 / Pelunasan"
                    required
                    class="editorial-input"
                >
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="amount" class="block text-xs font-bold text-charcoal uppercase tracking-wider">Nominal Pembayaran (Rp)</label>
                    <div class="flex items-center gap-1.5 text-[0.68rem] text-taupe">
                        <span>Pilihan Cepat:</span>
                        <button
                            type="button"
                            @click="setAmount(Math.round(contract * 0.3), 'DP 30%')"
                            class="chip !min-h-0 !py-0.5 !px-1.5 cursor-pointer"
                        >
                            DP 30%
                        </button>
                        <button
                            type="button"
                            @click="setAmount(Math.round(contract * 0.5), 'Termin 50%')"
                            class="chip !min-h-0 !py-0.5 !px-1.5 cursor-pointer"
                        >
                            Termin 50%
                        </button>
                        <button
                            type="button"
                            @click="setAmount(remaining, 'Pelunasan Akhir')"
                            class="chip !min-h-0 !py-0.5 !px-1.5 cursor-pointer text-wine font-bold"
                        >
                            Pelunasan Sisa
                        </button>
                    </div>
                </div>
                <input
                    id="amount"
                    type="number"
                    name="amount"
                    x-ref="amountInput"
                    value="{{ old('amount', min($vendor->remaining_balance, round($vendor->contract_amount * 0.3))) }}"
                    min="1000"
                    data-rupiah
                    required
                    class="editorial-input tabular-nums"
                >
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="paid_date" class="block text-xs font-bold text-charcoal uppercase tracking-wider">Tanggal Bayar</label>
                        <div class="flex items-center gap-1 text-[0.68rem] text-taupe">
                            <button
                                type="button"
                                @click="setDate('{{ now()->toDateString() }}')"
                                class="chip !min-h-0 !py-0.5 !px-1.5 cursor-pointer"
                            >
                                Hari Ini
                            </button>
                            <button
                                type="button"
                                @click="setDate('{{ now()->subDay()->toDateString() }}')"
                                class="chip !min-h-0 !py-0.5 !px-1.5 cursor-pointer"
                            >
                                Kemarin
                            </button>
                        </div>
                    </div>
                    <input
                        id="paid_date"
                        type="date"
                        name="paid_date"
                        x-ref="dateInput"
                        value="{{ old('paid_date', now()->toDateString()) }}"
                        required
                        class="editorial-input tabular-nums"
                    >
                </div>
                <div>
                    <label for="receipt_ref" class="mb-1 block text-xs font-bold text-charcoal uppercase tracking-wider">Nomor Referensi Transfer / Kwitansi</label>
                    <input
                        id="receipt_ref"
                        type="text"
                        name="receipt_ref"
                        value="{{ old('receipt_ref') }}"
                        placeholder="Contoh: TRF-BCA-98102"
                        class="editorial-input"
                    >
                </div>
            </div>

            <div>
                <label for="notes" class="mb-1 block text-xs font-bold text-charcoal uppercase tracking-wider">Catatan Khusus (Opsional)</label>
                <input
                    id="notes"
                    type="text"
                    name="notes"
                    value="{{ old('notes') }}"
                    placeholder="Contoh: Sudah tanda tangan kwitansi basah"
                    class="editorial-input"
                >
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-sketch">
                <a href="{{ route('vendors.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Bukti Kwitansi
                </button>
            </div>
        </form>
    </section>
@endsection
