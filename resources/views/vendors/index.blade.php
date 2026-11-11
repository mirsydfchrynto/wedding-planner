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

    <div class="flex flex-col gap-3 border-b border-[#E7E4DF] pb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold text-[#8C5835]">Direktori Mitra Vendor &amp; Pembayaran</p>
            <h1 class="mt-1.5 text-2xl font-bold tracking-tight text-[#2B2520] sm:text-3xl">
                Mitra Vendor &amp; Kwitansi
            </h1>
            <p class="text-xs text-[#6E665E] sm:text-sm mt-0.5">
                Catatan kontrak, penanggung jawab lapangan, termin terbayar, dan sisa pelunasan dalam satu lembar kerja.
            </p>
        </div>
        <div class="flex items-center gap-2 no-print">
            <a href="{{ route('vendors.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                + Tambah Mitra Vendor
            </a>
        </div>
    </div>

    {{-- Visual SVG Payment Journey Flow Diagram --}}
    <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm overflow-x-auto">
        <div class="flex items-center justify-between pb-3 border-b border-[#F0ECE4] text-xs">
            <span class="font-semibold text-[#2B2520]">Alur Pembayaran Vendor</span>
            <span class="text-[#6E665E]">Total Kontrak: {{ format_idr($totalContract) }}</span>
        </div>
        <div class="pt-3">
            <svg viewBox="0 0 760 70" class="w-full min-w-[620px] h-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="120" y1="28" x2="380" y2="28" stroke="#8C5835" stroke-width="2" />
                <line x1="380" y1="28" x2="640" y2="28" stroke="#E7E4DF" stroke-width="2" stroke-dasharray="4 4" />

                <circle cx="120" cy="28" r="14" fill="#FAF8F5" stroke="#8C5835" stroke-width="2" />
                <text x="120" y="32" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" font-weight="700" fill="#8C5835">1</text>
                <text x="120" y="56" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="600" fill="#2B2520">Terkontrak ({{ $summary['booked_vendors'] }} Mitra)</text>

                <circle cx="380" cy="28" r="14" fill="#FAF8F5" stroke="#8C5835" stroke-width="2" />
                <text x="380" y="32" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" font-weight="700" fill="#8C5835">2</text>
                <text x="380" y="56" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="600" fill="#2B2520">Terbayar: {{ format_idr($totalPaid) }}</text>

                <circle cx="640" cy="28" r="14" fill="#FAF8F5" stroke="#2E5E3E" stroke-width="2" />
                <text x="640" y="32" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" font-weight="700" fill="#2E5E3E">3</text>
                <text x="640" y="56" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="600" fill="#2E5E3E">Sisa: {{ format_idr($totalRemaining) }}</text>
            </svg>
        </div>
    </div>

    <section aria-label="Ringkasan vendor" class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Mitra Terkontrak</p>
            <p class="text-xl sm:text-3xl font-semibold text-[#2B2520] tabular-nums">
                {{ $summary['booked_vendors'] }} <span class="text-xs font-normal text-[#6E665E]">/ {{ $summary['total_vendors'] }}</span>
            </p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Nilai Total Kontrak</p>
            <p class="text-xl sm:text-2xl font-semibold text-[#2B2520] tabular-nums">{{ format_idr($totalContract) }}</p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Total Terbayar</p>
            <p class="text-xl sm:text-2xl font-semibold text-[#2E5E3E] tabular-nums">{{ format_idr($totalPaid) }}</p>
        </div>
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 shadow-sm text-center space-y-1">
            <p class="text-xs font-medium text-[#6E665E]">Sisa Pelunasan</p>
            <p class="text-xl sm:text-2xl font-semibold tabular-nums {{ $totalRemaining > 0 ? 'text-[#9E352B]' : 'text-[#2E5E3E]' }}">
                {{ format_idr($totalRemaining) }}
            </p>
        </div>
    </section>

    <form method="GET" action="{{ route('vendors.index') }}" class="rounded-xl border border-[#E7E4DF] bg-white p-4 shadow-sm text-xs no-print">
        <div class="flex flex-wrap items-center gap-2.5">
            <input
                type="search"
                name="search"
                value="{{ $searchQuery ?? '' }}"
                placeholder="Cari nama vendor atau PIC..."
                class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs text-[#2B2520] placeholder-[#9E968D] focus:border-[#8C5835] focus:outline-none w-full sm:w-52"
            >

            <select name="category" aria-label="Filter kategori" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                        {{ $category->label() }}
                    </option>
                @endforeach
            </select>

            <select name="booking_status" aria-label="Filter status booking" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                <option value="">Semua Status</option>
                @foreach($bookingStatuses as $status)
                    <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="sort" aria-label="Urutkan" onchange="this.form.submit()" class="rounded-md border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-medium text-[#2B2520] focus:border-[#8C5835] focus:outline-none">
                <option value="">Urutan Kategori</option>
                <option value="contract_desc" @selected(($selectedSort ?? null) === 'contract_desc')>Kontrak Terbesar</option>
                <option value="contract_asc" @selected(($selectedSort ?? null) === 'contract_asc')>Kontrak Terkecil</option>
                <option value="due_date_asc" @selected(($selectedSort ?? null) === 'due_date_asc')>Tenggat Terdekat</option>
                <option value="remaining_desc" @selected(($selectedSort ?? null) === 'remaining_desc')>Sisa Terbesar</option>
            </select>

            <button type="submit" class="rounded-md border border-[#E7E4DF] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer">Filter</button>

            @if($selectedCategory || $selectedStatus || ($searchQuery ?? '') !== '' || $selectedSort)
                <a href="{{ route('vendors.index') }}" class="text-xs font-medium text-[#8C5835] hover:underline ml-1">Reset</a>
            @endif

            <span class="ml-auto text-[#6E665E] text-xs tabular-nums">{{ $vendors->count() }} vendor tercatat</span>
        </div>
    </form>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @forelse($vendors as $vendor)
            @php
                $guide = \App\Support\ActionGuide::forVendor($vendor, $summary['profile']);
                $isPaid = $vendor->remaining_balance === 0 && $vendor->contract_amount > 0;
                $isDp = $vendor->paid_amount > 0 && $vendor->remaining_balance > 0;
            @endphp
            <div class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm space-y-4 relative" x-data="{ copiedPhone: false }">

                <div class="flex items-start justify-between gap-3 border-b border-[#F0ECE4] pb-3">
                    <div>
                        <span class="text-xs font-medium text-[#8C5835]">{{ $vendor->category->label() }}</span>
                        <h2 class="text-lg font-semibold text-[#2B2520] leading-snug mt-0.5">{{ $vendor->name }}</h2>
                    </div>
                    <div class="text-right shrink-0">
                        @if($isPaid)
                            <span class="inline-flex items-center rounded border border-[#D3E6DA] bg-[#EAF4EE] px-2 py-0.5 text-xs font-medium text-[#2E5E3E]">Lunas</span>
                        @elseif($isDp)
                            <span class="inline-flex items-center rounded border border-[#F6E6CC] bg-[#FEF7EC] px-2 py-0.5 text-xs font-medium text-[#A06414]">Sudah DP</span>
                        @else
                            <span class="inline-flex items-center rounded border border-[#E7E4DF] bg-[#FAF8F5] px-2 py-0.5 text-xs font-medium text-[#6E665E]">Baru Tanya</span>
                        @endif

                        @if($vendor->is_payment_overdue)
                            <span class="block text-xs font-semibold text-[#9E352B] mt-1">Tenggat Lewat</span>
                        @endif
                    </div>
                </div>

                <div class="rounded-lg border border-[#E7E4DF] bg-[#FAF8F5] p-3 text-xs space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[#6E665E]">Penanggung Jawab:</span>
                        <strong class="text-[#2B2520] font-medium">{{ $vendor->contact_person }}</strong>
                    </div>

                    @if($vendor->phone)
                        <div class="flex items-center justify-between pt-1.5 border-t border-[#E7E4DF]">
                            <span class="text-[#6E665E]">Kontak WA:</span>
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ $vendor->whatsapp_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center gap-1 rounded-md bg-[#2E5E3E] px-2.5 py-0.5 text-xs font-medium text-white transition hover:bg-[#254F34]"
                                    title="Langsung buka chat WhatsApp"
                                >
                                    <span>Chat WA</span>
                                </a>
                                <button
                                    type="button"
                                    @click="window.copyToClipboard('{{ $vendor->phone }}'); copiedPhone = true; setTimeout(() => copiedPhone = false, 1800)"
                                    class="text-xs text-[#6E665E] hover:text-[#2B2520] cursor-pointer underline no-print"
                                >
                                    <span x-text="copiedPhone ? 'Tersalin' : 'Salin'">Salin</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-2 border-y border-[#F0ECE4] py-2.5 text-center text-xs">
                    <div>
                        <span class="text-xs text-[#6E665E] block">Kontrak Deal</span>
                        <span class="font-semibold text-[#2B2520] tabular-nums">{{ format_idr($vendor->contract_amount) }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6E665E] block">Terbayar</span>
                        <span class="font-semibold text-[#2E5E3E] tabular-nums">{{ format_idr($vendor->paid_amount) }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#6E665E] block">Sisa Tagihan</span>
                        <span class="font-semibold tabular-nums {{ $vendor->remaining_balance > 0 ? 'text-[#9E352B]' : 'text-[#6E665E]' }}">
                            {{ format_idr($vendor->remaining_balance) }}
                        </span>
                    </div>
                </div>

                @if($vendor->payments->isNotEmpty())
                    <div class="text-xs space-y-1">
                        <span class="font-medium text-[#2B2520] text-xs block">Riwayat Termin Terbayar:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($vendor->payments as $p)
                                <span class="rounded border border-[#E7E4DF] bg-[#FAF8F5] px-2 py-0.5 text-xs text-[#6E665E]">
                                    {{ $p->payment_stage }} ({{ $p->paid_date->format('d/m/y') }}): <strong class="text-[#2E5E3E] tabular-nums font-medium">{{ format_idr($p->amount) }}</strong>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-[#F0ECE4] text-xs no-print">
                    <button
                        type="button"
                        @click="openModal({{ Js::from($guide) }})"
                        class="inline-flex items-center gap-1 rounded-md border border-[#E7E4DF] bg-white px-2.5 py-1 text-xs font-medium text-[#8C5835] hover:bg-[#F5F2EB] transition cursor-pointer"
                    >
                        <x-ui.icon name="lightbulb" class="h-3 w-3 text-[#8C5835]" />
                        <span>Cara Urus &amp; WA</span>
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('vendors.payments.create', $vendor) }}" class="text-xs font-semibold text-[#2E5E3E] hover:underline">
                            + Catat Termin
                        </a>
                        <a href="{{ route('vendors.edit', $vendor) }}" class="text-xs font-semibold text-[#8C5835] hover:underline">
                            Ubah
                        </a>
                        <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" onsubmit="return confirm('Hapus vendor ini dari buku?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-[#9E352B] hover:underline cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 py-12 px-4 text-center rounded-xl border border-[#E7E4DF] bg-white">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#FAF8F5] border border-[#E7E4DF] text-[#8C5835] mb-3">
                    <x-ui.icon name="building" class="h-6 w-6" />
                </div>
                <h3 class="text-sm font-semibold text-[#2B2520]">Belum ada mitra vendor terdaftar</h3>
                <p class="mt-1 text-xs text-[#6E665E] max-w-sm mx-auto">
                    Mulai catat vendor gedung, katering, foto, atau busana Anda. Nilai kontrak yang disepakati akan otomatis terhubung ke Budget Planner.
                </p>
                <div class="mt-4">
                    <a href="{{ route('vendors.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2B2520] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                        + Tambah Mitra Vendor
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print"
        role="dialog"
        aria-modal="true"
        x-on:keydown.escape.window="closeModal()"
    >
        <div class="fixed inset-0 bg-[#2B2520]/40 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative z-10 w-full max-w-lg rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between gap-3 border-b border-[#F0ECE4] pb-3">
                <div>
                    <span class="inline-flex items-center rounded-md border border-[#E7E4DF] bg-[#FAF8F5] px-2 py-0.5 text-xs font-medium text-[#8C5835]">Koordinasi Vendor</span>
                    <h2 class="text-lg font-semibold text-[#2B2520] mt-1" x-text="'Panduan: ' + (guideData ? guideData.target_name : '')"></h2>
                </div>
                <button type="button" @click="closeModal()" class="rounded border border-[#E7E4DF] bg-white p-1 text-sm font-semibold text-[#6E665E] hover:text-[#2B2520] cursor-pointer">✕</button>
            </div>

            <div class="mt-4" x-show="guideData">
                <p class="text-xs font-semibold text-[#8C5835]">Langkah Koordinasi:</p>
                <ol class="mt-2 space-y-2 text-xs sm:text-sm text-[#2B2520]">
                    <template x-for="(step, idx) in (guideData ? guideData.steps : [])" :key="idx">
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-[#8C5835]" x-text="(idx + 1) + '.'"></span>
                            <span x-text="step"></span>
                        </li>
                    </template>
                </ol>

                <div class="mt-5 border-t border-[#F0ECE4] pt-4">
                    <p class="text-xs font-semibold text-[#8C5835]">Draf Pesan WhatsApp ke Vendor:</p>
                    <div class="mt-2 rounded-lg border border-[#E7E4DF] bg-[#FAF8F5] p-3 font-mono text-xs text-[#2B2520] whitespace-pre-wrap leading-relaxed" x-text="guideData ? guideData.wa_text : ''"></div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                        <button
                            type="button"
                            @click="window.copyToClipboard(guideData ? guideData.wa_text : ''); copiedWa = true; setTimeout(() => copiedWa = false, 2200)"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2B2520] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#1C1815] cursor-pointer"
                        >
                            <span x-text="copiedWa ? 'Teks WA Berhasil Disalin!' : 'Salin Draf WhatsApp'">Salin Draf WhatsApp</span>
                        </button>

                        <template x-if="guideData && guideData.wa_url">
                            <a :href="guideData.wa_url" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-lg bg-[#2E5E3E] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#254F34]">
                                Buka Aplikasi WhatsApp &rarr;
                            </a>
                        </template>

                        <button type="button" @click="closeModal()" class="text-xs text-[#6E665E] hover:text-[#2B2520] transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
