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
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso" x-data="{ mobileNavOpen: false }">

    @php
        $isPublicPage = request()->routeIs('saas.*') || request()->routeIs('login') || request()->is('/');
        $workspaceItems = [
            ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
            ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Budget Planner', 'icon' => 'wallet'],
            ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Checklist', 'icon' => 'check-circle'],
            ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Timeline', 'icon' => 'calendar'],
            ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor', 'icon' => 'building'],
        ];
    @endphp

    {{-- Top Navigation Bar: Separates Public SaaS Marketing Nav from Authenticated Customer Workspace Nav --}}
    <header class="sticky top-0 z-30 border-b border-stone-line bg-pearl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('saas.landing') }}" class="group flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-espresso text-xs font-bold text-alabaster">
                        AV
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-espresso">Atelier Vow</span>
                        <span class="hidden text-[11px] font-medium text-taupe sm:inline-block sm:ml-1.5">Wedding SaaS</span>
                    </div>
                </a>
            </div>

            @if($isPublicPage)
                {{-- Dedicated Public SaaS Marketing Navigation --}}
                <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold text-charcoal" aria-label="Navigasi Produk SaaS">
                    <a href="{{ route('saas.landing') }}#masalah-solusi" class="hover:text-champagne">Masalah &amp; Solusi</a>
                    <a href="{{ route('saas.landing') }}#alur-bisnis" class="hover:text-champagne">Alur Kerja</a>
                    <a href="{{ route('saas.landing') }}#fitur-platform" class="hover:text-champagne">5 Modul Produk</a>
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="hover:text-champagne">Kalkulator Live</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="hover:text-champagne">Harga Paket</a>
                    <a href="{{ route('saas.landing') }}#faq-section" class="hover:text-champagne">FAQ</a>
                </nav>
            @else
                {{-- Authenticated Customer Workspace Navigation --}}
                <nav class="hidden lg:flex items-center gap-1 rounded-md border border-stone-line bg-alabaster p-1" aria-label="Navigasi Workspace Pengantin">
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-1.5 rounded px-3 py-1.5 text-xs font-semibold transition {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-pearl hover:text-espresso' }}"
                            @if($active) aria-current="page" @endif
                        >
                            <x-ui.icon :name="$item['icon']" class="h-3.5 w-3.5" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            {{-- Right Action Controls --}}
            <div class="flex items-center gap-2.5">
                @auth
                    @if($isPublicPage)
                        <a href="{{ route('dashboard.index') }}" class="editorial-btn-primary !py-2 !px-3.5 !text-xs">
                            <x-ui.icon name="layout-dashboard" class="h-3.5 w-3.5" />
                            <span>Buka Workspace ({{ auth()->user()->name }})</span>
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
                        class="inline-flex items-center gap-2 rounded-md border border-stone-strong bg-pearl px-3.5 py-2 text-xs font-semibold text-espresso hover:border-champagne hover:bg-alabaster"
                    >
                        <x-ui.google-icon class="h-4 w-4" />
                        <span>Masuk dengan Google</span>
                    </a>
                @endauth

                <button
                    type="button"
                    @click="mobileNavOpen = !mobileNavOpen"
                    class="inline-flex items-center justify-center rounded-md border border-stone-line bg-alabaster p-2 text-charcoal lg:hidden"
                    aria-label="Buka menu navigasi"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Drawer --}}
        <div x-show="mobileNavOpen" x-cloak class="border-t border-stone-line bg-pearl px-4 py-3 lg:hidden">
            @if($isPublicPage)
                <div class="flex flex-col gap-2">
                    <a href="{{ route('saas.landing') }}#masalah-solusi" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">Masalah &amp; Solusi</a>
                    <a href="{{ route('saas.landing') }}#alur-bisnis" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">Alur Kerja</a>
                    <a href="{{ route('saas.landing') }}#fitur-platform" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">5 Modul Produk</a>
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">Kalkulator Live</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal">Harga Paket</a>
                    <a href="{{ route('login') }}" class="mt-1 inline-flex items-center justify-center gap-2 rounded-md bg-espresso px-4 py-2.5 text-xs font-semibold text-alabaster">
                        <x-ui.google-icon class="h-4 w-4" />
                        <span>Masuk dengan Google</span>
                    </a>
                </div>
            @else
                <div class="flex flex-col gap-1">
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory' }}"
                        >
                            <x-ui.icon :name="$item['icon']" class="h-4 w-4" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                    <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-stone-line pt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-md border border-stone-line bg-alabaster px-3 py-2 text-center text-xs font-semibold text-charcoal">
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

    <footer class="mt-16 border-t border-stone-line bg-pearl py-10 no-print">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 pb-8 border-b border-stone-line sm:grid-cols-2 lg:grid-cols-4 text-xs">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded bg-espresso text-[11px] font-bold text-alabaster">AV</div>
                        <span class="text-sm font-bold text-espresso">Atelier Vow Nusantara</span>
                    </div>
                    <p class="mt-2.5 leading-relaxed text-taupe">
                        Software-as-a-Service (SaaS) manajemen anggaran, checklist keluarga, linimasa H-12 bulan, dan vendor pernikahan Indonesia.
                    </p>
                </div>
                <div>
                    <p class="font-bold text-espresso">5 Modul Produk</p>
                    <ul class="mt-2.5 space-y-1.5 text-taupe">
                        <li><a href="{{ route('dashboard.index') }}" class="hover:text-espresso">Executive Dashboard &amp; Weton</a></li>
                        <li><a href="{{ route('budget.index') }}" class="hover:text-espresso">Budget Planner 8 Kategori</a></li>
                        <li><a href="{{ route('checklist.index') }}" class="hover:text-espresso">Wedding Checklist &amp; PIC</a></li>
                        <li><a href="{{ route('timeline.index') }}" class="hover:text-espresso">Timeline H-12 s/d Hari-H</a></li>
                        <li><a href="{{ route('vendors.index') }}" class="hover:text-espresso">Vendor &amp; Pelunasan DP</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-bold text-espresso">Alur Layanan SaaS</p>
                    <ul class="mt-2.5 space-y-1.5 text-taupe">
                        <li><a href="{{ route('saas.landing') }}#masalah-solusi" class="hover:text-espresso">Perbandingan Solusi</a></li>
                        <li><a href="{{ route('saas.landing') }}#alur-bisnis" class="hover:text-espresso">4 Langkah Kerja</a></li>
                        <li><a href="{{ route('saas.landing') }}#simulasi-adat" class="hover:text-espresso">Kalkulator Anggaran Live</a></li>
                        <li><a href="{{ route('saas.landing') }}#paket-saas" class="hover:text-espresso">Harga Paket B2C &amp; B2B WO</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-bold text-espresso">Mulai Sekarang</p>
                    <p class="mt-2.5 leading-relaxed text-taupe">
                        Akses langsung workspace pernikahan Anda menggunakan akun Google tanpa instalasi tambahan.
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-md border border-stone-strong bg-alabaster px-3 py-2 font-semibold text-espresso hover:border-champagne">
                            <x-ui.google-icon class="h-3.5 w-3.5" />
                            <span>Masuk dengan Google</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col items-center justify-between gap-3 text-xs text-taupe sm:flex-row">
                <span>Atelier Vow — Sistem Perencanaan Pernikahan Terpadu</span>
                @if(isset($globalProfile))
                    <span class="tabular-nums">{{ $globalProfile->venue_name }}, {{ $globalProfile->city }} · Target {{ $globalProfile->guest_target }} Tamu</span>
                @endif
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
