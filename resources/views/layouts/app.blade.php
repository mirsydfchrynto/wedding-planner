<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Atelier Vow' : 'Atelier Vow — Wedding Planner' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso" x-data="{ mobileNavOpen: false }">

    {{-- Top Editorial Ribbon --}}
    <header class="sticky top-0 z-30 border-b border-stone-line bg-alabaster">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard.index') }}" class="group flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg border border-champagne/40 bg-champagne-light font-editorial text-lg font-bold text-champagne">
                        AV
                    </div>
                    <div>
                        <span class="font-editorial text-xl font-semibold tracking-tight text-espresso">Atelier Vow</span>
                        <span class="hidden text-xs text-taupe sm:inline-block sm:ml-2">Buku Induk Persiapan Pernikahan</span>
                    </div>
                </a>
            </div>

            {{-- Primary Desktop Navigation across 5 Modules --}}
            <nav class="hidden md:flex items-center gap-1" aria-label="Navigasi Utama">
                @php
                    $navItems = [
                        ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Dashboard'],
                        ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Budget Planner'],
                        ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Checklist'],
                        ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Timeline'],
                        ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['pattern']); @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        class="rounded-lg px-3.5 py-2 text-xs font-semibold transition {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory hover:text-espresso' }}"
                        @if($active) aria-current="page" @endif
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Live Couple & Countdown Pill --}}
            <div class="flex items-center gap-3">
                @if(isset($globalProfile))
                    <div class="hidden lg:flex items-center gap-3 rounded-md border border-stone-line bg-pearl px-3.5 py-1.5 text-xs">
                        <span class="font-editorial text-sm font-semibold text-espresso">{{ $globalProfile->couple_names }}</span>
                        <span class="h-3 w-px bg-stone-line"></span>
                        <span class="tabular-nums font-medium text-champagne">H-{{ $globalProfile->days_remaining }} Hari</span>
                    </div>
                @endif

                <button
                    type="button"
                    @click="mobileNavOpen = !mobileNavOpen"
                    class="inline-flex items-center justify-center rounded-lg border border-stone-line bg-pearl p-2 text-charcoal md:hidden"
                    aria-label="Buka menu navigasi"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Drawer --}}
        <div x-show="mobileNavOpen" x-cloak class="border-t border-stone-line bg-pearl px-4 py-3 md:hidden">
            <div class="flex flex-col gap-1">
                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['pattern']); @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    {{-- Flash Notification Toast --}}
    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4500)"
            x-show="show"
            x-transition
            class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print"
            role="status"
        >
            <div class="flex items-center justify-between gap-3 rounded-lg border border-sage/30 bg-sage-bg px-4 py-3 text-sm text-sage">
                <span class="font-medium">{{ session('success') }}</span>
                <button type="button" @click="show = false" class="text-xs font-semibold underline">Tutup</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print" role="alert">
            <div class="rounded-lg border border-wine/30 bg-wine-bg px-4 py-3 text-sm text-wine">
                <p class="font-semibold">Mohon periksa kembali data yang dimasukkan:</p>
                <ul class="mt-1 list-disc pl-5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Main Content Canvas --}}
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Editorial Footer --}}
    <footer class="mt-16 border-t border-stone-line bg-ivory/60 py-6 no-print">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 text-xs text-taupe sm:flex-row sm:px-6 lg:px-8">
            <div class="flex items-center gap-2">
                <span class="font-editorial text-sm font-semibold text-espresso">Atelier Vow</span>
                <span>— Sistem Perencanaan Pernikahan Terpadu</span>
            </div>
            @if(isset($globalProfile))
                <div class="tabular-nums">
                    {{ $globalProfile->venue_name }}, {{ $globalProfile->city }} · Target {{ $globalProfile->guest_target }} Tamu
                </div>
            @endif
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
