@extends('layouts.app', ['title' => 'Buku Kas Anggaran'])

@section('content')
<div x-data="{ capModalOpen: false }" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 border-b-2 border-ink pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-soga">
                <span class="stamp !bg-kunyit font-black">Pengendali Hemat</span>
                <span>Pencatatan Keuangan &amp; Auto-Split</span>
            </div>
            <h1 class="mt-1 font-display text-2xl font-black tracking-tight text-espresso sm:text-4xl">
                Anggaran Pernikahan
            </h1>
            <p class="text-xs text-taupe font-semibold sm:text-sm">
                Satu buku kas terkendali untuk pagu anggaran induk, rekomendasi alokasi otomatis, dan deteksi boros per pos.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 no-print">
            <button
                type="button"
                @click="capModalOpen = true"
                class="btn-neo-secondary !py-1.5 !px-3 !text-xs font-black cursor-pointer"
            >
                Ubah Pagu Anggaran
            </button>
            <a href="{{ route('budget.create') }}" class="btn-neo !py-1.5 !px-3.5 !text-xs font-black">
                + Tambah Pos Anggaran
            </a>
        </div>
    </div>

    {{-- 4 Kotak Ringkasan Finansial Bento --}}
    <section aria-label="Ringkasan kas" class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
        <div class="bento-card bento-paper p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Pagu Induk</p>
            <p class="rupiah font-display text-lg font-black text-ink sm:text-2xl">{{ format_idr($summary['total_budget_cap']) }}</p>
            <p class="text-[0.68rem] font-bold text-taupe">Target {{ number_format($profile->guest_target, 0, ',', '.') }} tamu</p>
        </div>
        <div class="bento-card bento-paper2 p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Estimasi Riset</p>
            <p class="rupiah font-display text-lg font-black text-taupe sm:text-2xl">{{ format_idr($summary['total_estimated']) }}</p>
            <p class="text-[0.68rem] font-bold text-taupe">Acuan batas pasar</p>
        </div>
        <div class="bento-card bento-mint p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Deal Vendor</p>
            <p class="rupiah font-display text-lg font-black text-ink sm:text-2xl">{{ format_idr($summary['total_actual']) }}</p>
            <p class="text-[0.68rem] font-bold text-forest">Terbayar {{ format_idr($summary['total_paid']) }}</p>
        </div>
        <div class="bento-card {{ $summary['remaining_budget'] >= 0 ? 'bento-mint' : 'bento-terracotta' }} p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Sisa Dana Aman</p>
            <p class="rupiah font-display text-lg font-black {{ $summary['remaining_budget'] >= 0 ? 'text-forest' : 'text-wine' }} sm:text-2xl">
                {{ format_idr($summary['remaining_budget']) }}
            </p>
            <p class="text-[0.68rem] font-bold text-taupe">
                {{ $summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Lewat ' . format_idr(abs($summary['variance_amount'])) }}
            </p>
        </div>
    </section>

    {{-- Kotak Rekomendasi Auto-Split Proporsional --}}
    <section class="bento-card bento-kunyit p-4 sm:p-5 space-y-3">
        <div class="flex items-center justify-between border-b-2 border-ink pb-2">
            <div class="flex items-center gap-2">
                <span class="stamp !bg-paper font-black">Auto-Split</span>
                <span class="font-display text-lg font-extrabold text-ink">Rekomendasi Alokasi Otomatis (Anti-Pusing)</span>
            </div>
            <span class="text-xs font-bold text-ink hidden sm:inline">Berdasarkan riset pernikahan Indonesia</span>
        </div>
        <p class="text-xs text-ink/90 font-semibold leading-relaxed">
            Dari pagu anggaran <strong>{{ format_idr($summary['total_budget_cap']) }}</strong>, sistem secara otomatis merekomendasikan batas aman tiap pos tanpa perlu menghitung rumus:
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Katering (30%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.30) }}</span>
            </div>
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Venue &amp; Gedung (15%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.15) }}</span>
            </div>
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Dekorasi (10%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.10) }}</span>
            </div>
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Busana &amp; Rias (13%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.13) }}</span>
            </div>
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Dokumentasi (8%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.08) }}</span>
            </div>
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Undangan &amp; Souvenir (5%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.05) }}</span>
            </div>
            <div class="bento-card bento-paper p-2.5">
                <span class="text-[0.65rem] font-bold text-taupe block">Administrasi &amp; KUA (2%)</span>
                <span class="rupiah font-black text-ink block">{{ format_idr($summary['total_budget_cap'] * 0.02) }}</span>
            </div>
            <div class="bento-card bento-mint p-2.5">
                <span class="text-[0.65rem] font-bold text-forest block">Dana Darurat (5%)</span>
                <span class="rupiah font-black text-forest block">{{ format_idr($summary['total_budget_cap'] * 0.05) }}</span>
            </div>
        </div>
    </section>

    {{-- Kotak Saran Pengendali Hemat (Jika ada pos yang lewat estimasi) --}}
    @php
        $overbudgetPos = collect($summary['category_breakdown'])->filter(fn($c) => $c['is_overbudget']);
    @endphp
    @if($overbudgetPos->isNotEmpty())
        <section class="bento-card bento-terracotta p-4 border-2 border-ink space-y-1 text-xs">
            <div class="flex items-center gap-2">
                <span class="stamp !bg-paper !border-wine !text-wine font-black">Peringatan Hemat</span>
                <span class="font-bold text-ink">Ada {{ $overbudgetPos->count() }} kategori yang melebihi estimasi awal:</span>
            </div>
            <p class="text-ink font-semibold mt-1">
                Kategori <strong class="text-wine">{{ $overbudgetPos->pluck('label')->join(', ') }}</strong> tercatat melebihi estimasi awal.
                <x-ui.icon name="lightbulb" class="h-3.5 w-3.5 text-soga inline-block mr-1 align-text-bottom" /><strong>Saran Pengendali Hemat:</strong> Anda dapat mengimbangi selisih ini dengan memilih paket standar pada pos <em>Dekorasi Tambahan</em> atau beralih ke <em>Undangan Digital</em> untuk menjaga saldo akhir tetap aman.
            </p>
        </section>
    @endif

    {{-- Buku Besar --}}
    <section class="bento-card bento-paper p-5 space-y-4">
        <div class="flex flex-col justify-between gap-3 border-b-2 border-ink pb-3 sm:flex-row sm:items-baseline">
            <div>
                <h2 class="font-display text-xl font-bold text-espresso">Buku Besar Pos Pengeluaran</h2>
                <p class="text-xs text-taupe font-semibold">{{ $items->count() }} pos rincian terdaftar.</p>
            </div>

            <form method="GET" action="{{ route('budget.index') }}" class="flex flex-wrap items-center gap-2 text-xs no-print">
                <select name="category" aria-label="Filter kategori" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                            {{ $category->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="status" aria-label="Filter status" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                    <option value="">Semua Status</option>
                    <option value="paid" @selected($selectedStatus === 'paid')>Lunas</option>
                    <option value="unpaid" @selected($selectedStatus === 'unpaid')>Belum Lunas / DP</option>
                    <option value="overbudget" @selected($selectedStatus === 'overbudget')>Lewat Estimasi</option>
                </select>
            </form>
        </div>

        {{-- Desktop: Tabel Ledger Neo-Brutalist --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="ledger">
                <thead>
                    <tr>
                        <th scope="col">Pos &amp; Kategori</th>
                        <th scope="col">Mitra Vendor</th>
                        <th scope="col" class="text-right">Estimasi</th>
                        <th scope="col" class="text-right">Deal Aktual</th>
                        <th scope="col" class="text-right">Terbayar</th>
                        <th scope="col" class="text-right">Sisa Tagihan</th>
                        <th scope="col">Indikator Hemat</th>
                        <th scope="col" class="text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <p class="font-bold text-ink">{{ $item->title }}</p>
                                <p class="text-[0.7rem] text-taupe font-medium">
                                    {{ $item->category->label() }}
                                    @if($item->due_date)
                                        · Jatuh tempo {{ $item->due_date->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                                @if($item->notes)
                                    <p class="text-[0.68rem] text-taupe mt-0.5">{{ $item->notes }}</p>
                                @endif
                            </td>
                            <td class="text-xs">
                                @if($item->vendor)
                                    <a href="{{ route('vendors.index', ['category' => $item->category->value]) }}" class="font-bold text-soga hover:underline">
                                        {{ $item->vendor->name }}
                                    </a>
                                @else
                                    <span class="text-taupe font-medium">Mandiri</span>
                                @endif
                            </td>
                            <td class="rupiah text-right text-taupe font-semibold">{{ format_idr($item->estimated_cost) }}</td>
                            <td class="rupiah text-right font-black text-ink">
                                {{ format_idr($item->actual_cost) }}
                                @if($item->is_overbudget)
                                    <span class="block text-[0.68rem] text-wine font-bold">+{{ format_idr(abs($item->variance_amount)) }}</span>
                                @endif
                            </td>
                            <td class="rupiah text-right text-forest font-bold">{{ format_idr($item->paid_amount) }}</td>
                            <td class="rupiah text-right font-extrabold {{ $item->remaining_balance > 0 ? 'text-wine' : 'text-taupe' }}">
                                {{ format_idr($item->remaining_balance) }}
                            </td>
                            <td>
                                @if($item->is_overbudget)
                                    <span class="stamp !bg-terracotta-light !border-wine !text-wine font-black">
                                        [Lewat Estimasi]
                                    </span>
                                @else
                                    <span class="stamp !bg-mint-light !border-forest !text-forest font-black">
                                        [Aman / Hemat]
                                    </span>
                                @endif
                            </td>
                            <td class="text-right no-print">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('budget.edit', $item) }}" class="text-xs font-bold text-soga hover:underline">Ubah</a>
                                    <form method="POST" action="{{ route('budget.destroy', $item) }}" onsubmit="return confirm('Hapus pos anggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-wine hover:underline cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-xs text-taupe font-bold">Tidak ada pos anggaran pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: Kartu Ledger Bertumpuk --}}
        <div class="sm:hidden space-y-3">
            @forelse($items as $item)
                <div class="bento-card bento-paper p-3 text-xs space-y-2">
                    <div class="flex items-baseline justify-between border-b-2 border-ink pb-1.5">
                        <span class="font-extrabold text-ink text-sm font-display">{{ $item->title }}</span>
                        @if($item->is_overbudget)
                            <span class="stamp !bg-terracotta-light !border-wine !text-wine font-black text-[0.6rem]">
                                [Lewat Estimasi]
                            </span>
                        @else
                            <span class="stamp !bg-mint-light !border-forest !text-forest font-black text-[0.6rem]">
                                [Aman / Hemat]
                            </span>
                        @endif
                    </div>
                    <p class="text-[0.7rem] text-taupe font-semibold">
                        {{ $item->category->label() }}
                        @if($item->vendor) · Vendor: <strong class="text-soga">{{ $item->vendor->name }}</strong> @endif
                    </p>
                    <div class="grid grid-cols-3 gap-2 border-y-2 border-ink py-2 text-center text-[0.72rem]">
                        <div>
                            <span class="text-taupe block text-[0.65rem] font-bold">Estimasi</span>
                            <span class="rupiah font-semibold">{{ format_idr($item->estimated_cost) }}</span>
                        </div>
                        <div>
                            <span class="text-taupe block text-[0.65rem] font-bold">Deal</span>
                            <span class="rupiah font-black text-soga">{{ format_idr($item->actual_cost) }}</span>
                        </div>
                        <div>
                            <span class="text-taupe block text-[0.65rem] font-bold">Sisa</span>
                            <span class="rupiah font-extrabold {{ $item->remaining_balance > 0 ? 'text-wine' : 'text-taupe' }}">{{ format_idr($item->remaining_balance) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-1 text-xs">
                        <a href="{{ route('budget.edit', $item) }}" class="text-soga font-bold hover:underline">Ubah</a>
                        <form method="POST" action="{{ route('budget.destroy', $item) }}" onsubmit="return confirm('Hapus pos anggaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-wine font-bold hover:underline cursor-pointer">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-xs text-taupe font-bold">Belum ada pos anggaran tercatat.</p>
            @endforelse
        </div>
    </section>

    {{-- Modal Ubah Pagu Anggaran --}}
    <div
        x-show="capModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print"
        role="dialog"
        aria-modal="true"
        x-on:keydown.escape.window="capModalOpen = false"
    >
        <div class="fixed inset-0 bg-ink/50" @click="capModalOpen = false"></div>

        <div class="modal-paper relative z-10 w-full max-w-md p-6">
            <div class="flex items-start justify-between border-b-2 border-ink pb-3">
                <div>
                    <span class="stamp !bg-kunyit font-black">Pagu Induk</span>
                    <h2 class="font-display text-xl font-extrabold text-ink mt-1">Ubah Batas Anggaran</h2>
                </div>
                <button type="button" @click="capModalOpen = false" class="btn-neo-secondary !min-h-0 !p-1 text-sm font-bold cursor-pointer">✕</button>
            </div>

            <form method="POST" action="{{ route('budget.updateCap') }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="total_budget" class="mb-1 block text-xs font-black text-ink uppercase tracking-wider">
                        Batas Anggaran Baru (Rp)
                    </label>
                    <input
                        id="total_budget"
                        type="number"
                        name="total_budget"
                        value="{{ $profile->total_budget }}"
                        min="1000000"
                        step="1000000"
                        data-rupiah
                        required
                        class="editorial-input tabular-nums"
                    >
                    <p class="mt-1 text-xs text-taupe font-semibold">Pagu saat ini: <span class="rupiah font-bold text-ink">{{ format_idr($profile->total_budget) }}</span></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-ink">
                    <button type="button" @click="capModalOpen = false" class="btn-neo-secondary !py-1.5 !px-3 !text-xs">
                        Batal
                    </button>
                    <button type="submit" class="btn-neo !py-1.5 !px-4 !text-xs font-black">
                        Simpan Batas Pagu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
