@extends('layouts.app', ['title' => 'Konsol Admin & Wedding Organizer'])

@section('content')
    <x-ui.section-header
        title="Konsol Admin & Operasional Wedding Organizer"
        subtitle="Pusat kendali backoffice untuk memantau kesehatan anggaran klien, mengeskalasi tugas terlambat, memverifikasi kontrak vendor, dan mengaudit kesiapan infrastruktur 12-Factor."
    >
        <x-slot:actions>
            @if($overdueTasks->count() > 0)
                <form method="POST" action="{{ route('admin.escalateOverdue') }}">
                    @csrf
                    <button type="submit" class="editorial-btn-primary">
                        Eskalasi {{ $overdueTasks->count() }} Tugas Terlambat
                    </button>
                </form>
            @endif
            <a href="{{ route('dashboard.index') }}" class="editorial-btn-secondary">
                Lihat Tampilan Pengantin
            </a>
        </x-slot:actions>
    </x-ui.section-header>

    {{-- Admin Operational KPI Strip --}}
    <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Workspace Pasangan Aktif"
            :value="$profile->couple_names"
            :subtitle="$profile->venue_name . ' · H-' . $summary['days_remaining'] . ' Hari'"
            badge="Paket Keraton Royal"
            badgeTone="champagne"
        />

        <x-ui.stat-card
            label="Indeks Kesiapan Operasional"
            :value="$summary['readiness_score'] . '%'"
            :subtitle="'Checklist ' . $summary['completion_percent'] . '% · Vendor ' . $summary['vendor_booking_percent'] . '% · Bayar ' . $summary['budget_paid_percent'] . '%'"
            :badge="$summary['readiness_score'] >= 60 ? 'Terkendali' : 'Perlu Supervisi'"
            :badgeTone="$summary['readiness_score'] >= 60 ? 'sage' : 'ochre'"
        />

        <x-ui.stat-card
            label="Eksposur Tagihan Vendor"
            :value="format_idr($summary['unpaid_balance'])"
            :subtitle="'Dari total kontrak vendor ' . format_idr($vendors->sum('contract_amount'))"
            :badge="$vendors->where('remaining_balance', '>', 0)->count() . ' Mitra Belum Lunas'"
            badgeTone="ochre"
        />

        <x-ui.stat-card
            label="Status Infrastruktur 12-Factor"
            value="12 / 12 Lulus"
            :subtitle="'PHP ' . $systemDiagnostics['php_version'] . ' · Laravel ' . $systemDiagnostics['laravel_version'] . ' · DB ' . strtoupper($systemDiagnostics['db_driver'])"
            badge="Production Ready"
            badgeTone="sage"
        />
    </section>

    {{-- Main Admin Grid: High-Risk Escalations & Vendor SLA Verification --}}
    <section class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left 6 Cols: Overdue Tasks & High-Risk Budget Audit --}}
        <div class="space-y-6 lg:col-span-6">
            <div class="editorial-card rounded-lg p-6">
                <div class="flex items-center justify-between border-b border-stone-line pb-4">
                    <div>
                        <h2 class="font-editorial text-2xl font-semibold text-espresso">Audit Keterlambatan &amp; Eskalasi PIC</h2>
                        <p class="text-xs text-taupe">Daftar tugas yang melewati tenggat waktu dan membutuhkan intervensi WO</p>
                    </div>
                    <x-ui.status-badge
                        :tone="$overdueTasks->count() > 0 ? 'wine' : 'sage'"
                        :label="$overdueTasks->count() . ' Overdue'"
                    />
                </div>

                <div class="mt-4 divide-y divide-stone-line/70">
                    @forelse($overdueTasks as $task)
                        <div class="flex items-start justify-between gap-4 py-3.5 first:pt-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-espresso">{{ $task->title }}</p>
                                <p class="mt-0.5 text-xs text-taupe">
                                    Fase {{ $task->phase }} · PIC: <strong class="text-charcoal">{{ $task->assigned_to }}</strong> · Tenggat: {{ $task->due_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <x-ui.status-badge :tone="$task->priority_tone" :label="$task->priority_label" />
                                <form method="POST" action="{{ route('checklist.toggle', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="editorial-btn-secondary !py-1 !px-2.5 !text-xs">
                                        Selesaikan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-taupe">Tidak ada tugas yang melewati tenggat waktu.</p>
                    @endforelse
                </div>
            </div>

            <div class="editorial-card rounded-lg p-6">
                <div class="flex items-center justify-between border-b border-stone-line pb-4">
                    <div>
                        <h2 class="font-editorial text-2xl font-semibold text-espresso">Audit Pos Anggaran Berisiko Tinggi</h2>
                        <p class="text-xs text-taupe">Komponen biaya yang melebihi estimasi (overbudget) atau memiliki sisa tagihan &gt; Rp 20 Juta</p>
                    </div>
                    <a href="{{ route('budget.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                        Buku Anggaran
                    </a>
                </div>

                <div class="mt-4 divide-y divide-stone-line/70">
                    @forelse($highRiskBudgetItems as $item)
                        <div class="flex items-center justify-between gap-4 py-3.5 first:pt-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-espresso">{{ $item->title }}</p>
                                <p class="mt-0.5 text-xs text-taupe">
                                    {{ $item->category->label() }} · Estimasi {{ format_idr($item->estimated_cost) }} vs Aktual {{ format_idr($item->actual_cost) }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                @if($item->is_overbudget)
                                    <x-ui.status-badge tone="wine" :label="'Over ' . format_idr(abs($item->variance_amount))" />
                                @else
                                    <x-ui.status-badge tone="ochre" :label="'Sisa ' . format_idr($item->remaining_balance)" />
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-taupe">Seluruh pos anggaran berada dalam batas aman.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right 6 Cols: Vendor SLA & Contract Verification Matrix --}}
        <div class="editorial-card rounded-lg p-6 lg:col-span-6">
            <div class="flex items-center justify-between border-b border-stone-line pb-4">
                <div>
                    <h2 class="font-editorial text-2xl font-semibold text-espresso">Verifikasi SLA &amp; Kontrak Mitra Vendor</h2>
                    <p class="text-xs text-taupe">Validasi status ikatan kontrak dan sisa kewajiban pembayaran mitra</p>
                </div>
                <a href="{{ route('vendors.index') }}" class="text-xs font-semibold text-champagne hover:underline">
                    Kelola Direktori
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-line text-taupe">
                            <th class="py-2.5 pr-3">Vendor &amp; PIC</th>
                            <th class="px-2.5 py-2.5 text-right">Kontrak / Sisa</th>
                            <th class="px-2.5 py-2.5 text-center">Status</th>
                            <th class="py-2.5 pl-2.5 text-right no-print">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-line/70">
                        @foreach($vendors as $vendor)
                            <tr>
                                <td class="py-3 pr-3">
                                    <p class="font-semibold text-espresso">{{ $vendor->name }}</p>
                                    <p class="text-[11px] text-taupe">{{ $vendor->category->shortLabel() }} · {{ $vendor->contact_person }}</p>
                                </td>
                                <td class="px-2.5 py-3 text-right tabular-nums">
                                    <p class="font-semibold text-espresso">{{ format_idr($vendor->contract_amount) }}</p>
                                    <p class="text-[11px] {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-sage' }}">
                                        Sisa: {{ format_idr($vendor->remaining_balance) }}
                                    </p>
                                </td>
                                <td class="px-2.5 py-3 text-center">
                                    <x-ui.status-badge :tone="$vendor->booking_status->badgeTone()" :label="$vendor->booking_status->label()" />
                                </td>
                                <td class="py-3 pl-2.5 text-right no-print">
                                    <form method="POST" action="{{ route('admin.verifyVendor', $vendor) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="editorial-btn-secondary !py-1 !px-2.5 !text-xs">
                                            Verifikasi Kontrak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Twelve-Factor App Architecture Compliance Matrix --}}
    <section class="mt-8 editorial-card rounded-lg p-6">
        <div class="flex flex-col justify-between gap-2 border-b border-stone-line pb-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-editorial text-2xl font-semibold text-espresso">Matriks Kepatuhan Arsitektur Twelve-Factor SaaS</h2>
                <p class="text-xs text-taupe">Pemeriksaan kesiapan aplikasi untuk deployment cloud, isolasi konfigurasi, dan kolaborasi tim 4 branch</p>
            </div>
            <x-ui.status-badge tone="sage" label="12 / 12 Faktor Terverifikasi" />
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($twelveFactors as $item)
                <div class="rounded border border-stone-line bg-alabaster p-3.5 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-semibold text-espresso">{{ $item['factor'] }}</span>
                        <span class="font-semibold text-sage">{{ $item['status'] }}</span>
                    </div>
                    <p class="mt-1.5 leading-relaxed text-taupe">{{ $item['detail'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
