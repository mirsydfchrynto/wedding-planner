<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Atelier Vow' : 'Atelier Vow — Wedding Planner SaaS' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- deslop-ignore-next-line 33 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso" x-data="{ mobileNavOpen: false }">

    {{-- Minimalist Startup Top Bar --}}
    <header class="sticky top-0 z-30 border-b border-stone-line bg-alabaster">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('dashboard.index') : route('saas.landing') }}" class="group flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md border border-champagne/40 bg-champagne-light text-xs font-bold text-champagne">
                        AV
                    </div>
                    <span class="text-base font-bold tracking-tight text-espresso">Atelier Vow</span>
                </a>
            </div>

            @php
                $navItems = [
                    ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                    ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Budget', 'icon' => 'wallet'],
                    ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Checklist', 'icon' => 'check-circle'],
                    ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Timeline', 'icon' => 'calendar'],
                    ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor', 'icon' => 'building'],
                ];
            @endphp

            @auth
                <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi Utama">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory hover:text-espresso' }}"
                            @if($active) aria-current="page" @endif
                        >
                            <x-ui.icon :name="$item['icon']" class="h-3.5 w-3.5" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            @else
                <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold text-charcoal" aria-label="Navigasi Publik">
                    <a href="{{ route('saas.landing') }}#fitur-platform" class="hover:text-champagne">5 Modul Utama</a>
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="hover:text-champagne">Kalkulator Anggaran</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="hover:text-champagne">Harga Paket</a>
                </nav>
            @endauth

            <div class="flex items-center gap-2.5">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.index') }}"
                            class="hidden lg:inline-flex items-center gap-1.5 rounded-md border border-wine/30 bg-wine-bg px-3 py-1.5 text-xs font-semibold text-wine hover:bg-wine hover:text-alabaster"
                        >
                            <x-ui.icon name="shield-check" class="h-3.5 w-3.5" />
                            <span>Konsol Admin Privat</span>
                        </a>
                    @endif

                    <div class="hidden xl:flex items-center gap-2 rounded-md border border-stone-line bg-pearl px-3 py-1.5 text-xs">
                        <x-ui.icon name="users" class="h-3.5 w-3.5 text-champagne" />
                        <span class="font-semibold text-espresso">{{ auth()->user()->name }}</span>
                        @if(isset($globalProfile))
                            <span class="h-3 w-px bg-stone-line"></span>
                            <span class="tabular-nums font-semibold text-champagne">H-{{ $globalProfile->days_remaining }}</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline-block">
                        @csrf
                        <button type="submit" class="editorial-btn-secondary !py-1.5 !px-3 !text-xs">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="editorial-btn-primary !py-2 !px-3.5 !text-xs">
                        Masuk dengan Google
                    </a>
                @endauth

                <button
                    type="button"
                    @click="mobileNavOpen = !mobileNavOpen"
                    class="inline-flex items-center justify-center rounded-md border border-stone-line bg-pearl p-2 text-charcoal lg:hidden"
                    aria-label="Buka menu navigasi"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileNavOpen" x-cloak class="border-t border-stone-line bg-pearl px-4 py-3 lg:hidden">
            @auth
                <div class="flex flex-col gap-1">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory' }}"
                        >
                            <x-ui.icon :name="$item['icon']" class="h-4 w-4" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="mt-1 rounded-md bg-wine-bg px-3 py-2 text-xs font-semibold text-wine">
                            Konsol Admin Privat
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-stone-line pt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-md border border-stone-line bg-alabaster px-3 py-2 text-center text-xs font-semibold text-charcoal">
                            Keluar ({{ auth()->user()->email }})
                        </button>
                    </form>
                </div>
            @else
                <div class="flex flex-col gap-2">
                    <a href="{{ route('saas.landing') }}#fitur-platform" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">5 Modul Utama</a>
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">Kalkulator Anggaran</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">Harga Paket</a>
                    <a href="{{ route('login') }}" class="editorial-btn-primary w-full text-center">Masuk dengan Google</a>
                </div>
            @endauth
        </div>
    </header>

    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4500)"
            x-show="show"
            x-transition
            class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print"
            role="status"
        >
            <div class="flex items-center justify-between gap-3 rounded-lg border border-sage/30 bg-sage-bg px-4 py-3 text-xs font-medium text-sage">
                <span>{{ session('success') }}</span>
                <button type="button" @click="show = false" class="font-semibold underline">Tutup</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print" role="alert">
            <div class="rounded-lg border border-wine/30 bg-wine-bg px-4 py-3 text-xs text-wine">
                <p class="font-semibold">Periksa kembali isian formulir berikut:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="mt-14 border-t border-stone-line bg-ivory/60 py-6 no-print">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-3 text-xs text-taupe sm:flex-row">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="font-bold text-espresso">Atelier Vow</span>
                    <span>·</span>
                    <a href="{{ route('saas.landing') }}" class="hover:text-espresso hover:underline">Beranda SaaS</a>
                    @auth
                        <span>·</span>
                        <a href="{{ route('dashboard.index') }}" class="hover:text-espresso hover:underline">Dashboard</a>
                        @if(auth()->user()->isAdmin())
                            <span>·</span>
                            <a href="{{ route('admin.index') }}" class="font-semibold text-wine hover:underline">Konsol Admin Privat</a>
                        @endif
                    @else
                        <span>·</span>
                        <a href="{{ route('login') }}" class="hover:text-espresso hover:underline">Masuk Akun Google</a>
                    @endauth
                </div>
                @if(isset($globalProfile))
                    <div class="tabular-nums">
                        {{ $globalProfile->venue_name }} · {{ $globalProfile->guest_target }} Tamu
                    </div>
                @endif
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
