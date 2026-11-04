@props([
    'percent' => 0,
    'tone' => 'champagne',
    'label' => null,
    'meta' => null,
    'size' => 'md',
])

@php
    $clamped = max(0, min(100, (int) $percent));
    $barColor = match ($tone) {
        'sage' => 'bg-sage',
        'ochre' => 'bg-ochre',
        'wine' => 'bg-wine',
        default => 'bg-champagne',
    };
    $heightClass = $size === 'sm' ? 'h-1.5' : 'h-2.5';
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label || $meta)
        <div class="mb-1.5 flex items-baseline justify-between gap-2 text-xs">
            @if($label)
                <span class="font-medium text-charcoal">{{ $label }}</span>
            @endif
            @if($meta)
                <span class="tabular-nums text-taupe">{{ $meta }}</span>
            @else
                <span class="tabular-nums font-semibold text-espresso">{{ $clamped }}%</span>
            @endif
        </div>
    @endif
    <div class="w-full overflow-hidden rounded-sm bg-ivory {{ $heightClass }}">
        <div
            class="{{ $heightClass }} rounded-sm transition-[width] duration-200 {{ $barColor }}"
            style="width: {{ $clamped }}%"
            role="progressbar"
            aria-valuenow="{{ $clamped }}"
            aria-valuemin="0"
            aria-valuemax="100"
        ></div>
    </div>
</div>
