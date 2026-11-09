@extends('layouts.app', ['title' => 'Mulai Perencanaan Pernikahan'])

@section('content')
    <div class="mx-auto max-w-3xl py-10">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-champagne">Langkah 1 dari 1</p>
        <h1 class="mt-3 text-3xl font-bold text-espresso">Mulai Perencanaan Pernikahan Anda</h1>
        <p class="mt-2 text-sm text-taupe">
            Isi detail dasar pernikahan Anda. Semua data anggaran, checklist, timeline, dan vendor akan terhubung ke profil ini.
        </p>

        <form method="POST" action="{{ route('onboarding.store') }}" class="mt-8 editorial-card rounded-lg p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="bride_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Wanita</label>
                    <input id="bride_name" type="text" name="bride_name" value="{{ old('bride_name') }}" required class="editorial-input">
                </div>
                <div>
                    <label for="groom_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Pria</label>
                    <input id="groom_name" type="text" name="groom_name" value="{{ old('groom_name') }}" required class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="wedding_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Pernikahan</label>
                    <input id="wedding_date" type="date" name="wedding_date" value="{{ old('wedding_date') }}" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="city" class="mb-1 block text-xs font-semibold text-charcoal">Kota</label>
                    <input id="city" type="text" name="city" value="{{ old('city', 'Jakarta') }}" required class="editorial-input">
                </div>
            </div>

            <div>
                <label for="venue_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Venue Utama</label>
                <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name') }}" required class="editorial-input">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="total_budget" class="mb-1 block text-xs font-semibold text-charcoal">Plafon Total Anggaran (Rp)</label>
                    <input id="total_budget" type="number" name="total_budget" value="{{ old('total_budget', 250000000) }}" min="1000000" step="500000" data-rupiah required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label for="guest_target" class="mb-1 block text-xs font-semibold text-charcoal">Target Jumlah Tamu</label>
                    <input id="guest_target" type="number" name="guest_target" value="{{ old('guest_target', 300) }}" min="10" required class="editorial-input tabular-nums">
                </div>
            </div>

            <button type="submit" class="editorial-btn-primary w-full">Buat Profil &amp; Buka Dashboard</button>
        </form>
    </div>
@endsection
