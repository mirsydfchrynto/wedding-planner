@extends('layouts.app', ['title' => $vendor ? 'Edit Vendor' : 'Tambah Vendor'])

@section('content')
    <x-ui.section-header
        :title="$vendor ? 'Edit Mitra Vendor' : 'Tambah Mitra Vendor'"
        subtitle="Catat data vendor, nilai kontrak, dan status booking ke dalam direktori mitra."
    >
        <x-slot:actions>
            <a href="{{ route('vendors.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ $vendor ? route('vendors.update', $vendor) : route('vendors.store') }}" class="space-y-5">
            @csrf
            @if($vendor)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Perusahaan / Vendor</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $vendor?->name) }}" placeholder="Contoh: Sovereign Wedding Lighting & Sound" required class="editorial-input">
                </div>
                <div>
                    <label for="category" class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select id="category" name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}" @selected(old('category', $vendor?->category?->value) === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="contact_person" class="mb-1 block text-xs font-semibold text-charcoal">Nama Penanggung Jawab</label>
                    <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person', $vendor?->contact_person) }}" placeholder="Nama penanggung jawab" required class="editorial-input">
                </div>
                <div>
                    <label for="phone" class="mb-1 block text-xs font-semibold text-charcoal">Nomor Telepon / WhatsApp</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $vendor?->phone) }}" placeholder="0812xxxxxxxx" required class="editorial-input tabular-nums">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="email" class="mb-1 block text-xs font-semibold text-charcoal">Email (Opsional)</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $vendor?->email) }}" placeholder="contact@vendor.id" class="editorial-input">
                </div>
                <div>
                    <label for="instagram" class="mb-1 block text-xs font-semibold text-charcoal">Instagram (Opsional)</label>
                    <input id="instagram" type="text" name="instagram" value="{{ old('instagram', $vendor?->instagram) }}" placeholder="@nama.vendor" class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="contract_amount" class="mb-1 block text-xs font-semibold text-charcoal">Nilai Kontrak (Rp)</label>
                    <input id="contract_amount" type="number" name="contract_amount" value="{{ old('contract_amount', $vendor?->contract_amount ?? 25000000) }}" min="0" step="500000" data-rupiah required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="paid_amount" class="mb-1 block text-xs font-semibold text-charcoal">DP / Terbayar (Rp)</label>
                    <input id="paid_amount" type="number" name="paid_amount" value="{{ old('paid_amount', $vendor?->paid_amount ?? 0) }}" min="0" step="500000" data-rupiah @required($vendor) class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="booking_status" class="mb-1 block text-xs font-semibold text-charcoal">Status Booking</label>
                    <select id="booking_status" name="booking_status" required class="editorial-input">
                        @foreach($bookingStatuses as $status)
                            <option value="{{ $status->value }}" @selected(old('booking_status', $vendor?->booking_status?->value ?? 'booked') === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="payment_due_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Jatuh Tempo Pelunasan</label>
                    <input id="payment_due_date" type="date" name="payment_due_date" value="{{ old('payment_due_date', $vendor?->payment_due_date?->format('Y-m-d')) }}" class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="notes" class="mb-1 block text-xs font-semibold text-charcoal">Catatan Kontrak / Deliverables</label>
                    <input id="notes" type="text" name="notes" value="{{ old('notes', $vendor?->notes) }}" class="editorial-input">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('vendors.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">{{ $vendor ? 'Simpan Perubahan Vendor' : 'Simpan Vendor' }}</button>
            </div>
        </form>
    </section>
@endsection
