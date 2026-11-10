@extends('layouts.app', ['title' => 'Tambah Sesi Rundown'])

@section('content')
    <x-ui.section-header
        title="Tambah Sesi Rundown Hari-H"
        subtitle="Susun urutan acara, lokasi, dan PIC untuk hari pernikahan."
    >
        <x-slot:actions>
            <a href="{{ route('timeline.index', ['tab' => 'rundown']) }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ route('timeline.storeRundown') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="mb-1 block text-xs font-semibold text-charcoal">Jam Mulai (WIB)</label>
                    <input id="start_time" type="text" name="start_time" value="{{ old('start_time') }}" placeholder="Contoh: 08:00" required class="editorial-input">
                </div>
                <div>
                    <label for="end_time" class="mb-1 block text-xs font-semibold text-charcoal">Jam Selesai (WIB)</label>
                    <input id="end_time" type="text" name="end_time" value="{{ old('end_time') }}" placeholder="Contoh: 09:30" required class="editorial-input">
                </div>
            </div>

            <div>
                <label for="session_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Sesi Acara</label>
                <input id="session_name" type="text" name="session_name" value="{{ old('session_name') }}" placeholder="Contoh: Akad Nikah / Kirab Pengantin" required class="editorial-input">
            </div>

            <div>
                <label for="activity" class="mb-1 block text-xs font-semibold text-charcoal">Rincian Aktivitas</label>
                <textarea id="activity" name="activity" rows="3" placeholder="Deskripsi tahapan acara..." required class="editorial-input">{{ old('activity') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="location" class="mb-1 block text-xs font-semibold text-charcoal">Lokasi / Ruangan</label>
                    <input id="location" type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Pendopo Utama" class="editorial-input">
                </div>
                <div>
                    <label for="pic_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama PIC</label>
                    <input id="pic_name" type="text" name="pic_name" value="{{ old('pic_name') }}" placeholder="Contoh: Lead WO Irsyad" class="editorial-input">
                </div>
            </div>

            <div>
                <label for="pic_phone" class="mb-1 block text-xs font-semibold text-charcoal">Nomor Telepon PIC (WhatsApp)</label>
                <input id="pic_phone" type="text" name="pic_phone" value="{{ old('pic_phone') }}" placeholder="Contoh: 08123456789" class="editorial-input">
            </div>

            <div>
                <label for="notes" class="mb-1 block text-xs font-semibold text-charcoal">Catatan Khusus (Opsional)</label>
                <input id="notes" type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Gamelan standby 15 menit sebelum kirab" class="editorial-input">
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('timeline.index', ['tab' => 'rundown']) }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">Simpan Rundown</button>
            </div>
        </form>
    </section>
@endsection
