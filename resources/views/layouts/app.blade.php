<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Tatanikah' : 'Tatanikah — Perencanaan Pernikahan Indonesia' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
@php
    $isPublicPage = request()->routeIs('login') || request()->routeIs('home') || request()->routeIs('saas.*');
    $hasProfile = isset($globalProfile) && $globalProfile->exists;
    $workspaceItems = [
        ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Budget Planner', 'icon' => 'wallet'],
        ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Checklist & Timeline', 'icon' => 'check-circle'],
        ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Vendor Management', 'icon' => 'building'],
    ];
@endphp
<body class="bg-[#FAF8F5] text-[#2B2520] antialiased selection:bg-[#F4ECE4] selection:text-[#2B2520]">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:border focus:border-[#E7E4DF] focus:bg-[#2B2520] focus:px-4 focus:py-2 focus:text-xs focus:font-semibold focus:text-white focus:rounded-md focus:shadow-sm">
        Lewati ke konten utama
    </a>

    <header class="border-b border-[#E7E4DF] bg-[#FAF8F5]/90 backdrop-blur-md sticky top-0 z-30">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="group flex items-center gap-2">
                    <span class="text-xl font-bold tracking-tight text-[#2B2520] group-hover:text-[#8C5835] transition">Tatanikah</span>
                    <span class="hidden rounded border border-[#E7E4DF] bg-[#F5F2EB] px-2 py-0.5 text-xs font-medium text-[#6E665E] sm:inline-flex">Buku Kerja</span>
                </a>
                @if(! $isPublicPage && $hasProfile)
                    <span class="hidden text-xs font-medium text-[#6E665E] md:inline border-l border-[#E7E4DF] pl-3">
                        {{ $globalProfile->couple_names }}
                    </span>
                @endif
            </div>

            @if(! $isPublicPage && $hasProfile)
                <nav class="hidden items-center gap-1 rounded-lg border border-[#E7E4DF] bg-[#F5F2EB] p-1 lg:flex" aria-label="Navigasi buku kerja pengantin">
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition {{ $active ? 'bg-white text-[#2B2520] font-semibold shadow-xs border border-[#E7E4DF]' : 'text-[#6E665E] hover:text-[#2B2520] hover:bg-white/60' }}"
                            @if($active) aria-current="page" @endif
                        >
                            <x-ui.icon :name="$item['icon']" class="h-3.5 w-3.5 {{ $active ? 'text-[#8C5835]' : 'text-[#6E665E]' }}" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            <div class="flex items-center gap-3">
                @auth
                    @if(! $isPublicPage)
                        @if(isset($globalProfile) && $globalProfile->exists)
                            <div class="flex items-center gap-1">
                                <span class="rounded-md border border-[#E7E4DF] bg-[#F4ECE4] px-2.5 py-0.5 text-xs font-medium text-[#8C5835] tabular-nums">
                                    {{ $globalProfile->days_remaining > 0 ? $globalProfile->days_remaining . ' Hari Lagi' : 'Hari Pernikahan' }}
                                </span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline-block">
                            @csrf
                            <button
                                type="submit"
                                class="text-xs font-medium text-[#6E665E] hover:text-[#2B2520] transition cursor-pointer"
                                title="Keluar akun"
                            >
                                Keluar
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-[#2B2520] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815] shadow-xs">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition
            class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print"
            role="status"
        >
            <div class="flex items-center justify-between gap-3 rounded-lg border border-[#D3E6DA] bg-[#EAF4EE] px-4 py-2.5 text-xs font-medium text-[#2E5E3E]">
                <span>{{ session('success') }}</span>
                <button type="button" @click="show = false" class="text-[#2E5E3E] hover:text-[#1E432B] cursor-pointer text-xs font-semibold">Tutup</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print" role="alert">
            <div class="rounded-lg border border-[#F3D5D0] bg-[#FDF2F0] px-4 py-3 text-xs text-[#9E352B]">
                <p class="font-semibold">Periksa isian formulir:</p>
                <ul class="mt-1 list-disc pl-5 space-y-0.5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main id="main-content" class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8 {{ (! $isPublicPage && $hasProfile) ? 'pb-24 lg:pb-12' : '' }}">
        @yield('content')
    </main>

    @if($isPublicPage || ! $hasProfile)
        <footer class="mt-20 border-t border-[#E7E4DF] bg-white py-8 text-[#6E665E] no-print">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-base font-semibold text-[#2B2520]">Tatanikah</p>
                        <p class="mt-0.5 text-xs text-[#6E665E]">
                            Buku catatan dan tata laksana pernikahan tradisi Indonesia.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-6 text-xs font-medium">
                        <a href="{{ route('home') }}" class="hover:text-[#2B2520] transition">Beranda</a>
                        <a href="{{ route('login') }}" class="hover:text-[#2B2520] transition">Buka Buku Kerja</a>
                    </div>
                </div>
            </div>
        </footer>
    @endif

    @if(! $isPublicPage && $hasProfile)
        <nav
            aria-label="Menu Lembar Kerja HP"
            class="fixed inset-x-0 bottom-0 z-40 border-t border-[#E7E4DF] bg-[#FAF8F5] lg:hidden no-print"
        >
            <div class="mx-auto grid max-w-md grid-cols-4 px-2 py-1">
                @foreach($workspaceItems as $mobileItem)
                    @php $mobileActive = request()->routeIs($mobileItem['pattern']); @endphp
                    <a
                        href="{{ route($mobileItem['route']) }}"
                        class="flex min-h-[50px] flex-col items-center justify-center gap-1 rounded-md py-1 text-[0.65rem] font-medium transition {{ $mobileActive ? 'text-[#8C5835] font-semibold' : 'text-[#6E665E] hover:text-[#2B2520]' }}"
                        @if($mobileActive) aria-current="page" @endif
                    >
                        <x-ui.icon :name="$mobileItem['icon']" class="h-4 w-4 {{ $mobileActive ? 'text-[#8C5835]' : 'text-[#6E665E]' }}" />
                        <span class="truncate">{{ $mobileItem['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>
    @endif

    @stack('scripts')
</body>
</html>
