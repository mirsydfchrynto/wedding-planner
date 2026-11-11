@extends('layouts.app', ['title' => $vendor ? 'Edit Vendor' : 'Tambah Vendor'])

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-[#8C5835]">
                    <span class="inline-flex items-center rounded-md border border-[#E7E4DF] bg-[#FAF8F5] px-2.5 py-0.5 text-xs font-medium text-[#8C5835]">Mitra</span>
                    <span>Direktori Vendor</span>
                </div>
                <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-[#2B2520] sm:text-3xl">
                    {{ $vendor ? 'Edit Mitra Vendor' : 'Tambah Mitra Vendor' }}
                </h1>
                <p class="text-xs text-[#6E665E] sm:text-sm mt-0.5">
                    Catat data vendor, nilai kontrak, dan status booking ke dalam direktori mitra.
                </p>
            </div>
            <div class="no-print">
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm">
            <form method="POST" action="{{ $vendor ? route('vendors.update', $vendor) : route('vendors.store') }}" class="space-y-5">
                @csrf
                @if($vendor)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nama Perusahaan / Vendor</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $vendor?->name) }}" placeholder="Contoh: Sovereign Wedding Lighting & Sound" required class="editorial-input">
                    </div>
                    <div>
                        <label for="category" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Kategori</label>
                        <select id="category" name="category" required class="editorial-input">
                            @foreach($categories as $category)
                                <option value="{{ $category->value }}" @selected(old('category', $vendor?->category?->value) === $category->value)>{{ $category->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="contact_person" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nama Penanggung Jawab</label>
                        <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person', $vendor?->contact_person) }}" placeholder="Nama penanggung jawab" required class="editorial-input">
                    </div>
                    <div>
                        <label for="phone" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nomor Telepon / WhatsApp</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $vendor?->phone) }}" placeholder="0812xxxxxxxx" required class="editorial-input tabular-nums">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Email (Opsional)</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $vendor?->email) }}" placeholder="contact@vendor.id" class="editorial-input">
                    </div>
                    <div>
                        <label for="instagram" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Instagram (Opsional)</label>
                        <input id="instagram" type="text" name="instagram" value="{{ old('instagram', $vendor?->instagram) }}" placeholder="@nama.vendor" class="editorial-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="contract_amount" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nilai Kontrak (Rp)</label>
                        <input id="contract_amount" type="number" name="contract_amount" value="{{ old('contract_amount', $vendor?->contract_amount ?? 25000000) }}" min="0" step="500000" data-rupiah required class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="paid_amount" class="mb-1.5 block text-xs font-medium text-[#2B2520]">DP / Terbayar (Rp)</label>
                        <input id="paid_amount" type="number" name="paid_amount" value="{{ old('paid_amount', $vendor?->paid_amount ?? 0) }}" min="0" step="500000" data-rupiah @required($vendor) class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="booking_status" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Status Booking</label>
                        <select id="booking_status" name="booking_status" required class="editorial-input">
                            @foreach($bookingStatuses as $status)
                                <option value="{{ $status->value }}" @selected(old('booking_status', $vendor?->booking_status?->value ?? 'booked') === $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="payment_due_date" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Tanggal Jatuh Tempo Pelunasan</label>
                        <input id="payment_due_date" type="date" name="payment_due_date" value="{{ old('payment_due_date', $vendor?->payment_due_date?->format('Y-m-d')) }}" class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="notes" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Catatan Kontrak / Deliverables</label>
                        <input id="notes" type="text" name="notes" value="{{ old('notes', $vendor?->notes) }}" class="editorial-input">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#F0ECE4] pt-4">
                    <a href="{{ route('vendors.index') }}" class="rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#2B2520] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        {{ $vendor ? 'Simpan Perubahan Vendor' : 'Simpan Vendor' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
