@props([
    'label' => '',
    'value' => '',
    'subtitle' => null,
    'badge' => null,
    'badgeTone' => 'neutral',
    'icon' => null,
])

<article {{ $attributes->merge(['class' => 'editorial-card flex flex-col justify-between rounded-lg p-5']) }} data-reveal>
    <div class="flex items-center justify-between gap-2.5">
        <div class="flex items-center gap-2 text-taupe">
            @if($icon)
                <x-ui.icon :name="$icon" class="h-4 w-4 text-champagne" />
            @endif
            <p class="text-sm font-medium text-taupe">{{ $label }}</p>
        </div>
        @if($badge)
            <x-ui.status-badge :tone="$badgeTone" :label="$badge" />
        @endif
    </div>

    <div class="mt-2.5">
        <p class="tabular-nums text-2xl font-bold tracking-tight text-espresso">
            {{ $value }}
        </p>
        @if($subtitle)
            <p class="mt-1 text-sm text-taupe tabular-nums">{{ $subtitle }}</p>
        @endif
    </div>

    @if(! $slot->isEmpty())
        <div class="mt-3.5 border-t border-stone-line/80 pt-3">
            {{ $slot }}
        </div>
    @endif
</article>
