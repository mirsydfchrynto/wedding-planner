<section
    id="simulasi-adat"
    class="w-full bg-espresso text-alabaster"
    x-data="{
        guests: 450,
        adatScale: 'keraton',
        baseAdat() {
            if (this.adatScale === 'intimate') return 45000000;
            if (this.adatScale === 'grand') return 135000000;
            return 95000000;
        },
        total() {
            return (Number(this.guests) * 650000) + this.baseAdat();
        },
        formatIdr(val) {
            return 'Rp ' + Number(val).toLocaleString('id-ID');
        }
    }"
>
    <div class="mx-auto grid min-h-[82svh] max-w-7xl grid-cols-1 gap-10 px-6 py-16 lg:grid-cols-12 lg:items-center lg:px-10 lg:py-24">
        {{-- Left Half (Interactive Sliders & Scale Controls) --}}
        <div class="space-y-6 lg:col-span-5" data-reveal>
            <h2 class="text-3xl font-bold leading-tight text-alabaster sm:text-4xl">
                Simulasi anggaran Nusantara.
            </h2>
            <p class="text-base sm:text-lg leading-relaxed text-stone-line">
                Geser jumlah tamu undangan dan pilih skala prosesi adat untuk menghitung alokasi anggaran secara langsung. Perubahan nilai di sebelah kiri seketika mengkalkulasi ulang lembar alokasi di sebelah kanan.
            </p>

            <div class="space-y-6 pt-2">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-center justify-between text-base font-semibold text-alabaster">
                        <label for="lw-guests" class="text-sm text-stone-line">Jumlah Tamu Undangan</label>
                        <span class="tabular-nums font-mono font-bold rounded-lg bg-charcoal px-3 py-1 text-base text-alabaster border border-white/10" x-text="guests + ' Tamu'">
                            450 Tamu
                        </span>
                    </div>
                    <input
                        id="lw-guests"
                        type="range"
                        min="100"
                        max="1000"
                        step="25"
                        x-model.number="guests"
                        class="mt-4 w-full accent-champagne cursor-pointer"
                        aria-label="Jumlah Tamu Undangan"
                    >
                    <div class="mt-2 flex justify-between text-xs text-stone-strong tabular-nums font-mono">
                        <span>100 Tamu</span>
                        <span>500 Tamu</span>
                        <span>1.000 Tamu</span>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-sm font-semibold text-stone-line">Pilih Skala Prosesi Adat</p>
                    <div class="grid grid-cols-3 gap-2.5">
                        <button
                            type="button"
                            @click="adatScale = 'intimate'"
                            class="rounded-xl px-3 py-3 text-xs sm:text-sm font-semibold transition text-center cursor-pointer border"
                            :class="adatScale === 'intimate' ? 'bg-alabaster text-espresso border-alabaster shadow' : 'bg-white/5 text-stone-line border-white/10 hover:bg-white/10'"
                        >
                            Akad Intimate
                            <span class="block text-[11px] font-normal opacity-75 mt-0.5">Dasar Rp 45 Jt</span>
                        </button>
                        <button
                            type="button"
                            @click="adatScale = 'keraton'"
                            class="rounded-xl px-3 py-3 text-xs sm:text-sm font-semibold transition text-center cursor-pointer border"
                            :class="adatScale === 'keraton' ? 'bg-alabaster text-espresso border-alabaster shadow' : 'bg-white/5 text-stone-line border-white/10 hover:bg-white/10'"
                        >
                            Jawa Keraton
                            <span class="block text-[11px] font-normal opacity-75 mt-0.5">Dasar Rp 95 Jt</span>
                        </button>
                        <button
                            type="button"
                            @click="adatScale = 'grand'"
                            class="rounded-xl px-3 py-3 text-xs sm:text-sm font-semibold transition text-center cursor-pointer border"
                            :class="adatScale === 'grand' ? 'bg-alabaster text-espresso border-alabaster shadow' : 'bg-white/5 text-stone-line border-white/10 hover:bg-white/10'"
                        >
                            Resepsi Agung
                            <span class="block text-[11px] font-normal opacity-75 mt-0.5">Dasar Rp 135 Jt</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Half (Instant Real-Time Output Sheet) --}}
        <div class="lg:col-span-7" data-ui data-reveal style="--delay: 120ms">
            <div class="rounded-2xl bg-alabaster p-6 text-espresso sm:p-8 shadow-xl">
                <div class="flex flex-col justify-between gap-2 pb-5 border-b border-stone-line sm:flex-row sm:items-end">
                    <div>
                        <p class="text-base sm:text-lg font-bold text-espresso">Simulasi Interaktif Anggaran &amp; Adat Nusantara</p>
                        <p class="text-xs sm:text-sm text-taupe">
                            Kalkulasi Otomatis (<span class="tabular-nums font-bold text-espresso" x-text="guests">450</span> Tamu)
                        </p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-taupe block">Estimasi Plafon Induk</span>
                        <p class="tabular-nums text-2xl font-bold text-champagne sm:text-3xl whitespace-nowrap" x-text="formatIdr(total())">
                            Rp 387.500.000
                        </p>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    <div class="rounded-xl bg-pearl p-4 border border-black/[0.05]">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-espresso">Katering &amp; Jamuan (35%)</span>
                            <span class="tabular-nums font-mono font-bold text-champagne whitespace-nowrap" x-text="formatIdr(Math.round(total() * 0.35))">
                                Rp 135.625.000
                            </span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-stone-line/50 overflow-hidden">
                            <div class="h-full bg-champagne rounded-full" style="width: 35%"></div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-pearl p-4 border border-black/[0.05]">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-espresso">Venue &amp; Gedung (22%)</span>
                            <span class="tabular-nums font-mono font-bold text-champagne whitespace-nowrap" x-text="formatIdr(Math.round(total() * 0.22))">
                                Rp 85.250.000
                            </span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-stone-line/50 overflow-hidden">
                            <div class="h-full bg-champagne rounded-full" style="width: 22%"></div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-pearl p-4 border border-black/[0.05]">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-espresso">Dekorasi &amp; Adat Paes (23%)</span>
                            <span class="tabular-nums font-mono font-bold text-champagne whitespace-nowrap" x-text="formatIdr(Math.round(total() * 0.23))">
                                Rp 89.125.000
                            </span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-stone-line/50 overflow-hidden">
                            <div class="h-full bg-champagne rounded-full" style="width: 23%"></div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-pearl p-4 border border-black/[0.05]">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-espresso">Busana, Foto &amp; WO (20%)</span>
                            <span class="tabular-nums font-mono font-bold text-champagne whitespace-nowrap" x-text="formatIdr(Math.round(total() * 0.20))">
                                Rp 77.500.000
                            </span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-stone-line/50 overflow-hidden">
                            <div class="h-full bg-champagne rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-stone-line flex items-center justify-between text-xs text-taupe">
                    <span>*Rasio kalkulasi berdasarkan standar operasional WO pernikahan Indonesia</span>
                    <a href="{{ auth()->check() ? route('dashboard.index') : route('login') }}" class="font-bold text-champagne hover:underline">
                        Buka Rincian Lengkap &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
