@extends('layouts.app', ['title' => $guest ? 'Edit Tamu' : 'Tambah Tamu'])

@section('content')
    <x-ui.section-header
        :title="$guest ? 'Edit Tamu Undangan' : 'Tambah Tamu Undangan'"
        subtitle="Catat data tamu, kategori, jumlah pax, dan status RSVP."
    >
        <x-slot:actions>
            <a href="{{ route('guests.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ $guest ? route('guests.update', $guest) : route('guests.store') }}" class="space-y-5">
            @csrf
            @if($guest)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Tamu</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $guest?->name) }}" required class="editorial-input">
                </div>
                <div>
                    <label for="category" class="mb-1 block text-xs font-semibold text-charcoal">Kategori Tamu</label>
                    <select id="category" name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(old('category', $guest?->category) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="pax" class="mb-1 block text-xs font-semibold text-charcoal">Jumlah Pax</label>
                    <input id="pax" type="number" name="pax" value="{{ old('pax', $guest?->pax ?? 2) }}" min="1" max="20" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="session" class="mb-1 block text-xs font-semibold text-charcoal">Sesi Acara</label>
                    <input id="session" type="text" name="session" value="{{ old('session', $guest?->session ?? 'Resepsi Malam') }}" required class="editorial-input">
                </div>
                <div>
                    <label for="rsvp_status" class="mb-1 block text-xs font-semibold text-charcoal">Status RSVP</label>
                    <select id="rsvp_status" name="rsvp_status" required class="editorial-input">
                        @foreach(['pending' => 'Belum Konfirmasi', 'confirmed' => 'Hadir', 'declined' => 'Tidak Hadir'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('rsvp_status', $guest?->rsvp_status ?? 'pending') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="table_number" class="mb-1 block text-xs font-semibold text-charcoal">Nomor Meja</label>
                    <input id="table_number" type="text" name="table_number" value="{{ old('table_number', $guest?->table_number) }}" class="editorial-input">
                </div>
                <div>
                    <label for="whatsapp_number" class="mb-1 block text-xs font-semibold text-charcoal">Nomor WhatsApp</label>
                    <input id="whatsapp_number" type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $guest?->whatsapp_number) }}" class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="notes" class="mb-1 block text-xs font-semibold text-charcoal">Catatan</label>
                    <input id="notes" type="text" name="notes" value="{{ old('notes', $guest?->notes) }}" class="editorial-input">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('guests.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">{{ $guest ? 'Simpan Perubahan' : 'Simpan Tamu' }}</button>
            </div>
        </form>
    </section>
@endsection
