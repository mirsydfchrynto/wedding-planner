@props([
    'name' => 'modal',
    'title' => '',
    'subtitle' => null,
    'maxWidth' => 'max-w-xl',
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6 no-print"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title-{{ $name }}"
>
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-espresso/50 backdrop-blur-[2px]"
        @click="open = false"
    ></div>

    <div
        x-show="open"
        x-transition
        class="relative z-10 w-full {{ $maxWidth }} rounded-2xl border border-stone-line bg-pearl p-6 shadow-2xl sm:p-7"
    >
        <div class="flex items-start justify-between gap-4 border-b border-stone-line pb-4">
            <div>
                <h2 id="modal-title-{{ $name }}" class="font-editorial text-2xl font-semibold text-espresso">
                    {{ $title }}
                </h2>
                @if($subtitle)
                    <p class="mt-0.5 text-xs text-taupe">{{ $subtitle }}</p>
                @endif
            </div>
            <button
                type="button"
                @click="open = false"
                class="rounded-lg p-1.5 text-taupe hover:bg-ivory hover:text-espresso"
                aria-label="Tutup dialog"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-5">
            {{ $slot }}
        </div>
    </div>
</div>
