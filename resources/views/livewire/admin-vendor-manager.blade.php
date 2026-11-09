<div id="vendor-sla-section" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm" x-data="{ statusFilter: '' }">
    <div class="flex flex-col justify-between gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-800">
                <x-ui.icon name="building" class="h-4 w-4" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Verifikasi SLA &amp; Kontrak Mitra Vendor</h2>
                <p class="text-xs text-slate-500">Filter cepat dan verifikasi status kontrak operasional secara instan</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <select
                x-model="statusFilter"
                aria-label="Filter Status SLA Vendor"
                class="rounded-lg border border-slate-300 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400"
            >
                <option value="">Semua Status SLA ({{ $vendors->count() }} Mitra)</option>
                @foreach($statuses as $st)
                    <option value="{{ $st->value }}">{{ $st->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px]">
                    <th scope="col" class="py-3 pr-3 font-semibold">Mitra Vendor</th>
                    <th scope="col" class="px-3 py-3 font-semibold">Kategori</th>
                    <th scope="col" class="px-3 py-3 text-right font-semibold">Nilai Kontrak</th>
                    <th scope="col" class="px-3 py-3 text-right font-semibold">Sisa Tagihan</th>
                    <th scope="col" class="px-3 py-3 text-center font-semibold">Status SLA</th>
                    <th scope="col" class="py-3 pl-3 text-right font-semibold no-print">Tindakan Operasional</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($vendors as $vendor)
                    <tr
                        x-show="!statusFilter || statusFilter === '{{ $vendor->booking_status->value }}'"
                        class="hover:bg-slate-50/80 transition"
                    >
                        <td class="py-3.5 pr-3">
                            <p class="font-bold text-slate-900">{{ $vendor->name }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $vendor->contact_person }} · {{ $vendor->phone }}</p>
                        </td>
                        <td class="px-3 py-3.5 text-slate-600 font-medium">
                            {{ $vendor->category->label() }}
                        </td>
                        <td class="px-3 py-3.5 text-right tabular-nums font-mono font-semibold text-slate-900 whitespace-nowrap">
                            {{ format_idr($vendor->contract_amount) }}
                        </td>
                        <td class="px-3 py-3.5 text-right tabular-nums font-mono font-semibold whitespace-nowrap {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-sage' }}">
                            {{ format_idr($vendor->remaining_balance) }}
                        </td>
                        <td class="px-3 py-3.5 text-center">
                            <x-ui.status-badge :tone="$vendor->booking_status->badgeTone()" :label="$vendor->booking_status->label()" />
                        </td>
                        <td class="py-3.5 pl-3 text-right no-print">
                            <div class="inline-flex items-center gap-2">
                                <a
                                    href="{{ $vendor->whatsapp_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="rounded-md border border-emerald-300 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700 hover:bg-emerald-100 transition"
                                    title="Hubungi PIC Vendor via WhatsApp"
                                >
                                    WhatsApp
                                </a>

                                <form method="POST" action="{{ route('admin.verifyVendor', $vendor) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="rounded-md border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-100 hover:border-slate-400 transition cursor-pointer"
                                    >
                                        Verifikasi SLA
                                    </button>
                                </form>

                                @if($vendor->remaining_balance > 0)
                                    <form method="POST" action="{{ route('admin.markPaid', $vendor) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="rounded-md bg-slate-900 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-slate-800 transition cursor-pointer"
                                        >
                                            Tandai Lunas
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada vendor terdata.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
