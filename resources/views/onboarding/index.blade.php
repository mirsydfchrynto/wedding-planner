@extends('layouts.app', ['title' => 'Buka Buku Kerja Baru'])

@section('content')
<div
    class="mx-auto max-w-2xl py-6"
    x-data="{
        step: 1,
        totalSteps: 6,
        eventType: 'akad_resepsi',
        weddingDate: '',
        city: 'Jakarta',
        venueName: '',
        venueType: 'Gedung / Ballroom',
        weddingTime: '08:00 – 13:00 WIB',
        budgetChoice: '350000000',
        guestChoice: '500',
        groomName: '',
        brideName: '',
        helperMode: 'self',
        themeConcept: '',
        setDateInMonths(months) {
            const d = new Date();
            d.setMonth(d.getMonth() + months);
            this.weddingDate = d.toISOString().split('T')[0];
        },
        formatIdr(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        },
        currentBudget() {
            if (this.budgetChoice === 'custom') {
                return Number(document.getElementById('budget_custom')?.value || 0);
            }
            return Number(this.budgetChoice);
        },
        currentGuests() {
            if (this.guestChoice === 'custom') {
                return Number(document.getElementById('guest_custom')?.value || 0);
            }
            return Number(this.guestChoice);
        }
    }"
    x-init="setDateInMonths(6)"
