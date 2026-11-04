@props([
    'label' => '',
    'value' => '',
    'subtitle' => null,
    'badge' => null,
    'badgeTone' => 'neutral',
])

<article {{ $attributes->merge(['class' => 'editorial-card flex flex-col justify-between rounded-xl p-5 sm:p-6']) }}>
    <div class="flex items-start justify-between gap-3">
        <p class="text-xs font-medium text-taupe">{{ $label }}</p>
        @if($badge)
            <x-ui.status-badge :tone="$badgeTone" :label="$badge" />
        @endif
    </div>

    <div class="mt-3">
        <p class="font-editorial tabular-nums text-3xl font-semibold text-espresso sm:text-[2.15rem] leading-tight">
            {{ $value }}
        </p>
        @if($subtitle)
            <p class="mt-1.5 text-xs text-taupe tabular-nums">{{ $subtitle }}</p>
        @endif
    </div>

    @if(! $slot->isEmpty())
        <div class="mt-4 border-t border-stone-line/80 pt-3">
            {{ $slot }}
        </div>
    @endif
</article>
