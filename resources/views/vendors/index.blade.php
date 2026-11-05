@extends('layouts.app', ['title' => 'Vendor Management'])

@section('content')
    <x-ui.section-header
        title="Manajemen Vendor & Status Pembayaran"
        subtitle="Kelola kurasi vendor pernikahan, rincian kontak PIC, nilai kontrak kerja, pembayaran DP/termin, dan sisa pelunasan."
    >
        <x-slot:actions>
            <button
                type="button"
                @click="$dispatch('open-modal', 'create-vendor-modal')"
                class="editorial-btn-primary"
            >
                + Tambah Mitra Vendor
            </button>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Vendor Financial & Booking Summary Cards --}}
    <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Total Vendor Terdaftar"
            :value="$summary['booked_vendors'] . ' / ' . $summary['total_vendors'] . ' Mitra'"
            subtitle="Vendor terkunci kontrak, DP, atau lunas"
            badge="Direktori Aktif"
            badgeTone="champagne"
        />

        <x-ui.stat-card
            label="Total Nilai Kontrak Vendor"
            :value="format_idr($totalContract)"
            subtitle="Akumulasi nilai kontrak pada filter aktif"
        />

        <x-ui.stat-card
            label="Total DP & Pelunasan Terbayar"
            :value="format_idr($totalPaid)"
            :subtitle="($totalContract > 0 ? round(($totalPaid / $totalContract) * 100) : 0) . '% dari nilai kontrak vendor'"
            badge="Terbayar"
            badgeTone="sage"
        />

        <x-ui.stat-card
            label="Sisa Tagihan Kontrak Vendor"
            :value="format_idr($totalRemaining)"
            subtitle="Kewajiban pelunasan sebelum Hari-H"
            :badge="$totalRemaining > 0 ? 'Menunggu Pelunasan' : 'Seluruh Kontrak Lunas'"
            :badgeTone="$totalRemaining > 0 ? 'ochre' : 'sage'"
        />
    </section>

    {{-- Filter, Search & Sort Controls --}}
    <section class="mt-6 editorial-card rounded-lg p-5">
        <form method="GET" action="{{ route('vendors.index') }}" class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div class="flex flex-wrap items-center gap-2">
                <input
                    type="search"
                    name="search"
                    value="{{ $searchQuery ?? '' }}"
                    placeholder="Cari nama vendor atau PIC..."
                    aria-label="Cari Nama Vendor atau PIC"
                    class="editorial-input !w-full sm:!w-56 !py-1.5 !text-xs"
                >

                <select name="category" aria-label="Filter Kategori Vendor" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua Kategori Vendor</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                            {{ $category->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="booking_status" aria-label="Filter Status Booking Vendor" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Semua Status Booking</option>
                    @foreach($bookingStatuses as $status)
                        <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="sort" aria-label="Urutkan Daftar Vendor" onchange="this.form.submit()" class="editorial-input !w-auto !py-1.5 !text-xs">
                    <option value="">Urutan Default (Kategori)</option>
                    <option value="contract_desc" @selected(($selectedSort ?? null) === 'contract_desc')>Nilai Kontrak Terbesar</option>
                    <option value="contract_asc" @selected(($selectedSort ?? null) === 'contract_asc')>Nilai Kontrak Terkecil</option>
                    <option value="due_date_asc" @selected(($selectedSort ?? null) === 'due_date_asc')>Terdekat Jatuh Tempo</option>
                    <option value="remaining_desc" @selected(($selectedSort ?? null) === 'remaining_desc')>Sisa Tagihan Terbesar</option>
                </select>

                <button type="submit" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">
                    Cari
                </button>

                @if($selectedCategory || $selectedStatus || ($searchQuery ?? '') !== '' || $selectedSort)
                    <a href="{{ route('vendors.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                        Reset Filter
                    </a>
                @endif
            </div>

            <p class="text-xs text-taupe tabular-nums">
                Menampilkan <strong>{{ $vendors->count() }}</strong> mitra vendor
            </p>
        </form>
    </section>

    {{-- Vendor Editorial Directory Grid --}}
    <section class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
        @forelse($vendors as $vendor)
            <article class="editorial-card flex flex-col justify-between rounded-lg p-6">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="text-xs font-semibold text-champagne">{{ $vendor->category->label() }}</span>
                            <h2 class="mt-0.5 font-editorial text-2xl font-semibold text-espresso">{{ $vendor->name }}</h2>
                        </div>
                        <div class="flex flex-wrap items-center justify-end gap-1.5">
                            @if($vendor->is_payment_overdue)
                                <x-ui.status-badge tone="wine" label="Jatuh Tempo Terlewat" />
                            @endif
                            <x-ui.status-badge :tone="$vendor->booking_status->badgeTone()" :label="$vendor->booking_status->label()" />
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-2 rounded border border-stone-line bg-alabaster p-3.5 text-xs sm:grid-cols-2">
                        <div>
                            <span class="text-taupe">Kontak PIC:</span>
                            <p class="font-semibold text-espresso">{{ $vendor->contact_person }}</p>
                        </div>
                        <div>
                            <span class="text-taupe">Telepon / WhatsApp:</span>
                            <p class="tabular-nums font-semibold text-espresso">
                                <a href="{{ $vendor->whatsapp_url }}" target="_blank" rel="noopener" class="text-champagne hover:underline">
                                    {{ $vendor->phone }}
                                </a>
                            </p>
                        </div>
                        @if($vendor->email)
                            <div>
                                <span class="text-taupe">Email:</span>
                                <p class="truncate text-charcoal">{{ $vendor->email }}</p>
                            </div>
                        @endif
                        @if($vendor->instagram)
                            <div>
                                <span class="text-taupe">Instagram:</span>
                                <p class="font-medium text-charcoal">{{ $vendor->instagram }}</p>
                            </div>
                        @endif
                    </div>

                    @if($vendor->notes)
                        <p class="mt-3 text-xs leading-relaxed text-taupe">{{ $vendor->notes }}</p>
                    @endif
                </div>

                <div class="mt-5 border-t border-stone-line pt-4">
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div>
                            <span class="text-taupe">Nilai Kontrak</span>
                            <p class="mt-0.5 tabular-nums font-semibold text-espresso">{{ format_idr($vendor->contract_amount) }}</p>
                        </div>
                        <div>
                            <span class="text-taupe">Sudah Dibayar</span>
                            <p class="mt-0.5 tabular-nums font-semibold text-sage">{{ format_idr($vendor->paid_amount) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-taupe">Sisa Pelunasan</span>
                            <p class="mt-0.5 tabular-nums font-semibold {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-taupe' }}">
                                {{ format_idr($vendor->remaining_balance) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-ui.progress-bar
                            size="sm"
                            :percent="$vendor->payment_progress_percent"
                            :tone="$vendor->payment_progress_percent === 100 ? 'sage' : 'champagne'"
                            :label="'Progres Pembayaran (' . $vendor->payment_progress_percent . '%)'"
                            :meta="$vendor->payment_due_date ? 'Jatuh tempo ' . $vendor->payment_due_date->format('d M Y') : 'Tanpa tenggat tetap'"
                        />
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-stone-line/60 pt-3 text-xs no-print">
                        <span class="text-taupe">
                            Terhubung ke {{ $vendor->budget_items_count }} pos anggaran &amp; {{ $vendor->tasks_count }} tugas
                        </span>

                        <div class="flex items-center gap-3">
                            <a
                                href="{{ $vendor->whatsapp_url }}"
                                target="_blank"
                                rel="noopener"
                                class="font-semibold text-sage hover:underline"
                            >
                                WhatsApp PIC
                            </a>
                            <button
                                type="button"
                                @click="$dispatch('open-modal', 'edit-vendor-{{ $vendor->id }}')"
                                class="font-semibold text-champagne hover:underline"
                            >
                                Edit Kontrak &amp; Pembayaran
                            </button>
                            <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" onsubmit="return confirm('Hapus vendor ini dari direktori?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-wine hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal: Edit Vendor --}}
                <x-ui.modal name="edit-vendor-{{ $vendor->id }}" :title="'Edit Vendor: ' . $vendor->name" subtitle="Perbarui rincian kontak, status kontrak, dan progres pembayaran">
                    <form method="POST" action="{{ route('vendors.update', $vendor) }}" class="space-y-4 text-left">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Perusahaan / Vendor</label>
                                <input type="text" name="name" value="{{ $vendor->name }}" required class="editorial-input">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                                <select name="category" required class="editorial-input">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->value }}" @selected($vendor->category === $category)>
                                            {{ $category->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Kontak PIC</label>
                                <input type="text" name="contact_person" value="{{ $vendor->contact_person }}" required class="editorial-input">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" value="{{ $vendor->phone }}" required class="editorial-input tabular-nums">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Email</label>
                                <input type="email" name="email" value="{{ $vendor->email }}" class="editorial-input">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Akun Instagram</label>
                                <input type="text" name="instagram" value="{{ $vendor->instagram }}" class="editorial-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Nilai Kontrak (Rp)</label>
                                <input type="number" name="contract_amount" value="{{ $vendor->contract_amount }}" min="0" required class="editorial-input tabular-nums">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Sudah Dibayar (Rp)</label>
                                <input type="number" name="paid_amount" value="{{ $vendor->paid_amount }}" min="0" required class="editorial-input tabular-nums">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Status Booking</label>
                                <select name="booking_status" required class="editorial-input">
                                    @foreach($bookingStatuses as $status)
                                        <option value="{{ $status->value }}" @selected($vendor->booking_status === $status)>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Tanggal Jatuh Tempo Pelunasan</label>
                                <input type="date" name="payment_due_date" value="{{ $vendor->payment_due_date?->format('Y-m-d') }}" class="editorial-input tabular-nums">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-charcoal">Catatan Kontrak / Deliverables</label>
                                <input type="text" name="notes" value="{{ $vendor->notes }}" class="editorial-input">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                            <button type="button" @click="$dispatch('close-modal', 'edit-vendor-{{ $vendor->id }}')" class="editorial-btn-secondary">
                                Batal
                            </button>
                            <button type="submit" class="editorial-btn-primary">
                                Simpan Perubahan Vendor
                            </button>
                        </div>
                    </form>
                </x-ui.modal>
            </article>
        @empty
            <div class="col-span-full editorial-card rounded-lg p-10 text-center">
                <p class="text-sm text-taupe">Belum ada vendor pada kategori atau status yang dipilih.</p>
            </div>
        @endforelse
    </section>

    {{-- Modal: Create New Vendor --}}
    <x-ui.modal name="create-vendor-modal" title="Tambah Mitra Vendor Baru" subtitle="Catat vendor baru ke dalam direktori mitra pernikahan">
        <form method="POST" action="{{ route('vendors.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Perusahaan / Vendor</label>
                    <input type="text" name="name" placeholder="Contoh: Sovereign Wedding Lighting & Sound" required class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select name="category" required class="editorial-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}">{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Kontak PIC</label>
                    <input type="text" name="contact_person" placeholder="Nama Account Manager" required class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" placeholder="0812xxxxxxxx" required class="editorial-input tabular-nums">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Email (Opsional)</label>
                    <input type="email" name="email" placeholder="contact@vendor.id" class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Instagram (Opsional)</label>
                    <input type="text" name="instagram" placeholder="@nama.vendor" class="editorial-input">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nilai Kontrak (Rp)</label>
                    <input type="number" name="contract_amount" value="25000000" min="0" step="500000" required class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">DP / Terbayar (Rp)</label>
                    <input type="number" name="paid_amount" value="5000000" min="0" step="500000" class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Status Booking</label>
                    <select name="booking_status" required class="editorial-input">
                        @foreach($bookingStatuses as $status)
                            <option value="{{ $status->value }}" @selected($status->value === 'booked')>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Jatuh Tempo Pelunasan</label>
                    <input type="date" name="payment_due_date" class="editorial-input tabular-nums">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Catatan Deliverables</label>
                    <input type="text" name="notes" placeholder="Rincian paket layanan..." class="editorial-input">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'create-vendor-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Mitra Vendor
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
