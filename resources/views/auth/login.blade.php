@extends('layouts.app', ['title' => 'Masuk dengan Google'])

@section('content')
    <div class="mx-auto max-w-4xl py-4" x-data="{ showAdminGate: false }">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left 7 Cols: Official Google OAuth Customer Sign-In Card --}}
            <section class="editorial-card rounded-lg p-6 sm:p-8 lg:col-span-7">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-stone-line bg-alabaster">
                        <x-ui.google-icon class="h-5 w-5" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-espresso">Masuk ke Portal Calon Pengantin</h1>
                        <p class="text-xs text-taupe">Autentikasi Single Sign-On (SSO) Google OAuth 2.0</p>
                    </div>
                </div>

                {{-- One-Click Official Google Sign-In Button --}}
                <form method="POST" action="{{ route('auth.google') }}" class="mt-6 space-y-4">
                    @csrf

                    <div class="rounded-lg border border-stone-line bg-alabaster p-4 space-y-3">
                        <div>
                            <label for="google_email" class="mb-1 block text-xs font-semibold text-charcoal">
                                Email Akun Google Pasangan
                            </label>
                            <input
                                id="google_email"
                                type="email"
                                name="google_email"
                                value="aurelia.reza@gmail.com"
                                required
                                class="editorial-input !bg-pearl"
                            >
                        </div>

                        <div>
                            <label for="google_name" class="mb-1 block text-xs font-semibold text-charcoal">
                                Nama Pasangan di Workspace
                            </label>
                            <input
                                id="google_name"
                                type="text"
                                name="google_name"
                                value="Aurelia Larasati & Reza Mahendra"
                                required
                                class="editorial-input !bg-pearl"
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-3 rounded-md border border-stone-strong bg-pearl px-4 py-3 text-sm font-bold text-espresso transition hover:border-espresso hover:bg-ivory cursor-pointer"
                    >
                        <x-ui.google-icon class="h-5 w-5" />
                        <span>Lanjutkan dengan Akun Google</span>
                    </button>
                </form>

                {{-- deslop-ignore-next-line 28 --}}
                <div class="mt-6 grid grid-cols-3 gap-2 border-t border-stone-line pt-4 text-center text-[11px] text-taupe">
                    <div class="rounded bg-alabaster p-2">
                        <p class="font-bold text-espresso">5 Modul</p>
                        <p>Aktif Otomatis</p>
                    </div>
                    <div class="rounded bg-alabaster p-2">
                        <p class="font-bold text-espresso">Real-Time</p>
                        <p>Sinkronisasi Data</p>
                    </div>
                    <div class="rounded bg-alabaster p-2">
                        <p class="font-bold text-espresso">Privat</p>
                        <p>Proteksi Sesi</p>
                    </div>
                </div>
            </section>

            {{-- Right 5 Cols: What Unlocks After Login + Private Admin Gate --}}
            <div class="flex flex-col justify-between gap-4 lg:col-span-5">
                <section class="editorial-card rounded-lg p-6">
                    <h2 class="text-xl font-bold text-espresso">Alur Kerja Setelah Login Google</h2>
                    <ul class="mt-4 space-y-3 text-xs text-charcoal">
                        <li class="flex items-start gap-2.5">
                            <x-ui.icon name="layout-dashboard" class="mt-0.5 h-4 w-4 text-champagne" />
                            <div>
                                <p class="font-bold text-espresso">1. Executive Dashboard &amp; Weton</p>
                                <p class="text-taupe">Pantau countdown akad, kesiapan %, &amp; hitung Neptu Jawa.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-ui.icon name="wallet" class="mt-0.5 h-4 w-4 text-champagne" />
                            <div>
                                <p class="font-bold text-espresso">2. Budget Planner 8 Kategori</p>
                                <p class="text-taupe">Kendalikan plafon Rp 350 Jt, biaya aktual, &amp; selisih hemat.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-ui.icon name="check-circle" class="mt-0.5 h-4 w-4 text-champagne" />
                            <div>
                                <p class="font-bold text-espresso">3. Checklist &amp; Timeline H-12</p>
                                <p class="text-taupe">Bagi tugas PIC keluarga &amp; pantau 7 fase menuju Hari-H.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-ui.icon name="building" class="mt-0.5 h-4 w-4 text-champagne" />
                            <div>
                                <p class="font-bold text-espresso">4. Direktori Vendor &amp; Kontrak</p>
                                <p class="text-taupe">Catat DP/pelunasan &amp; hubungi WhatsApp PIC vendor.</p>
                            </div>
                        </li>
                    </ul>
                </section>

                {{-- Private Admin Gate Box --}}
                <section class="editorial-card rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-ui.icon name="shield-check" class="h-4 w-4 text-wine" />
                            <span class="text-xs font-bold text-espresso">Otorisasi Privat Administrator</span>
                        </div>
                        <button
                            type="button"
                            @click="showAdminGate = !showAdminGate"
                            class="text-xs font-semibold text-champagne hover:underline cursor-pointer"
                            x-text="showAdminGate ? 'Tutup' : 'Buka Akses'"
                        >
                            Buka Akses
                        </button>
                    </div>

                    <div x-show="showAdminGate" x-cloak class="mt-4 border-t border-stone-line pt-4">
                        <form method="POST" action="{{ route('auth.admin') }}" class="space-y-3">
                            @csrf
                            <div>
                                <label for="admin_email" class="mb-1 block text-[11px] font-semibold text-charcoal">Email Administrator</label>
                                <input id="admin_email" type="email" name="email" value="irsyad@ateliervow.id" required class="editorial-input !py-1.5 !text-xs">
                            </div>
                            <div>
                                <label for="admin_password" class="mb-1 block text-[11px] font-semibold text-charcoal">Kunci Otorisasi Admin</label>
                                <input id="admin_password" type="password" name="password" value="AtelierAdmin2026!" required class="editorial-input !py-1.5 !text-xs">
                            </div>
                            <button type="submit" class="editorial-btn-primary w-full !py-2 !text-xs">
                                Buka Konsol Admin Privat
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
