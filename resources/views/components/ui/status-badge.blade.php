@props([
    'tone' => 'neutral',
    'label' => '',
])

@php
    $classes = match ($tone) {
        'sage' => 'text-sage',
        'ochre' => 'text-ochre',
        'wine' => 'text-wine',
        'champagne' => 'text-champagne',
        default => 'text-taupe',
    };
@endphp

{{-- Status sebagai kata berwarna, bukan chip berkotak. --}}
<span {{ $attributes->merge(['class' => "text-xs font-semibold whitespace-nowrap {$classes}"]) }}>
    {{ $label ?: $slot }}
</span>
