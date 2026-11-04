<section id="simulasi-adat" class="mt-10 editorial-card rounded-lg p-6 sm:p-8">
    <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-5 lg:flex-row lg:items-center">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-md bg-champagne-light text-champagne">
                <x-ui.icon name="sliders" class="h-5 w-5" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-espresso">Simulasi Interaktif Anggaran &amp; Adat Nusantara</h2>
                <p class="text-xs text-taupe">Geser jumlah tamu untuk melihat pembagian biaya otomatis (Livewire Reactive)</p>
            </div>
        </div>
        <div class="rounded-md border border-stone-line bg-alabaster px-4 py-2.5 text-left lg:text-right">
            <span class="text-[11px] font-medium text-taupe">Rekomendasi Total Plafon</span>
            <p class="tabular-nums text-2xl font-bold text-champagne">{{ format_idr($total) }}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12 lg:items-center">
        <div class="space-y-5 lg:col-span-5">
            <div>
                <div class="flex items-center justify-between text-xs font-semibold text-charcoal">
                    <label for="lw-guests">Jumlah Undangan</label>
                    <span class="tabular-nums rounded bg-ivory px-2 py-0.5 text-espresso">{{ $guests }} Tamu</span>
                </div>
                <input
                    id="lw-guests"
                    type="range"
                    min="100"
                    max="1000"
                    step="50"
                    wire:model.live="guests"
                    class="mt-2 w-full accent-champagne cursor-pointer"
                >
                <div class="mt-1 flex justify-between text-[11px] text-taupe tabular-nums">
                    <span>100</span>
                    <span>500</span>
                    <span>1.000 Tamu</span>
                </div>
            </div>

            <div>
                <span class="mb-2 block text-xs font-semibold text-charcoal">Skala Prosesi Adat</span>
                {{-- deslop-ignore-next-line 28 --}}
                <div class="grid grid-cols-3 gap-2">
                    <button
                        type="button"
                        wire:click="setScale('intimate')"
                        class="rounded border px-3 py-2 text-left text-xs font-semibold transition cursor-pointer {{ $adatScale === 'intimate' ? 'border-espresso bg-espresso text-alabaster' : 'border-stone-line bg-alabaster text-charcoal' }}"
                    >
                        Akad Intimate
                    </button>
                    <button
                        type="button"
                        wire:click="setScale('keraton')"
                        class="rounded border px-3 py-2 text-left text-xs font-semibold transition cursor-pointer {{ $adatScale === 'keraton' ? 'border-espresso bg-espresso text-alabaster' : 'border-stone-line bg-alabaster text-charcoal' }}"
                    >
                        Jawa Keraton
                    </button>
                    <button
                        type="button"
                        wire:click="setScale('grand')"
                        class="rounded border px-3 py-2 text-left text-xs font-semibold transition cursor-pointer {{ $adatScale === 'grand' ? 'border-espresso bg-espresso text-alabaster' : 'border-stone-line bg-alabaster text-charcoal' }}"
                    >
                        Resepsi Agung
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:col-span-7">
            @foreach($allocations as $alloc)
                <div class="rounded border border-stone-line bg-alabaster p-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1.5 font-semibold text-charcoal">
                            <x-ui.icon :name="$alloc['icon']" class="h-4 w-4 text-champagne" />
                            <span>{{ $alloc['label'] }}</span>
                        </span>
                        <span class="tabular-nums font-bold text-champagne">{{ $alloc['percent'] }}%</span>
                    </div>
                    <p class="mt-2 tabular-nums text-lg font-bold text-espresso">{{ format_idr($alloc['amount']) }}</p>
                    <div class="mt-2">
                        <x-ui.progress-bar size="sm" :percent="$alloc['percent'] * 2" tone="champagne" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
