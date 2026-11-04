@extends('layouts.app', ['title' => 'Layanan SaaS Pernikahan Nusantara'])

@section('content')
    {{-- Section 1: SaaS Hero + 3D Spatial Product Preview --}}
    <section class="editorial-card batik-kawung-bg relative rounded-lg p-6 sm:p-10 lg:p-12">
        <x-ui.batik-ornament variant="gunungan" class="absolute right-6 bottom-6 h-48 w-36 opacity-30" />

        <div class="relative z-10 grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-6">
                {{-- deslop-ignore-next-line 11 --}}
                <h1 class="font-editorial text-4xl font-semibold leading-[1.06] text-espresso sm:text-5xl">
                    Satu buku kerja untuk anggaran, vendor, dan adat pernikahan.
                </h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-charcoal">
                    Atelier Vow membantu calon pengantin dan biro Wedding Organizer di Indonesia menyusun plafon biaya 8 kategori, memantau kontrak serta jatuh tempo DP vendor, dan menjadwalkan tahapan adat dari H-12 bulan hingga Hari-H tanpa lembar kerja yang tercerai-berai.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard.index') : route('login') }}" class="editorial-btn-primary">
                        {{ auth()->check() ? 'Buka Dashboard Pernikahan' : 'Masuk dengan Google & Mulai Rencanakan' }}
                    </a>
                    <a href="#simulasi-adat" class="editorial-btn-secondary">
                        Simulasi Anggaran Nusantara
                    </a>
                </div>

                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-8 grid grid-cols-3 gap-4 border-t border-stone-line pt-5 text-xs">
                    <div>
                        <span class="text-taupe">Cakupan Modul</span>
                        <p class="mt-0.5 tabular-nums font-semibold text-espresso">5 Modul Terhubung</p>
                    </div>
                    <div>
                        <span class="text-taupe">Kategori Anggaran</span>
                        <p class="mt-0.5 tabular-nums font-semibold text-espresso">8 Pos Standar WO</p>
                    </div>
                    <div>
                        <span class="text-taupe">Arsitektur Cloud</span>
                        <p class="mt-0.5 tabular-nums font-semibold text-champagne">12-Factor Ready</p>
                    </div>
                </div>
            </div>

            {{-- 3D Interactive App Fragment Preview (data-ui) --}}
            <div class="stage-3d lg:col-span-6" data-ui>
                <div
                    x-data="spatialTilt(4)"
                    @mousemove="handleMove($event)"
                    @mouseleave="handleLeave()"
                    :style="{ transform: transformStyle }"
                    class="tilt-card-3d rounded-lg border border-stone-strong bg-pearl p-5 sm:p-6"
                >
                    <div class="flex items-center justify-between border-b border-stone-line pb-3 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="font-editorial text-base font-semibold text-espresso">{{ $profile->couple_names }}</span>
                            <span class="text-taupe">· {{ $profile->venue_name }}</span>
                        </div>
                        <x-ui.status-badge tone="champagne" :label="'H-' . $summary['days_remaining'] . ' Hari'" />
                    </div>

                    {{-- deslop-ignore-next-line 28 --}}
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        <div class="rounded border border-stone-line bg-alabaster p-3">
                            <span class="text-[11px] text-taupe">Plafon Anggaran</span>
                            <p class="mt-1 font-editorial tabular-nums text-lg font-semibold text-espresso">{{ format_idr($summary['total_budget_cap']) }}</p>
                        </div>
                        <div class="rounded border border-stone-line bg-alabaster p-3">
                            <span class="text-[11px] text-taupe">Realisasi Aktual</span>
                            <p class="mt-1 font-editorial tabular-nums text-lg font-semibold text-espresso">{{ format_idr($summary['total_actual']) }}</p>
                        </div>
                        <div class="rounded border border-stone-line bg-alabaster p-3">
                            <span class="text-[11px] text-taupe">Sisa Plafon</span>
                            <p class="mt-1 font-editorial tabular-nums text-lg font-semibold text-sage">{{ format_idr($summary['remaining_budget']) }}</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2.5 rounded border border-stone-line bg-alabaster p-3.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-espresso">Kesiapan Prosesi &amp; Kontrak Mitra</span>
                            <span class="tabular-nums font-semibold text-champagne">{{ $summary['readiness_score'] }}% Terukur</span>
                        </div>
                        <x-ui.progress-bar size="sm" :percent="$summary['readiness_score']" tone="champagne" />
                        <div class="flex items-center justify-between pt-1 text-[11px] text-taupe">
                            <span>{{ $summary['booked_vendors'] }}/{{ $summary['total_vendors'] }} vendor terkonfirmasi</span>
                            <span>{{ $summary['completed_tasks'] }}/{{ $summary['total_tasks'] }} tugas checklist selesai</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-taupe">
                        <span>Arahkan kursor untuk melihat kedalaman spasial 3D</span>
                        <a href="{{ auth()->check() ? route('dashboard.index') : route('login') }}" class="font-semibold text-champagne hover:underline">Masuk ke Workspace</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 2: "One Live Thing" — Interactive Nusantara Wedding Budget & Adat Simulator --}}
    <section
        id="simulasi-adat"
        class="mt-12 editorial-card rounded-lg p-6 sm:p-8"
        x-data="{
            guests: 450,
            adatType: 'keraton',
            perGuestRate: 650000,
            adatBaseCost() {
                if (this.adatType === 'intimate') return 45000000;
                if (this.adatType === 'keraton') return 95000000;
                return 135000000;
            },
            totalEstimate() {
                return (this.guests * this.perGuestRate) + this.adatBaseCost();
            },
            formatRupiah(val) {
                return 'Rp ' + Math.round(val).toLocaleString('id-ID');
            }
        }"
    >
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-5 lg:flex-row lg:items-end">
            <div>
                <h2 class="font-editorial text-3xl font-semibold text-espresso">Simulasi Interaktif Anggaran &amp; Adat Nusantara</h2>
                <p class="mt-1 text-sm text-taupe">
                    Sesuaikan jumlah undangan dan rangkaian prosesi adat untuk menghitung rekomendasi plafon anggaran secara instan.
                </p>
            </div>
            <div class="text-left lg:text-right">
                <span class="text-xs text-taupe">Estimasi Total Plafon Rekomendasi</span>
                <p class="font-editorial tabular-nums text-3xl font-bold text-champagne" x-text="formatRupiah(totalEstimate())">Rp 387.500.000</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-5 lg:col-span-6">
                <div>
                    <div class="flex items-center justify-between text-xs font-semibold text-charcoal">
                        <label for="sim-guests">Target Jumlah Tamu Undangan</label>
                        <span class="tabular-nums text-espresso"><strong x-text="guests">450</strong> Tamu</span>
                    </div>
                    <input
                        id="sim-guests"
                        type="range"
                        min="100"
                        max="1000"
                        step="50"
                        x-model.number="guests"
                        class="mt-2 w-full accent-champagne cursor-pointer"
                    >
                    <div class="mt-1 flex justify-between text-[11px] text-taupe tabular-nums">
                        <span>100 Tamu (Intimate)</span>
                        <span>500 Tamu (Gedung)</span>
                        <span>1.000 Tamu (Grand Ballroom)</span>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-charcoal">Pilih Skala Rangkaian Prosesi Adat</label>
                    {{-- deslop-ignore-next-line 28 --}}
                    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                        <button
                            type="button"
                            @click="adatType = 'intimate'"
                            :class="adatType === 'intimate' ? 'border-espresso bg-espresso text-alabaster' : 'border-stone-line bg-alabaster text-charcoal'"
                            class="rounded border p-3 text-left text-xs transition cursor-pointer"
                        >
                            <p class="font-semibold">Akad Intimate</p>
                            <p class="mt-0.5 text-[11px] opacity-80">Ijab Qabul &amp; Jamuan Keluarga</p>
                        </button>
                        <button
                            type="button"
                            @click="adatType = 'keraton'"
                            :class="adatType === 'keraton' ? 'border-espresso bg-espresso text-alabaster' : 'border-stone-line bg-alabaster text-charcoal'"
                            class="rounded border p-3 text-left text-xs transition cursor-pointer"
                        >
                            <p class="font-semibold">Adat Jawa Keraton</p>
                            <p class="mt-0.5 text-[11px] opacity-80">Siraman, Midodareni, Panggih</p>
                        </button>
                        <button
                            type="button"
                            @click="adatType = 'grand'"
                            :class="adatType === 'grand' ? 'border-espresso bg-espresso text-alabaster' : 'border-stone-line bg-alabaster text-charcoal'"
                            class="rounded border p-3 text-left text-xs transition cursor-pointer"
                        >
                            <p class="font-semibold">Nusantara Agung</p>
                            <p class="mt-0.5 text-[11px] opacity-80">Adat Lengkap &amp; Resepsi Malam</p>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:col-span-6">
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <span class="text-xs text-taupe">Katering &amp; Jamuan (35%)</span>
                    <p class="mt-1.5 font-editorial tabular-nums text-xl font-semibold text-espresso" x-text="formatRupiah(totalEstimate() * 0.35)"></p>
                    <p class="mt-1 text-[11px] text-taupe">Buffet &amp; pondokan tamu</p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <span class="text-xs text-taupe">Venue &amp; Gedung (22%)</span>
                    <p class="mt-1.5 font-editorial tabular-nums text-xl font-semibold text-espresso" x-text="formatRupiah(totalEstimate() * 0.22)"></p>
                    <p class="mt-1 text-[11px] text-taupe">Sewa ruang akad &amp; resepsi</p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <span class="text-xs text-taupe">Dekorasi &amp; Adat (23%)</span>
                    <p class="mt-1.5 font-editorial tabular-nums text-xl font-semibold text-espresso" x-text="formatRupiah(totalEstimate() * 0.23)"></p>
                    <p class="mt-1 text-[11px] text-taupe">Pelaminan, gebyok &amp; floral</p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <span class="text-xs text-taupe">Busana, Foto &amp; WO (20%)</span>
                    <p class="mt-1.5 font-editorial tabular-nums text-xl font-semibold text-espresso" x-text="formatRupiah(totalEstimate() * 0.20)"></p>
                    <p class="mt-1 text-[11px] text-taupe">Kebaya, beskap, dokumentasi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3: 5 Integrated Modules & Nusantara Cultural Heritage --}}
    <section id="fitur-platform" class="mt-12 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <article class="editorial-card rounded-lg p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <h2 class="font-editorial text-2xl font-semibold text-espresso">1. Lima Modul Inti yang Saling Terhubung</h2>
                <x-ui.status-badge tone="sage" label="Workspace Pengantin" />
            </div>
            <p class="mt-3 text-sm leading-relaxed text-charcoal">
                Begitu masuk menggunakan akun Google, seluruh anggota keluarga inti dan pasangan bekerja pada satu sumber data yang sama.
            </p>
            <ul class="mt-4 space-y-2.5 text-xs text-charcoal">
                <li class="flex items-start justify-between border-b border-stone-line/60 pb-2">
                    <span>Executive Dashboard: Hitung mundur akad, indeks kesiapan, dan biaya per tamu</span>
                    <span class="font-semibold text-champagne">Modul #5</span>
                </li>
                <li class="flex items-start justify-between border-b border-stone-line/60 pb-2">
                    <span>Budget Planner: Plafon induk, 8 kategori pengeluaran, estimasi vs aktual</span>
                    <span class="font-semibold text-champagne">Modul #1</span>
                </li>
                <li class="flex items-start justify-between border-b border-stone-line/60 pb-2">
                    <span>Wedding Checklist &amp; Timeline: Daftar tugas PIC &amp; peta H-12 Bulan s/d Hari-H</span>
                    <span class="font-semibold text-champagne">Modul #2 &amp; #3</span>
                </li>
                <li class="flex items-start justify-between">
                    <span>Vendor Management: Kontrak mitra, pelacakan DP/lunas, dan WhatsApp PIC</span>
                    <span class="font-semibold text-champagne">Modul #4</span>
                </li>
            </ul>
        </article>

        <article class="editorial-card rounded-lg p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <h2 class="font-editorial text-2xl font-semibold text-espresso">2. Tata Adat Jawa &amp; Kendali Mitra WO</h2>
                <x-ui.status-badge tone="champagne" label="Nusantara & Enterprise" />
            </div>
            <p class="mt-3 text-sm leading-relaxed text-charcoal">
                Dirancang khusus mengikuti alur pernikahan di Indonesia, mulai dari perhitungan Weton Jawa hingga pengawasan kontrak vendor bernilai ratusan juta rupiah.
            </p>
            <ul class="mt-4 space-y-2.5 text-xs text-charcoal">
                <li class="flex items-start justify-between border-b border-stone-line/60 pb-2">
                    <span>Panduan kesiapan Siraman, Malam Midodareni, Ijab Qabul, dan Panggih</span>
                    <span class="font-semibold text-sage">Tersedia</span>
                </li>
                <li class="flex items-start justify-between border-b border-stone-line/60 pb-2">
                    <span>Kalkulator Neptu Weton Jawa interaktif untuk hari &amp; pasaran mempelai</span>
                    <span class="font-semibold text-sage">Tersedia</span>
                </li>
                <li class="flex items-start justify-between border-b border-stone-line/60 pb-2">
                    <span>Autentikasi Google OAuth satu-klik &amp; isolasi hak akses berbasis peran</span>
                    <span class="font-semibold text-sage">Tersedia</span>
                </li>
                <li class="flex items-start justify-between">
                    <span>Cetak laporan eksekutif siap bawa ke pertemuan keluarga besar</span>
                    <span class="font-semibold text-sage">Tersedia</span>
                </li>
            </ul>
        </article>
    </section>

    {{-- Section 4: SaaS Pricing Plans --}}
    <section id="paket-saas" class="mt-12">
        <div class="border-b border-stone-line pb-4">
            <h2 class="font-editorial text-3xl font-semibold text-espresso">Paket Layanan Software-as-a-Service (SaaS)</h2>
            <p class="mt-1 text-sm text-taupe">Pilihan ruang kerja untuk pasangan mandiri maupun biro Wedding Organizer profesional.</p>
        </div>

        {{-- deslop-ignore-next-line 28 --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            @foreach($packages as $pkg)
                <article class="editorial-card flex flex-col justify-between rounded-lg p-6 {{ $pkg['featured'] ? 'border-champagne bg-champagne-light/30' : '' }}">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-champagne">{{ $pkg['audience'] }}</span>
                            @if($pkg['featured'])
                                <x-ui.status-badge tone="champagne" label="Pilihan Utama" />
                            @endif
                        </div>
                        <h3 class="mt-1 font-editorial text-2xl font-semibold text-espresso">{{ $pkg['name'] }}</h3>
                        <p class="mt-3 font-editorial tabular-nums text-3xl font-bold text-espresso">{{ $pkg['price'] }}</p>
                        <p class="text-xs text-taupe">{{ $pkg['period'] }}</p>
                        <p class="mt-3 text-xs leading-relaxed text-charcoal">{{ $pkg['description'] }}</p>

                        <ul class="mt-4 space-y-2 border-t border-stone-line pt-4 text-xs text-charcoal">
                            @foreach($pkg['features'] as $feat)
                                <li class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-sage" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5l3.5 3.5 7.5-7.5"/>
                                    </svg>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-6 pt-4 border-t border-stone-line">
                        <a
                            href="{{ $pkg['cta_route'] }}"
                            class="w-full {{ $pkg['featured'] ? 'editorial-btn-primary' : 'editorial-btn-secondary' }}"
                        >
                            {{ $pkg['cta_label'] }}
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Section 5: Marketing Campaign & Positioning Blueprint --}}
    <section class="mt-12 editorial-card rounded-lg p-6 sm:p-8">
        <div class="border-b border-stone-line pb-4">
            <h2 class="font-editorial text-2xl font-semibold text-espresso">Strategi Kampanye Pemasaran &amp; Positioning Produk</h2>
            <p class="mt-1 text-xs text-taupe">Rangkaian pesan komunikasi produk untuk peluncuran kepada calon pengantin dan mitra Wedding Organizer.</p>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-6 lg:grid-cols-12 text-xs">
            <div class="space-y-3 lg:col-span-5">
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <p class="font-semibold text-espresso">Positioning Statement</p>
                    <p class="mt-1 leading-relaxed text-charcoal">
                        Atelier Vow membantu pasangan calon pengantin Indonesia dan biro Wedding Organizer mengendalikan anggaran ratusan juta rupiah serta ratusan detail prosesi adat melalui satu buku kerja terhubung antara biaya, jadwal H-12 bulan, dan tagihan vendor.
                    </p>
                </div>
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <p class="font-semibold text-espresso">Sudut Kampanye Utama (Campaign Angle)</p>
                    <p class="mt-1 leading-relaxed text-charcoal">
                        Pembengkakan biaya pernikahan jarang terjadi karena satu pengeluaran besar, melainkan akumulasi selisih Rp 2 juta hingga Rp 5 juta pada katering, dekorasi, dan seragam yang tidak tercatat bersama jadwal jatuh tempo DP vendor.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-7">
                <p class="mb-2.5 font-semibold text-espresso">Alur Kampanye Email &amp; Pesan Peluncuran</p>
                <div class="divide-y divide-stone-line rounded border border-stone-line bg-alabaster">
                    <div class="p-3.5">
                        <div class="flex items-center justify-between font-semibold text-espresso">
                            <span>Hari 1 · Masalah Selisih Anggaran Tersembunyi</span>
                            <span class="text-champagne">Subjek: Mengapa anggaran 350 juta sering membengkak di H-30?</span>
                        </div>
                        <p class="mt-1 text-taupe">
                            Menunjukkan perbandingan langsung antara catatan spreadsheet terpisah dengan tabel realisasi 8 kategori di Atelier Vow.
                        </p>
                    </div>
                    <div class="p-3.5">
                        <div class="flex items-center justify-between font-semibold text-espresso">
                            <span>Hari 3 · Koordinasi Keluarga &amp; Prosesi Adat Nusantara</span>
                            <span class="text-champagne">Subjek: Pembagian tugas PIC pengantin, orang tua, dan WO</span>
                        </div>
                        <p class="mt-1 text-taupe">
                            Mendemonstrasikan filter checklist per PIC dan peta linimasa dari pengurusan berkas KUA (H-6 Bulan) hingga Gladi Bersih (H-7 Hari).
                        </p>
                    </div>
                    <div class="p-3.5">
                        <div class="flex items-center justify-between font-semibold text-espresso">
                            <span>Hari 7 · Undangan Ruang Kerja Live &amp; Cetak Laporan Keluarga</span>
                            <span class="text-champagne">Subjek: Siapkan ringkasan rapat keluarga dalam 1 klik cetak</span>
                        </div>
                        <p class="mt-1 text-taupe">
                            Mengajak pasangan dan WO mencoba langsung simulasi anggaran serta fitur Cetak Ringkasan Eksekutif untuk rapat keluarga besar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
