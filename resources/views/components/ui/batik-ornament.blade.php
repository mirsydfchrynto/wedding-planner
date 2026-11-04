@props([
    'variant' => 'kawung',
])

@if($variant === 'gunungan')
    <svg {{ $attributes->merge(['class' => 'text-champagne/25 select-none pointer-events-none']) }} viewBox="0 0 120 160" fill="none" aria-hidden="true">
        <path d="M60 8C42 42 14 78 14 118C14 138 34 152 60 152C86 152 106 138 106 118C106 78 78 42 60 8Z" stroke="currentColor" stroke-width="1.5"/>
        <path d="M60 24C46 52 26 82 26 116C26 132 41 142 60 142C79 142 94 132 94 116C94 82 74 52 60 24Z" stroke="currentColor" stroke-width="1"/>
        <line x1="60" y1="24" x2="60" y2="148" stroke="currentColor" stroke-width="1"/>
        <path d="M60 80C68 88 68 104 60 112C52 104 52 88 60 80Z" stroke="currentColor" stroke-width="1.2"/>
        <path d="M44 96C52 88 68 88 76 96C68 104 52 104 44 96Z" stroke="currentColor" stroke-width="1.2"/>
        <path d="M40 122H80M34 132H86" stroke="currentColor" stroke-width="1.25"/>
    </svg>
@else
    <svg {{ $attributes->merge(['class' => 'w-full h-4 text-champagne/35 select-none pointer-events-none']) }} viewBox="0 0 400 16" fill="none" aria-hidden="true">
        <line x1="0" y1="8" x2="156" y2="8" stroke="currentColor" stroke-width="1"/>
        <path d="M172 8C176 3 184 3 188 8C184 13 176 13 172 8Z" stroke="currentColor" stroke-width="1.2"/>
        <path d="M192 8L200 2L208 8L200 14L192 8Z" stroke="currentColor" stroke-width="1.4"/>
        <path d="M197 8L200 5L203 8L200 11L197 8Z" fill="currentColor"/>
        <path d="M212 8C216 3 224 3 228 8C224 13 216 13 212 8Z" stroke="currentColor" stroke-width="1.2"/>
        <line x1="244" y1="8" x2="400" y2="8" stroke="currentColor" stroke-width="1"/>
    </svg>
@endif
