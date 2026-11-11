@extends('layouts.app', ['title' => 'Direktori Kwitansi Vendor'])

@section('content')
<div x-data="{
    modalOpen: false,
    guideData: null,
    copiedWa: false,
    openModal(data) {
        this.guideData = data;
        this.copiedWa = false;
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
    }
}" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 border-b-2 border-ink pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-soga">
                <span class="stamp !bg-kunyit font-black">1-Pandang</span>
                <span>Mitra Vendor &amp; Pengendali Pembayaran</span>
            </div>
            <h1 class="mt-1 font-display text-2xl font-black tracking-tight text-espresso sm:text-4xl">
                Mitra Vendor &amp; Kwitansi
            </h1>
            <p class="text-xs text-taupe font-semibold sm:text-sm">
                Catatan kontrak, penanggung jawab lapangan, termin terbayar, dan sisa pelunasan dalam satu lembar.
            </p>
        </div>
        <div class="flex items-center gap-2 no-print">
            <a href="{{ route('vendors.create') }}" class="btn-neo !py-1.5 !px-3.5 !text-xs font-black">
                + Tambah Mitra Vendor
            </a>
        </div>
    </div>

    {{-- 4 Angka Ringkasan Vendor Bento --}}
    <section aria-label="Ringkasan vendor" class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
        <div class="bento-card bento-paper p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Mitra Terkontrak</p>
            <p class="font-display text-xl font-black text-ink sm:text-3xl">
                {{ $summary['booked_vendors'] }} <span class="text-xs font-bold text-taupe">/ {{ $summary['total_vendors'] }}</span>
            </p>
        </div>
        <div class="bento-card bento-kunyit p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Nilai Total Kontrak</p>
            <p class="rupiah font-display text-lg font-black text-ink sm:text-2xl">{{ format_idr($totalContract) }}</p>
        </div>
        <div class="bento-card bento-mint p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Total Terbayar</p>
            <p class="rupiah font-display text-lg font-black text-forest sm:text-2xl">{{ format_idr($totalPaid) }}</p>
        </div>
        <div class="bento-card {{ $totalRemaining > 0 ? 'bento-terracotta' : 'bento-mint' }} p-3 text-center">
            <p class="text-[0.68rem] font-black uppercase tracking-wider text-taupe">Sisa Pelunasan</p>
            <p class="rupiah font-display text-lg font-black {{ $totalRemaining > 0 ? 'text-wine' : 'text-forest' }} sm:text-2xl">
                {{ format_idr($totalRemaining) }}
            </p>
        </div>
    </section>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('vendors.index') }}" class="bento-card bento-paper p-3.5 text-xs no-print">
        <div class="flex flex-wrap items-center gap-2">
            <input
                type="search"
                name="search"
                value="{{ $searchQuery ?? '' }}"
                placeholder="Cari nama vendor atau PIC..."
                class="editorial-input !w-full sm:!w-48 !py-1 !text-xs font-bold"
            >

            <select name="category" aria-label="Filter kategori" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                        {{ $category->label() }}
                    </option>
                @endforeach
            </select>

            <select name="booking_status" aria-label="Filter status booking" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                <option value="">Semua Status</option>
                @foreach($bookingStatuses as $status)
                    <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="sort" aria-label="Urutkan" onchange="this.form.submit()" class="editorial-input !w-auto !py-1 !text-xs font-bold">
                <option value="">Urutan Kategori</option>
                <option value="contract_desc" @selected(($selectedSort ?? null) === 'contract_desc')>Kontrak Terbesar</option>
                <option value="contract_asc" @selected(($selectedSort ?? null) === 'contract_asc')>Kontrak Terkecil</option>
                <option value="due_date_asc" @selected(($selectedSort ?? null) === 'due_date_asc')>Tenggat Terdekat</option>
                <option value="remaining_desc" @selected(($selectedSort ?? null) === 'remaining_desc')>Sisa Terbesar</option>
            </select>

            <button type="submit" class="btn-neo-secondary !min-h-0 !py-1 !px-3 !text-xs font-bold">Filter</button>

            @if($selectedCategory || $selectedStatus || ($searchQuery ?? '') !== '' || $selectedSort)
                <a href="{{ route('vendors.index') }}" class="text-xs font-bold text-soga hover:underline ml-1">Reset</a>
            @endif

            <span class="ml-auto text-taupe font-bold text-[0.72rem]">{{ $vendors->count() }} vendor tercatat</span>
        </div>
    </form>

    {{-- Grid Kartu Bento Kwitansi Vendor (2 Kolom Desktop, 1 Kolom HP) --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @forelse($vendors as $vendor)
            @php
                $guide = \App\Support\ActionGuide::forVendor($vendor, $summary['profile']);
                $isPaid = $vendor->remaining_balance === 0 && $vendor->contract_amount > 0;
                $isDp = $vendor->paid_amount > 0 && $vendor->remaining_balance > 0;
            @endphp
            <div class="bento-card bento-paper p-5 space-y-3 relative" x-data="{ copiedPhone: false }">
                <div class="tape"></div>

                {{-- Header Kartu: Kategori, Nama, Stamp Status 1-Pandang --}}
                <div class="flex items-start justify-between gap-3 pt-1 border-b-2 border-ink pb-2.5">
                    <div>
                        <span class="text-[0.68rem] font-black uppercase tracking-wider text-soga">{{ $vendor->category->label() }}</span>
                        <h2 class="font-display text-xl font-black text-ink leading-snug">{{ $vendor->name }}</h2>
                    </div>
                    <div class="text-right shrink-0">
                        @if($isPaid)
                            <span class="stamp !bg-mint !border-forest !text-forest font-black">LUNAS</span>
                        @elseif($isDp)
                            <span class="stamp !bg-kunyit font-black">Sudah DP</span>
                        @else
                            <span class="stamp !bg-paper-2 font-bold">Baru Tanya</span>
                        @endif

                        @if($vendor->is_payment_overdue)
                            <span class="block text-[0.65rem] font-black text-wine mt-1">Tenggat Lewat</span>
                        @endif
                    </div>
                </div>

                {{-- Kontak PIC & WhatsApp 1-Klik --}}
                <div class="bento-card bento-paper2 p-3 text-xs space-y-1.5 border-2 border-ink">
                    <div class="flex items-center justify-between">
                        <span class="text-taupe font-semibold">Penanggung Jawab:</span>
                        <strong class="text-ink">{{ $vendor->contact_person }}</strong>
                    </div>

                    @if($vendor->phone)
                        <div class="flex items-center justify-between pt-1 border-t border-ink/30">
                            <span class="text-taupe font-semibold">Kontak WA:</span>
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ $vendor->whatsapp_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="btn-neo-wa !min-h-0 !py-0.5 !px-2 !text-[0.7rem] font-black"
                                    title="Langsung buka chat WhatsApp"
                                >
                                    💬 Chat WA Langsung
                                </a>
                                <button
                                    type="button"
                                    @click="window.copyToClipboard('{{ $vendor->phone }}'); copiedPhone = true; setTimeout(() => copiedPhone = false, 1800)"
                                    class="text-[0.68rem] text-taupe hover:text-ink cursor-pointer underline no-print font-bold"
                                >
                                    <span x-text="copiedPhone ? 'Tersalin' : 'Salin'">Salin</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Ledger Angka: Kontrak Deal, Terbayar, Sisa Pelunasan --}}
                <div class="grid grid-cols-3 gap-2 border-y-2 border-ink py-2 text-center text-xs">
                    <div>
                        <span class="text-[0.65rem] text-taupe uppercase tracking-wider block font-bold">Kontrak Deal</span>
                        <span class="rupiah font-black text-ink">{{ format_idr($vendor->contract_amount) }}</span>
                    </div>
                    <div>
                        <span class="text-[0.65rem] text-taupe uppercase tracking-wider block font-bold">Terbayar</span>
                        <span class="rupiah font-black text-forest">{{ format_idr($vendor->paid_amount) }}</span>
                    </div>
                    <div>
                        <span class="text-[0.65rem] text-taupe uppercase tracking-wider block font-bold">Sisa Tagihan</span>
                        <span class="rupiah font-black {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-taupe' }}">
                            {{ format_idr($vendor->remaining_balance) }}
                        </span>
                    </div>
                </div>

                {{-- Riwayat Pembayaran Termin --}}
                @if($vendor->payments->isNotEmpty())
                    <div class="text-[0.72rem] text-taupe space-y-1">
                        <span class="font-black text-ink uppercase tracking-wider text-[0.65rem] block">Riwayat Termin Terbayar:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($vendor->payments as $p)
                                <span class="bento-card bento-paper2 px-1.5 py-0.5 border border-ink font-semibold">
                                    {{ $p->payment_stage }} ({{ $p->paid_date->format('d/m/y') }}): <strong class="rupiah text-forest">{{ format_idr($p->amount) }}</strong>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tombol Aksi di Bawah Kartu --}}
                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-ink/40 text-xs no-print">
                    <button
                        type="button"
                        @click="openModal({{ Js::from($guide) }})"
                        class="btn-neo-secondary !min-h-0 !py-1 !px-2.5 !text-[0.7rem] font-bold text-soga hover:text-ink cursor-pointer"
                    >
                        💡 Cara Urus &amp; WA
                    </button>

                    <div class="flex items-center gap-3 font-bold">
                        <a href="{{ route('vendors.payments.create', $vendor) }}" class="text-forest hover:underline">
                            + Catat Termin
                        </a>
                        <a href="{{ route('vendors.edit', $vendor) }}" class="text-soga hover:underline">
                            Ubah
                        </a>
                        <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" onsubmit="return confirm('Hapus vendor ini dari buku?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-wine hover:underline cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 py-10 text-center text-xs text-taupe font-bold bento-card bento-paper">
                Belum ada mitra vendor pada saringan pencarian ini.
            </div>
        @endforelse
    </div>

    {{-- Modal Dialog Kertas Neo-Brutalist: Cara Urus Vendor & WhatsApp --}}
    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print"
        role="dialog"
        aria-modal="true"
        x-on:keydown.escape.window="closeModal()"
    >
        <div class="fixed inset-0 bg-ink/50" @click="closeModal()"></div>

        <div class="modal-paper relative z-10 w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between gap-3 border-b-2 border-ink pb-3">
                <div>
                    <span class="stamp !bg-kunyit font-black">Koordinasi Vendor</span>
                    <h2 class="font-display text-xl font-extrabold text-ink mt-1" x-text="'Panduan: ' + (guideData ? guideData.target_name : '')"></h2>
                </div>
                <button type="button" @click="closeModal()" class="btn-neo-secondary !min-h-0 !p-1 text-sm font-bold cursor-pointer">✕</button>
            </div>

            <div class="mt-4" x-show="guideData">
                <p class="text-xs font-black uppercase tracking-wider text-soga">Langkah Koordinasi yang Disarankan:</p>
                <ol class="mt-2 space-y-2 text-xs sm:text-sm text-ink">
                    <template x-for="(step, idx) in (guideData ? guideData.steps : [])" :key="idx">
                        <li class="flex items-start gap-2">
                            <span class="hand font-bold text-soga" x-text="(idx + 1) + '.'"></span>
                            <span class="font-semibold" x-text="step"></span>
                        </li>
                    </template>
                </ol>

                <div class="mt-5 border-t-2 border-ink pt-3">
                    <p class="text-xs font-black uppercase tracking-wider text-soga">Draf Pesan WhatsApp ke Vendor:</p>
                    <div class="bento-card bento-paper2 mt-2 p-3 font-mono text-[0.72rem] text-ink whitespace-pre-wrap leading-relaxed border-2 border-ink" x-text="guideData ? guideData.wa_text : ''"></div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                        <button
                            type="button"
                            @click="window.copyToClipboard(guideData ? guideData.wa_text : ''); copiedWa = true; setTimeout(() => copiedWa = false, 2200)"
                            class="btn-neo !py-1.5 !px-3 !text-xs cursor-pointer font-black"
                        >
                            <span x-text="copiedWa ? 'Teks WA Berhasil Disalin!' : 'Salin Draf WhatsApp'">Salin Draf WhatsApp</span>
                        </button>

                        <template x-if="guideData && guideData.wa_url">
                            <a :href="guideData.wa_url" target="_blank" rel="noopener" class="btn-neo-wa !py-1.5 !px-3 !text-xs font-black">
                                Buka Aplikasi WhatsApp &rarr;
                            </a>
                        </template>

                        <button type="button" @click="closeModal()" class="text-xs text-taupe hover:text-ink font-bold underline">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
