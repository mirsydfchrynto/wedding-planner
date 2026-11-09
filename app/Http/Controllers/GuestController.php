<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingGuest;
use App\Services\WeddingMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            $keyword = '%' . $search . '%';
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

        $categories = ['Tamu VIP Kehormatan', 'Keluarga CPW', 'Keluarga CPP', 'Sahabat Pengantin', 'Rekan Kerja & Partner', 'Teman Pengantin', 'Kolega Bisnis'];

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


    public function exportCsv(Request $request): StreamedResponse
    {
        // Mengambil filter yang sedang digunakan.
        $selectedCategory = is_string($request->query('category'))
            ? $request->query('category')
            : null;

        $selectedStatus = is_string($request->query('status'))
            ? $request->query('status')
            : null;

        $search = is_string($request->query('search'))
            ? trim($request->query('search'))
            : '';

        // Mengambil data tamu sesuai filter.
        $query = WeddingGuest::query();

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        if ($selectedStatus) {
            $query->where('rsvp_status', $selectedStatus);
        }

        if ($search !== '') {
            $keyword = '%' . $search . '%';

            $query->where(function ($q) use ($keyword): void {
                $q->where('name', 'like', $keyword)
                    ->orWhere('table_number', 'like', $keyword)
                    ->orWhere('whatsapp_number', 'like', $keyword)
                    ->orWhere('notes', 'like', $keyword);
            });
        }

        // Membuat file CSV yang dapat diunduh.
        return response()->streamDownload(function () use ($query): void {

            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            // BOM agar karakter UTF-8 terbaca dengan baik di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            // Header CSV.
            fputcsv($handle, [
                'Nama Tamu',
                'Kategori',
                'Jumlah Undangan (Pax)',
                'Sesi Acara',
                'Nomor Meja',
                'Status RSVP',
                'Nomor WhatsApp',
                'Catatan',
            ], ',', '"', '');

            // Mengambil data dan memasukkannya ke CSV.
            foreach ($query->orderBy('name')->cursor() as $guest) {

                $status = match ($guest->rsvp_status) {
                    'confirmed' => 'Hadir',
                    'declined' => 'Berhalangan',
                    default => 'Menunggu Konfirmasi',
                };

                $row = [
                    $guest->name,
                    $guest->category,
                    $guest->pax,
                    $guest->session,
                    $guest->table_number ?? '-',
                    $status,
                    $guest->whatsapp_number ?? '-',
                    $guest->notes ?? '-',
                ];

                // Mencegah interpretasi formula berbahaya di spreadsheet.
                $row = array_map(static function ($value): string {
                    $value = (string) ($value ?? '');

                    if (preg_match('/^[\s]*[=+\-@]/u', $value) === 1) {
                        $value = "'" . $value;
                    }

                    return $value;
                }, $row);

                fputcsv($handle, $row, ',', '"', '');
            }

            fclose($handle);
        }, 'rekapitulasi-tamu-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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
            ->with('success', 'Tamu undangan "' . $validated['name'] . '" berhasil dicatat.');
    }


    public function bulkStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'names' => ['required', 'string', 'max:20000'],
            'category' => ['required', 'string', 'max:60'],
            'pax' => ['required', 'integer', 'min:1', 'max:20'],
            'session' => ['required', 'string', 'max:60'],
            'rsvp_status' => ['required', 'in:pending,confirmed,declined'],
        ]);

        // Memisahkan nama berdasarkan baris baru.
        $names = preg_split('/\r\n|\r|\n/', $validated['names']) ?: [];

        // Menghapus spasi dan mengabaikan baris kosong.
        $names = array_values(array_filter(
            array_map('trim', $names),
            fn($name) => $name !== ''
        ));

        // Memastikan minimal ada satu nama.
        if (count($names) === 0) {
            return back()
                ->withErrors(['names' => 'Masukkan minimal satu nama tamu.'])
                ->withInput();
        }

        // Membatasi maksimal 100 nama dalam satu proses.
        if (count($names) > 100) {
            return back()
                ->withErrors(['names' => 'Maksimal 100 nama dalam satu kali input.'])
                ->withInput();
        }

        // Memastikan setiap nama tidak lebih dari 140 karakter.
        foreach ($names as $name) {
            if (mb_strlen($name) > 140) {
                return back()
                    ->withErrors(['names' => 'Setiap nama maksimal 140 karakter.'])
                    ->withInput();
            }
        }

        // Menyimpan seluruh tamu dalam satu transaksi database.
        DB::transaction(function () use ($names, $validated): void {
            foreach ($names as $name) {
                WeddingGuest::query()->create([
                    'name' => $name,
                    'category' => $validated['category'],
                    'pax' => (int) $validated['pax'],
                    'session' => $validated['session'],
                    'rsvp_status' => $validated['rsvp_status'],
                ]);
            }
        });

        return redirect()
            ->route('guests.index')
            ->with('success', count($names) . ' tamu berhasil ditambahkan.');
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
            ->with('success', 'Data tamu "' . $guest->name . '" berhasil diperbarui.');
    }

    public function destroy(WeddingGuest $guest): RedirectResponse
    {
        $name = $guest->name;
        $guest->delete();

        return redirect()->route('guests.index')
            ->with('success', 'Tamu "' . $name . '" telah dihapus dari daftar.');
    }
}