>
    <div class="paper-card relative border-2 border-sketch bg-paper p-6 sm:p-8">
        <div class="tape"></div>

        {{-- Progress Header --}}
        <div class="border-b border-sketch pb-4">
            <div class="flex items-center justify-between text-xs">
                <span class="stamp">Mulai Perencanaan Pernikahan</span>
                <span class="font-bold text-soga font-mono" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
            </div>
            <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-espresso sm:text-3xl">
                Buka Buku Catatan Pernikahan
            </h1>
            <p class="text-xs text-taupe mt-0.5">
                Isi 6 langkah dasar untuk menyusun rencana anggaran, tanggal relatif, dan daftar tugas otomatis.
            </p>

            {{-- Progress bar --}}
            <div class="mt-3 h-1.5 w-full bg-paper-2 border border-sketch">
                <div class="h-full bg-soga transition-all duration-200" :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('onboarding.store') }}" class="mt-6 space-y-6">
            @csrf

            {{-- LANGKAH 1: Rangkaian Acara --}}
            <div x-show="step === 1" class="space-y-4">
                <h2 class="font-display text-xl font-bold text-espresso">1. Pilih Konsep Rangkaian Acara</h2>
                <p class="text-xs text-taupe">Pilihan ini menentukan daftar tugas yang relevan dan pos anggaran awal.</p>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <label
                        class="paper-card block p-4 cursor-pointer border transition"
                        :class="eventType === 'akad_only' ? 'border-soga bg-paper-2' : 'border-sketch hover:border-soga'"
                    >
                        <input type="radio" name="event_type" value="akad_only" x-model="eventType" class="sr-only">
                        <span class="stamp !text-[0.6rem] !py-0">Khidmat</span>
                        <p class="font-display font-bold text-espresso text-base mt-1.5">Akad Nikah Saja</p>
                        <p class="text-[0.72rem] text-taupe mt-1">Fokus pada administrasi KUA, mahar, busana akad, dan jamuan keluarga inti.</p>
                    </label>

                    <label
                        class="paper-card block p-4 cursor-pointer border transition"
                        :class="eventType === 'akad_resepsi' ? 'border-soga bg-paper-2' : 'border-sketch hover:border-soga'"
                    >
                        <input type="radio" name="event_type" value="akad_resepsi" x-model="eventType" class="sr-only">
                        <span class="stamp !text-[0.6rem] !py-0">Populer</span>
                        <p class="font-display font-bold text-espresso text-base mt-1.5">Akad &amp; Resepsi Modern</p>
                        <p class="text-[0.72rem] text-taupe mt-1">Rangkaian lengkap akad nikah dan pesta perayaan resepsi bersama tamu undangan.</p>
                    </label>

                    <label
                        class="paper-card block p-4 cursor-pointer border transition"
                        :class="eventType === 'jawa_inti' ? 'border-soga bg-paper-2' : 'border-sketch hover:border-soga'"
                    >
                        <input type="radio" name="event_type" value="jawa_inti" x-model="eventType" class="sr-only">
                        <span class="stamp !text-[0.6rem] !py-0">Tradisi</span>
                        <p class="font-display font-bold text-espresso text-base mt-1.5">Adat Jawa Inti</p>
                        <p class="text-[0.72rem] text-taupe mt-1">Akad, siraman, midodareni, dan panggih inti dengan tatanan adat luhur.</p>
                    </label>

                    <label
                        class="paper-card block p-4 cursor-pointer border transition"
                        :class="eventType === 'jawa_lengkap' ? 'border-soga bg-paper-2' : 'border-sketch hover:border-soga'"
                    >
                        <input type="radio" name="event_type" value="jawa_lengkap" x-model="eventType" class="sr-only">
                        <span class="stamp !text-[0.6rem] !py-0">Adat Agung</span>
                        <p class="font-display font-bold text-espresso text-base mt-1.5">Adat Jawa Lengkap &amp; Kirab</p>
                        <p class="text-[0.72rem] text-taupe mt-1">Seluruh prosesi adat Jawa lengkap, gamelan, kirab pengantin agung, dan resepsi.</p>
                    </label>
                </div>

                <div class="flex justify-end pt-3 border-t border-sketch">
                    <button type="button" @click="step = 2" class="editorial-btn-primary !py-1.5 !px-4 !text-xs">
                        Lanjut ke Tanggal &rarr;
                    </button>
                </div>
            </div>

            {{-- LANGKAH 2: Hari & Tanggal --}}
            <div x-show="step === 2" x-cloak class="space-y-4">
                <h2 class="font-display text-xl font-bold text-espresso">2. Hari &amp; Tanggal Pernikahan</h2>
                <p class="text-xs text-taupe">Jadwal seluruh tugas akan otomatis dihitung mundur (back-scheduling) dari tanggal ini.</p>

                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-taupe">Pilihan Cepat Target:</span>
                        <button type="button" @click="setDateInMonths(3)" class="chip !min-h-0 !py-1 !px-2">3 Bulan Lagi</button>
                        <button type="button" @click="setDateInMonths(6)" class="chip !min-h-0 !py-1 !px-2">6 Bulan Lagi</button>
                        <button type="button" @click="setDateInMonths(12)" class="chip !min-h-0 !py-1 !px-2">1 Tahun Lagi</button>
                    </div>

                    <div>
                        <label for="wedding_date" class="mb-1 block text-xs font-bold text-charcoal uppercase tracking-wider">
                            Pilih Tanggal Hari H
                        </label>
                        <input
                            id="wedding_date"
                            type="date"
                            name="wedding_date"
                            x-model="weddingDate"
                            min="{{ now()->addDay()->toDateString() }}"
                            required
                            class="editorial-input tabular-nums"
                        >
                    </div>

                    <div class="paper-card-soft p-3 text-xs text-charcoal border border-sketch/70">
                        <p>Tanggal Akad: <strong class="text-soga" x-text="weddingDate || 'Belum dipilih'"></strong></p>
                        <p class="text-taupe text-[0.7rem] mt-0.5">Semua 124 tugas akan tersusun rapi hingga hari H tersebut.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-sketch">
                    <button type="button" @click="step = 1" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">&larr; Kembali</button>
                    <button type="button" @click="step = 3" class="editorial-btn-primary !py-1.5 !px-4 !text-xs">Lanjut ke Lokasi &rarr;</button>
                </div>
            </div>

            {{-- LANGKAH 3: Lokasi & Jam Acara --}}
            <div x-show="step === 3" x-cloak class="space-y-4">
                <h2 class="font-display text-xl font-bold text-espresso">3. Kota, Venue, &amp; Jam Acara</h2>

                <div class="space-y-3 text-xs">
                    <div>
                        <label for="city" class="mb-1 block font-bold text-charcoal uppercase tracking-wider">Kota Penyelenggaraan</label>
                        <input id="city" type="text" name="city" x-model="city" placeholder="Contoh: Jakarta / Yogyakarta / Surabaya" required class="editorial-input">
                    </div>

                    <div>
                        <label for="venue_name" class="mb-1 block font-bold text-charcoal uppercase tracking-wider">Nama Gedung / Tempat Acara</label>
                        <input id="venue_name" type="text" name="venue_name" x-model="venueName" placeholder="Contoh: Plataran Menteng / Gedung Sasana Kriya / Rumah Mempelai" required class="editorial-input">
                    </div>

                    <div>
                        <label class="mb-1 block font-bold text-charcoal uppercase tracking-wider">Waktu Pelaksanaan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <label class="chip cursor-pointer" :class="weddingTime === '08:00 – 12:00 WIB' ? 'chip-active' : ''">
                                <input type="radio" name="wedding_time" value="08:00 – 12:00 WIB" x-model="weddingTime" class="sr-only">
                                Pagi (08.00–12.00)
                            </label>
                            <label class="chip cursor-pointer" :class="weddingTime === '11:00 – 14:00 WIB' ? 'chip-active' : ''">
                                <input type="radio" name="wedding_time" value="11:00 – 14:00 WIB" x-model="weddingTime" class="sr-only">
                                Siang (11.00–14.00)
                            </label>
                            <label class="chip cursor-pointer" :class="weddingTime === '15:30 – 21:00 WIB' ? 'chip-active' : ''">
                                <input type="radio" name="wedding_time" value="15:30 – 21:00 WIB" x-model="weddingTime" class="sr-only">
                                Sore/Malam (15.30–21.00)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-sketch">
                    <button type="button" @click="step = 2" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">&larr; Kembali</button>
                    <button type="button" @click="step = 4" class="editorial-btn-primary !py-1.5 !px-4 !text-xs">Lanjut ke Anggaran &rarr;</button>
                </div>
            </div>

            {{-- LANGKAH 4: Pagu Anggaran --}}
            <div x-show="step === 4" x-cloak class="space-y-4">
                <h2 class="font-display text-xl font-bold text-espresso">4. Pagu Anggaran Pernikahan</h2>
                <p class="text-xs text-taupe">Pilih estimasi total anggaran. Sistem akan otomatis membagi ke dalam 29 pos biaya.</p>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach(['150000000' => '150 Juta', '250000000' => '250 Juta', '350000000' => '350 Juta', '500000000' => '500 Juta', '750000000' => '750 Juta', '1000000000' => '1 Miliar'] as $val => $label)
                            <label class="chip cursor-pointer text-center" :class="budgetChoice === '{{ $val }}' ? 'chip-active' : ''">
                                <input type="radio" name="total_budget" value="{{ $val }}" x-model="budgetChoice" class="sr-only">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <label class="chip w-full cursor-pointer justify-center" :class="budgetChoice === 'custom' ? 'chip-active' : ''">
                        <input type="radio" name="total_budget" value="custom" x-model="budgetChoice" class="sr-only">
                        Nominal Lainnya (Custom)
                    </label>

                    <div x-show="budgetChoice === 'custom'" class="pt-2">
                        <label for="budget_custom" class="mb-1 block text-xs font-bold text-charcoal uppercase tracking-wider">Tentukan Nominal Anggaran (Rp)</label>
                        <input id="budget_custom" type="number" name="budget_custom" min="1000000" step="1000000" placeholder="Contoh: 400000000" class="editorial-input tabular-nums">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-sketch">
                    <button type="button" @click="step = 3" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">&larr; Kembali</button>
                    <button type="button" @click="step = 5" class="editorial-btn-primary !py-1.5 !px-4 !text-xs">Lanjut ke Tamu &rarr;</button>
                </div>
            </div>

            {{-- LANGKAH 5: Jumlah Tamu Undangan --}}
            <div x-show="step === 5" x-cloak class="space-y-4">
                <h2 class="font-display text-xl font-bold text-espresso">5. Target Jumlah Tamu Undangan</h2>
                <p class="text-xs text-taupe">Digunakan untuk menghitung rasio katering, suvenir, dan kapasitas ruangan.</p>

                <div class="space-y-3">
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                        @foreach(['100' => '100', '200' => '200', '300' => '300', '500' => '500', '800' => '800', '1000' => '1.000'] as $val => $label)
                            <label class="chip cursor-pointer text-center" :class="guestChoice === '{{ $val }}' ? 'chip-active' : ''">
                                <input type="radio" name="guest_target" value="{{ $val }}" x-model="guestChoice" class="sr-only">
                                {{ $label }} Tamu
                            </label>
                        @endforeach
                    </div>

                    <label class="chip w-full cursor-pointer justify-center" :class="guestChoice === 'custom' ? 'chip-active' : ''">
                        <input type="radio" name="guest_target" value="custom" x-model="guestChoice" class="sr-only">
                        Jumlah Tamu Kustom
                    </label>

                    <div x-show="guestChoice === 'custom'" class="pt-2">
                        <label for="guest_custom" class="mb-1 block text-xs font-bold text-charcoal uppercase tracking-wider">Jumlah Tamu</label>
                        <input id="guest_custom" type="number" name="guest_custom" min="10" max="5000" placeholder="Contoh: 450" class="editorial-input tabular-nums">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-sketch">
                    <button type="button" @click="step = 4" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">&larr; Kembali</button>
                    <button type="button" @click="step = 6" class="editorial-btn-primary !py-1.5 !px-4 !text-xs">Lanjut ke Data Pasangan &rarr;</button>
                </div>
            </div>

            {{-- LANGKAH 6: Pasangan & Tim Bantuan --}}
            <div x-show="step === 6" x-cloak class="space-y-4">
                <h2 class="font-display text-xl font-bold text-espresso">6. Nama Pasangan &amp; Tim Kerja</h2>

                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="groom_name" class="mb-1 block font-bold text-charcoal uppercase tracking-wider">Nama Calon Mempelai Pria</label>
                            <input id="groom_name" type="text" name="groom_name" x-model="groomName" placeholder="Contoh: Reza Mahendra" required class="editorial-input">
                        </div>
                        <div>
                            <label for="bride_name" class="mb-1 block font-bold text-charcoal uppercase tracking-wider">Nama Calon Mempelai Wanita</label>
                            <input id="bride_name" type="text" name="bride_name" x-model="brideName" placeholder="Contoh: Aurelia Larasati" required class="editorial-input">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block font-bold text-charcoal uppercase tracking-wider">Siapa yang Akan Mengeksekusi Tugas?</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <label class="chip cursor-pointer" :class="helperMode === 'self' ? 'chip-active' : ''">
                                <input type="radio" name="helper_mode" value="self" x-model="helperMode" class="sr-only">
                                Mandiri Berdua (Pengantin)
                            </label>
                            <label class="chip cursor-pointer" :class="helperMode === 'family' ? 'chip-active' : ''">
                                <input type="radio" name="helper_mode" value="family" x-model="helperMode" class="sr-only">
                                Dibantu Keluarga Inti
                            </label>
                            <label class="chip cursor-pointer" :class="helperMode === 'wo' ? 'chip-active' : ''">
                                <input type="radio" name="helper_mode" value="wo" x-model="helperMode" class="sr-only">
                                Didampingi Wedding Organizer
                            </label>
                        </div>
                    </div>

                    {{-- Ringkasan Akhir Sebelum Simpan --}}
                    <div class="paper-card-soft border border-sketch p-3 text-xs space-y-1 mt-4">
                        <span class="stamp !text-[0.6rem] !py-0">Siap Dibuat</span>
                        <p class="font-bold text-espresso text-sm mt-1" x-text="(brideName || 'Mempelai Wanita') + ' & ' + (groomName || 'Mempelai Pria')"></p>
                        <p class="text-taupe" x-text="'Tanggal: ' + (weddingDate || '-') + ' di ' + (venueName || '-') + ', ' + city"></p>
                        <p class="text-taupe" x-text="'Pagu Anggaran: ' + formatIdr(currentBudget()) + ' · ' + currentGuests() + ' Tamu Undangan'"></p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-sketch">
                    <button type="button" @click="step = 5" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">&larr; Kembali</button>
                    <button type="submit" class="editorial-btn-primary !py-2 !px-5 !text-xs">
                        Buka Buku Catatan &amp; Masuk ke Dashboard &rarr;
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
