@extends('layouts.app', ['title' => 'Catat Termin: ' . $vendor->name])

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-[#8C5835]">
                    <span class="inline-flex items-center rounded-md border border-[#E7E4DF] bg-[#FAF8F5] px-2.5 py-0.5 text-xs font-medium text-[#8C5835]">Kwitansi</span>
                    <span>Pencatatan Termin Vendor</span>
                </div>
                <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-[#2B2520] sm:text-3xl">
                    Catat Termin: {{ $vendor->name }}
                </h1>
                <p class="text-xs text-[#6E665E] sm:text-sm mt-0.5">
                    Sisa tagihan kontrak: <strong class="text-[#9E352B] tabular-nums">{{ format_idr($vendor->remaining_balance) }}</strong>
                    dari total kontrak <span class="font-medium text-[#2B2520] tabular-nums">{{ format_idr($vendor->contract_amount) }}</span>.
                </p>
            </div>
            <div class="no-print">
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm" x-data="{
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
                    <label for="payment_stage" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Tahap Termin</label>
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
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="amount" class="block text-xs font-medium text-[#2B2520]">Nominal Pembayaran (Rp)</label>
                        <div class="flex items-center gap-1.5 text-xs text-[#6E665E]">
                            <span>Pilihan Cepat:</span>
                            <button
                                type="button"
                                @click="setAmount(Math.round(contract * 0.3), 'DP 30%')"
                                class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition cursor-pointer"
                            >
                                DP 30%
                            </button>
                            <button
                                type="button"
                                @click="setAmount(Math.round(contract * 0.5), 'Termin 50%')"
                                class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs font-medium text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition cursor-pointer"
                            >
                                Termin 50%
                            </button>
                            <button
                                type="button"
                                @click="setAmount(remaining, 'Pelunasan Akhir')"
                                class="rounded-md border border-[#F3D5D0] bg-[#FDF2F0] px-2 py-0.5 text-xs font-medium text-[#9E352B] hover:bg-[#FCEAE7] transition cursor-pointer"
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
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="paid_date" class="block text-xs font-medium text-[#2B2520]">Tanggal Bayar</label>
                            <div class="flex items-center gap-1 text-xs text-[#6E665E]">
                                <button
                                    type="button"
                                    @click="setDate('{{ now()->toDateString() }}')"
                                    class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition cursor-pointer"
                                >
                                    Hari Ini
                                </button>
                                <button
                                    type="button"
                                    @click="setDate('{{ now()->subDay()->toDateString() }}')"
                                    class="rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-xs text-[#6E665E] hover:bg-[#F5F2EB] hover:text-[#2B2520] transition cursor-pointer"
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
                        <label for="receipt_ref" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nomor Referensi Transfer / Kwitansi</label>
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
                    <label for="notes" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Catatan Khusus (Opsional)</label>
                    <input
                        id="notes"
                        type="text"
                        name="notes"
                        value="{{ old('notes') }}"
                        placeholder="Contoh: Sudah tanda tangan kwitansi basah"
                        class="editorial-input"
                    >
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F0ECE4]">
                    <a href="{{ route('vendors.index') }}" class="rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#2B2520] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        Simpan Bukti Kwitansi
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
