<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\Vendor;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class VendorController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');
        $selectedStatus = $request->query('booking_status');

        $query = Vendor::query()->withCount(['budgetItems', 'tasks'])->orderBy('category')->orderBy('name');

        if (is_string($selectedCategory) && $selectedCategory !== '') {
            $query->where('category', $selectedCategory);
        }

        if (is_string($selectedStatus) && $selectedStatus !== '') {
            $query->where('booking_status', $selectedStatus);
        }

        $vendors = $query->get();
        $summary = $this->metricsService->getDashboardSummary();

        $totalContract = (int) $vendors->sum('contract_amount');
        $totalPaid = (int) $vendors->sum('paid_amount');
        $totalRemaining = max(0, $totalContract - $totalPaid);

        return view('vendors.index', [
            'summary' => $summary,
            'vendors' => $vendors,
            'categories' => WeddingCategory::cases(),
            'bookingStatuses' => VendorBookingStatus::cases(),
            'selectedCategory' => $selectedCategory,
            'selectedStatus' => $selectedStatus,
            'totalContract' => $totalContract,
            'totalPaid' => $totalPaid,
            'totalRemaining' => $totalRemaining,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', Rule::enum(WeddingCategory::class)],
            'contact_person' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'contract_amount' => ['required', 'integer', 'min:0'],
            'paid_amount' => ['nullable', 'integer', 'min:0'],
            'booking_status' => ['required', Rule::enum(VendorBookingStatus::class)],
            'payment_due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['paid_amount'] = (int) ($validated['paid_amount'] ?? 0);

        Vendor::query()->create($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Mitra vendor "' . $validated['name'] . '" berhasil ditambahkan ke direktori.');
    }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', Rule::enum(WeddingCategory::class)],
            'contact_person' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'contract_amount' => ['required', 'integer', 'min:0'],
            'paid_amount' => ['required', 'integer', 'min:0'],
            'booking_status' => ['required', Rule::enum(VendorBookingStatus::class)],
            'payment_due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Data vendor "' . $vendor->name . '" berhasil diperbarui.');
    }

    public function updatePayment(Request $request, Vendor $vendor): RedirectResponse
    {
        $validated = $request->validate([
            'paid_amount' => ['required', 'integer', 'min:0'],
            'booking_status' => ['required', Rule::enum(VendorBookingStatus::class)],
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Catatan pembayaran vendor "' . $vendor->name . '" berhasil diperbarui.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $name = $vendor->name;
        $vendor->delete();

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor "' . $name . '" telah dihapus dari direktori.');
    }
}
