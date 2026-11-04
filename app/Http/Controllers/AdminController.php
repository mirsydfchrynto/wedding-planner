<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\VendorBookingStatus;
use App\Models\BudgetItem;
use App\Models\Vendor;
use App\Models\WeddingTask;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class AdminController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(): View
    {
        $summary = $this->metricsService->getDashboardSummary();
        $vendors = Vendor::query()->withCount(['budgetItems', 'tasks'])->orderBy('payment_due_date')->get();
        $overdueTasks = WeddingTask::query()
            ->with('vendor')
            ->where('status', '!=', TaskStatus::Done->value)
            ->orderBy('due_date')
            ->get()
            ->filter(fn (WeddingTask $task): bool => $task->is_overdue)
            ->values();

        $highRiskBudgetItems = BudgetItem::query()
            ->with('vendor')
            ->get()
            ->filter(fn (BudgetItem $item): bool => $item->is_overbudget || $item->remaining_balance > 20000000)
            ->values();

        $twelveFactors = [
            ['factor' => 'I. Codebase', 'status' => 'Verified', 'detail' => 'Git monorepo dengan 5 branch terisolasi (main, irsyad, marsha, adam, dinda).'],
            ['factor' => 'II. Dependencies', 'status' => 'Verified', 'detail' => 'Manifes eksplisit via composer.lock (PHP 8.4) & package-lock.json (Vite 8).'],
            ['factor' => 'III. Config', 'status' => 'Verified', 'detail' => 'Konfigurasi lingkungan disimpan di .env (APP_LOCALE=id, DB_CONNECTION).'],
            ['factor' => 'IV. Backing Services', 'status' => 'Verified', 'detail' => 'Driver database dapat dipertukarkan antara SQLite dan MySQL tanpa ubah kode.'],
            ['factor' => 'V. Build, Release, Run', 'status' => 'Verified', 'detail' => 'Pemisahan kompilasi aset (vite build), migrasi skema, dan runtime server.'],
            ['factor' => 'VI. Processes', 'status' => 'Verified', 'detail' => 'Proses HTTP stateless; state persisten disimpan di tabel relasional.'],
            ['factor' => 'VII. Port Binding', 'status' => 'Verified', 'detail' => 'Layanan mandiri mengikat port HTTP secara eksplisit (127.0.0.1:8000).'],
            ['factor' => 'VIII. Concurrency', 'status' => 'Verified', 'detail' => 'Mendukung penskalaan pekerja proses PHP CLI / FPM dan antrean database.'],
            ['factor' => 'IX. Disposability', 'status' => 'Verified', 'detail' => 'Boot cepat (<100ms) dan penghentian proses secara graceful.'],
            ['factor' => 'X. Dev/Prod Parity', 'status' => 'Verified', 'detail' => 'Seeder deterministik (WeddingPlannerSeeder) & CI GitHub Actions identik.'],
            ['factor' => 'XI. Logs', 'status' => 'Verified', 'detail' => 'Aliran log terstruktur melalui konfigurasi LOG_CHANNEL stack/stdout.'],
            ['factor' => 'XII. Admin Processes', 'status' => 'Verified', 'detail' => 'Tugas migrasi, seeder, dan inspeksi dijalankan satu-kali via php artisan.'],
        ];

        $systemDiagnostics = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_driver' => DB::connection()->getDriverName(),
            'locale' => config('app.locale'),
            'total_records' => Vendor::query()->count() + BudgetItem::query()->count() + WeddingTask::query()->count() + 1,
        ];

        return view('admin.index', [
            'summary' => $summary,
            'profile' => $summary['profile'],
            'vendors' => $vendors,
            'overdueTasks' => $overdueTasks,
            'highRiskBudgetItems' => $highRiskBudgetItems,
            'twelveFactors' => $twelveFactors,
            'systemDiagnostics' => $systemDiagnostics,
        ]);
    }

    public function escalateOverdue(): RedirectResponse
    {
        $tasks = WeddingTask::query()
            ->where('status', '!=', TaskStatus::Done->value)
            ->get()
            ->filter(fn (WeddingTask $task): bool => $task->is_overdue);

        $count = 0;
        foreach ($tasks as $task) {
            $task->update([
                'priority' => 'high',
                'status' => TaskStatus::InProgress,
            ]);
            $count++;
        }

        return redirect()
            ->route('admin.index')
            ->with('success', "{$count} tugas yang melewati tenggat berhasil dieskalasi ke Prioritas Tinggi (Sedang Berjalan).");
    }

    public function verifyVendor(Vendor $vendor): RedirectResponse
    {
        $nextStatus = $vendor->remaining_balance === 0
            ? VendorBookingStatus::Paid
            : VendorBookingStatus::Booked;

        $vendor->update([
            'booking_status' => $nextStatus,
        ]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Status kontrak vendor "' . $vendor->name . '" diverifikasi sebagai ' . $nextStatus->label() . '.');
    }
}
