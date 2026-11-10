@props([
    'label' => '',
    'value' => '',
    'subtitle' => null,
    'badge' => null,
    'badgeTone' => 'neutral',
    'icon' => null,
])

@php
    $badgeClass = match ($badgeTone) {
        'sage' => 'text-sage',
        'ochre' => 'text-ochre',
        'wine' => 'text-wine',
        'champagne' => 'text-champagne',
        default => 'text-taupe',
    };
@endphp

{{-- Angka tanpa kotak: label, nilai besar, keterangan kecil. --}}
<div {{ $attributes->merge(['class' => 'min-w-0']) }}>
    <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-taupe">
        @if($icon)
            <x-ui.icon :name="$icon" class="h-3.5 w-3.5 text-champagne" />
        @endif
        <span>{{ $label }}</span>
    </p>
    <p class="mt-2 text-xl font-bold text-espresso tabular-nums">{{ $value }}</p>
    @if($subtitle || $badge)
        <p class="mt-1 text-xs text-taupe">
            @if($subtitle){{ $subtitle }}@endif
            @if($subtitle && $badge) · @endif
            @if($badge)<span class="font-semibold {{ $badgeClass }}">{{ $badge }}</span>@endif
        </p>
    @endif
    @if(! $slot->isEmpty())
        <div class="mt-2">{{ $slot }}</div>
    @endif
</div>
