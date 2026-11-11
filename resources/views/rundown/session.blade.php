@extends('layouts.app', ['title' => 'Tambah Sesi Acara'])

@section('content')
    <x-ui.section-header
        title="Tambah Sesi Acara"
        subtitle="Satu sesi, satu baris. Isi jam mulai dan durasi, jam selesai dihitung otomatis."
    >
        <x-slot:actions>
            <a href="{{ route('rundown.index', ['tab' => 'hari']) }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form
            method="POST"
            action="{{ route('rundown.sessions.store') }}"
            class="space-y-5"
            x-data="{
                sessions: {
                    'Akad Nikah': { activity: 'Ijab kabul, penyerahan mahar, dan doa restu keluarga inti.', location: 'Pendopo utama' },
                    'Kirab Pengantin': { activity: 'Prosesi masuk pengantin, diiringi keluarga dan gamelan.', location: 'Aisle utama' },
                    'Resepsi': { activity: 'Sambutan tamu, foto keluarga, dan jamuan.', location: 'Ballroom' },
                    'Makan Malam Keluarga': { activity: 'Jamuan keluarga inti dan sesi foto bersama.', location: 'Ruang privat' },
                    'Hiburan': { activity: 'Penampilan musik akustik dan pengisi acara.', location: 'Panggung utama' }
                },
                pick() {
                    const tpl = this.sessions[$refs.session.value];
                    if (!tpl) return;
                    if (!$refs.activity.value) $refs.activity.value = tpl.activity;
                    if (!$refs.location.value) $refs.location.value = tpl.location;
                }
            }"
        >
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="session_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Sesi Acara</label>
                    <input id="session_name" type="text" name="session_name" value="{{ old('session_name') }}" list="session-templates" placeholder="Contoh: Akad Nikah" required class="editorial-input" x-ref="session" @change="pick()" @keyup="pick()">
                    <datalist id="session-templates">
                        <option value="Akad Nikah"></option>
                        <option value="Kirab Pengantin"></option>
                        <option value="Resepsi"></option>
                        <option value="Makan Malam Keluarga"></option>
                        <option value="Hiburan"></option>
                    </datalist>
                </div>
                <div>
                    <label for="location" class="mb-1 block text-xs font-semibold text-charcoal">Lokasi / Ruangan</label>
                    <input id="location" type="text" name="location" value="{{ old('location') }}" list="location-templates" placeholder="Contoh: Pendopo utama" class="editorial-input" x-ref="location">
                    <datalist id="location-templates">
                        <option value="Pendopo utama"></option>
                        <option value="Aisle utama"></option>
                        <option value="Ballroom"></option>
                        <option value="Ruang privat"></option>
                        <option value="Panggung utama"></option>
                    </datalist>
                </div>
            </div>

            <div>
                <label for="activity" class="mb-1 block text-xs font-semibold text-charcoal">Rincian Aktivitas</label>
                <input id="activity" type="text" name="activity" value="{{ old('activity') }}" placeholder="Contoh: Ijab kabul dan penyerahan mahar" required class="editorial-input" x-ref="activity">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="start_time" class="mb-1 block text-xs font-semibold text-charcoal">Jam Mulai (WIB)</label>
                    <input id="start_time" type="time" name="start_time" value="{{ old('start_time', '08:00') }}" required class="editorial-input">
                </div>
                <div>
                    <label for="duration_minutes" class="mb-1 block text-xs font-semibold text-charcoal">Durasi</label>
                    <select id="duration_minutes" name="duration_minutes" class="editorial-input">
                        @foreach([30 => '30 menit', 45 => '45 menit', 60 => '1 jam', 90 => '1,5 jam', 120 => '2 jam', 150 => '2,5 jam', 180 => '3 jam', 240 => '4 jam'] as $minutes => $label)
                            <option value="{{ $minutes }}" @selected((int) old('duration_minutes', 60) === $minutes)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="pic_name" class="mb-1 block text-xs font-semibold text-charcoal">Penanggung Jawab</label>
                    <input id="pic_name" type="text" name="pic_name" value="{{ old('pic_name') }}" list="pic-templates" placeholder="Contoh: Lead WO" class="editorial-input">
                    <datalist id="pic-templates">
                        <option value="Lead WO"></option>
                        <option value="Koordinator Keluarga"></option>
                        <option value="MC"></option>
                        <option value="Vendor"></option>
                        <option value="Pengantin"></option>
                    </datalist>
                </div>
                <div>
                    <label for="pic_phone" class="mb-1 block text-xs font-semibold text-charcoal">Nomor Penanggung Jawab (WhatsApp, opsional)</label>
                    <input id="pic_phone" type="tel" name="pic_phone" value="{{ old('pic_phone') }}" placeholder="0812..." class="editorial-input">
                </div>
            </div>

            <div>
                <label for="notes" class="mb-1 block text-xs font-semibold text-charcoal">Catatan Khusus (opsional)</label>
                <input id="notes" type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Gamelan standby 15 menit sebelum kirab" class="editorial-input">
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('rundown.index', ['tab' => 'hari']) }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">Simpan Sesi</button>
            </div>
        </form>
    </section>
@endsection
