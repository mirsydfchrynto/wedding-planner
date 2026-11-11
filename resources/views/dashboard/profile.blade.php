@extends('layouts.app', ['title' => 'Edit Profil Pernikahan'])

@section('content')
    <div class="space-y-6">
        <x-ui.section-header
            title="Profil Pernikahan"
            subtitle="Perbarui nama mempelai, tanggal, venue, dan target anggaran induk."
        >
            <x-slot:actions>
                <a href="{{ route('dashboard.index') }}" class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                    &larr; Kembali
                </a>
            </x-slot:actions>
        </x-ui.section-header>

        <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('dashboard.updateProfile') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="bride_name" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nama Mempelai Wanita</label>
                        <input id="bride_name" type="text" name="bride_name" value="{{ old('bride_name', $profile->bride_name) }}" required class="editorial-input">
                    </div>
                    <div>
                        <label for="groom_name" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nama Mempelai Pria</label>
                        <input id="groom_name" type="text" name="groom_name" value="{{ old('groom_name', $profile->groom_name) }}" required class="editorial-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="wedding_date" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Tanggal Pernikahan</label>
                        <input id="wedding_date" type="date" name="wedding_date" value="{{ old('wedding_date', $profile->wedding_date?->format('Y-m-d')) }}" required class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="wedding_time" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Waktu Acara</label>
                        <input id="wedding_time" type="text" name="wedding_time" value="{{ old('wedding_time', $profile->wedding_time) }}" placeholder="Contoh: 08:00 - 14:00 WIB" required class="editorial-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="venue_name" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Nama Venue</label>
                        <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name', $profile->venue_name) }}" required class="editorial-input">
                    </div>
                    <div>
                        <label for="city" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Kota</label>
                        <input id="city" type="text" name="city" value="{{ old('city', $profile->city) }}" required class="editorial-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="total_budget" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Batas Anggaran (Rp)</label>
                        <input id="total_budget" type="number" name="total_budget" value="{{ old('total_budget', $profile->total_budget) }}" min="1000000" step="1000000" data-rupiah required class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="guest_target" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Target Jumlah Tamu</label>
                        <input id="guest_target" type="number" name="guest_target" value="{{ old('guest_target', $profile->guest_target) }}" min="10" required class="editorial-input tabular-nums">
                    </div>
                </div>

                <div>
                    <label for="theme_concept" class="mb-1.5 block text-xs font-medium text-[#2B2520]">Konsep / Tema</label>
                    <input id="theme_concept" type="text" name="theme_concept" value="{{ old('theme_concept', $profile->theme_concept) }}" required class="editorial-input">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#F0ECE4] pt-4">
                    <a href="{{ route('dashboard.index') }}" class="rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#2B2520] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
