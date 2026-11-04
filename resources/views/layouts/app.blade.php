<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Atelier Vow Nusantara' : 'Atelier Vow — Sasmita Janji Nusantara' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso" x-data="{ mobileNavOpen: false }">

    {{-- Top Apple-Tactile x Nusantara Ribbon --}}
    <header class="sticky top-0 z-30 border-b border-stone-line bg-alabaster">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('dashboard.index') : route('saas.landing') }}" class="group flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-champagne/40 bg-champagne-light font-editorial text-lg font-bold text-champagne">
                        AV
                    </div>
                    <div>
                        <span class="font-editorial text-xl font-semibold tracking-tight text-espresso">Atelier Vow</span>
                        <span class="hidden text-xs text-taupe xl:inline-block xl:ml-1.5">Sasmita Janji Nusantara</span>
                    </div>
                </a>
            </div>

            @php
                $navItems = [
                    ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Dashboard'],
                    ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Budget Planner'],
                    ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Checklist'],
                    ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Timeline'],
                    ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor'],
                ];
            @endphp

            {{-- Desktop Navigation: Public Marketing Links for Guests vs 5 Workspace Modules for Logged-in Users --}}
            @auth
                <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi Utama">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="rounded-md px-3 py-1.5 text-xs font-semibold transition {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory hover:text-espresso' }}"
                            @if($active) aria-current="page" @endif
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            @else
                <nav class="hidden lg:flex items-center gap-5 text-xs font-semibold text-charcoal" aria-label="Navigasi Publik">
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="hover:text-champagne">Simulasi Anggaran &amp; Adat</a>
                    <a href="{{ route('saas.landing') }}#fitur-platform" class="hover:text-champagne">Arsitektur 5 Modul</a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="hover:text-champagne">Paket Layanan SaaS</a>
                </nav>
            @endauth

            {{-- Right Actions: Google Login for Guests vs User Session & Hidden Admin Console for Admins --}}
            <div class="flex items-center gap-2.5">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.index') }}"
                            class="hidden lg:inline-flex items-center rounded-md px-3 py-1.5 text-xs font-semibold transition {{ request()->routeIs('admin.*') ? 'bg-wine text-alabaster' : 'border border-wine/30 bg-wine-bg text-wine hover:bg-wine hover:text-alabaster' }}"
                        >
                            Konsol Admin Privat
                        </a>
                    @endif

                    <div class="hidden xl:flex items-center gap-2 rounded-md border border-stone-line bg-pearl px-3 py-1.5 text-xs">
                        <span class="font-medium text-espresso">{{ auth()->user()->name }}</span>
                        @if(isset($globalProfile))
                            <span class="h-3 w-px bg-stone-line"></span>
                            <span class="tabular-nums font-medium text-champagne">H-{{ $globalProfile->days_remaining }} Hari</span>
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

        {{-- Mobile Navigation Drawer --}}
        <div x-show="mobileNavOpen" x-cloak class="border-t border-stone-line bg-pearl px-4 py-3 lg:hidden">
            @auth
                <div class="flex flex-col gap-1">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="rounded-md px-3 py-2 text-sm font-medium {{ $active ? 'bg-espresso text-alabaster' : 'text-charcoal hover:bg-ivory' }}"
                        >
                            {{ $item['label'] }}
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
                    <a href="{{ route('saas.landing') }}#simulasi-adat" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal hover:bg-ivory">
                        Simulasi Anggaran &amp; Adat
                    </a>
                    <a href="{{ route('saas.landing') }}#paket-saas" class="rounded-md px-3 py-2 text-sm font-medium text-charcoal hover:bg-ivory">
                        Paket Layanan SaaS
                    </a>
                    <a href="{{ route('login') }}" class="editorial-btn-primary w-full text-center">
                        Masuk dengan Google
                    </a>
                </div>
            @endauth
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
                <p class="font-semibold">Periksa kembali isian formulir berikut:</p>
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

    {{-- Nusantara Batik Editorial Footer --}}
    <footer class="mt-16 border-t border-stone-line bg-ivory/60 py-8 no-print">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-ui.batik-ornament class="mb-5" />
            <div class="flex flex-col items-center justify-between gap-3 text-xs text-taupe sm:flex-row">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="font-editorial text-sm font-semibold text-espresso">Atelier Vow Nusantara</span>
                    <span>·</span>
                    <a href="{{ route('saas.landing') }}" class="hover:text-espresso hover:underline">Halaman Utama SaaS</a>
                    @auth
                        <span>·</span>
                        <a href="{{ route('dashboard.index') }}" class="hover:text-espresso hover:underline">Dashboard Pengantin</a>
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
                        {{ $globalProfile->venue_name }}, {{ $globalProfile->city }} · {{ $globalProfile->guest_target }} Tamu
                    </div>
                @endif
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
