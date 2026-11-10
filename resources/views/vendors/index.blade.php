@extends('layouts.app', ['title' => 'Vendor'])

@section('content')
    <x-ui.section-header
        title="Mitra Vendor"
        subtitle="Kontrak, kontak PIC, rekening, dan termin pembayaran dalam satu daftar."
    >
        <x-slot:actions>
            <a href="{{ route('vendors.create') }}" class="editorial-btn-primary">
                + Mitra vendor
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Angka pokok --}}
    <section aria-label="Ringkasan vendor" class="grid grid-cols-2 gap-x-8 gap-y-7 border-b border-stone-line py-7 lg:grid-cols-4">
        <x-ui.stat-card
            label="Terkontrak"
            :value="$summary['booked_vendors'] . ' / ' . $summary['total_vendors']"
            subtitle="Mitra aktif"
        />
        <x-ui.stat-card
            label="Nilai kontrak"
            :value="format_idr($totalContract)"
            subtitle="Seluruh mitra"
        />
        <x-ui.stat-card
            label="Terbayar"
            :value="format_idr($totalPaid)"
            :subtitle="'dari ' . format_idr($totalContract)"
        />
        <x-ui.stat-card
            label="Sisa tagihan"
            :value="format_idr($totalRemaining)"
            :badge="$totalRemaining > 0 ? 'Belum lunas' : 'Lunas'"
            :badgeTone="$totalRemaining > 0 ? 'ochre' : 'sage'"
        />
    </section>

    {{-- Filter --}}
    <form method="GET" action="{{ route('vendors.index') }}" class="flex flex-wrap items-center gap-2 border-b border-stone-line py-4 no-print">
        <input
            type="search"
            name="search"
            value="{{ $searchQuery ?? '' }}"
            placeholder="Cari nama vendor atau PIC..."
            aria-label="Cari nama vendor atau PIC"
            class="editorial-input !w-full sm:!w-56 !py-1.5 !text-xs"
        >

        <select name="category" aria-label="Filter kategori vendor" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>{{ $category->label() }}</option>
            @endforeach
        </select>

        <select name="booking_status" aria-label="Filter status booking" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
            <option value="">Semua status</option>
            @foreach($bookingStatuses as $status)
                <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>

        <select name="sort" aria-label="Urutkan vendor" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
            <option value="">Urutan kategori</option>
            <option value="contract_desc" @selected(($selectedSort ?? null) === 'contract_desc')>Kontrak terbesar</option>
            <option value="contract_asc" @selected(($selectedSort ?? null) === 'contract_asc')>Kontrak terkecil</option>
            <option value="due_date_asc" @selected(($selectedSort ?? null) === 'due_date_asc')>Tenggat terdekat</option>
            <option value="remaining_desc" @selected(($selectedSort ?? null) === 'remaining_desc')>Sisa terbesar</option>
        </select>

        <button type="submit" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">Cari</button>

        @if($selectedCategory || $selectedStatus || ($searchQuery ?? '') !== '' || $selectedSort)
            <a href="{{ route('vendors.index') }}" class="text-xs font-semibold text-champagne hover:underline">Reset</a>
        @endif

        <span class="ml-auto text-xs text-taupe tabular-nums">{{ $vendors->count() }} mitra</span>
    </form>

    {{-- Daftar vendor --}}
    <ul class="divide-y divide-stone-line/70">
        @forelse($vendors as $vendor)
            @php
                $vendorBankNumber = '8720' . str_pad((string) (($vendor->id * 184291) % 1000000), 6, '0', STR_PAD_LEFT);
                $vendorBankLabel = 'BCA ' . $vendorBankNumber;
            @endphp
            <li class="py-6">
                <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-baseline">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-champagne">{{ $vendor->category->label() }}</p>
                        <h2 class="mt-0.5 text-xl font-bold text-espresso">{{ $vendor->name }}</h2>
                    </div>
                    <p class="text-sm">
                        <x-ui.status-badge :tone="$vendor->booking_status->badgeTone()" :label="$vendor->booking_status->label()" />
                        @if($vendor->is_payment_overdue)
                            <span class="ml-2 text-xs font-semibold text-wine">tenggat terlewat</span>
                        @endif
                    </p>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-x-8 gap-y-2 text-xs sm:grid-cols-2">
                    <p class="text-taupe">
                        PIC <span class="font-semibold text-espresso">{{ $vendor->contact_person }}</span>
                        @if($vendor->phone)
                            · <a href="{{ $vendor->whatsapp_url }}" target="_blank" rel="noopener" class="font-semibold text-champagne hover:underline tabular-nums">{{ $vendor->phone }}</a>
                            <button
                                type="button"
                                x-data="{ copiedPhone: false }"
                                @click="window.copyToClipboard('{{ $vendor->phone }}'); copiedPhone = true; setTimeout(() => copiedPhone = false, 1800)"
                                class="ml-1 font-semibold text-taupe hover:text-espresso cursor-pointer no-print"
                                title="Salin nomor PIC"
                            ><span x-text="copiedPhone ? 'Tersalin' : 'Salin'">Salin</span></button>
                        @endif
                    </p>
                    <p class="text-taupe" x-data="{ copiedRek: false }">
                        Rekening <span class="font-mono font-semibold text-espresso">{{ $vendorBankLabel }}</span>
                        <button
                            type="button"
                            @click="window.copyToClipboard('{{ $vendorBankNumber }}'); copiedRek = true; setTimeout(() => copiedRek = false, 1800)"
                            class="ml-1 font-semibold text-taupe hover:text-espresso cursor-pointer no-print"
                            title="Salin nomor rekening"
                        ><span x-text="copiedRek ? 'Tersalin' : 'Salin'">Salin</span></button>
                        @if($vendor->email)
                            · {{ $vendor->email }}
                        @endif
                        @if($vendor->instagram)
                            · {{ $vendor->instagram }}
                        @endif
                    </p>
                </div>

                @if($vendor->notes)
                    <p class="mt-2 text-xs leading-relaxed text-taupe">{{ $vendor->notes }}</p>
                @endif

                <div class="mt-3 grid grid-cols-3 gap-4 border-t border-stone-line/70 pt-3 text-xs">
                    <p>
                        <span class="block text-taupe">Kontrak</span>
                        <span class="mt-0.5 block font-semibold text-espresso tabular-nums">{{ format_idr($vendor->contract_amount) }}</span>
                    </p>
                    <p>
                        <span class="block text-taupe">Terbayar</span>
                        <span class="mt-0.5 block font-semibold text-sage tabular-nums">{{ format_idr($vendor->paid_amount) }}</span>
                    </p>
                    <p class="text-right">
                        <span class="block text-taupe">Sisa</span>
                        <span class="mt-0.5 block font-semibold tabular-nums {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-taupe' }}">{{ format_idr($vendor->remaining_balance) }}</span>
                    </p>
                </div>

                @if($vendor->payments->isNotEmpty())
                    <div class="mt-2 text-xs text-taupe">
                        <span class="font-semibold text-charcoal">Termin:</span>
                        @foreach($vendor->payments as $p)
                            <span class="whitespace-nowrap">{{ $p->payment_stage }} {{ $p->paid_date->format('d/m/Y') }} <span class="font-mono font-semibold text-forest">{{ format_idr($p->amount) }}</span>@if(! $loop->last) · @endif</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs no-print">
                    <span class="text-taupe">
                        {{ $vendor->budget_items_count }} pos anggaran · {{ $vendor->tasks_count }} tugas
                        @if($vendor->payment_due_date)
                            · tenggat {{ $vendor->payment_due_date->format('d M Y') }}
                        @endif
                    </span>
                    <div class="flex items-center gap-4">
                        <a href="{{ $vendor->whatsapp_url }}" target="_blank" rel="noopener" class="font-semibold text-sage hover:underline">WhatsApp</a>
                        <a href="{{ route('vendors.payments.create', $vendor) }}" class="font-semibold text-forest hover:underline">+ Termin</a>
                        <a href="{{ route('vendors.edit', $vendor) }}" class="font-semibold text-champagne hover:underline">Ubah</a>
                        <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" onsubmit="return confirm('Hapus vendor ini dari direktori?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-wine hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="py-10 text-sm text-taupe">Belum ada vendor pada filter ini.</li>
        @endforelse
    </ul>
@endsection
