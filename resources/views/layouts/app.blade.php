<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Tatanikah' : 'Tatanikah — Perencanaan Pernikahan Indonesia' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=JetBrains+Mono:wght@400;600;800&family=Kalam:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.shareToFamilyWhatsApp = function(data) {
            const couple = data.couple || 'Pengantin';
            const date = data.date || 'Hari Bahagia';
            const days = data.days || 0;
            const venue = data.venue || 'Venue Acara';
            const budgetCap = data.budgetCap || '-';
            const remaining = data.remaining || '-';
            const tasksDone = data.tasksDone || 0;
            const tasksTotal = data.tasksTotal || 0;
            const vendorsBooked = data.vendorsBooked || 0;

            const text = `*💍 BUKU CATATAN PERNIKAHAN: ${couple.toUpperCase()}*\n` +
                `_Pusat Koordinasi & Info Resmi Keluarga_\n\n` +
                `🗓️ *Hari H:* ${date} (*H-${days} Hari Lagi*)\n` +
                `📍 *Lokasi:* ${venue}\n\n` +
                `📊 *STATUS KESIAPAN KELUARGA:*\n` +
                `• Kebutuhan Selesai: *${tasksDone} dari ${tasksTotal} Kebutuhan*\n` +
                `• Anggaran Induk: *${budgetCap}* (Sisa Dana Aman: *${remaining}*)\n` +
                `• Vendor Terkunci: *${vendorsBooked} Mitra*\n\n` +
                `🤝 *Pesan untuk Keluarga:*\n` +
                `Semua persiapan dicatat rapi dan transparan. Mohon doa restu dan bantuan koordinasi agar acara lancar, khidmat, dan berkah.\n\n` +
                `Buka buku catatan online: ${window.location.origin}/dashboard`;

            const url = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(text);
            window.open(url, '_blank', 'noopener,noreferrer');
        };
    </script>
</head>
@php
    $isPublicPage = request()->routeIs('login') || request()->routeIs('home') || request()->routeIs('saas.*');
    $hasProfile = isset($globalProfile) && $globalProfile->exists;
    $workspaceItems = [
        ['route' => 'dashboard.index', 'pattern' => 'dashboard.*', 'label' => 'Ringkasan', 'icon' => 'layout-dashboard'],
        ['route' => 'budget.index', 'pattern' => 'budget.*', 'label' => 'Buku Kas', 'icon' => 'wallet'],
        ['route' => 'checklist.index', 'pattern' => 'checklist.*', 'label' => 'Catatan Tugas', 'icon' => 'check-circle'],
        ['route' => 'rundown.index', 'pattern' => 'rundown.*', 'label' => 'Rundown', 'icon' => 'calendar'],
        ['route' => 'vendors.index', 'pattern' => 'vendors.*', 'label' => 'Kwitansi Vendor', 'icon' => 'building'],
    ];
