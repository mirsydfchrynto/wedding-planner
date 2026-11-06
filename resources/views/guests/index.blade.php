@extends('layouts.app', ['title' => 'Buku Tamu & RSVP'])

@section('content')
    <x-ui.section-header title="Buku Tamu & RSVP">
        <x-slot:actions>
            <button
                type="button"
                @click="$dispatch('open-modal', 'create-guest-modal')"
                class="editorial-btn-primary"
            >
                + Tambah Tamu
            </button>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Guest KPI Summary --}}
    <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Target Undangan"
            :value="number_format($profile->guest_target, 0, ',', '.') . ' Pax'"
            :subtitle="'Terdata: ' . $totalGuestsListed . ' pax (' . $guests->count() . ' kontak)'"
            badge="Kapasitas"
            badgeTone="champagne"
        />

        <x-ui.stat-card
            label="Konfirmasi Hadir"
            :value="$confirmedGuests . ' Pax'"
            :subtitle="round(($confirmedGuests / max(1, $profile->guest_target)) * 100) . '% dari kapasitas'"
            badge="Hadir"
            badgeTone="sage"
        >
            <x-ui.progress-bar size="sm" :percent="min(100, round(($confirmedGuests / max(1, $profile->guest_target)) * 100))" tone="sage" />
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Menunggu RSVP"
            :value="$pendingGuests . ' Pax'"
            subtitle="Belum konfirmasi kehadiran"
            badge="Pending"
            badgeTone="ochre"
        />

        <x-ui.stat-card
            label="Berhalangan"
            :value="$declinedGuests . ' Pax'"
            subtitle="Telah mengabari tidak hadir"
            badge="Absen"
            badgeTone="wine"
        />
    </section>

    {{-- Filter Bar & Guest Table --}}
    <section class="mt-6 editorial-card p-6">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-line pb-4 lg:flex-row lg:items-center">
            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ route('guests.index', ['category' => null, 'status' => $selectedStatus, 'search' => $search]) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ !$selectedCategory ? 'bg-espresso text-alabaster shadow-sm' : 'bg-pearl text-charcoal border border-stone-line hover:border-champagne' }}"
                >
                    Semua Kategori ({{ $guests->count() }})
                </a>
                @foreach($categories as $cat)
                    @php $isActive = $selectedCategory === $cat; @endphp
                    <a
                        href="{{ route('guests.index', ['category' => $isActive ? null : $cat, 'status' => $selectedStatus, 'search' => $search]) }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $isActive ? 'bg-champagne text-pearl shadow-sm' : 'bg-pearl text-charcoal border border-stone-line hover:border-champagne' }}"
                    >
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('guests.index') }}" class="flex flex-wrap items-center gap-2 no-print">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <select name="status" onchange="this.form.submit()" aria-label="Filter Status RSVP" class="editorial-input !w-auto !py-1.5 !text-xs font-medium">
                    <option value="">Semua Status RSVP</option>
                    <option value="confirmed" @selected($selectedStatus === 'confirmed')>Hadir (Confirmed)</option>
                    <option value="pending" @selected($selectedStatus === 'pending')>Menunggu (Pending)</option>
                    <option value="declined" @selected($selectedStatus === 'declined')>Berhalangan (Declined)</option>
                </select>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama / meja..."
                    class="editorial-input !w-44 !py-1.5 !text-xs"
                >
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-stone-line text-taupe font-semibold">
                        <th scope="col" class="py-3 pr-3">Nama Tamu &amp; Catatan</th>
                        <th scope="col" class="px-3 py-3">Kategori</th>
                        <th scope="col" class="px-3 py-3 text-center">Pax</th>
                        <th scope="col" class="px-3 py-3">Sesi Acara</th>
                        <th scope="col" class="px-3 py-3 text-center">Meja</th>
                        <th scope="col" class="px-3 py-3 text-center">Status RSVP</th>
                        <th scope="col" class="py-3 pl-3 text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-line/70">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-alabaster/60">
                            <td class="py-3.5 pr-3">
                                <p class="font-semibold text-espresso">{{ $guest->name }}</p>
                                @if($guest->notes)
                                    <p class="mt-0.5 text-[11px] text-taupe">{{ $guest->notes }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-3.5 text-taupe font-medium">
                                {{ $guest->category }}
                            </td>
                            <td class="px-3 py-3.5 text-center font-bold text-espresso">
                                {{ $guest->pax }}
                            </td>
                            <td class="px-3 py-3.5 text-charcoal">
                                {{ $guest->session }}
                            </td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-espresso">
                                <span class="rounded bg-champagne/15 px-2 py-0.5 text-[11px] text-espresso">
                                    {{ $guest->table_number ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-3.5 text-center">
                                @php
                                    $tone = match($guest->rsvp_status) {
                                        'confirmed' => 'sage',
                                        'declined' => 'wine',
                                        default => 'ochre',
                                    };
                                    $label = match($guest->rsvp_status) {
                                        'confirmed' => 'Hadir',
                                        'declined' => 'Absen',
                                        default => 'Pending',
                                    };
                                @endphp
                                <x-ui.status-badge :tone="$tone" :label="$label" />
                            </td>
                            <td class="py-3.5 pl-3 text-right no-print">
                                <div class="flex items-center justify-end gap-2.5">
                                    @if($guest->whatsapp_url)
                                        <a
                                            href="{{ $guest->whatsapp_url }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="font-semibold text-forest hover:underline"
                                            title="Kirim konfirmasi WhatsApp"
                                        >
                                            Kirim WA
                                        </a>
                                    @endif
                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal', 'edit-guest-{{ $guest->id }}')"
                                        class="font-semibold text-champagne hover:underline cursor-pointer"
                                    >
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('guests.destroy', $guest) }}" onsubmit="return confirm('Hapus tamu ini dari daftar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-wine hover:underline cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit Tamu --}}
                        <x-ui.modal name="edit-guest-{{ $guest->id }}" :title="'Edit Tamu: ' . $guest->name">
                            <form method="POST" action="{{ route('guests.update', $guest) }}" class="space-y-4 text-left">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Tamu Undangan</label>
                                    <input type="text" name="name" value="{{ $guest->name }}" required class="editorial-input">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                                        <select name="category" required class="editorial-input">
                                            @foreach($categories as $c)
                                                <option value="{{ $c }}" @selected($guest->category === $c)>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Jumlah Undangan (Pax)</label>
                                        <input type="number" name="pax" min="1" max="20" value="{{ $guest->pax }}" required class="editorial-input">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Sesi Acara</label>
                                        <input type="text" name="session" value="{{ $guest->session }}" required class="editorial-input">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Status RSVP</label>
                                        <select name="rsvp_status" required class="editorial-input">
                                            <option value="pending" @selected($guest->rsvp_status === 'pending')>Menunggu Konfirmasi</option>
                                            <option value="confirmed" @selected($guest->rsvp_status === 'confirmed')>Konfirmasi Hadir</option>
                                            <option value="declined" @selected($guest->rsvp_status === 'declined')>Berhalangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">Nomor Meja</label>
                                        <input type="text" name="table_number" value="{{ $guest->table_number }}" class="editorial-input">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-charcoal">WhatsApp</label>
                                        <input type="text" name="whatsapp_number" value="{{ $guest->whatsapp_number }}" class="editorial-input">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-charcoal">Catatan Khusus</label>
                                    <input type="text" name="notes" value="{{ $guest->notes }}" class="editorial-input">
                                </div>
                                <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                                    <button type="button" @click="$dispatch('close-modal', 'edit-guest-{{ $guest->id }}')" class="editorial-btn-secondary">
                                        Batal
                                    </button>
                                    <button type="submit" class="editorial-btn-primary">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </x-ui.modal>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-taupe">Belum ada tamu pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Modal Tambah Tamu Baru --}}
    <x-ui.modal name="create-guest-modal" title="Tambah Tamu Undangan Baru">
        <form method="POST" action="{{ route('guests.store') }}" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Nama Tamu Undangan</label>
                <input type="text" name="name" placeholder="Contoh: Bpk. Bambang Soesatyo & Ibu" required class="editorial-input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Kategori</label>
                    <select name="category" required class="editorial-input">
                        @foreach($categories as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Jumlah Undangan (Pax)</label>
                    <input type="number" name="pax" min="1" max="20" value="2" required class="editorial-input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Sesi Acara</label>
                    <input type="text" name="session" value="Resepsi Malam" required class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Status RSVP</label>
                    <select name="rsvp_status" required class="editorial-input">
                        <option value="pending" selected>Menunggu Konfirmasi</option>
                        <option value="confirmed">Konfirmasi Hadir</option>
                        <option value="declined">Berhalangan</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nomor Meja (Opsional)</label>
                    <input type="text" name="table_number" placeholder="Contoh: VIP-01 / Meja 04" class="editorial-input">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-charcoal">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp_number" placeholder="Contoh: 08123456789" class="editorial-input">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-charcoal">Catatan Khusus (Opsional)</label>
                <input type="text" name="notes" placeholder="Contoh: Vegetarian / Akomodasi kursi roda" class="editorial-input">
            </div>
            <div class="flex items-center justify-end gap-2.5 border-t border-stone-line pt-4">
                <button type="button" @click="$dispatch('close-modal', 'create-guest-modal')" class="editorial-btn-secondary">
                    Batal
                </button>
                <button type="submit" class="editorial-btn-primary">
                    Simpan Tamu
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
