@extends('layouts.app', ['title' => 'Anggaran'])

@section('content')
    <x-ui.section-header
        title="Anggaran Pernikahan"
        subtitle="Satu buku besar untuk plafon, estimasi riset, kontrak vendor, dan pelunasan."
    >
        <x-slot:actions>
            <button
                type="button"
                @click="$dispatch('open-modal', 'update-budget-cap-modal')"
                class="editorial-btn-secondary"
            >
                Ubah plafon
            </button>
            <a href="{{ route('budget.create') }}" class="editorial-btn-primary">
                + Pos anggaran
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Angka pokok --}}
    <section aria-label="Ringkasan anggaran" class="grid grid-cols-2 gap-x-8 gap-y-7 border-b border-stone-line py-7 lg:grid-cols-4">
        <x-ui.stat-card
            label="Plafon"
            :value="format_idr($summary['total_budget_cap'])"
            :subtitle="'Target ' . number_format($profile->guest_target, 0, ',', '.') . ' tamu'"
        />
        <x-ui.stat-card
            label="Estimasi"
            :value="format_idr($summary['total_estimated'])"
            :subtitle="'Acuan riset biaya'"
        />
        <x-ui.stat-card
            label="Deal vendor"
            :value="format_idr($summary['total_actual'])"
            :subtitle="'Terbayar ' . format_idr($summary['total_paid'])"
        />
        <x-ui.stat-card
            label="Sisa anggaran"
            :value="format_idr($summary['remaining_budget'])"
            :badge="$summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Lewat ' . format_idr(abs($summary['variance_amount']))"
            :badgeTone="$summary['variance_amount'] >= 0 ? 'sage' : 'wine'"
        />
    </section>

    {{-- Alokasi kategori --}}
    <section class="pt-8">
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-3 sm:flex-row sm:items-baseline">
            <div>
                <h2 class="text-lg font-bold text-espresso">Alokasi kategori</h2>
                <p class="mt-0.5 text-xs text-taupe">Klik kategori untuk menyaring buku besar di bawah.</p>
            </div>
            @if($selectedCategory || $selectedStatus)
                <a href="{{ route('budget.index') }}" class="text-sm font-semibold text-champagne hover:underline">
                    Reset filter
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-x-10 sm:grid-cols-2">
            @foreach($summary['category_breakdown'] as $cat)
                @php $isSelected = $selectedCategory === $cat['key']; @endphp
                <a
                    href="{{ route('budget.index', ['category' => $isSelected ? null : $cat['key']]) }}"
                    class="flex items-baseline justify-between gap-4 border-b border-stone-line/70 py-3 transition hover:text-champagne {{ $isSelected ? 'text-champagne' : 'text-espresso' }}"
                >
                    <span class="text-sm font-semibold">{{ $cat['label'] }}</span>
                    <span class="shrink-0 text-right">
                        <span class="block text-sm font-semibold tabular-nums">{{ format_idr($cat['actual']) }}</span>
                        <span class="block text-xs text-taupe tabular-nums">
                            estimasi {{ format_idr($cat['estimated']) }}
                            @if($cat['is_overbudget'])
                                <span class="font-semibold text-wine">· lewat</span>
                            @endif
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Buku besar --}}
    <section class="pt-9">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-4 sm:flex-row sm:items-baseline">
            <div>
                <h2 class="text-lg font-bold text-espresso">Buku besar · {{ $items->count() }} pos</h2>
                <p class="mt-0.5 text-xs text-taupe">Estimasi, kontrak aktual, terbayar, dan sisa tiap pos.</p>
            </div>

            <form method="GET" action="{{ route('budget.index') }}" class="flex flex-wrap items-center gap-2 no-print">
                <select name="category" aria-label="Filter kategori anggaran" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                            {{ $category->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="status" aria-label="Filter status pembayaran" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua status</option>
                    <option value="paid" @selected($selectedStatus === 'paid')>Lunas</option>
                    <option value="unpaid" @selected($selectedStatus === 'unpaid')>Belum lunas / DP</option>
                    <option value="overbudget" @selected($selectedStatus === 'overbudget')>Lewat estimasi</option>
                </select>
            </form>
        </div>

        <div class="mt-2 overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-line text-xs font-semibold uppercase tracking-wider text-taupe">
                        <th scope="col" class="py-3 pr-4">Pos &amp; kategori</th>
                        <th scope="col" class="px-3 py-3">Vendor</th>
                        <th scope="col" class="px-3 py-3 text-right">Estimasi</th>
                        <th scope="col" class="px-3 py-3 text-right">Aktual</th>
                        <th scope="col" class="px-3 py-3 text-right">Terbayar</th>
                        <th scope="col" class="px-3 py-3 text-right">Sisa</th>
                        <th scope="col" class="px-3 py-3">Status</th>
                        <th scope="col" class="py-3 pl-3 text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-line/70">
                    @forelse($items as $item)
                        <tr>
                            <td class="py-3.5 pr-4">
                                <p class="font-semibold text-espresso">{{ $item->title }}</p>
                                <p class="mt-0.5 text-xs text-taupe">
                                    {{ $item->category->label() }}
                                    @if($item->due_date)
                                        · jatuh tempo {{ $item->due_date->format('d M Y') }}
                                    @endif
                                </p>
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
                                        <p class="mt-0.5 text-xs text-taupe">
                                            {{ $item->vendor->payments->take(2)->pluck('payment_stage')->join(' · ') }}
                                        </p>
                                    @endif
                                @else
                                    <span class="text-taupe">Mandiri</span>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-right text-xs text-charcoal tabular-nums">{{ format_idr($item->estimated_cost) }}</td>
                            <td class="px-3 py-3.5 text-right text-xs font-semibold text-espresso tabular-nums">
                                {{ format_idr($item->actual_cost) }}
                                @if($item->is_overbudget)
                                    <span class="block text-xs font-normal text-wine">+{{ format_idr(abs($item->variance_amount)) }}</span>
                                @elseif($item->variance_amount > 0 && $item->actual_cost > 0)
                                    <span class="block text-xs font-normal text-sage">hemat {{ format_idr($item->variance_amount) }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-right text-xs text-sage tabular-nums">{{ format_idr($item->paid_amount) }}</td>
                            <td class="px-3 py-3.5 text-right text-xs font-semibold {{ $item->remaining_balance > 0 ? 'text-ochre' : 'text-taupe' }} tabular-nums">
                                {{ format_idr($item->remaining_balance) }}
                            </td>
                            <td class="px-3 py-3.5">
                                <x-ui.status-badge :tone="$item->payment_status_tone" :label="$item->payment_status_label" />
                            </td>
                            <td class="py-3.5 pl-3 text-right no-print">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('budget.edit', $item) }}" class="text-xs font-semibold text-champagne hover:underline">Ubah</a>
                                    <form method="POST" action="{{ route('budget.destroy', $item) }}" onsubmit="return confirm('Hapus pos anggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-wine hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-sm text-taupe">Tidak ada pos anggaran pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Modal: ubah plafon --}}
    <x-ui.modal name="update-budget-cap-modal" title="Ubah plafon total" subtitle="Batas anggaran induk pernikahan" maxWidth="max-w-md">
        <form method="POST" action="{{ route('budget.updateCap') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="total_budget" class="mb-1 block text-xs font-semibold text-charcoal">Plafon baru (Rp)</label>
                <input id="total_budget" type="number" name="total_budget" value="{{ $profile->total_budget }}" min="1000000" step="1000000" data-rupiah required class="editorial-input tabular-nums">
                <p class="mt-1 text-xs text-taupe">Saat ini: {{ format_idr($profile->total_budget) }}</p>
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'update-budget-cap-modal')" class="editorial-btn-secondary">Batal</button>
                <button type="submit" class="editorial-btn-primary">Simpan plafon</button>
            </div>
        </form>
    </x-ui.modal>
@endsection
