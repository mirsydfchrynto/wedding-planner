<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Atelier Vow' : 'Atelier Vow — Platform Perencanaan Pernikahan Nusantara' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- deslop-ignore-next-line 33 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
@php
    $isLandingPage = request()->routeIs('saas.*') || request()->is('/');
    $isPublicPage = $isLandingPage || request()->routeIs('login');
    $hasProfile = isset($globalProfile) && $globalProfile->exists;
    $workspaceItems = [
        ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Budget Planner', 'icon' => 'wallet'],
        ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Checklist', 'icon' => 'check-circle'],
        ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Timeline', 'icon' => 'calendar'],
        ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor', 'icon' => 'building'],
        ['route' => 'guests.index', 'pattern' => 'guests.*', 'label' => 'Buku Tamu', 'icon' => 'users'],
    ];
@endphp
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso" x-data="{ mobileNavOpen: false }">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded focus:bg-espresso focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-alabaster">
        Lewati ke konten utama
    </a>

    {{-- Top Navigation Bar: Tone-separated on Landing Page (no hairline border), bordered on internal Workspace --}}
    <header class="sticky top-0 z-30 {{ $isLandingPage ? 'bg-alabaster' : 'border-b border-stone-line bg-pearl' }}">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 lg:px-10">
            <div class="flex items-center gap-3">
                <a href="{{ route('saas.landing') }}" class="group flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded bg-espresso text-sm font-bold text-alabaster">
                        AV
                    </div>
                    <span class="text-base font-bold tracking-tight text-espresso">Atelier Vow</span>
                </a>
            </div>

            @if($isPublicPage)
                <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-charcoal" aria-label="Navigasi Produk SaaS">
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="hover:text-champagne">Simulasi Anggaran</a>
                    <a href="{{ route('saas.landing') }}#alur-bisnis" class="hover:text-champagne">Alur Kerja</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="hover:text-champagne">Harga Paket</a>
                    <a href="{{ route('saas.landing') }}#faq-section" class="hover:text-champagne">FAQ</a>
                </nav>
            @else
                @if($hasProfile)
                <nav class="hidden lg:flex items-center gap-1 rounded-xl border border-black/[0.06] bg-white p-1 shadow-[0_1px_2px_rgba(0,0,0,0.02)]" aria-label="Navigasi Workspace Pengantin">
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-xs font-semibold transition {{ $active ? 'bg-espresso text-alabaster shadow-sm' : 'text-taupe hover:bg-black/[0.03] hover:text-espresso' }}"
                            @if($active) aria-current="page" @endif
                        >
                            <x-ui.icon :name="$item['icon']" class="h-3.5 w-3.5" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                @endif
            @endif

            <div class="flex items-center gap-3">
                @auth
                    @if($isPublicPage)
                        <a href="{{ route('dashboard.index') }}" class="editorial-btn-primary !py-2 !px-4 !text-sm">
                            <span>Buka Workspace</span>
                        </a>
                    @else
                        @if(auth()->user()->isAdmin())
                            <a
                                href="{{ route('admin.index') }}"
                                class="hidden lg:inline-flex items-center gap-1.5 rounded-md border border-wine/30 bg-wine-bg px-3 py-1.5 text-xs font-semibold text-wine hover:bg-wine hover:text-alabaster"
                            >
                                <x-ui.icon name="shield-check" class="h-3.5 w-3.5" />
                                <span>Konsol Admin</span>
                            </a>
                        @endif

                        <div class="hidden xl:flex items-center gap-2 rounded-md border border-stone-line bg-alabaster px-3 py-1.5 text-xs">
                            <x-ui.google-icon class="h-3.5 w-3.5" />
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
                    @endif
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 rounded bg-espresso px-4 py-2 text-sm font-semibold text-alabaster hover:bg-champagne"
                    >
                        <span class="flex h-5 w-5 items-center justify-center rounded-sm bg-pearl p-0.5">
                            <x-ui.google-icon class="h-3.5 w-3.5" />
                        </span>
                        <span>Masuk dengan Google</span>
                    </a>
                @endauth

                <button
                    type="button"
                    @click="mobileNavOpen = !mobileNavOpen"
                    class="inline-flex items-center justify-center rounded bg-ivory p-2.5 text-charcoal lg:hidden"
                    aria-label="Buka menu navigasi"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileNavOpen" x-cloak class="bg-ivory px-6 py-4 lg:hidden">
            @if($isPublicPage)
                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="rounded px-3 py-2 text-base font-medium text-charcoal">Simulasi Anggaran</a>
                    <a href="{{ route('saas.landing') }}#alur-bisnis" class="rounded px-3 py-2 text-base font-medium text-charcoal">Alur Kerja</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="rounded px-3 py-2 text-base font-medium text-charcoal">Harga Paket</a>
                    <a href="{{ route('saas.landing') }}#faq-section" class="rounded px-3 py-2 text-base font-medium text-charcoal">FAQ</a>
                    <a href="{{ route('login') }}" class="mt-1 inline-flex items-center justify-center gap-2 rounded bg-espresso px-4 py-3 text-sm font-semibold text-alabaster">
                        <x-ui.google-icon class="h-4 w-4" />
                        <span>Masuk dengan Google</span>
                    </a>
                </div>
            @else
                <div class="flex flex-col gap-1">
                    @if($hasProfile)
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-pearl' }}"
                        >
                            <x-ui.icon :name="$item['icon']" class="h-4 w-4" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-2 pt-2">
                        @csrf
                        <button type="submit" class="w-full rounded bg-pearl px-3 py-2.5 text-center text-sm font-semibold text-charcoal">
                            Keluar
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </header>

    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4500)"
            x-show="show"
            x-transition
            class="mx-auto mt-4 max-w-7xl px-6 lg:px-10 no-print"
            role="status"
        >
            <div class="flex items-center justify-between gap-3 rounded-lg bg-sage-bg px-4 py-3 text-sm font-medium text-sage">
                <span>{{ session('success') }}</span>
                <button type="button" @click="show = false" class="font-semibold underline">Tutup</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-6 lg:px-10 no-print" role="alert">
            <div class="rounded-lg bg-wine-bg px-4 py-3 text-sm text-wine">
                <p class="font-semibold">Periksa kembali isian formulir berikut:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($isLandingPage)
        <main id="main-content" class="w-full">
            @yield('content')
        </main>
    @else
        <main id="main-content" class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    @endif

    <footer class="{{ $isLandingPage ? 'bg-espresso text-alabaster py-14' : 'mt-16 border-t border-stone-line bg-pearl py-10 text-taupe' }} no-print">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                <div>
                    <p class="text-xl font-bold {{ $isLandingPage ? 'text-alabaster' : 'text-espresso' }}">Atelier Vow Nusantara</p>
                    <p class="mt-1 text-base {{ $isLandingPage ? 'text-stone-strong' : 'text-taupe' }}">
                        Sistem Perencanaan Anggaran, Vendor, dan Adat Pernikahan Terpadu.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-6 text-sm font-semibold">
                    <a href="{{ route('saas.landing') }}" class="hover:underline">Beranda SaaS</a>
                    <a href="{{ route('login') }}" class="hover:underline">Masuk Akun Google</a>
                    <a href="{{ route('dashboard.index') }}" class="hover:underline">Workspace Pengantin</a>
                </div>
            </div>
            @if($isLandingPage)
                <p class="mt-8 text-sm text-stone-strong">
                    Fotografi suasana (Unsplash Licence via Picsum): Billy Lam (#302), Paul Jarvis (#989), Jon Ottosson (#255), Jill Heyer (#269).
                </p>
            @endif
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
