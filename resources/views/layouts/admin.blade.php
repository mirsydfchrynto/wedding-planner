<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Admin Backoffice' : 'Admin Backoffice — Atelier Vow' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- deslop-ignore-next-line 33 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-dvh bg-slate-100 font-sans text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded focus:bg-slate-900 focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-white">
        Lewati ke konten utama
    </a>

    <div class="flex min-h-dvh">
        {{-- Standard Breeze / Backoffice Dark Slate Sidebar --}}
        <aside class="hidden w-64 shrink-0 flex-col bg-slate-900 text-slate-200 lg:flex">
            <div class="flex h-16 items-center justify-between border-b border-slate-800 px-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-white text-xs font-bold text-slate-900">
                        AV
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white leading-none">Atelier Vow</p>
                        <p class="mt-0.5 text-[11px] text-slate-400">Breeze Admin Console</p>
                    </div>
                </div>
                <span class="rounded bg-slate-800 px-2 py-0.5 text-[10px] font-semibold text-slate-300">v1.0</span>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4 text-xs font-medium">
                <p class="px-2 pb-1.5 text-[11px] font-semibold text-slate-400">Operasional</p>
                <a href="{{ route('admin.index') }}" class="flex items-center gap-2.5 rounded-md bg-slate-800 px-3 py-2.5 text-white">
                    <x-ui.icon name="layout-dashboard" class="h-4 w-4 text-slate-200" />
                    <span>Overview &amp; Telemetry</span>
                </a>
                <a href="#bottleneck-section" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="alert-triangle" class="h-4 w-4 text-slate-400" />
                    <span>Bottleneck &amp; Overdue</span>
                </a>
                <a href="#vendor-sla-section" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="building" class="h-4 w-4 text-slate-400" />
                    <span>Verifikasi Vendor SLA</span>
                </a>
                <a href="#system-health-section" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="server" class="h-4 w-4 text-slate-400" />
                    <span>12-Factor Infrastructure</span>
                </a>

                <p class="px-2 pt-5 pb-1.5 text-[11px] font-semibold text-slate-400">Inspeksi Modul Klien</p>
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="users" class="h-4 w-4 text-slate-400" />
                    <span>Portal Pengantin</span>
                </a>
                <a href="{{ route('budget.index') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="wallet" class="h-4 w-4 text-slate-400" />
                    <span>Budget Planner</span>
                </a>
                <a href="{{ route('checklist.index') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="check-circle" class="h-4 w-4 text-slate-400" />
                    <span>Checklist Tugas</span>
                </a>
                <a href="{{ route('vendors.index') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-slate-300 hover:bg-slate-800/70 hover:text-white">
                    <x-ui.icon name="file-text" class="h-4 w-4 text-slate-400" />
                    <span>Direktori Vendor</span>
                </a>
            </nav>

            <div class="border-t border-slate-800 p-4 text-xs">
                <div class="truncate">
                    <p class="font-semibold text-white truncate">{{ auth()->user()?->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()?->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-md bg-slate-800 px-3 py-1.5 text-center text-xs font-semibold text-slate-200 hover:bg-slate-700 hover:text-white cursor-pointer">
                        Logout Sesi Admin
                    </button>
                </form>
            </div>
        </aside>

        {{-- Right Main Workspace --}}
        <div class="flex flex-1 flex-col min-w-0">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        class="rounded-md border border-slate-200 p-2 text-slate-600 lg:hidden"
                        aria-label="Toggle Menu"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold text-slate-900">
                        {{ $title ?? 'Konsol Admin & Operasional Wedding Organizer' }}
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 rounded-md bg-slate-100 border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-800">
                        <x-ui.icon name="shield-check" class="h-3.5 w-3.5 text-sage" />
                        <span>Private Admin Mode</span>
                    </span>
                    <a href="{{ route('dashboard.index') }}" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Ke Workspace Klien
                    </a>
                </div>
            </header>

            <div x-show="sidebarOpen" x-cloak class="border-b border-slate-200 bg-slate-900 px-4 py-3 text-xs text-white lg:hidden">
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('admin.index') }}" class="rounded px-2.5 py-1.5 bg-slate-800 font-semibold">Overview Admin</a>
                    <a href="{{ route('dashboard.index') }}" class="rounded px-2.5 py-1.5 text-slate-300">Portal Pengantin</a>
                    <a href="{{ route('budget.index') }}" class="rounded px-2.5 py-1.5 text-slate-300">Budget Planner</a>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between rounded-lg border border-sage/30 bg-sage-bg px-4 py-2.5 text-xs font-medium text-sage">
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <main id="main-content" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
