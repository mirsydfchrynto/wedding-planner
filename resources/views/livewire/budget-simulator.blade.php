<section id="simulasi-adat" class="w-full bg-espresso text-alabaster">
    <div class="mx-auto grid min-h-[82svh] max-w-7xl grid-cols-1 gap-10 px-6 py-16 lg:grid-cols-12 lg:items-center lg:px-10 lg:py-24">
        {{-- Left Half (Input Half on Dark Field) --}}
        <div class="space-y-6 lg:col-span-5" data-reveal>
            <h2 class="text-3xl font-bold leading-tight text-alabaster sm:text-4xl">
                Simulasi anggaran Nusantara.
            </h2>
            <p class="text-lg leading-relaxed text-stone-line">
                Sesuaikan jumlah tamu undangan dan skala rangkaian adat untuk menghitung rekomendasi plafon anggaran secara instan. Mengubah input di sebelah kiri langsung memperbarui lembar alokasi 8 kategori di sebelah kanan.
            </p>

            <div class="space-y-5 pt-2">
                <div>
                    <div class="flex items-center justify-between text-base font-semibold text-alabaster">
                        <label for="lw-guests">Jumlah Tamu Undangan</label>
                        <span class="tabular-nums rounded bg-charcoal px-3 py-1 text-base text-alabaster">{{ $guests }} Tamu</span>
                    </div>
                    <input
                        id="lw-guests"
                        type="range"
                        min="100"
                        max="1000"
                        step="50"
                        wire:model.live="guests"
                        class="mt-3 w-full accent-champagne cursor-pointer"
                    >
                    <div class="mt-1.5 flex justify-between text-sm text-stone-strong tabular-nums">
                        <span>100 Tamu</span>
                        <span>500 Tamu</span>
                        <span>1.000 Tamu</span>
                    </div>
                </div>

                <div>
                    <p class="mb-2.5 text-base font-semibold text-alabaster">Pilih Skala Prosesi Adat</p>
                    {{-- deslop-ignore-next-line 28 --}}
                    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                        <button
                            type="button"
                            wire:click="setScale('intimate')"
                            class="rounded px-4 py-3 text-left text-sm font-semibold transition cursor-pointer {{ $adatScale === 'intimate' ? 'bg-alabaster text-espresso' : 'bg-charcoal text-stone-line hover:bg-taupe' }}"
                        >
                            Akad Intimate
                        </button>
                        <button
                            type="button"
                            wire:click="setScale('keraton')"
                            class="rounded px-4 py-3 text-left text-sm font-semibold transition cursor-pointer {{ $adatScale === 'keraton' ? 'bg-alabaster text-espresso' : 'bg-charcoal text-stone-line hover:bg-taupe' }}"
                        >
                            Jawa Keraton
                        </button>
                        <button
                            type="button"
                            wire:click="setScale('grand')"
                            class="rounded px-4 py-3 text-left text-sm font-semibold transition cursor-pointer {{ $adatScale === 'grand' ? 'bg-alabaster text-espresso' : 'bg-charcoal text-stone-line hover:bg-taupe' }}"
                        >
                            Resepsi Agung
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Half (Output Half — True-Size Interface Fragment #2 marked data-ui) --}}
        <div class="lg:col-span-7" data-ui data-reveal style="--delay: 120ms">
            <div class="rounded-lg bg-alabaster p-6 text-espresso sm:p-8">
                <div class="flex flex-col justify-between gap-2 pb-5 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-lg font-bold text-espresso">Simulasi Interaktif Anggaran &amp; Adat Nusantara</p>
                        <p class="text-sm text-taupe">Rekomendasi Alokasi Otomatis ({{ $guests }} Tamu)</p>
                    </div>
                    <p class="tabular-nums text-2xl font-bold text-champagne sm:text-3xl">{{ format_idr($total) }}</p>
                </div>

                <div class="space-y-3">
                    @foreach($allocations as $alloc)
                        <div class="rounded bg-pearl p-4">
                            <div class="flex items-center justify-between text-base">
                                <span class="font-bold text-espresso">{{ $alloc['label'] }} ({{ $alloc['percent'] }}%)</span>
                                <span class="tabular-nums font-bold text-champagne">{{ format_idr($alloc['amount']) }}</span>
                            </div>
                            <div class="mt-2">
                                <x-ui.progress-bar size="sm" :percent="$alloc['percent'] * 2" tone="champagne" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
