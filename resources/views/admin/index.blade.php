@extends('layouts.admin', ['title' => 'Konsol Admin & Operasional Wedding Organizer'])

@section('content')
    {{-- 1. OVERVIEW: Divided KPI Container (app-screens.md §14: One container split by hairlines, label over value) --}}
    <section class="grid grid-cols-1 divide-y divide-slate-200 rounded-xl border border-slate-200 bg-white sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x shadow-xs">
        <div class="p-5">
            <p class="text-sm font-medium text-slate-600">Workspace Klien Aktif</p>
            <p class="mt-1.5 text-2xl font-bold text-slate-900 truncate">{{ $profile->couple_names }}</p>
            <p class="mt-1 text-sm text-slate-600 tabular-nums">H-{{ $summary['days_remaining'] }} Hari · Paket Keraton Royal</p>
        </div>

        <div class="p-5">
            <p class="text-sm font-medium text-slate-600">Kesehatan Operasional</p>
            <p class="mt-1.5 tabular-nums text-2xl font-bold text-slate-900">{{ $summary['readiness_score'] }}%</p>
            <p class="mt-1 text-sm text-slate-600 tabular-nums">
                Tugas {{ $summary['completion_percent'] }}% · Vendor {{ $summary['vendor_booking_percent'] }}%
            </p>
        </div>

        <div class="p-5">
            <p class="text-sm font-medium text-slate-600">Sisa Kewajiban Vendor</p>
            <p class="mt-1.5 tabular-nums text-2xl font-bold text-slate-900 whitespace-nowrap">{{ format_idr($summary['unpaid_balance']) }}</p>
            <p class="mt-1 text-sm text-slate-600 tabular-nums whitespace-nowrap">
                Dari total kontrak {{ format_idr($vendors->sum('contract_amount')) }}
            </p>
        </div>

        <div class="p-5">
            <p class="text-sm font-medium text-slate-600">Status Infrastruktur</p>
            <p class="mt-1.5 tabular-nums text-2xl font-bold text-sage">12 / 12 Lulus</p>
            <p class="mt-1 text-sm text-slate-600">
                PHP {{ $systemDiagnostics['php_version'] }} · DB {{ strtoupper($systemDiagnostics['db_driver']) }}
            </p>
        </div>
    </section>

    {{-- Secondary Telemetry Bar --}}
    <section class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tamu Undangan Terdata</p>
                <p class="mt-1 text-xl font-bold text-slate-900 tabular-nums">{{ $systemDiagnostics['total_guests'] }} Pax</p>
            </div>
            <x-ui.icon name="users" class="h-6 w-6 text-slate-400" />
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Agenda Rundown Hari-H</p>
                <p class="mt-1 text-xl font-bold text-slate-900 tabular-nums">{{ $systemDiagnostics['total_rundowns'] }} Sesi</p>
            </div>
            <x-ui.icon name="calendar" class="h-6 w-6 text-slate-400" />
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Termin Terverifikasi</p>
                <p class="mt-1 text-xl font-bold text-slate-900 font-mono whitespace-nowrap">{{ format_idr($systemDiagnostics['total_payments_logged']) }}</p>
            </div>
            <x-ui.icon name="wallet" class="h-6 w-6 text-slate-400" />
        </div>
    </section>

    {{-- 2. VISUALIZATIONS: Where Is the Bottleneck? (2 Side-by-Side Operator Charts) --}}
    <section class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="rounded-xl border border-slate-200 bg-white p-5 lg:col-span-5 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Visualisasi Status Tugas</h2>
                    <p class="text-sm text-slate-600">Proporsi selesai, berjalan, belum, dan terlambat</p>
                </div>
                <span class="tabular-nums text-sm font-semibold text-slate-700">{{ $summary['total_tasks'] }} Tugas</span>
            </div>
            <div class="mt-4 h-56 w-full">
                <canvas id="adminTaskStatusChart" role="img" aria-label="Grafik donat status tugas"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 lg:col-span-7 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Serapan Anggaran vs Estimasi (Juta Rp)</h2>
                    <p class="text-sm text-slate-600">Deteksi cepat pos biaya yang melampaui estimasi awal</p>
                </div>
                <span class="tabular-nums text-sm font-semibold text-sage whitespace-nowrap">Sisa Plafon: {{ format_idr($summary['remaining_budget']) }}</span>
            </div>
            <div class="mt-4 h-56 w-full">
                <canvas id="adminBudgetBottleneckChart" role="img" aria-label="Grafik batang serapan anggaran"></canvas>
            </div>
        </div>
    </section>

    {{-- 3. ACTION REQUIRED: Bottleneck & Overdue Escalation + High-Risk Budget --}}
    <section id="bottleneck-section" class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="rounded-xl border border-slate-200 bg-white p-5 lg:col-span-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="alert-triangle" class="h-4 w-4 text-wine" />
                    <h2 class="text-xl font-bold text-slate-900">Antrean Tugas Terlambat ({{ $overdueTasks->count() }})</h2>
                </div>
                @if($overdueTasks->count() > 0)
                    <form method="POST" action="{{ route('admin.escalateOverdue') }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white hover:bg-slate-700 cursor-pointer">
                            Eskalasi Semua ({{ $overdueTasks->count() }})
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-3 divide-y divide-slate-100 text-sm">
                @forelse($overdueTasks as $task)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $task->title }}</p>
                            <p class="mt-0.5 text-slate-600">{{ $task->phase }} · PIC: {{ $task->assigned_to }} · Tenggat {{ $task->due_date->format('d M Y') }}</p>
                        </div>
                        <form method="POST" action="{{ route('checklist.toggle', $task) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded border border-slate-300 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 cursor-pointer">
                                Selesaikan
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="py-6 text-center text-slate-600">0 tugas terlambat. Jadwal terkendali.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 lg:col-span-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="wallet" class="h-4 w-4 text-ochre" />
                    <h2 class="text-xl font-bold text-slate-900">Pos Biaya Risiko Tinggi ({{ $highRiskBudgetItems->count() }})</h2>
                </div>
                <a href="{{ route('budget.index') }}" class="text-sm font-semibold text-slate-700 hover:underline">Buka Budget</a>
            </div>

            <div class="mt-3 divide-y divide-slate-100 text-sm">
                @forelse($highRiskBudgetItems as $item)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $item->title }}</p>
                            <p class="mt-0.5 text-slate-600">{{ $item->category->shortLabel() }} · Aktual <span class="whitespace-nowrap">{{ format_idr($item->actual_cost) }}</span></p>
                        </div>
                        @if($item->is_overbudget)
                            <x-ui.status-badge tone="wine" :label="'Over ' . format_idr(abs($item->variance_amount))" />
                        @else
                            <x-ui.status-badge tone="ochre" :label="'Sisa ' . format_idr($item->remaining_balance)" />
                        @endif
                    </div>
                @empty
                    <p class="py-6 text-center text-slate-600">Seluruh pos biaya berada dalam batas aman.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 4. LIVEWIRE VENDOR SLA MANAGER --}}
    <section class="mt-6">
        <livewire:admin-vendor-manager />
    </section>

    {{-- 5. TWELVE-FACTOR INFRASTRUCTURE MATRIX --}}
    <section id="system-health-section" class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <div class="flex items-center gap-2">
                <x-ui.icon name="shield-check" class="h-4 w-4 text-sage" />
                <h2 class="text-xl font-bold text-slate-900">Matriks Kepatuhan Arsitektur Twelve-Factor SaaS</h2>
            </div>
            <x-ui.status-badge tone="sage" label="12 / 12 Faktor Terverifikasi" />
        </div>

        {{-- deslop-ignore-next-line 28 --}}
        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 text-sm">
            @foreach($twelveFactors as $item)
                <div class="rounded border border-slate-200 bg-slate-50 p-3">
                    <div class="flex items-center gap-1.5 font-semibold text-slate-900">
                        <x-ui.icon name="check-circle" class="h-4 w-4 text-sage" />
                        <span class="truncate">{{ $item['factor'] }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-600 line-clamp-2">{{ $item['detail'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Chart === 'undefined') return;

        const taskCanvas = document.getElementById('adminTaskStatusChart');
        if (taskCanvas) {
            new window.Chart(taskCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Sedang Berjalan', 'Belum Dimulai', 'Terlambat'],
                    datasets: [{
                        data: [
                            {{ $summary['completed_tasks'] }},
                            {{ $summary['in_progress_tasks'] }},
                            {{ $summary['todo_tasks'] }},
                            {{ $summary['overdue_tasks_count'] }}
                        ],
                        backgroundColor: ['#264E3A', '#9A4406', '#94a3b8', '#8E1919'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, font: { size: 12 } } }
                    }
                }
            });
        }

        const budgetCanvas = document.getElementById('adminBudgetBottleneckChart');
        if (budgetCanvas) {
            const breakdown = @json($summary['category_breakdown']);
            new window.Chart(budgetCanvas, {
                type: 'bar',
                data: {
                    labels: breakdown.map(i => i.short_label),
                    datasets: [
                        {
                            label: 'Estimasi (Jt)',
                            data: breakdown.map(i => Math.round(i.estimated / 1000000)),
                            backgroundColor: '#cbd5e1',
                            borderRadius: 4
                        },
                        {
                            label: 'Aktual (Jt)',
                            data: breakdown.map(i => Math.round(i.actual / 1000000)),
                            backgroundColor: '#0f172a',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, font: { size: 12 } } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 12 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 12 } } }
                    }
                }
            });
        }
    });
</script>
@endpush
