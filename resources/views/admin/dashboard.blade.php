<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Konsol Administrator — Tatanikah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#2B2520] antialiased min-h-screen">
    <header class="border-b border-[#E7E4DF] bg-white sticky top-0 z-30">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2.5">
                <span class="rounded bg-[#2B2520] px-2 py-0.5 text-xs font-bold text-white uppercase tracking-wider">Admin</span>
                <span class="text-base font-bold text-[#2B2520]">Tatanikah Platform Monitoring</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-[#6E665E] hidden sm:inline">Admin: {{ auth()->user()->email }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-[#E7E4DF] bg-white px-3 py-1.5 text-xs font-semibold text-[#9E352B] hover:bg-[#FDF2F0] transition cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-[#E7E4DF] bg-white p-5 shadow-xs">
                <span class="text-xs font-medium text-[#6E665E] block">Total Pengantin Terdaftar</span>
                <span class="text-2xl font-bold text-[#2B2520] tabular-nums block mt-1">{{ $totalProfiles }}</span>
                <span class="text-[11px] text-[#2E5E3E] mt-1 block">Akun aktif di platform</span>
            </div>

            <div class="rounded-2xl border border-[#E7E4DF] bg-white p-5 shadow-xs">
                <span class="text-xs font-medium text-[#6E665E] block">Total Anggaran Dikelola</span>
                <span class="text-2xl font-bold text-[#8C5835] tabular-nums block mt-1">{{ format_idr($totalBudgetManaged) }}</span>
                <span class="text-[11px] text-[#6E665E] mt-1 block">Akumulasi pagu pengantin</span>
            </div>

            <div class="rounded-2xl border border-[#E7E4DF] bg-white p-5 shadow-xs">
                <span class="text-xs font-medium text-[#6E665E] block">Mitra Vendor Terdata</span>
                <span class="text-2xl font-bold text-[#2B2520] tabular-nums block mt-1">{{ $totalVendors }}</span>
                <span class="text-[11px] text-[#6E665E] mt-1 block">Katering, venue, foto, dll</span>
            </div>

            <div class="rounded-2xl border border-[#E7E4DF] bg-white p-5 shadow-xs">
                <span class="text-xs font-medium text-[#6E665E] block">Total Kebutuhan Tercatat</span>
                <span class="text-2xl font-bold text-[#2E5E3E] tabular-nums block mt-1">{{ $totalTasks }}</span>
                <span class="text-[11px] text-[#6E665E] mt-1 block">Checklist persiapan aktif</span>
            </div>
        </div>

        {{-- Tabel Daftar Pasangan Pengantin --}}
        <section class="rounded-2xl border border-[#E7E4DF] bg-white p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-[#F0ECE4] pb-4">
                <div>
                    <h2 class="text-lg font-bold text-[#2B2520]">Daftar Pasangan Pengantin</h2>
                    <p class="text-xs text-[#6E665E] mt-0.5">Monitoring jadwal hari-H dan pagu anggaran per pasangan.</p>
                </div>
                <span class="text-xs font-semibold text-[#8C5835] tabular-nums">{{ $profiles->total() }} Pasangan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="border-b border-[#E7E4DF] text-[#6E665E] font-semibold bg-[#FAF8F5]">
                        <tr>
                            <th scope="col" class="py-2.5 px-3">Nama Pasangan</th>
                            <th scope="col" class="py-2.5 px-3">Tanggal Hari-H</th>
                            <th scope="col" class="py-2.5 px-3">Kota</th>
                            <th scope="col" class="py-2.5 px-3 text-right">Pagu Anggaran</th>
                            <th scope="col" class="py-2.5 px-3 text-right">Target Tamu</th>
                            <th scope="col" class="py-2.5 px-3 text-center">Mode Pelaksanaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F0ECE4]">
                        @forelse($profiles as $p)
                            <tr class="hover:bg-[#FAF8F5]">
                                <td class="py-3 px-3 font-semibold text-[#2B2520]">
                                    {{ $p->couple_names }}
                                    <span class="block text-[11px] font-normal text-[#6E665E]">{{ $p->user?->email }}</span>
                                </td>
                                <td class="py-3 px-3 text-[#2B2520] tabular-nums">
                                    {{ $p->wedding_date?->translatedFormat('d M Y') }}
                                    <span class="block text-[11px] text-[#8C5835] font-medium">
                                        {{ $p->days_remaining > 0 ? $p->days_remaining . ' hari lagi' : 'Hari Pernikahan' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-[#6E665E]">{{ $p->city }}</td>
                                <td class="py-3 px-3 text-right font-bold text-[#2B2520] tabular-nums">{{ format_idr($p->total_budget) }}</td>
                                <td class="py-3 px-3 text-right tabular-nums">{{ number_format($p->guest_target, 0, ',', '.') }} Tamu</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex rounded-md border border-[#E7E4DF] bg-white px-2 py-0.5 text-[11px] font-medium text-[#6E665E]">
                                        {{ $p->helper_mode === 'wo' ? 'Dengan WO' : ($p->helper_mode === 'family' ? 'Keluarga' : 'Mandiri') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-[#6E665E]">Belum ada pasangan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $profiles->links() }}
            </div>
        </section>
    </main>
</body>
</html>
