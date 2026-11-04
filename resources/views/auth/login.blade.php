@extends('layouts.app', ['title' => 'Masuk ke Ruang Kerja Pernikahan'])

@section('content')
    <div class="mx-auto max-w-lg py-6" x-data="{ showAdminGate: false }">
        {{-- Primary Card: Customer Google Sign-In --}}
        <section class="editorial-card batik-kawung-bg rounded-lg p-6 sm:p-8">
            <h1 class="font-editorial text-3xl font-semibold text-espresso sm:text-4xl">
                Masuk ke Portal Calon Pengantin
            </h1>
            <p class="mt-2 text-sm leading-relaxed text-charcoal">
                Gunakan akun Google Anda untuk mengakses buku induk persiapan pernikahan: Dashboard, Anggaran 8 Kategori, Checklist Tugas, Linimasa H-12 Bulan, dan Direktori Vendor.
            </p>

            <form method="POST" action="{{ route('auth.google') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="google_email" class="mb-1 block text-xs font-semibold text-charcoal">
                        Alamat Email Akun Google Pasangan
                    </label>
                    <input
                        id="google_email"
                        type="email"
                        name="google_email"
                        value="aurelia.reza@gmail.com"
                        required
                        class="editorial-input"
                    >
                </div>

                <div>
                    <label for="google_name" class="mb-1 block text-xs font-semibold text-charcoal">
                        Nama Pasangan
                    </label>
                    <input
                        id="google_name"
                        type="text"
                        name="google_name"
                        value="Aurelia Larasati & Reza Mahendra"
                        required
                        class="editorial-input"
                    >
                </div>

                <button type="submit" class="editorial-btn-primary w-full py-3">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor" d="M21.35 11.1h-9.17v2.73h6.51c-.33 3.81-3.5 5.44-6.5 5.44C8.36 19.27 5 15.65 5 12c0-3.65 3.36-7.27 7.2-7.27 3.09 0 4.9 1.97 4.9 1.97L19 4.72S16.56 2 12.1 2C6.42 2 2.03 6.8 2.03 12c0 5.05 4.13 10 10.22 10 5.35 0 9.25-3.67 9.25-9.09 0-1.15-.15-1.81-.15-1.81Z"/>
                    </svg>
                    <span>Lanjutkan dengan Akun Google</span>
                </button>
            </form>

            <div class="mt-5 border-t border-stone-line pt-4 text-xs text-taupe">
                <div class="flex items-center justify-between">
                    <span>Belum memilih paket layanan?</span>
                    <a href="{{ route('saas.landing') }}" class="font-semibold text-champagne hover:underline">
                        Kembali ke Halaman Utama SaaS
                    </a>
                </div>
            </div>
        </section>

        {{-- Discrete Private Admin Gate Toggle --}}
        <div class="mt-6 text-center">
            <button
                type="button"
                @click="showAdminGate = !showAdminGate"
                class="text-xs font-medium text-taupe hover:text-espresso underline cursor-pointer"
            >
                Otorisasi Privat Administrator &amp; Lead WO
            </button>
        </div>

        {{-- Hidden Private Admin Credentials Form --}}
        <section
            x-show="showAdminGate"
            x-cloak
            class="mt-4 editorial-card rounded-lg border-stone-strong bg-pearl p-6"
        >
            <div class="flex items-center justify-between border-b border-stone-line pb-3">
                <h2 class="font-editorial text-2xl font-semibold text-espresso">Gerbang Privat Konsol Admin</h2>
                <x-ui.status-badge tone="wine" label="Akses Terbatas" />
            </div>
            <p class="mt-2 text-xs text-taupe">
                Halaman ini tersembunyi dari navigasi pelanggan. Masukkan kredensial Lead Administrator untuk membuka Konsol Operasional WO &amp; Matriks 12-Factor.
            </p>

            <form method="POST" action="{{ route('auth.admin') }}" class="mt-4 space-y-4">
                @csrf

                <div>
                    <label for="admin_email" class="mb-1 block text-xs font-semibold text-charcoal">Email Administrator</label>
                    <input
                        id="admin_email"
                        type="email"
                        name="email"
                        value="irsyad@ateliervow.id"
                        required
                        class="editorial-input"
                    >
                </div>

                <div>
                    <label for="admin_password" class="mb-1 block text-xs font-semibold text-charcoal">Kunci Otorisasi Admin</label>
                    <input
                        id="admin_password"
                        type="password"
                        name="password"
                        value="AtelierAdmin2026!"
                        required
                        class="editorial-input"
                    >
                </div>

                <button type="submit" class="editorial-btn-secondary w-full">
                    Buka Konsol Admin Privat
                </button>
            </form>
        </section>
    </div>
@endsection
