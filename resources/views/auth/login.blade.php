@extends('layouts.app', ['title' => 'Masuk ke Portal'])

@section('content')
    <div class="flex min-h-[78vh] items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-6">
            {{-- Brand Header --}}
            <div class="text-center">
                <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-espresso text-sm font-bold text-alabaster shadow-sm mb-3">
                    AV
                </div>
                <h1 class="font-editorial text-2xl sm:text-3xl font-bold tracking-tight text-espresso">
                    Masuk ke Atelier Vow
                </h1>
                <p class="text-xs sm:text-sm text-taupe mt-1">
                    Buku kerja terpadu untuk persiapan pernikahan Anda
                </p>
            </div>

            {{-- Main Login Card --}}
            <div class="editorial-card p-6 sm:p-8 rounded-2xl shadow-sm border border-black/[0.08] bg-white">
                {{-- 1. Google Single Sign-On (Industry Standard SaaS) --}}
                <form method="POST" action="{{ route('auth.google') }}">
                    @csrf
                    <input type="hidden" name="google_email" value="aurelia.reza@gmail.com">
                    <input type="hidden" name="google_name" value="Aurelia Larasati & Reza Mahendra">
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2.5 rounded-xl border border-black/[0.1] bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-espresso hover:bg-black/[0.02] hover:border-black/[0.2] transition shadow-xs cursor-pointer"
                    >
                        <x-ui.google-icon class="h-4 w-4" />
                        <span>Lanjutkan dengan Akun Google</span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-stone-line/70"></div>
                    </div>
                    <span class="relative bg-white px-3 text-[11px] font-semibold uppercase tracking-wider text-taupe">
                        atau gunakan akun
                    </span>
                </div>

                {{-- 2. Standard Unified Email & Password Form --}}
                <form method="POST" action="{{ route('login.password') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="login_field" class="block text-xs font-semibold text-charcoal mb-1.5">
                            Email atau Nama Pengguna
                        </label>
                        <input
                            id="login_field"
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="nama@email.com"
                            required
                            autofocus
                            class="editorial-input"
                        >
                        @error('login')
                            <p class="mt-1 text-xs text-wine font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password_field" class="block text-xs font-semibold text-charcoal">
                                Kata Sandi
                            </label>
                            <span class="text-[11px] text-taupe">Demo: user / admin</span>
                        </div>
                        <input
                            id="password_field"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="editorial-input"
                        >
                    </div>

                    <button
                        type="submit"
                        class="editorial-btn-primary w-full !min-h-[2.85rem] !text-sm shadow-sm hover:shadow"
                    >
                        Masuk ke Workspace
                    </button>
                </form>
            </div>

            {{-- Discrete Professional Footer --}}
            <div class="flex items-center justify-between text-xs text-taupe px-2">
                <a href="{{ route('saas.landing') }}" class="hover:text-espresso transition flex items-center gap-1">
                    <span>&larr; Kembali ke Beranda</span>
                </a>

                {{-- Hidden / Discrete Administrator Portal Modal Trigger --}}
                <div x-data="{ openAdmin: false }">
                    <button
                        type="button"
                        @click="openAdmin = true"
                        class="text-[11px] text-taupe/80 hover:text-espresso transition cursor-pointer"
                    >
                        Portal WO &amp; Admin
                    </button>

                    {{-- Sleek, Quiet Admin Login Modal --}}
                    <div
                        x-show="openAdmin"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs"
                    >
                        <div
                            @click.away="openAdmin = false"
                            class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl border border-black/[0.08] text-left space-y-4"
                        >
                            <div class="flex items-center justify-between pb-3 border-b border-stone-line">
                                <div class="flex items-center gap-2">
                                    <x-ui.icon name="shield-check" class="h-4 w-4 text-wine" />
                                    <h3 class="font-bold text-espresso text-sm">Otorisasi Administrator WO</h3>
                                </div>
                                <button type="button" @click="openAdmin = false" class="text-taupe hover:text-espresso text-xs font-bold">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('auth.admin') }}" class="space-y-3 text-xs">
                                @csrf
                                <div>
                                    <label for="admin_email" class="block font-semibold text-charcoal mb-1">Email / Username Admin</label>
                                    <input id="admin_email" type="text" name="email" value="admin" required class="editorial-input !py-1.5 !text-xs">
                                </div>
                                <div>
                                    <label for="admin_password" class="block font-semibold text-charcoal mb-1">Kunci Keamanan</label>
                                    <input id="admin_password" type="password" name="password" value="admin" required class="editorial-input !py-1.5 !text-xs">
                                </div>
                                <button type="submit" class="editorial-btn-primary w-full !py-2 !text-xs">
                                    Buka Konsol Backoffice
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
