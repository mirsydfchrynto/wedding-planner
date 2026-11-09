@extends('layouts.app', ['title' => 'Masuk ke Portal'])

@section('content')
    <div class="mx-auto max-w-4xl py-6" x-data="{ mode: 'instant', showAdminGate: false }">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Left 7 Cols: Quick & Easy Sign-In Card --}}
            <section class="editorial-card p-6 sm:p-8 lg:col-span-7">
                <div class="flex items-center justify-between border-b border-stone-line pb-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-champagne">Gerbang Autentikasi</span>
                        <h1 class="font-editorial text-2xl font-bold text-espresso mt-0.5">Masuk ke Atelier Vow</h1>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-espresso text-xs font-bold text-alabaster">
                        AV
                    </div>
                </div>

                {{-- Quick Access 1-Click Buttons --}}
                <div class="mt-6">
                    <p class="text-xs font-semibold text-taupe mb-2">Akses Cepat 1-Klik (Pengujian &amp; Demo):</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <form method="POST" action="{{ route('login.password') }}">
                            @csrf
                            <input type="hidden" name="login" value="user">
                            <input type="hidden" name="password" value="user">
                            <button
                                type="submit"
                                class="w-full text-left rounded-xl border border-black/[0.08] bg-pearl p-3.5 hover:border-champagne hover:shadow-sm transition cursor-pointer"
                            >
                                <div class="flex items-center gap-2">
                                    <x-ui.icon name="users" class="h-4 w-4 text-champagne" />
                                    <span class="font-bold text-espresso text-sm">Calon Pengantin</span>
                                </div>
                                <p class="text-[11px] text-taupe mt-1 font-mono">user / user</p>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('login.password') }}">
                            @csrf
                            <input type="hidden" name="login" value="admin">
                            <input type="hidden" name="password" value="admin">
                            <button
                                type="submit"
                                class="w-full text-left rounded-xl border border-black/[0.08] bg-pearl p-3.5 hover:border-wine hover:shadow-sm transition cursor-pointer"
                            >
                                <div class="flex items-center gap-2">
                                    <x-ui.icon name="shield-check" class="h-4 w-4 text-wine" />
                                    <span class="font-bold text-espresso text-sm">Administrator WO</span>
                                </div>
                                <p class="text-[11px] text-taupe mt-1 font-mono">admin / admin</p>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Manual Login Form --}}
                <div class="mt-6 border-t border-stone-line/70 pt-5">
                    <p class="text-xs font-semibold text-taupe mb-3">Atau Masuk dengan Akun Mandiri:</p>
                    <form method="POST" action="{{ route('login.password') }}" class="space-y-3.5">
                        @csrf
                        <div>
                            <label for="login_field" class="mb-1 block text-xs font-semibold text-charcoal">
                                Email atau Username
                            </label>
                            <input
                                id="login_field"
                                type="text"
                                name="login"
                                value="{{ old('login', 'user') }}"
                                placeholder="Ketik 'user' atau 'admin'"
                                required
                                class="editorial-input"
                            >
                            @error('login')
                                <p class="mt-1 text-xs text-wine">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_field" class="mb-1 block text-xs font-semibold text-charcoal">
                                Kata Sandi
                            </label>
                            <input
                                id="password_field"
                                type="password"
                                name="password"
                                value="user"
                                placeholder="Ketik 'user' atau 'admin'"
                                required
                                class="editorial-input"
                            >
                        </div>

                        <button
                            type="submit"
                            class="editorial-btn-primary w-full !min-h-[2.75rem]"
                        >
                            Masuk ke Workspace
                        </button>
                    </form>
                </div>

                {{-- Google Sign-In Fallback --}}
                <div class="mt-6 border-t border-stone-line/70 pt-4">
                    <form method="POST" action="{{ route('auth.google') }}">
                        @csrf
                        <input type="hidden" name="google_email" value="aurelia.reza@gmail.com">
                        <input type="hidden" name="google_name" value="Aurelia Larasati & Reza Mahendra">
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2.5 rounded-xl border border-stone-line bg-alabaster/60 px-4 py-2.5 text-xs font-semibold text-charcoal hover:bg-pearl hover:border-black/20 transition cursor-pointer"
                        >
                            <x-ui.google-icon class="h-4 w-4" />
                            <span>Lanjutkan dengan Google SSO (aurelia.reza@gmail.com)</span>
                        </button>
                    </form>
                </div>
            </section>

            {{-- Right 5 Cols: Guide & Admin Direct Gate --}}
            <div class="flex flex-col justify-between gap-5 lg:col-span-5">
                <section class="editorial-card p-6">
                    <h2 class="font-editorial text-xl font-bold text-espresso">Panduan Kredensial</h2>
                    <div class="mt-4 space-y-3 text-xs">
                        <div class="rounded-xl border border-black/[0.06] bg-alabaster/80 p-3">
                            <p class="font-bold text-espresso text-sm">Akun Calon Pengantin (Testing)</p>
                            <p class="text-taupe mt-0.5">Username: <code class="font-mono font-bold text-espresso">user</code></p>
                            <p class="text-taupe">Password: <code class="font-mono font-bold text-espresso">user</code></p>
                            <p class="text-taupe mt-1 text-[11px]">Membuka 6 modul pernikahan (Dashboard, Anggaran, Checklist, Linimasa, Vendor, Buku Tamu).</p>
                        </div>

                        <div class="rounded-xl border border-wine/20 bg-wine-bg/40 p-3">
                            <p class="font-bold text-wine text-sm">Akun Administrator &amp; Lead WO</p>
                            <p class="text-taupe mt-0.5">Username: <code class="font-mono font-bold text-wine">admin</code></p>
                            <p class="text-taupe">Password: <code class="font-mono font-bold text-wine">admin</code></p>
                            <p class="text-taupe mt-1 text-[11px]">Membuka konsol manajemen WO privat (<code class="font-mono">/admin</code>), SLA vendor, dan telemetri.</p>
                        </div>
                    </div>
                </section>

                {{-- Direct Admin Gate --}}
                <section class="editorial-card p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-bold text-espresso">
                            <x-ui.icon name="shield-check" class="h-4 w-4 text-wine" />
                            <span>Otorisasi Langsung Admin</span>
                        </div>
                        <button
                            type="button"
                            @click="showAdminGate = !showAdminGate"
                            class="text-xs font-semibold text-champagne hover:underline cursor-pointer"
                            x-text="showAdminGate ? 'Tutup' : 'Buka Form'"
                        >
                            Buka Form
                        </button>
                    </div>

                    <div x-show="showAdminGate" x-cloak class="mt-3 border-t border-stone-line pt-3">
                        <form method="POST" action="{{ route('auth.admin') }}" class="space-y-3 text-xs">
                            @csrf
                            <div>
                                <label for="admin_email" class="mb-1 block font-semibold text-charcoal">Email Admin</label>
                                <input id="admin_email" type="text" name="email" value="admin" required class="editorial-input !py-1 !text-xs">
                            </div>
                            <div>
                                <label for="admin_password" class="mb-1 block font-semibold text-charcoal">Password Admin</label>
                                <input id="admin_password" type="password" name="password" value="admin" required class="editorial-input !py-1 !text-xs">
                            </div>
                            <button type="submit" class="editorial-btn-primary w-full !py-2 !text-xs">
                                Masuk Konsol Admin
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
