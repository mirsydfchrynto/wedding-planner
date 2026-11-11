@extends('layouts.app', ['title' => 'Buku Kas Anggaran'])

@section('content')
<div x-data="{ capModalOpen: false }" class="space-y-6">

    <div class="flex flex-col gap-3 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#2B2520] sm:text-3xl">
                Anggaran Pernikahan
            </h1>
            <p class="text-xs text-[#6E665E] mt-0.5">
                Buku kas terkendali untuk pagu anggaran induk, rekomendasi alokasi proporsional, dan pemantauan per pos pengeluaran.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 no-print">
            <button
                type="button"
                @click="capModalOpen = true"
                class="inline-flex items-center rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#2B2520] transition hover:bg-[#F5F2EB] cursor-pointer"
            >
                Ubah Pagu Anggaran
            </button>
            <a href="{{ route('budget.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                + Tambah Pos Anggaran
            </a>
        </div>
    </div>

    {{-- Visual Financial Ratio Cards --}}
    <section aria-label="Ringkasan kas" class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Pagu Induk</p>
            <p class="text-xl sm:text-2xl font-semibold text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap']) }}</p>
            <p class="text-xs text-[#6E665E]">Target {{ number_format($profile->guest_target, 0, ',', '.') }} tamu</p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Estimasi Riset</p>
            <p class="text-xl sm:text-2xl font-semibold text-[#6E665E] tabular-nums">{{ format_idr($summary['total_estimated']) }}</p>
            <p class="text-xs text-[#6E665E]">Acuan batas pasar</p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Deal Vendor</p>
            <p class="text-xl sm:text-2xl font-semibold text-[#2B2520] tabular-nums">{{ format_idr($summary['total_actual']) }}</p>
            <p class="text-xs text-[#2E5E3E] font-medium">Terbayar {{ format_idr($summary['total_paid']) }}</p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Sisa Dana Aman</p>
            <p class="text-xl sm:text-2xl font-semibold tabular-nums {{ $summary['remaining_budget'] >= 0 ? 'text-[#2E5E3E]' : 'text-[#9E352B]' }}">
                {{ format_idr($summary['remaining_budget']) }}
            </p>
            <p class="text-xs text-[#6E665E]">
                {{ $summary['variance_amount'] >= 0 ? 'Hemat ' . format_idr($summary['variance_amount']) : 'Lewat ' . format_idr(abs($summary['variance_amount'])) }}
            </p>
        </div>
    </section>

    {{-- Visual Proportional Allocation Diagram (SVG Flow & Segmented Bar) --}}
    <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-[#F0ECE4] pb-3">
            <div>
                <h2 class="text-base font-semibold text-[#2B2520]">Peta Alokasi Proporsional</h2>
                <p class="text-xs text-[#6E665E]">Distribusi pembagian pagu anggaran otomatis berbasis standar pernikahan nasional.</p>
            </div>
            <span class="text-xs text-[#6E665E] hidden sm:inline">Pagu: {{ format_idr($summary['total_budget_cap']) }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-lg bg-[#FAF8F5] border border-[#E7E4DF] space-y-1">
                <span class="text-[#6E665E] block font-medium">1. Pagu Anggaran Induk</span>
                <span class="text-xl font-bold text-[#2B2520] tabular-nums block">{{ format_idr($summary['total_budget_cap']) }}</span>
                <span class="text-xs text-[#6E665E] block">Batas pagu disepakati bersama keluarga</span>
            </div>
            <div class="p-4 rounded-lg bg-[#FAF8F5] border border-[#E7E4DF] space-y-1">
                <span class="text-[#6E665E] block font-medium">2. Komitmen Deal Vendor</span>
                <span class="text-xl font-bold text-[#8C5835] tabular-nums block">{{ format_idr($summary['total_actual']) }}</span>
                <span class="text-xs text-[#6E665E] block">{{ $summary['budget_utilization_percent'] }}% dari total pagu dialokasikan</span>
            </div>
            <div class="p-4 rounded-lg bg-[#FAF8F5] border border-[#E7E4DF] space-y-1">
                <span class="text-[#6E665E] block font-medium">3. Sisa Dana Aman</span>
                <span class="text-xl font-bold {{ $summary['remaining_budget'] >= 0 ? 'text-[#2E5E3E]' : 'text-[#9E352B]' }} tabular-nums block">{{ format_idr($summary['remaining_budget']) }}</span>
                <span class="text-xs text-[#6E665E] block">Cadangan dana darurat tidak terpakai</span>
            </div>
        </div>
        {{-- Visual Stacked Proportion Bar Diagram --}}
        <div class="space-y-2">
            <div class="h-6 w-full rounded-md overflow-hidden flex text-[10px] font-semibold text-white shadow-xs">
                <div class="bg-[#2E5E3E] flex items-center justify-center transition-all duration-300" style="width: 30%" title="Katering (30%)">30% Katering</div>
                <div class="bg-[#8C5835] flex items-center justify-center transition-all duration-300" style="width: 15%" title="Venue & Gedung (15%)">15% Gedung</div>
                <div class="bg-[#A06414] flex items-center justify-center transition-all duration-300" style="width: 13%" title="Busana & Rias (13%)">13% Busana</div>
                <div class="bg-[#483725] flex items-center justify-center transition-all duration-300" style="width: 10%" title="Dekorasi (10%)">10% Dekor</div>
                <div class="bg-[#6E665E] flex items-center justify-center transition-all duration-300" style="width: 8%" title="Dokumentasi (8%)">8% Foto</div>
                <div class="bg-[#78716C] flex items-center justify-center transition-all duration-300" style="width: 5%" title="Undangan & Souvenir (5%)">5% Souvenir</div>
                <div class="bg-[#A8A29E] flex items-center justify-center transition-all duration-300" style="width: 2%" title="KUA (2%)">2% KUA</div>
                <div class="bg-[#D3E6DA] text-[#2E5E3E] flex items-center justify-center transition-all duration-300" style="width: 17%" title="Dana Cadangan Aman (17%)">17% Cadangan</div>
            </div>

            {{-- Legend Breakdown Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 text-xs">
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#2E5E3E] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">Katering (30%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.30) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#8C5835] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">Venue &amp; Gedung (15%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.15) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#A06414] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">Busana &amp; Rias (13%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.13) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#483725] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">Dekorasi (10%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.10) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#6E665E] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">Dokumentasi (8%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.08) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#78716C] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">Undangan &amp; Souvenir (5%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.05) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#A8A29E] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#6E665E] block">KUA &amp; Administrasi (2%)</span>
                        <strong class="text-[#2B2520] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.02) }}</strong>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#D3E6DA] mt-1 shrink-0"></span>
                    <div>
                        <span class="text-[#2E5E3E] font-medium block">Dana Cadangan (17%)</span>
                        <strong class="text-[#2E5E3E] tabular-nums">{{ format_idr($summary['total_budget_cap'] * 0.17) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $overbudgetPos = collect($summary['category_breakdown'])->filter(fn($c) => $c['is_overbudget']);
    @endphp
    @if($overbudgetPos->isNotEmpty())
        <section class="rounded-xl border border-[#F3D5D0] bg-[#FDF2F0] p-4 text-xs space-y-1.5 text-[#9E352B]">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-[#2B2520]">Peringatan Anggaran:</span>
                <span>Kategori {{ $overbudgetPos->pluck('label')->join(', ') }} melebihi estimasi awal.</span>
            </div>
            <p class="text-[#6E665E] leading-relaxed">
                <x-ui.icon name="lightbulb" class="h-3.5 w-3.5 text-[#8C5835] inline-block mr-1 align-text-bottom" />
                Anda dapat mengimbangi selisih ini dengan memilih paket standar pada pos <em>Dekorasi Tambahan</em> atau <em>Undangan Digital</em> untuk menjaga saldo aman tetap positif.
            </p>
        </section>
    @endif

    {{-- Main Ledger Table --}}
    <section class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm space-y-4">
        <div class="flex flex-col justify-between gap-3 border-b border-[#F0ECE4] pb-4 sm:flex-row sm:items-baseline">
            <div>
                <h2 class="text-lg font-semibold text-[#2B2520]">Buku Besar Pos Pengeluaran</h2>
                <p class="text-xs text-[#6E665E]">{{ $items->count() }} pos rincian terdaftar.</p>
            </div>

            <form method="GET" action="{{ route('budget.index') }}" class="flex flex-wrap items-center gap-2 text-xs no-print">
                <select name="category" aria-label="Filter kategori" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                            {{ $category->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="status" aria-label="Filter status" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="paid" @selected($selectedStatus === 'paid')>Lunas</option>
                    <option value="unpaid" @selected($selectedStatus === 'unpaid')>Belum Lunas / DP</option>
                    <option value="overbudget" @selected($selectedStatus === 'overbudget')>Lewat Estimasi</option>
                </select>
            </form>
        </div>

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
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <p class="font-semibold text-[#2B2520]">{{ $item->title }}</p>
                                <p class="text-xs text-[#6E665E]">
                                    {{ $item->category->label() }}
                                    @if($item->due_date)
                                        · Jatuh tempo {{ $item->due_date->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                                @if($item->notes)
                                    <p class="text-xs text-[#6E665E] mt-0.5">{{ $item->notes }}</p>
                                @endif
                            </td>
                            <td class="text-xs">
                                @if($item->vendor)
                                    <a href="{{ route('vendors.index', ['category' => $item->category->value]) }}" class="font-medium text-[#8C5835] hover:underline">
                                        {{ $item->vendor->name }}
                                    </a>
                                @else
                                    <span class="text-[#6E665E]">Mandiri</span>
                                @endif
                            </td>
                            <td class="text-right text-[#6E665E] tabular-nums">{{ format_idr($item->estimated_cost) }}</td>
                            <td class="text-right font-semibold text-[#2B2520] tabular-nums">
                                {{ format_idr($item->actual_cost) }}
                                @if($item->is_overbudget)
                                    <span class="block text-xs text-[#9E352B] font-medium">+{{ format_idr(abs($item->variance_amount)) }}</span>
                                @endif
                            </td>
                            <td class="text-right text-[#2E5E3E] font-medium tabular-nums">{{ format_idr($item->paid_amount) }}</td>
                            <td class="text-right font-semibold tabular-nums {{ $item->remaining_balance > 0 ? 'text-[#9E352B]' : 'text-[#6E665E]' }}">
                                {{ format_idr($item->remaining_balance) }}
                            </td>
                            <td>
                                @if($item->is_overbudget)
                                    <span class="text-xs font-semibold text-[#9E352B]">Lewat Estimasi</span>
                                @else
                                    <span class="text-xs font-semibold text-[#2E5E3E]">Aman</span>
                                @endif
                            </td>
                            <td class="text-right no-print">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('budget.edit', $item) }}" class="text-xs font-semibold text-[#8C5835] hover:underline">Ubah</a>
                                    <form method="POST" action="{{ route('budget.destroy', $item) }}" onsubmit="return confirm('Hapus pos anggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-[#9E352B] hover:underline cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-4 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#FAF8F5] border border-[#E7E4DF] text-[#8C5835] mb-3">
                                    <x-ui.icon name="wallet" class="h-6 w-6" />
                                </div>
                                <h3 class="text-sm font-semibold text-[#2B2520]">Belum ada pos anggaran tercatat</h3>
                                <p class="mt-1 text-xs text-[#6E665E]">Tambahkan pos pengeluaran pertama untuk mulai mengontrol batas biaya pernikahan Anda.</p>
                                <div class="mt-4">
                                    <a href="{{ route('budget.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                                        + Tambah Pos Anggaran
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sm:hidden space-y-3">
            @forelse($items as $item)
                <div class="rounded-lg border border-[#E7E4DF] bg-[#FAF8F5] p-3 text-xs space-y-2">
                    <div class="flex items-baseline justify-between border-b border-[#E7E4DF] pb-2">
                        <span class="font-semibold text-[#2B2520] text-sm">{{ $item->title }}</span>
                        @if($item->is_overbudget)
                            <span class="text-xs font-semibold text-[#9E352B]">Lewat Estimasi</span>
                        @else
                            <span class="text-xs font-semibold text-[#2E5E3E]">Aman</span>
                        @endif
                    </div>
                    <p class="text-xs text-[#6E665E]">
                        {{ $item->category->label() }}
                        @if($item->vendor) · Vendor: <strong class="text-[#8C5835]">{{ $item->vendor->name }}</strong> @endif
                    </p>
                    <div class="grid grid-cols-3 gap-2 border-y border-[#E7E4DF] py-2 text-center text-xs">
                        <div>
                            <span class="text-[#6E665E] block text-xs">Estimasi</span>
                            <span class="tabular-nums font-medium text-[#6E665E]">{{ format_idr($item->estimated_cost) }}</span>
                        </div>
                        <div>
                            <span class="text-[#6E665E] block text-xs">Deal</span>
                            <span class="tabular-nums font-semibold text-[#2B2520]">{{ format_idr($item->actual_cost) }}</span>
                        </div>
                        <div>
                            <span class="text-[#6E665E] block text-xs">Sisa</span>
                            <span class="tabular-nums font-semibold {{ $item->remaining_balance > 0 ? 'text-[#9E352B]' : 'text-[#6E665E]' }}">{{ format_idr($item->remaining_balance) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-1 text-xs">
                        <a href="{{ route('budget.edit', $item) }}" class="text-[#8C5835] font-semibold hover:underline">Ubah</a>
                        <form method="POST" action="{{ route('budget.destroy', $item) }}" onsubmit="return confirm('Hapus pos anggaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[#9E352B] font-semibold hover:underline cursor-pointer">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-[#E7E4DF] bg-white p-8 text-center">
                    <p class="text-xs text-[#6E665E]">Belum ada pos anggaran tercatat.</p>
                    <a href="{{ route('budget.create') }}" class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        + Tambah Pos
                    </a>
                </div>
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
        <div class="fixed inset-0 bg-[#2B2520]/40 backdrop-blur-sm" @click="capModalOpen = false"></div>

        <div class="relative z-10 w-full max-w-md rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-md">
            <div class="flex items-start justify-between border-b border-[#F0ECE4] pb-3">
                <div>
                    <h2 class="text-lg font-semibold text-[#2B2520]">Ubah Batas Anggaran</h2>
                </div>
                <button type="button" @click="capModalOpen = false" class="rounded border border-[#E7E4DF] bg-white p-1 text-sm font-semibold text-[#6E665E] hover:text-[#2B2520] cursor-pointer">✕</button>
            </div>

            <form method="POST" action="{{ route('budget.updateCap') }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="total_budget" class="mb-1.5 block text-xs font-medium text-[#2B2520]">
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
                    <p class="mt-1.5 text-xs text-[#6E665E]">Pagu saat ini: <span class="font-semibold text-[#2B2520] tabular-nums">{{ format_idr($profile->total_budget) }}</span></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F0ECE4]">
                    <button type="button" @click="capModalOpen = false" class="rounded-lg border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#6E665E] hover:text-[#2B2520] hover:bg-[#F5F2EB] transition">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#2B2520] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        Simpan Batas Pagu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
