@extends('layouts.app', ['title' => 'Budget Planner'])

@section('content')
    <x-ui.section-header
        title="Anggaran Pernikahan"
    >
        <x-slot:actions>
            <button
                type="button"
                @click="$dispatch('open-modal', 'update-budget-cap-modal')"
                class="editorial-btn-secondary"
            >
                Ubah Plafon Induk
            </button>
            <a href="{{ route('budget.create') }}" class="editorial-btn-primary">
                + Tambah Pos Anggaran
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Financial Summary Strip --}}
    <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Plafon Anggaran"
            :value="format_idr($summary['total_budget_cap'])"
            :subtitle="'Target: ' . number_format($profile->guest_target, 0, ',', '.') . ' tamu'"
            badge="Plafon"
            badgeTone="champagne"
        />

        <x-ui.stat-card
            label="Total Estimasi"
            :value="format_idr($summary['total_estimated'])"
            :subtitle="round(($summary['total_estimated'] / max(1, $summary['total_budget_cap'])) * 100) . '% dari plafon'"
        />

        <x-ui.stat-card
            label="Total Realisasi"
            :value="format_idr($summary['total_actual'])"
            :subtitle="'Terbayar: ' . format_idr($summary['total_paid'])"
            :badge="$summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Over ' . format_idr(abs($summary['variance_amount']))"
            :badgeTone="$summary['variance_amount'] >= 0 ? 'sage' : 'wine'"
        />

        <x-ui.stat-card
            label="Sisa Anggaran"
            :value="format_idr($summary['remaining_budget'])"
            :subtitle="'Sisa tagihan: ' . format_idr(max(0, $summary['total_actual'] - $summary['total_paid']))"
            :badge="$summary['remaining_budget'] >= 0 ? 'Aman' : 'Over'"
            :badgeTone="$summary['remaining_budget'] >= 0 ? 'sage' : 'wine'"
        />
    </section>

    {{-- Category Allocation Progress Grid --}}
    <section class="mt-6 editorial-card rounded-lg p-6">
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-editorial text-2xl font-semibold text-espresso">Alokasi Kategori</h2>
                <p class="text-xs text-taupe">Klik salah satu kategori untuk menyaring tabel pos anggaran di bawah</p>
            </div>
            @if($selectedCategory || $selectedStatus)
                <a href="{{ route('budget.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                    Reset Filter Kategori
                </a>
            @endif
        </div>
        {{-- Smart Budget Allocator (Preset Konsep & Adat) --}}
        <div x-data="{ activePreset: 'jawa', totalCap: {{ $summary['total_budget_cap'] }} }" class="mt-4 rounded-xl border border-stone-line bg-pearl p-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="sliders" class="h-4 w-4 text-champagne" />
                    <span class="text-xs font-bold uppercase tracking-wider text-espresso">Smart Allocator Adat</span>
                </div>
                <div class="flex flex-wrap gap-1.5 text-xs">
                    <button
                        type="button"
                        @click="activePreset = 'jawa'"
                        :class="activePreset === 'jawa' ? 'bg-espresso text-alabaster shadow-sm' : 'bg-alabaster text-charcoal hover:bg-stone-line/50'"
                        class="rounded-lg px-3 py-1.5 font-semibold transition cursor-pointer"
                    >
                        Jawa Paes Ageng
                    </button>
                    <button
                        type="button"
                        @click="activePreset = 'sunda'"
                        :class="activePreset === 'sunda' ? 'bg-espresso text-alabaster shadow-sm' : 'bg-alabaster text-charcoal hover:bg-stone-line/50'"
                        class="rounded-lg px-3 py-1.5 font-semibold transition cursor-pointer"
                    >
                        Sunda Siger
                    </button>
                    <button
                        type="button"
                        @click="activePreset = 'modern'"
                        :class="activePreset === 'modern' ? 'bg-espresso text-alabaster shadow-sm' : 'bg-alabaster text-charcoal hover:bg-stone-line/50'"
                        class="rounded-lg px-3 py-1.5 font-semibold transition cursor-pointer"
                    >
                        Modern Intimate
                    </button>
                </div>
            </div>
            <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 text-xs">
                <div class="rounded-lg bg-alabaster/80 p-2.5">
                    <span class="text-taupe">Venue</span>
                    <p class="font-bold text-espresso font-mono mt-0.5 whitespace-nowrap" x-text="'Rp ' + Number(totalCap * (activePreset === 'jawa' ? 0.25 : (activePreset === 'sunda' ? 0.22 : 0.30))).toLocaleString('id-ID')"></p>
                </div>
                <div class="rounded-lg bg-alabaster/80 p-2.5">
                    <span class="text-taupe">Catering</span>
                    <p class="font-bold text-espresso font-mono mt-0.5 whitespace-nowrap" x-text="'Rp ' + Number(totalCap * (activePreset === 'jawa' ? 0.35 : (activePreset === 'sunda' ? 0.38 : 0.30))).toLocaleString('id-ID')"></p>
                </div>
                <div class="rounded-lg bg-alabaster/80 p-2.5">
                    <span class="text-taupe">Busana &amp; Rias</span>
                    <p class="font-bold text-espresso font-mono mt-0.5 whitespace-nowrap" x-text="'Rp ' + Number(totalCap * (activePreset === 'jawa' ? 0.18 : (activePreset === 'sunda' ? 0.16 : 0.12))).toLocaleString('id-ID')"></p>
                </div>
                <div class="rounded-lg bg-alabaster/80 p-2.5">
                    <span class="text-taupe">Dekorasi</span>
                    <p class="font-bold text-espresso font-mono mt-0.5 whitespace-nowrap" x-text="'Rp ' + Number(totalCap * (activePreset === 'jawa' ? 0.12 : (activePreset === 'sunda' ? 0.14 : 0.14))).toLocaleString('id-ID')"></p>
                </div>
                <div class="rounded-lg bg-alabaster/80 p-2.5">
                    <span class="text-taupe">Dokumentasi</span>
                    <p class="font-bold text-espresso font-mono mt-0.5 whitespace-nowrap" x-text="'Rp ' + Number(totalCap * (activePreset === 'jawa' ? 0.06 : (activePreset === 'sunda' ? 0.06 : 0.10))).toLocaleString('id-ID')"></p>
                </div>
                <div class="rounded-lg bg-alabaster/80 p-2.5">
                    <span class="text-taupe">Lainnya / Cadangan</span>
                    <p class="font-bold text-espresso font-mono mt-0.5 whitespace-nowrap" x-text="'Rp ' + Number(totalCap * 0.04).toLocaleString('id-ID')"></p>
                </div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($summary['category_breakdown'] as $cat)
                @php $isSelected = $selectedCategory === $cat['key']; @endphp
                <a
                    href="{{ route('budget.index', ['category' => $isSelected ? null : $cat['key']]) }}"
                    class="rounded border p-4 transition {{ $isSelected ? 'border-champagne bg-champagne-light/60' : 'border-stone-line bg-alabaster hover:border-champagne/60 hover:bg-pearl' }}"
                >
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-espresso">{{ $cat['label'] }}</span>
                        <span class="tabular-nums text-[11px] font-medium text-taupe">{{ $cat['share_percent'] }}%</span>
                    </div>
                    <p class="mt-2 font-editorial tabular-nums text-xl font-semibold text-espresso">
                        {{ format_idr($cat['actual']) }}
                    </p>
                    <p class="mt-0.5 tabular-nums text-[11px] text-taupe">
                        Estimasi: {{ format_idr($cat['estimated']) }}
                    </p>
                    <div class="mt-2.5">
                        <x-ui.progress-bar
                            size="sm"
                            :percent="$cat['utilization_percent']"
                            :tone="$cat['is_overbudget'] ? 'wine' : 'champagne'"
                        />
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Filter Bar & Budget Ledger Table --}}
    <section class="mt-6 editorial-card rounded-lg p-6">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-editorial text-2xl font-semibold text-espresso">Rincian Buku Besar Anggaran ({{ $items->count() }} Komponen)</h2>
                <p class="text-xs text-taupe">Setiap baris mencatat biaya estimasi, kontrak aktual, nominal terbayar, dan mitra vendor terkait</p>
            </div>

            <form method="GET" action="{{ route('budget.index') }}" class="flex flex-wrap items-center gap-2 no-print">
                <select name="category" aria-label="Filter Kategori Anggaran" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                            {{ $category->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="status" aria-label="Filter Status Pembayaran Anggaran" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua Status</option>
                    <option value="paid" @selected($selectedStatus === 'paid')>Lunas</option>
                    <option value="unpaid" @selected($selectedStatus === 'unpaid')>Belum Lunas / DP</option>
                    <option value="overbudget" @selected($selectedStatus === 'overbudget')>Overbudget</option>
                </select>
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-line text-xs font-semibold text-taupe">
                        <th scope="col" class="py-3 pr-4">Komponen &amp; Kategori</th>
                        <th scope="col" class="px-3 py-3">Mitra Vendor</th>
                        <th scope="col" class="px-3 py-3 text-right">Biaya Estimasi</th>
                        <th scope="col" class="px-3 py-3 text-right">Biaya Aktual</th>
                        <th scope="col" class="px-3 py-3 text-right">Sudah Dibayar</th>
                        <th scope="col" class="px-3 py-3 text-right">Sisa Tagihan</th>
                        <th scope="col" class="px-3 py-3 text-center">Status</th>
                        <th scope="col" class="py-3 pl-3 text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-line/70">
                    @forelse($items as $item)
                        <tr class="group hover:bg-alabaster/70" x-data="{ editing: false }">
                            <td class="py-3.5 pr-4">
                                <p class="font-semibold text-espresso">{{ $item->title }}</p>
                                <div class="mt-0.5 flex flex-wrap items-center gap-2 text-xs text-taupe">
                                    <span class="font-medium text-champagne">{{ $item->category->label() }}</span>
                                    @if($item->due_date)
                                        <span>· Jatuh tempo {{ $item->due_date->format('d M Y') }}</span>
                                    @endif
                                </div>
                                @if($item->notes)
                                    <p class="mt-1 text-xs text-taupe">{{ $item->notes }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-xs text-charcoal">
                                @if($item->vendor)
                                    <a href="{{ route('vendors.index', ['category' => $item->category->value]) }}" class="font-medium text-espresso hover:text-champagne hover:underline">
                                        {{ $item->vendor->name }}
                                    </a>
                                    @if($item->vendor->payments->isNotEmpty())
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            @foreach($item->vendor->payments->take(2) as $pay)
                                                <span class="inline-flex items-center rounded bg-forest/10 px-1.5 py-0.2 text-[10px] font-semibold text-forest" title="{{ $pay->notes }}">
                                                    {{ $pay->payment_stage }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <span class="text-taupe">Mandiri / Internal</span>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-right tabular-nums text-xs text-charcoal whitespace-nowrap">
                                {{ format_idr($item->estimated_cost) }}
                            </td>
                            <td class="px-3 py-3.5 text-right tabular-nums text-xs font-semibold text-espresso whitespace-nowrap">
                                {{ format_idr($item->actual_cost) }}
                                @if($item->is_overbudget)
                                    <span class="block text-[11px] font-normal text-wine">
                                        +{{ format_idr(abs($item->variance_amount)) }}
                                    </span>
                                @elseif($item->variance_amount > 0 && $item->actual_cost > 0)
                                    <span class="block text-[11px] font-normal text-sage">
                                        Hemat {{ format_idr($item->variance_amount) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-right tabular-nums text-xs text-sage font-medium whitespace-nowrap">
                                {{ format_idr($item->paid_amount) }}
                            </td>
                            <td class="px-3 py-3.5 text-right tabular-nums text-xs font-semibold {{ $item->remaining_balance > 0 ? 'text-ochre' : 'text-taupe' }} whitespace-nowrap">
                                {{ format_idr($item->remaining_balance) }}
                            </td>
                            <td class="px-3 py-3.5 text-center">
                                <x-ui.status-badge :tone="$item->payment_status_tone" :label="$item->payment_status_label" />
                            </td>
                            <td class="py-3.5 pl-3 text-right no-print">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('budget.edit', $item) }}" class="text-xs font-semibold text-champagne hover:underline">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('budget.destroy', $item) }}" onsubmit="return confirm('Hapus pos anggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-wine hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-sm text-taupe">
                                Tidak ada komponen anggaran pada filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Modal: Update Master Budget Cap --}}
    <x-ui.modal name="update-budget-cap-modal" title="Ubah Plafon Total Anggaran" subtitle="Sesuaikan batas anggaran induk pernikahan" maxWidth="max-w-md">
        <form method="POST" action="{{ route('budget.updateCap') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Plafon Total Anggaran Baru (Rp)</label>
                <input type="number" name="total_budget" value="{{ $profile->total_budget }}" min="1000000" step="1000000" data-rupiah required class="editorial-input tabular-nums">
                <p class="mt-1 text-xs text-taupe">Saat ini: {{ format_idr($profile->total_budget) }}</p>
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'update-budget-cap-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Plafon Anggaran
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
