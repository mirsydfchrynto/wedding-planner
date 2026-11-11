@extends('layouts.app', ['title' => 'Mulai Buku Kerja Pernikahan'])

@section('content')
<div
    class="mx-auto max-w-2xl py-6"
    x-data="{
        step: 1,
        totalSteps: 3,
        weddingDate: '',
        budgetChoice: '50000000',
        guestChoice: '300',
        brideName: '',
        groomName: '',
        city: 'Jakarta',
        venueName: '',
        eventType: 'akad_resepsi_gabung',
        akadTime: '08:00',
        resepsiTime: '11:00',
        helperMode: 'self',
        setDateInDays(days) {
            const d = new Date();
            d.setDate(d.getDate() + days);
            this.weddingDate = d.toISOString().split('T')[0];
        },
        setDateInMonths(months) {
            const d = new Date();
            d.setMonth(d.getMonth() + months);
            this.weddingDate = d.toISOString().split('T')[0];
        },
        daysLeft() {
            if (!this.weddingDate) return 0;
            const target = new Date(this.weddingDate);
            const now = new Date();
            return Math.ceil((target - now) / (1000 * 60 * 60 * 24));
        },
        formatIdr(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        },
        currentBudget() {
            if (this.budgetChoice === 'custom') {
                return Number(document.getElementById('budget_custom')?.value || 0);
            }
            return Number(this.budgetChoice);
        }
    }"
    x-init="setDateInMonths(3)"
