<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WeddingProfile;
use App\Services\BudgetAllocationService;
use App\Services\TimelineGeneratorService;
use App\Support\WeddingMasterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class OnboardingController extends Controller
{
    public const GUEST_PRESETS = ['100', '200', '300', '500', '800', '1000', 'custom'];

    public const BUDGET_PRESETS = ['10000000', '25000000', '35000000', '50000000', '70000000', '100000000', '150000000', '250000000', '350000000', '500000000', '750000000', '1000000000', 'custom'];

    public const EVENT_TYPES = ['akad_only', 'akad_resepsi', 'akad_resepsi_gabung', 'jawa_inti', 'jawa_lengkap', 'custom'];

    public const HELPER_MODES = ['self', 'family', 'wo'];

    public function __construct(
        private readonly BudgetAllocationService $budget,
        private readonly TimelineGeneratorService $timeline,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()->weddingProfile()->exists()) {
            return redirect()->route('dashboard.index');
        }

        return view('onboarding.index', [
            'guestPresets' => self::GUEST_PRESETS,
            'budgetPresets' => self::BUDGET_PRESETS,
            'eventTypes' => self::EVENT_TYPES,
            'helperModes' => self::HELPER_MODES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Kompatibilitas dengan input lama (akad_time, venue_type) dan input wizard baru
        $eventType = $request->input('event_type', 'akad_resepsi');
        $defaultVenueName = $request->input('venue_name') ?: $request->input('venue_type', 'Gedung / Tempat Acara');
        
        $akadTime = $request->input('akad_time', '08:00');
        $resepsiTime = $request->input('resepsi_time', '11:00');
        $weddingTime = $request->input('wedding_time') ?: (
            $eventType === 'akad_resepsi_gabung'
                ? 'Akad & Resepsi Sekaligus (' . $akadTime . ' – ' . $resepsiTime . ' WIB)'
                : ($eventType === 'akad_only'
                    ? 'Akad Nikah (' . $akadTime . ' WIB)'
                    : 'Akad ' . $akadTime . ' WIB · Resepsi ' . $resepsiTime . ' WIB')
        );

        $request->merge([
            'event_type' => $request->input('event_type', $eventType),
            'venue_name' => $defaultVenueName,
            'wedding_time' => $weddingTime,
            'helper_mode' => $request->input('helper_mode', 'self'),
        ]);

        $validated = $request->validate([
            'event_type' => ['required', Rule::in(self::EVENT_TYPES)],
            'wedding_date' => ['required', 'date', 'after:today'],
            'city' => ['required', 'string', 'max:80'],
            'venue_name' => ['required', 'string', 'max:160'],
            'venue_type' => ['nullable', 'string', 'max:80'],
            'wedding_time' => ['required', 'string', 'max:80'],
            'adat_tradition' => ['nullable', Rule::in(WeddingMasterData::WEDDING_TRADITIONS)],
            'total_budget' => ['required', Rule::in(self::BUDGET_PRESETS)],
            'budget_custom' => ['nullable', 'integer', 'min:1000000'],
            'guest_target' => ['required', Rule::in(self::GUEST_PRESETS)],
            'guest_custom' => ['nullable', 'integer', 'min:10', 'max:5000'],
            'bride_name' => ['required', 'string', 'max:120'],
            'groom_name' => ['required', 'string', 'max:120'],
            'helper_mode' => ['required', Rule::in(self::HELPER_MODES)],
            'theme_concept' => ['nullable', 'string', 'max:200'],
        ]);

        $guestTarget = $this->resolveAmount((string) $validated['guest_target'], $validated['guest_custom'] ?? null, 'guest_custom');
        $totalBudget = $this->resolveAmount((string) $validated['total_budget'], $validated['budget_custom'] ?? null, 'budget_custom');

        $profile = DB::transaction(function () use ($request, $validated, $guestTarget, $totalBudget): WeddingProfile {
            $eventType = $validated['event_type'];
            $helperMode = $validated['helper_mode'];
            $adatTradition = $validated['adat_tradition'] ?? (str_starts_with($eventType, 'jawa') ? 'jawa' : 'none');

            $profile = $request->user()->weddingProfile()->create([
                'bride_name' => $validated['bride_name'],
                'groom_name' => $validated['groom_name'],
                'wedding_date' => $validated['wedding_date'],
                'venue_name' => $validated['venue_name'],
                'venue_type' => $validated['venue_type'] ?? 'Gedung / Ballroom',
                'city' => $validated['city'],
                'total_budget' => $totalBudget,
                'guest_target' => $guestTarget,
                'wedding_time' => $validated['wedding_time'],
                'theme_concept' => ($validated['theme_concept'] ?? null) ?: match ($eventType) {
                    'akad_only' => 'Akad Nikah Khidmat',
                    'jawa_inti' => 'Pernikahan Tradisi Adat Jawa Inti',
                    'jawa_lengkap' => 'Pernikahan Adat Jawa Lengkap & Resepsi',
                    default => 'Akad & Resepsi Modern Nusantara',
                },
                'event_type' => $eventType,
                'helper_mode' => $helperMode,
            ]);

            WeddingProfile::useTenancy($profile->id);

            try {
                $this->budget->allocate($profile, $eventType);
                $this->timeline->generate($profile, $adatTradition, $eventType, $helperMode);
            } finally {
                WeddingProfile::useTenancy(null);
            }

            return $profile;
        });

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Buku kerja pernikahan berhasil dibuka: '.$profile->budgetItems()->count().' pos anggaran dan '.$profile->tasks()->count().' tugas checklist siap dijalankan.');
    }

    private function resolveAmount(string $choice, ?int $custom, string $field): int
    {
        if ($choice !== 'custom') {
            return (int) $choice;
        }

        if ($custom === null || $custom <= 0) {
            throw ValidationException::withMessages([$field => 'Isi nilai angka yang valid.']);
        }

        return $custom;
    }
}