@endphp
<body class="bg-desk text-ink antialiased selection:bg-kunyit selection:text-ink">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:border-2 focus:border-ink focus:bg-soga focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-paper focus:shadow-[3px_3px_0px_#2B2118]">
        Lewati ke konten utama
    </a>

    {{-- Header Buku Kerja Neo-Brutalist --}}
    <header class="border-b-2 border-ink bg-paper-2 sticky top-0 z-30">
        <div class="mx-auto flex max-w-7xl items-end justify-between gap-4 px-4 pt-3 pb-0 sm:px-6 lg:px-8">
            <div class="flex items-baseline gap-4 pb-2.5">
                <a href="{{ route('home') }}" class="group flex items-baseline gap-2">
                    <span class="font-display text-2xl font-bold tracking-tight text-soga group-hover:text-soga-dark">Tatanikah</span>
                    <span class="stamp hidden sm:inline-flex">Buku Kerja</span>
                </a>
                @if(! $isPublicPage && $hasProfile)
                    <span class="hidden font-display text-base font-bold text-ink md:inline">
                        {{ $globalProfile->couple_names }}
                    </span>
                @endif
            </div>

            @if(! $isPublicPage && $hasProfile)
                {{-- Folder Tab Navigation (Desktop Neo-Brutalist) --}}
                <nav class="hidden items-end gap-1.5 lg:flex" aria-label="Navigasi buku kerja pengantin">
                    @foreach($workspaceItems as $item)
                        @php $active = request()->routeIs($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="index-tab {{ $active ? 'index-tab-active font-bold text-ink' : '' }}"
                            @if($active) aria-current="page" @endif
                        >
                            <x-ui.icon :name="$item['icon']" class="h-3.5 w-3.5 {{ $active ? 'text-soga' : 'text-taupe' }}" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            <div class="flex items-center gap-3 pb-2.5">
                @auth
                    @if(! $isPublicPage)
                        @if(isset($globalProfile) && $globalProfile->exists)
                            <div class="flex items-baseline gap-1 text-xs">
                                <span class="stamp !bg-kunyit !border-ink !text-ink font-bold tabular-nums">H-{{ $globalProfile->days_remaining }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline-block">
                            @csrf
                            <button
                                type="submit"
                                class="text-xs font-bold text-taupe hover:text-ink cursor-pointer underline underline-offset-4"
                                title="Keluar akun"
                            >
                                Keluar
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-neo !min-h-0 !py-1 !px-3 !text-xs">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Flash status notification (gaya memo terselip) --}}
    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition
            class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print"
            role="status"
        >
            <div class="bento-card bento-kunyit flex items-center justify-between gap-3 px-4 py-2.5 text-xs font-bold text-ink">
                <span class="flex items-center gap-2">
                    <span class="stamp !bg-paper">Catatan</span>
                    <span>{{ session('success') }}</span>
                </span>
                <button type="button" @click="show = false" class="text-ink hover:text-soga cursor-pointer underline text-[0.7rem]">Tutup</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8 no-print" role="alert">
            <div class="bento-card bento-terracotta px-4 py-3 text-xs text-ink">
                <p class="font-bold flex items-center gap-2">
                    <span class="stamp !border-ink !bg-paper">Koreksi</span>
                    Periksa isian formulir:
                </p>
                <ul class="mt-1 list-disc pl-5 space-y-0.5 font-semibold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Lembaran Kerja Utama --}}
    <main id="main-content" class="mx-auto max-w-7xl px-3 py-4 sm:px-6 sm:py-6 lg:px-8 {{ (! $isPublicPage && $hasProfile) ? 'pb-24 lg:pb-10' : '' }}">
        @yield('content')
    </main>

    {{-- Footer hanya muncul di halaman publik / landing / auth --}}
    @if($isPublicPage || ! $hasProfile)
        <footer class="mt-16 border-t-2 border-ink bg-paper-2 pt-8 pb-10 text-taupe no-print">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <p class="font-display text-xl font-bold text-soga">Tatanikah</p>
                        <p class="text-xs text-taupe font-medium">
                            Buku catatan &amp; perencanaan pernikahan tradisi Indonesia.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-5 text-xs font-bold">
                        <a href="{{ route('home') }}" class="hover:text-ink">Sampul</a>
                        <a href="{{ route('login') }}" class="hover:text-ink">Buka Buku Kerja</a>
                    </div>
                </div>
            </div>
        </footer>
    @endif

    {{-- Navigasi Bawah Layar HP (1 Baris Tunggal, Neo-Brutalist, Tanpa Menu Hamburger) --}}
    @if(! $isPublicPage && $hasProfile)
        <nav
            aria-label="Menu Lembar Kerja HP"
            class="bottom-nav fixed inset-x-0 bottom-0 z-40 lg:hidden no-print"
        >
            <div class="mx-auto grid max-w-lg grid-cols-5 px-1 py-0.5">
                @foreach($workspaceItems as $mobileItem)
                    @php $mobileActive = request()->routeIs($mobileItem['pattern']); @endphp
                    <a
                        href="{{ route($mobileItem['route']) }}"
                        class="bottom-nav-item"
                        @if($mobileActive) aria-current="page" @endif
                    >
                        <x-ui.icon :name="$mobileItem['icon']" class="h-4 w-4 {{ $mobileActive ? 'text-soga' : 'text-taupe' }}" />
                        <span class="truncate">{{ $mobileItem['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>
    @endif

    @stack('scripts')
</body>
</html>