>
    <div class="rounded-xl border border-[#E7E4DF] bg-white p-6 sm:p-8 shadow-sm space-y-6">

        {{-- Header Stepper 3-Langkah --}}
        <div class="border-b border-[#F0ECE4] pb-5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-[#8C5835]">
                    Langkah Awal Perencanaan
                </span>
                <span class="font-medium text-[#6E665E] tabular-nums" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
            </div>
            <h1 class="mt-1.5 text-2xl font-bold tracking-tight text-[#2B2520]">
                Mulai Perencanaan Pernikahan
            </h1>
            <p class="text-xs text-[#6E665E] mt-0.5">
                Sistem otomatis menyiapkan jadwal tugas dan pembagian anggaran sesuai rencana acara Anda.
            </p>

            {{-- Stepper Progress Bar --}}
            <div class="pt-4">
                <div class="grid grid-cols-3 gap-2">
                    <div class="h-1.5 rounded-full transition-colors" :class="step >= 1 ? 'bg-[#8C5835]' : 'bg-[#E7E4DF]'"></div>
                    <div class="h-1.5 rounded-full transition-colors" :class="step >= 2 ? 'bg-[#8C5835]' : 'bg-[#E7E4DF]'"></div>
                    <div class="h-1.5 rounded-full transition-colors" :class="step >= 3 ? 'bg-[#8C5835]' : 'bg-[#E7E4DF]'"></div>
                </div>
                <div class="flex justify-between text-[11px] font-medium text-[#6E665E] pt-1.5">
                    <span :class="step === 1 ? 'text-[#8C5835] font-semibold' : ''">1. Tanggal Hari-H</span>
                    <span :class="step === 2 ? 'text-[#8C5835] font-semibold' : ''">2. Target Anggaran</span>
                    <span :class="step === 3 ? 'text-[#8C5835] font-semibold' : ''">3. Detail Pasangan &amp; Acara</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('onboarding.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="helper_mode" :value="helperMode">

            {{-- LANGKAH 1: TANGGAL RESEPSI & PENYESUAIAN PINTAR --}}
            <div x-show="step === 1" class="space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-[#2B2520]">Kapan Hari Bahagia Anda?</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">
                        Pilih tanggal pernikahan. Sistem otomatis menyusun jadwal tenggat waktu mundur yang pas dengan waktu yang Anda miliki.
                    </p>
                </div>

                {{-- Pilihan Cepat --}}
                <div class="space-y-2">
                    <span class="text-xs font-medium text-[#2B2520] block">Pilihan Cepat Waktu:</span>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <button type="button" @click="setDateInDays(7)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() <= 10 && daysLeft() > 0 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            1 Minggu Lagi
                        </button>
                        <button type="button" @click="setDateInDays(14)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() > 10 && daysLeft() <= 20 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            2 Minggu Lagi
                        </button>
                        <button type="button" @click="setDateInDays(30)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() > 20 && daysLeft() <= 45 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            1 Bulan Lagi
                        </button>
                        <button type="button" @click="setDateInMonths(3)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() > 45 && daysLeft() <= 100 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            3 Bulan Lagi
                        </button>
                        <button type="button" @click="setDateInMonths(6)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() > 100 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            6 Bulan Lagi
                        </button>
                    </div>
                </div>

                <div>
                    <label for="wedding_date" class="mb-1.5 block text-xs font-medium text-[#2B2520]">
                        Pilih Tanggal di Kalender:
                    </label>
                    <input
                        id="wedding_date"
                        type="date"
                        name="wedding_date"
                        x-model="weddingDate"
                        min="{{ now()->addDay()->toDateString() }}"
                        required
                        class="editorial-input tabular-nums font-medium"
                    >
                </div>

                {{-- Kotak Informasi Pintar --}}
                <div class="rounded-xl border border-[#E7E4DF] bg-[#FAF8F5] p-4 text-xs space-y-1">
                    <span class="font-semibold text-[#8C5835] block">Informasi Waktu Persiapan</span>
                    <p class="text-[#2B2520] text-xs">
                        Target Tanggal: <strong class="text-[#8C5835]" x-text="weddingDate || 'Belum dipilih'"></strong>
                        <template x-if="daysLeft() > 0">
                            <span> · Tersisa <strong class="tabular-nums" x-text="daysLeft()"></strong> hari menuju hari pernikahan.</span>
                        </template>
                    </p>
                    <p class="text-[#6E665E] text-[11px] leading-relaxed">
                        Sistem secara cerdas menyusun tugas berurutan otomatis dari tanggal paling mendesak hingga hari-H.
                    </p>
                </div>

                <div class="flex justify-end pt-4 border-t border-[#F0ECE4]">
                    <button
                        type="button"
                        @click="if(weddingDate) step = 2; else alert('Pilih tanggal pernikahan terlebih dahulu');"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-5 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815] cursor-pointer"
                    >
                        <span>Lanjut ke Anggaran</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>

            {{-- LANGKAH 2: TARGET TOTAL ANGGARAN & TAMU UNDANGAN --}}
            <div x-show="step === 2" x-cloak class="space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-[#2B2520]">Berapa Target Total Anggaran Pernikahan?</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">
                        Pilih nominal budget yang Anda siapkan. Sistem akan otomatis membagi pos katering, venue, dan biaya lainnya secara seimbang.
                    </p>
                </div>

                {{-- Preset Chips Anggaran (Default: 10 Juta, 50 Juta, 100 Juta, Custom) --}}
                <div class="space-y-2">
                    <span class="text-xs font-medium text-[#2B2520] block">Pilihan Target Anggaran:</span>
                    <input type="hidden" name="total_budget" :value="budgetChoice">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach([
                            '10000000' => 'Rp 10 Juta (Sederhana)',
                            '50000000' => 'Rp 50 Juta (Menengah)',
                            '100000000' => 'Rp 100 Juta (Lengkap)',
                            '25000000' => 'Rp 25 Juta',
                            '70000000' => 'Rp 70 Juta',
                            '150000000' => 'Rp 150 Juta',
                            '250000000' => 'Rp 250 Juta',
                            'custom' => 'Isi Sendiri (Custom)'
                        ] as $val => $label)
                            <button
                                type="button"
                                @click="budgetChoice = '{{ $val }}'; if ('{{ $val }}' === 'custom') $nextTick(() => document.getElementById('budget_custom')?.focus())"
                                class="flex items-center justify-center rounded-lg border p-2.5 text-xs font-medium transition text-center cursor-pointer select-none"
                                :class="budgetChoice === '{{ $val }}' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold shadow-xs' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Input Bebas / Custom Anggaran --}}
                    <div x-show="budgetChoice === 'custom'" class="pt-2 rounded-lg bg-[#FAF8F5] border border-[#E7E4DF] p-3 space-y-1">
                        <label for="budget_custom" class="block text-xs font-semibold text-[#2B2520]">Ketikkan Nominal Anggaran Anda Sendiri (Rp)</label>
                        <input id="budget_custom" type="number" name="budget_custom" min="1000000" step="100000" placeholder="Contoh: 30000000 (30 Juta)" class="editorial-input tabular-nums font-semibold">
                        <span class="text-[11px] text-[#6E665E] block">Bebas masukkan nominal berapapun sesuai dana yang tersedia.</span>
                    </div>
                </div>

                {{-- Pilihan Tamu Undangan --}}
                <div class="space-y-2 pt-2 border-t border-[#F0ECE4]">
                    <span class="text-xs font-medium text-[#2B2520] block">Perkiraan Jumlah Tamu Undangan:</span>
                    <input type="hidden" name="guest_target" :value="guestChoice">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach([
                            '100' => '100 Tamu (Intimate)',
                            '300' => '300 Tamu (Keluarga)',
                            '500' => '500 Tamu (Standar)',
                            '800' => '800+ Tamu'
                        ] as $val => $label)
                            <button
                                type="button"
                                @click="guestChoice = '{{ $val }}'"
                                class="flex items-center justify-center rounded-lg border p-2.5 text-xs font-medium transition text-center cursor-pointer select-none"
                                :class="guestChoice === '{{ $val }}' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold shadow-xs' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Visual Alokasi Otomatis --}}
                <div class="rounded-xl border border-[#E7E4DF] bg-[#FAF8F5] p-3.5 text-xs space-y-2">
                    <span class="font-semibold text-[#2B2520] block">Estimasi Pembagian Alokasi:</span>
                    <div class="h-3 w-full rounded-full overflow-hidden flex">
                        <div class="bg-[#8C5835]" style="width: 40%" title="Venue & Katering 40%"></div>
                        <div class="bg-[#A06414]" style="width: 15%" title="Dekorasi 15%"></div>
                        <div class="bg-[#483725]" style="width: 15%" title="Busana & MUA 15%"></div>
                        <div class="bg-[#2E5E3E]" style="width: 10%" title="Dokumentasi 10%"></div>
                        <div class="bg-[#6E665E]" style="width: 10%" title="Dokumen KUA 10%"></div>
                        <div class="bg-[#D3E6DA]" style="width: 10%" title="Dana Cadangan 10%"></div>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-[#6E665E] pt-1">
                        <span>• Venue &amp; Katering: 40%</span>
                        <span>• Dekorasi: 15%</span>
                        <span>• Busana &amp; MUA: 15%</span>
                        <span>• Foto/Video: 10%</span>
                        <span>• Dokumen KUA: 10%</span>
                        <span>• Cadangan: 10%</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-[#F0ECE4]">
                    <button type="button" @click="step = 1" class="rounded-lg border border-[#E7E4DF] bg-white px-4 py-2 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer">&larr; Kembali</button>
                    <button type="button" @click="step = 3" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-5 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815] cursor-pointer">
                        <span>Lanjut ke Detail Acara</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>

            {{-- LANGKAH 3: DETAIL DATA DIRI MEMPELAI & RANGKAIAN ACARA --}}
            <div x-show="step === 3" x-cloak class="space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-[#2B2520]">Detail Calon Pengantin &amp; Acara</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">
                        Isikan nama calon mempelai dan rincian jam acara pernikahan Anda.
                    </p>
                </div>

                {{-- Data Calon Pengantin --}}
                <div class="space-y-3">
                    <span class="text-xs font-semibold text-[#8C5835] block uppercase tracking-wider">Data Calon Mempelai:</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label for="groom_name" class="mb-1 block font-medium text-[#2B2520]">Nama Calon Mempelai Pria</label>
                            <input id="groom_name" type="text" name="groom_name" x-model="groomName" placeholder="Contoh: Reza Mahendra" required class="editorial-input">
                        </div>
                        <div>
                            <label for="bride_name" class="mb-1 block font-medium text-[#2B2520]">Nama Calon Mempelai Wanita</label>
                            <input id="bride_name" type="text" name="bride_name" x-model="brideName" placeholder="Contoh: Aurelia Larasati" required class="editorial-input">
                        </div>
                    </div>
                </div>

                {{-- Format Rangkaian Acara --}}
                <div class="space-y-2 pt-2 border-t border-[#F0ECE4]">
                    <span class="text-xs font-semibold text-[#8C5835] block uppercase tracking-wider">Format Rangkaian Acara:</span>
                    <input type="hidden" name="event_type" :value="eventType">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <button
                            type="button"
                            @click="eventType = 'akad_resepsi_gabung'"
                            class="flex items-center gap-2.5 p-3 rounded-lg border text-left cursor-pointer transition select-none"
                            :class="eventType === 'akad_resepsi_gabung' ? 'border-[#8C5835] bg-[#F4ECE4]/40 font-semibold text-[#2B2520] shadow-xs' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            <span class="h-3.5 w-3.5 rounded-full border flex items-center justify-center shrink-0" :class="eventType === 'akad_resepsi_gabung' ? 'border-[#8C5835] bg-[#8C5835]' : 'border-[#E7E4DF] bg-white'">
                                <span x-show="eventType === 'akad_resepsi_gabung'" class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            <span>Akad &amp; Resepsi Jadi Satu Hari (Sekaligus)</span>
                        </button>
                        <button
                            type="button"
                            @click="eventType = 'akad_resepsi'"
                            class="flex items-center gap-2.5 p-3 rounded-lg border text-left cursor-pointer transition select-none"
                            :class="eventType === 'akad_resepsi' ? 'border-[#8C5835] bg-[#F4ECE4]/40 font-semibold text-[#2B2520] shadow-xs' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            <span class="h-3.5 w-3.5 rounded-full border flex items-center justify-center shrink-0" :class="eventType === 'akad_resepsi' ? 'border-[#8C5835] bg-[#8C5835]' : 'border-[#E7E4DF] bg-white'">
                                <span x-show="eventType === 'akad_resepsi'" class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            <span>Akad &amp; Resepsi Terpisah (Beda Sesi/Jam)</span>
                        </button>
                        <button
                            type="button"
                            @click="eventType = 'akad_only'"
                            class="flex items-center gap-2.5 p-3 rounded-lg border text-left cursor-pointer transition select-none"
                            :class="eventType === 'akad_only' ? 'border-[#8C5835] bg-[#F4ECE4]/40 font-semibold text-[#2B2520] shadow-xs' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            <span class="h-3.5 w-3.5 rounded-full border flex items-center justify-center shrink-0" :class="eventType === 'akad_only' ? 'border-[#8C5835] bg-[#8C5835]' : 'border-[#E7E4DF] bg-white'">
                                <span x-show="eventType === 'akad_only'" class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            <span>Akad Nikah Saja (Khidmat)</span>
                        </button>
                        <button
                            type="button"
                            @click="eventType = 'jawa_lengkap'"
                            class="flex items-center gap-2.5 p-3 rounded-lg border text-left cursor-pointer transition select-none"
                            :class="eventType === 'jawa_lengkap' ? 'border-[#8C5835] bg-[#F4ECE4]/40 font-semibold text-[#2B2520] shadow-xs' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            <span class="h-3.5 w-3.5 rounded-full border flex items-center justify-center shrink-0" :class="eventType === 'jawa_lengkap' ? 'border-[#8C5835] bg-[#8C5835]' : 'border-[#E7E4DF] bg-white'">
                                <span x-show="eventType === 'jawa_lengkap'" class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            <span>Rangkaian Tradisi Adat Lengkap</span>
                        </button>
                    </div>
                </div>

                {{-- Jam Akad & Resepsi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2 border-t border-[#F0ECE4]">
                    <div>
                        <label for="akad_time" class="mb-1 block font-medium text-[#2B2520]">Jam Akad Nikah</label>
                        <select id="akad_time" name="akad_time" x-model="akadTime" class="editorial-input">
                            <option value="08:00">Pukul 08:00 WIB (Pagi)</option>
                            <option value="09:00">Pukul 09:00 WIB (Pagi)</option>
                            <option value="10:00">Pukul 10:00 WIB (Menjelang Siang)</option>
                            <option value="15:30">Pukul 15:30 WIB (Sore)</option>
                            <option value="19:00">Pukul 19:00 WIB (Malam)</option>
                        </select>
                    </div>
                    <div>
                        <label for="resepsi_time" class="mb-1 block font-medium text-[#2B2520]">Jam Resepsi / Ramah Tamah</label>
                        <select id="resepsi_time" name="resepsi_time" x-model="resepsiTime" class="editorial-input">
                            <option value="11:00 – 13:00">Siang (11:00 – 13:00 WIB)</option>
                            <option value="13:00 – 15:00">Siang (13:00 – 15:00 WIB)</option>
                            <option value="18:30 – 21:00">Malam (18:30 – 21:00 WIB)</option>
                            <option value="09:00 – 15:00">Seharian Penuh</option>
                        </select>
                    </div>
                </div>

                {{-- Tempat & Kota --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2 border-t border-[#F0ECE4]">
                    <div>
                        <label for="venue_name" class="mb-1 block font-medium text-[#2B2520]">Tempat / Gedung Acara</label>
                        <input id="venue_name" type="text" name="venue_name" x-model="venueName" placeholder="Contoh: Gedung Sasana Kriya / Rumah Mempelai" required class="editorial-input">
                    </div>
                    <div>
                        <label for="city" class="mb-1 block font-medium text-[#2B2520]">Kota Penyelenggaraan</label>
                        <select id="city" name="city" x-model="city" class="editorial-input">
                            <option value="Jakarta">Jakarta</option>
                            <option value="Bandung">Bandung</option>
                            <option value="Surabaya">Surabaya</option>
                            <option value="Yogyakarta">Yogyakarta</option>
                            <option value="Semarang">Semarang</option>
                            <option value="Medan">Medan</option>
                            <option value="Makassar">Makassar</option>
                            <option value="Kota Lain">Kota Lainnya</option>
                        </select>
                    </div>
                </div>

                {{-- Rangkuman Siap Buat --}}
                <div class="rounded-xl border border-[#D3E6DA] bg-[#EAF4EE] p-4 text-xs space-y-1.5 mt-2">
                    <span class="inline-flex items-center rounded-md border border-[#D3E6DA] bg-white px-2 py-0.5 text-xs font-semibold text-[#2E5E3E]">
                        Rencana Siap Dibuat Otomatis
                    </span>
                    <p class="font-bold text-sm text-[#2B2520]" x-text="(groomName || 'Mempelai Pria') + ' & ' + (brideName || 'Mempelai Wanita')"></p>
                    <p class="text-[#6E665E]" x-text="'Target Tanggal: ' + weddingDate + ' (' + daysLeft() + ' hari lagi)'"></p>
                    <p class="text-[#6E665E]" x-text="'Total Anggaran: ' + formatIdr(currentBudget()) + ' · ' + guestChoice + ' Tamu di ' + city"></p>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-[#F0ECE4]">
                    <button type="button" @click="step = 2" class="rounded-lg border border-[#E7E4DF] bg-white px-4 py-2 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer">&larr; Kembali</button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#2E5E3E] px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#254F34] cursor-pointer"
                    >
                        <span>Buat Rencana Otomatis &amp; Buka Dashboard</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
