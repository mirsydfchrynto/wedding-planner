@extends('layouts.app', ['title' => 'Mulai Buku Kerja Pernikahan'])

@section('content')
<div
    class="mx-auto max-w-2xl py-6"
    x-data="{
        step: 1,
        totalSteps: 3,
        weddingDate: '',
        budgetChoice: '70000000',
        guestChoice: '300',
        brideName: '',
        groomName: '',
        city: 'Jakarta',
        eventType: 'akad_resepsi',
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
        isUrgentMode() {
            const d = this.daysLeft();
            return d > 0 && d <= 45;
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
                    Setup Cepat 3-Langkah (Siap Langsung Pakai)
                </span>
                <span class="font-medium text-[#6E665E] tabular-nums" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
            </div>
            <h1 class="mt-1.5 text-2xl font-bold tracking-tight text-[#2B2520]">
                Mulai Perencanaan Pernikahan
            </h1>
            <p class="text-xs text-[#6E665E] mt-0.5">
                Sistem otomatis menyusun jadwal tugas, batas anggaran, dan linimasa sesuai situasi Anda.
            </p>

            {{-- SVG Stepper 3-Langkah Minimalis --}}
            <div class="pt-4">
                <div class="grid grid-cols-3 gap-2">
                    <div class="h-1.5 rounded-full transition-colors" :class="step >= 1 ? 'bg-[#8C5835]' : 'bg-[#E7E4DF]'"></div>
                    <div class="h-1.5 rounded-full transition-colors" :class="step >= 2 ? 'bg-[#8C5835]' : 'bg-[#E7E4DF]'"></div>
                    <div class="h-1.5 rounded-full transition-colors" :class="step >= 3 ? 'bg-[#8C5835]' : 'bg-[#E7E4DF]'"></div>
                </div>
                <div class="flex justify-between text-[11px] font-medium text-[#6E665E] pt-1.5">
                    <span :class="step === 1 ? 'text-[#8C5835] font-semibold' : ''">1. Tanggal Hari-H</span>
                    <span :class="step === 2 ? 'text-[#8C5835] font-semibold' : ''">2. Target Anggaran</span>
                    <span :class="step === 3 ? 'text-[#8C5835] font-semibold' : ''">3. Data Pasangan</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('onboarding.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="event_type" :value="eventType">
            <input type="hidden" name="helper_mode" :value="helperMode">
            <input type="hidden" name="venue_name" value="Gedung / Tempat Acara">

            {{-- LANGKAH 1: TANGGAL RESEPSI & DETEKSI OTOMATIS MODE KILAT --}}
            <div x-show="step === 1" class="space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-[#2B2520]">Kapan Hari Pernikahan Anda?</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">
                        Pilih tanggal hari-H. Sistem otomatis menyesuaikan jadwal persiapan baik waktu mepet maupun santai.
                    </p>
                </div>

                {{-- Pilihan Cepat --}}
                <div class="space-y-2">
                    <span class="text-xs font-medium text-[#2B2520] block">Pilihan Cepat Waktu:</span>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <button type="button" @click="setDateInDays(30)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() <= 35 && daysLeft() > 0 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            1 Bulan Lagi (Mendesak)
                        </button>
                        <button type="button" @click="setDateInDays(45)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() > 35 && daysLeft() <= 50 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                            1,5 Bulan Lagi
                        </button>
                        <button type="button" @click="setDateInMonths(3)" class="rounded-lg border px-3 py-1.5 font-medium transition cursor-pointer" :class="daysLeft() > 50 && daysLeft() <= 100 ? 'border-[#8C5835] bg-[#F4ECE4] text-[#8C5835] font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
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

                {{-- Kotak Status Adaptif Waktu --}}
                <div class="rounded-xl border p-4 text-xs transition" :class="isUrgentMode() ? 'border-[#F6E6CC] bg-[#FEF7EC] text-[#A06414]' : 'border-[#D3E6DA] bg-[#EAF4EE] text-[#2E5E3E]'">
                    <div class="flex items-center gap-2 font-semibold text-sm">
                        <template x-if="isUrgentMode()">
                            <span>⚡ Mode Kilat &amp; Persiapan Cepat Aktif</span>
                        </template>
                        <template x-if="!isUrgentMode()">
                            <span>✓ Mode Terencana Aktif</span>
                        </template>
                    </div>
                    <p class="mt-1 leading-relaxed text-xs text-[#2B2520]">
                        <template x-if="isUrgentMode()">
                            <span>Sisa waktu <strong class="tabular-nums" x-text="daysLeft()"></strong> hari. Sistem otomatis mengunci prioritas terdepan: Berkas KUA dan pemilihan Venue diletakkan di minggu pertama agar pernikahan tetap siap tepat waktu.</span>
                        </template>
                        <template x-if="!isUrgentMode()">
                            <span>Sisa waktu <strong class="tabular-nums" x-text="daysLeft()"></strong> hari. Waktu persiapan Anda ideal. Rangkaian tugas disebar secara merata dan tenang hingga hari-H.</span>
                        </template>
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

            {{-- LANGKAH 2: TARGET ANGGARAN & JUMLAH TAMU --}}
            <div x-show="step === 2" x-cloak class="space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-[#2B2520]">Berapa Estimasi Anggaran Anda?</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">
                        Pilih nominal pagu. Sistem akan otomatis membagi proporsi katering, venue, dan pos biaya lainnya secara ideal.
                    </p>
                </div>

                {{-- Preset Chips Anggaran --}}
                <div class="space-y-2">
                    <span class="text-xs font-medium text-[#2B2520] block">Pilihan Pagu Anggaran:</span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['35000000' => 'Rp 35 Juta', '50000000' => 'Rp 50 Juta', '70000000' => 'Rp 70 Juta', '100000000' => 'Rp 100 Juta', '150000000' => 'Rp 150 Juta', '250000000' => 'Rp 250 Juta', '350000000' => 'Rp 350 Juta', 'custom' => 'Nominal Lain'] as $val => $label)
                            <label class="flex items-center justify-center rounded-lg border p-2.5 text-xs font-medium cursor-pointer transition text-center" :class="budgetChoice === '{{ $val }}' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                                <input type="radio" name="total_budget" value="{{ $val }}" x-model="budgetChoice" class="sr-only">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div x-show="budgetChoice === 'custom'" class="pt-2">
                        <label for="budget_custom" class="mb-1 block text-xs font-medium text-[#2B2520]">Ketikkan Nominal Anggaran (Rp)</label>
                        <input id="budget_custom" type="number" name="budget_custom" min="10000000" step="1000000" placeholder="Contoh: 80000000" class="editorial-input tabular-nums">
                    </div>
                </div>

                {{-- Pilihan Tamu Undangan --}}
                <div class="space-y-2 pt-2 border-t border-[#F0ECE4]">
                    <span class="text-xs font-medium text-[#2B2520] block">Perkiraan Jumlah Tamu:</span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['100' => '100 Tamu (Intimate)', '300' => '300 Tamu', '500' => '500 Tamu (Standar)', '800' => '800+ Tamu'] as $val => $label)
                            <label class="flex items-center justify-center rounded-lg border p-2.5 text-xs font-medium cursor-pointer transition text-center" :class="guestChoice === '{{ $val }}' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">
                                <input type="radio" name="guest_target" value="{{ $val }}" x-model="guestChoice" class="sr-only">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Visual Alokasi Otomatis --}}
                <div class="rounded-xl border border-[#E7E4DF] bg-[#FAF8F5] p-3.5 text-xs space-y-2">
                    <span class="font-semibold text-[#2B2520] block">Estimasi Pembagian Proporsi Otomatis:</span>
                    <div class="h-3 w-full rounded-full overflow-hidden flex">
                        <div class="bg-[#8C5835]" style="width: 40%" title="Venue & Katering 40%"></div>
                        <div class="bg-[#A06414]" style="width: 15%" title="Dekorasi 15%"></div>
                        <div class="bg-[#483725]" style="width: 15%" title="Busana & Rias 15%"></div>
                        <div class="bg-[#2E5E3E]" style="width: 10%" title="Dokumentasi 10%"></div>
                        <div class="bg-[#6E665E]" style="width: 10%" title="Dokumen KUA 10%"></div>
                        <div class="bg-[#D3E6DA]" style="width: 10%" title="Dana Cadangan 10%"></div>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-[#6E665E] pt-1">
                        <span>• Venue &amp; Katering: 40%</span>
                        <span>• Dekorasi: 15%</span>
                        <span>• MUA &amp; Busana: 15%</span>
                        <span>• Foto/Video: 10%</span>
                        <span>• Dokumen KUA: 10%</span>
                        <span>• Cadangan: 10%</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-[#F0ECE4]">
                    <button type="button" @click="step = 1" class="rounded-lg border border-[#E7E4DF] bg-white px-4 py-2 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer">&larr; Kembali</button>
                    <button type="button" @click="step = 3" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-5 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815] cursor-pointer">
                        <span>Lanjut ke Data Diri</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>

            {{-- LANGKAH 3: NAMA MEMPELAI & KOTA (SUBMIT) --}}
            <div x-show="step === 3" x-cloak class="space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-[#2B2520]">Data Pasangan &amp; Lokasi Acara</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">
                        Isikan nama Anda berdua untuk judul lembar kerja dan laporan.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label for="bride_name" class="mb-1.5 block font-medium text-[#2B2520]">Nama Calon Pengantin Wanita</label>
                        <input id="bride_name" type="text" name="bride_name" x-model="brideName" placeholder="Contoh: Aurelia Larasati" required class="editorial-input">
                    </div>
                    <div>
                        <label for="groom_name" class="mb-1.5 block font-medium text-[#2B2520]">Nama Calon Pengantin Pria</label>
                        <input id="groom_name" type="text" name="groom_name" x-model="groomName" placeholder="Contoh: Reza Mahendra" required class="editorial-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label for="city" class="mb-1.5 block font-medium text-[#2B2520]">Kota Acara</label>
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
                    <div>
                        <label for="wedding_time" class="mb-1.5 block font-medium text-[#2B2520]">Waktu Resepsi</label>
                        <select id="wedding_time" name="wedding_time" class="editorial-input">
                            <option value="08:00 – 13:00 WIB (Pagi/Siang)">Pagi / Siang (08.00 – 13.00)</option>
                            <option value="15:30 – 21:00 WIB (Sore/Malam)">Sore / Malam (15.30 – 21.00)</option>
                            <option value="09:00 – 17:00 WIB (Seharian)">Seharian Penuh</option>
                        </select>
                    </div>
                </div>

                {{-- Rangkuman Siap Generate --}}
                <div class="rounded-xl border border-[#D3E6DA] bg-[#EAF4EE] p-4 text-xs space-y-1.5">
                    <span class="inline-flex items-center rounded-md border border-[#D3E6DA] bg-white px-2 py-0.5 text-xs font-semibold text-[#2E5E3E]">
                        Rencana Siap Dibuat Otomatis
                    </span>
                    <p class="font-semibold text-sm text-[#2B2520]" x-text="(brideName || 'Calon Pengantin') + ' & ' + (groomName || 'Pasangan')"></p>
                    <p class="text-[#6E665E]" x-text="'Target Tanggal: ' + weddingDate + ' (' + daysLeft() + ' hari lagi) di ' + city"></p>
                    <p class="text-[#6E665E]" x-text="'Pagu Anggaran: ' + formatIdr(currentBudget()) + ' · Target ' + guestChoice + ' Tamu'"></p>
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
