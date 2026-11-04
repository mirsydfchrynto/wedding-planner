<div id="vendor-sla-section" class="rounded-lg border border-slate-200 bg-white p-5">
    <div class="flex flex-col justify-between gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-700">
                <x-ui.icon name="building" class="h-4 w-4" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Verifikasi SLA &amp; Kontrak Mitra Vendor (Livewire)</h2>
                <p class="text-xs text-slate-500">Filter dan verifikasi status kontrak secara instan tanpa muat ulang halaman</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <select wire:model.live="statusFilter" aria-label="Filter Status SLA Vendor" class="rounded-md border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs font-medium text-slate-700">
                <option value="">Semua Status ({{ $vendors->count() }})</option>
                @foreach($statuses as $st)
                    <option value="{{ $st->value }}">{{ $st->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500">
                    <th scope="col" class="py-2.5 pr-3 font-semibold">Mitra Vendor</th>
                    <th scope="col" class="px-2.5 py-2.5 font-semibold">Kategori</th>
                    <th scope="col" class="px-2.5 py-2.5 text-right font-semibold">Nilai Kontrak</th>
                    <th scope="col" class="px-2.5 py-2.5 text-right font-semibold">Sisa Tagihan</th>
                    <th scope="col" class="px-2.5 py-2.5 text-center font-semibold">Status SLA</th>
                    <th scope="col" class="py-2.5 pl-2.5 text-right font-semibold">Tindakan Cepat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($vendors as $vendor)
                    <tr wire:key="admin-vendor-{{ $vendor->id }}" class="hover:bg-slate-50">
                        <td class="py-3 pr-3">
                            <p class="font-semibold text-slate-900">{{ $vendor->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $vendor->contact_person }} · {{ $vendor->phone }}</p>
                        </td>
                        <td class="px-2.5 py-3 text-slate-600">
                            {{ $vendor->category->shortLabel() }}
                        </td>
                        <td class="px-2.5 py-3 text-right tabular-nums font-semibold text-slate-900">
                            {{ format_idr($vendor->contract_amount) }}
                        </td>
                        <td class="px-2.5 py-3 text-right tabular-nums font-semibold {{ $vendor->remaining_balance > 0 ? 'text-wine' : 'text-sage' }}">
                            {{ format_idr($vendor->remaining_balance) }}
                        </td>
                        <td class="px-2.5 py-3 text-center">
                            <x-ui.status-badge :tone="$vendor->booking_status->badgeTone()" :label="$vendor->booking_status->label()" />
                        </td>
                        <td class="py-3 pl-2.5 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <form method="POST" action="{{ route('admin.verifyVendor', $vendor) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="rounded border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-100 cursor-pointer"
                                    >
                                        Verifikasi Kontrak
                                    </button>
                                </form>
                                @if($vendor->remaining_balance > 0)
                                    <button
                                        type="button"
                                        wire:click="markPaid({{ $vendor->id }})"
                                        class="rounded bg-slate-900 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-slate-700 cursor-pointer"
                                    >
                                        Tandai Lunas
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada vendor pada status ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
