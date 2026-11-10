<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingGuest;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GuestController extends Controller
{
    public function __construct(
        private readonly WeddingMetricsService $metricsService,
    ) {}

    public function index(Request $request): View
    {
        $selectedCategory = is_string($request->query('category')) ? $request->query('category') : null;
        $selectedStatus = is_string($request->query('status')) ? $request->query('status') : null;
        $search = is_string($request->query('search')) ? trim($request->query('search')) : '';

        $query = WeddingGuest::query();

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        if ($selectedStatus) {
            $query->where('rsvp_status', $selectedStatus);
        }

        if ($search !== '') {
            $keyword = '%'.$search.'%';
            $query->where(function ($q) use ($keyword): void {
                $q->where('name', 'like', $keyword)
                    ->orWhere('table_number', 'like', $keyword)
                    ->orWhere('whatsapp_number', 'like', $keyword)
                    ->orWhere('notes', 'like', $keyword);
            });
        }

        $guests = $query->orderBy('name')->get();
        $summary = $this->metricsService->getDashboardSummary();
        $profile = $summary['profile'];

        $totalGuestsListed = (int) $guests->sum('pax');
        $confirmedGuests = (int) $guests->where('rsvp_status', 'confirmed')->sum('pax');
        $pendingGuests = (int) $guests->where('rsvp_status', 'pending')->sum('pax');
        $declinedGuests = (int) $guests->where('rsvp_status', 'declined')->sum('pax');

        $categories = self::categories();

        return view('guests.index', [
            'summary' => $summary,
            'profile' => $profile,
            'guests' => $guests,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'selectedStatus' => $selectedStatus,
            'search' => $search,
            'totalGuestsListed' => $totalGuestsListed,
            'confirmedGuests' => $confirmedGuests,
            'pendingGuests' => $pendingGuests,
            'declinedGuests' => $declinedGuests,
        ]);
    }

    public function create(): View
    {
        return view('guests.form', [
            'guest' => null,
            'categories' => self::categories(),
        ]);
    }

    public function edit(WeddingGuest $guest): View
    {
        return view('guests.form', [
            'guest' => $guest,
            'categories' => self::categories(),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private static function categories(): array
    {
        return ['Tamu VIP Kehormatan', 'Keluarga CPW', 'Keluarga CPP', 'Sahabat Pengantin', 'Rekan Kerja & Partner', 'Teman Pengantin', 'Kolega Bisnis'];
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'category' => ['required', 'string', 'max:60'],
            'pax' => ['required', 'integer', 'min:1', 'max:20'],
            'session' => ['required', 'string', 'max:60'],
            'rsvp_status' => ['required', 'in:pending,confirmed,declined'],
            'table_number' => ['nullable', 'string', 'max:40'],
            'whatsapp_number' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        WeddingGuest::query()->create($validated);

        return redirect()->route('guests.index')
            ->with('success', 'Tamu undangan "'.$validated['name'].'" berhasil dicatat.');
    }

    public function update(Request $request, WeddingGuest $guest): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'category' => ['required', 'string', 'max:60'],
            'pax' => ['required', 'integer', 'min:1', 'max:20'],
            'session' => ['required', 'string', 'max:60'],
            'rsvp_status' => ['required', 'in:pending,confirmed,declined'],
            'table_number' => ['nullable', 'string', 'max:40'],
            'whatsapp_number' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $guest->update($validated);

        return redirect()->route('guests.index')
            ->with('success', 'Data tamu "'.$guest->name.'" berhasil diperbarui.');
    }

    public function destroy(WeddingGuest $guest): RedirectResponse
    {
        $name = $guest->name;
        $guest->delete();

        return redirect()->route('guests.index')
            ->with('success', 'Tamu "'.$name.'" telah dihapus dari daftar.');
    }
}
