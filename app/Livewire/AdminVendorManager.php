<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\VendorBookingStatus;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class AdminVendorManager extends Component
{
    public string $statusFilter = '';

    public function verifyContract(int $vendorId): void
    {
        $vendor = Vendor::query()->findOrFail($vendorId);
        $nextStatus = $vendor->remaining_balance === 0
            ? VendorBookingStatus::Paid
            : VendorBookingStatus::Booked;

        $vendor->update(['booking_status' => $nextStatus]);
    }

    public function markPaid(int $vendorId): void
    {
        $vendor = Vendor::query()->findOrFail($vendorId);
        $vendor->update([
            'paid_amount' => $vendor->contract_amount,
            'booking_status' => VendorBookingStatus::Paid,
        ]);
    }

    public function render(): View
    {
        $query = Vendor::query()->orderBy('payment_due_date');
        if ($this->statusFilter !== '') {
            $query->where('booking_status', $this->statusFilter);
        }

        return view('livewire.admin-vendor-manager', [
            'vendors' => $query->get(),
            'statuses' => VendorBookingStatus::cases(),
        ]);
    }
}
