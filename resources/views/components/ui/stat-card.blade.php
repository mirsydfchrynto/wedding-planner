@props([
    'label' => '',
    'value' => '',
    'subtitle' => null,
    'badge' => null,
    'badgeTone' => 'neutral',
    'icon' => null,
])

<article {{ $attributes->merge(['class' => 'editorial-card flex flex-col justify-between p-5 transition hover:border-black/15']) }} data-reveal>
    <div class="flex items-center justify-between gap-2.5">
        <div class="flex items-center gap-2 text-taupe">
            @if($icon)
                <x-ui.icon :name="$icon" class="h-3.5 w-3.5 text-champagne" />
            @endif
            <p class="text-xs font-semibold uppercase tracking-wider text-taupe">{{ $label }}</p>
        </div>
        @if($badge)
            <x-ui.status-badge :tone="$badgeTone" :label="$badge" />
        @endif
    </div>

    <div class="mt-3">
        <p class="tabular-nums text-2xl font-bold tracking-tight text-espresso">
            {{ $value }}
        </p>
        @if($subtitle)
            <p class="mt-1 text-xs text-taupe/90 tabular-nums">{{ $subtitle }}</p>
        @endif
    </div>

    @if(! $slot->isEmpty())
        <div class="mt-3.5 border-t border-black/[0.06] pt-3">
            {{ $slot }}
        </div>
    @endif
</article>
