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
            </div>

            {{-- Discrete Professional Footer --}}
            <div class="flex items-center justify-between text-xs text-taupe px-2">
                <a href="{{ route('home') }}" class="hover:text-espresso transition flex items-center gap-1">
                    <span>&larr; Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>
@endsection
