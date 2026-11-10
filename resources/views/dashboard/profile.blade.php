@extends('layouts.app', ['title' => 'Edit Profil Pernikahan'])

@section('content')
    <x-ui.section-header
        title="Profil Pernikahan"
        subtitle="Perbarui nama mempelai, tanggal, venue, dan target anggaran induk."
    >
        <x-slot:actions>
            <a href="{{ route('dashboard.index') }}" class="editorial-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-ui.section-header>

    <section class="mt-6 editorial-card rounded-lg p-6">
        <form method="POST" action="{{ route('dashboard.updateProfile') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="bride_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Wanita</label>
                    <input id="bride_name" type="text" name="bride_name" value="{{ old('bride_name', $profile->bride_name) }}" required class="editorial-input">
                </div>
                <div>
                    <label for="groom_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Pria</label>
                    <input id="groom_name" type="text" name="groom_name" value="{{ old('groom_name', $profile->groom_name) }}" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="wedding_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Pernikahan</label>
                    <input id="wedding_date" type="date" name="wedding_date" value="{{ old('wedding_date', $profile->wedding_date?->format('Y-m-d')) }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="wedding_time" class="mb-1 block text-xs font-semibold text-charcoal">Waktu Acara</label>
                    <input id="wedding_time" type="text" name="wedding_time" value="{{ old('wedding_time', $profile->wedding_time) }}" placeholder="Contoh: 08:00 - 14:00 WIB" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="venue_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Venue</label>
                    <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name', $profile->venue_name) }}" required class="editorial-input">
                </div>
                <div>
                    <label for="city" class="mb-1 block text-xs font-semibold text-charcoal">Kota</label>
                    <input id="city" type="text" name="city" value="{{ old('city', $profile->city) }}" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="total_budget" class="mb-1 block text-xs font-semibold text-charcoal">Plafon Anggaran (Rp)</label>
                    <input id="total_budget" type="number" name="total_budget" value="{{ old('total_budget', $profile->total_budget) }}" min="1000000" step="1000000" data-rupiah required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="guest_target" class="mb-1 block text-xs font-semibold text-charcoal">Target Jumlah Tamu</label>
                    <input id="guest_target" type="number" name="guest_target" value="{{ old('guest_target', $profile->guest_target) }}" min="10" required class="editorial-input tabular-nums">
                </div>
            </div>

            <div>
                <label for="theme_concept" class="mb-1 block text-xs font-semibold text-charcoal">Konsep / Tema</label>
                <input id="theme_concept" type="text" name="theme_concept" value="{{ old('theme_concept', $profile->theme_concept) }}" required class="editorial-input">
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <a href="{{ route('dashboard.index') }}" class="editorial-btn-secondary">Batal</a>
                <button type="submit" class="editorial-btn-primary">Simpan Profil</button>
            </div>
        </form>
    </section>
@endsection
