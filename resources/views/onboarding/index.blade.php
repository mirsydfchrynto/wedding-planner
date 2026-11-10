@extends('layouts.app', ['title' => 'Mulai Perencanaan Pernikahan'])

@section('content')
    <div class="mx-auto max-w-2xl py-10" x-data="{ step: {{ $errors->hasAny(['total_budget', 'guest_target', 'adat_tradition']) ? 2 : 1 }} }">
        <div class="flex items-center gap-3">
            <template x-for="n in [1, 2]" :key="n">
                <div class="flex flex-1 items-center gap-3">
                    <span
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                        :class="step >= n ? 'bg-espresso text-alabaster' : 'bg-stone-line text-taupe'"
                        x-text="n"
                    ></span>
                    <span class="text-xs font-semibold" :class="step >= n ? 'text-espresso' : 'text-taupe'" x-text="n === 1 ? 'Data Pasangan' : 'Anggaran & Acara'"></span>
                    <span class="h-px flex-1 bg-stone-line" x-show="n === 1"></span>
                </div>
            </template>
        </div>

        <h1 class="mt-6 text-3xl font-bold text-espresso">Mulai Perencanaan</h1>
        <p class="mt-2 text-sm text-taupe">Dua langkah singkat. Anggaran dan checklist dibuat otomatis setelahnya.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-wine/30 bg-wine/5 p-4 text-sm text-wine">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('onboarding.store') }}" class="mt-6 editorial-card rounded-lg p-6">
            @csrf

            <fieldset x-show="step === 1" x-cloak class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="bride_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Wanita</label>
                        <input id="bride_name" type="text" name="bride_name" value="{{ old('bride_name') }}" :required="step === 1" class="editorial-input">
                    </div>
                    <div>
                        <label for="groom_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Mempelai Pria</label>
                        <input id="groom_name" type="text" name="groom_name" value="{{ old('groom_name') }}" :required="step === 1" class="editorial-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="wedding_date" class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Pernikahan</label>
                        <input id="wedding_date" type="date" name="wedding_date" value="{{ old('wedding_date') }}" :required="step === 1" class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="city" class="mb-1 block text-xs font-semibold text-charcoal">Kota</label>
                        <input id="city" type="text" name="city" value="{{ old('city', 'Jakarta') }}" :required="step === 1" class="editorial-input">
                    </div>
                </div>

                <div>
                    <label for="venue_name" class="mb-1 block text-xs font-semibold text-charcoal">Nama Venue Utama</label>
                    <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name') }}" :required="step === 1" class="editorial-input">
                </div>

                <button type="button" @click="$el.closest('form').reportValidity() && (step = 2)" class="editorial-btn-primary w-full">Lanjut ke Anggaran</button>
            </fieldset>

            <div x-show="step === 2" x-cloak class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="total_budget" class="mb-1 block text-xs font-semibold text-charcoal">Plafon Total Anggaran (Rp)</label>
                        <input id="total_budget" type="number" name="total_budget" value="{{ old('total_budget', 250000000) }}" min="1000000" step="500000" required class="editorial-input tabular-nums">
                    </div>
                    <div>
                        <label for="guest_target" class="mb-1 block text-xs font-semibold text-charcoal">Target Jumlah Tamu</label>
                        <input id="guest_target" type="number" name="guest_target" value="{{ old('guest_target', 300) }}" min="10" required class="editorial-input tabular-nums">
                    </div>
                </div>

                <div>
                    <label for="adat_tradition" class="mb-1 block text-xs font-semibold text-charcoal">Tradisi Adat (Opsional)</label>
                    <select id="adat_tradition" name="adat_tradition" class="editorial-input">
                        <option value="none" {{ old('adat_tradition') === 'none' ? 'selected' : '' }}>Tidak ada (Umum)</option>
                        <option value="jawa" {{ old('adat_tradition') === 'jawa' ? 'selected' : '' }}>Jawa</option>
                        <option value="sunda" {{ old('adat_tradition') === 'sunda' ? 'selected' : '' }}>Sunda</option>
                        <option value="minang" {{ old('adat_tradition') === 'minang' ? 'selected' : '' }}>Minangkabau</option>
                        <option value="batak" {{ old('adat_tradition') === 'batak' ? 'selected' : '' }}>Batak</option>
                    </select>
                </div>

                <p class="text-xs text-taupe">29 pos anggaran otomatis memakai estimasi minimum riset (harga satuan × jumlah tamu) dan 124 tugas checklist. Semua angka bisa diedit setelah dashboard terbuka.</p>

                <div class="flex gap-3">
                    <button type="button" @click="step = 1" class="editorial-btn-secondary flex-1">Kembali</button>
                    <button type="submit" class="editorial-btn-primary flex-[2]">Buat Profil & Buka Dashboard</button>
                </div>
            </div>
        </form>
    </div>
@endsection
