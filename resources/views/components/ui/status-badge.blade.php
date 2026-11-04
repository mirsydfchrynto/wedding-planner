@props([
    'tone' => 'neutral',
    'label' => '',
])

@php
    $classes = match ($tone) {
        'sage' => 'bg-sage-bg text-sage border-sage/20',
        'ochre' => 'bg-ochre-bg text-ochre border-ochre/20',
        'wine' => 'bg-wine-bg text-wine border-wine/20',
        'champagne' => 'bg-champagne-light text-champagne border-champagne/25',
        default => 'bg-ivory text-charcoal border-stone-line',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-75"></span>
    {{ $label ?: $slot }}
</span>
