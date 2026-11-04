@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 border-b border-stone-line pb-5 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div>
        @if($eyebrow)
            <p class="mb-1 text-xs font-medium tracking-wide text-champagne">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-editorial text-3xl font-semibold text-espresso sm:text-4xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1.5 max-w-2xl text-sm text-taupe">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions) || ! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            {{ $actions ?? $slot }}
        </div>
    @endif
</div>
