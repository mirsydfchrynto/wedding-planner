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
</head>
@php
    $isPublicPage = request()->routeIs('login');
    $hasProfile = isset($globalProfile) && $globalProfile->exists;
    $workspaceItems = [
        ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Ringkasan', 'icon' => 'layout-dashboard'],
        ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Anggaran', 'icon' => 'wallet'],
        ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Tugas', 'icon' => 'check-circle'],
        ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Linimasa', 'icon' => 'calendar'],
        ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor', 'icon' => 'building'],
    ];
@endphp
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso" x-data="{ mobileNavOpen: false }">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded focus:bg-espresso focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-alabaster">
        Lewati ke konten utama
    </a>

    <header class="sticky top-0 z-30 border-b border-stone-line bg-pearl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 lg:px-10">
            <a href="{{ route('home') }}" class="group flex items-baseline gap-2.5">
                <span class="text-base font-bold tracking-tight text-espresso">Atelier Vow</span>
                <span class="text-xs text-taupe">Nusantara</span>
            </a>

            @if(! $isPublicPage && $hasProfile)
                <nav class="hidden items-baseline gap-6 lg:flex" aria-label="Navigasi workspace pengantin">
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="text-sm font-semibold transition {{ $active ? 'text-espresso underline decoration-espresso underline-offset-4' : 'text-taupe hover:text-espresso' }}"
                            @if($active) aria-current="page" @endif
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            @endif

            <div class="flex items-center gap-3">
                @auth
                    @if(! $isPublicPage)
                        <div class="hidden items-baseline gap-2 text-xs xl:flex">
                            <span class="font-semibold text-espresso">{{ auth()->user()->name }}</span>
                            @if(isset($globalProfile))
                                <span class="text-stone-strong">·</span>
                                <span class="tabular-nums font-semibold text-champagne">H-{{ $globalProfile->days_remaining }}</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline-block">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-taupe underline decoration-stone-strong underline-offset-4 hover:text-espresso cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-espresso underline decoration-stone-strong underline-offset-4 hover:decoration-espresso">
                        Masuk
                    </a>
                @endauth

                @auth
                    @if(! $isPublicPage)
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
                    @endif
                @endauth
            </div>
        </div>

        @auth
            @if(! $isPublicPage)
                <div x-show="mobileNavOpen" x-cloak class="bg-ivory px-6 py-4 lg:hidden">
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
                </div>
            @endif
        @endauth
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

    <main id="main-content" class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-stone-line bg-pearl pt-10 pb-24 lg:pb-10 text-taupe no-print">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                <div>
                    <p class="text-base font-bold text-espresso">Atelier Vow</p>
                    <p class="mt-1 text-sm text-taupe">
                        Perencanaan anggaran, tugas, linimasa, dan vendor pernikahan Nusantara.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-6 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                    <a href="{{ route('login') }}" class="hover:underline">Masuk Akun Google</a>
                    <a href="{{ route('dashboard.index') }}" class="hover:underline">Workspace Pengantin</a>
                </div>
            </div>
        </div>
    </footer>

    @if(!$isPublicPage && $hasProfile)
        @php
            $mobileBottomItems = [
                ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Ringkasan', 'icon' => 'layout-dashboard'],
                ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Anggaran', 'icon' => 'wallet'],
                ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Tugas', 'icon' => 'check-circle'],
                ['route' => 'timeline.index', 'pattern' => 'timeline.*', 'label' => 'Linimasa', 'icon' => 'calendar'],
                ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor', 'icon' => 'building'],
            ];
        @endphp
        <nav
            aria-label="Menu Bawah Layar HP"
            class="fixed inset-x-0 bottom-0 z-40 border-t border-stone-line bg-pearl/95 backdrop-blur-md lg:hidden no-print"
        >
            <div class="mx-auto grid max-w-lg grid-cols-5 px-1 py-1">
                @foreach($mobileBottomItems as $mobileItem)
                    @php $mobileActive = request()->routeIs($mobileItem['pattern']); @endphp
                    <a
                        href="{{ route($mobileItem['route']) }}"
                        class="flex min-h-[52px] flex-col items-center justify-center gap-1 border-t-2 px-1 py-1.5 text-xs font-semibold transition {{ $mobileActive ? 'border-espresso text-espresso' : 'border-transparent text-taupe hover:text-espresso' }}"
                        @if($mobileActive) aria-current="page" @endif
                    >
                        <x-ui.icon :name="$mobileItem['icon']" class="h-4 w-4" />
                        <span class="truncate">{{ $mobileItem['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>
    @endif

    @stack('scripts')
</body>
</html>
