@extends('layouts.app', ['title' => 'Masuk'])

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center py-10 px-2 sm:px-0">
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-7 sm:p-9 shadow-sm space-y-6">
            <div>
                <p class="text-xs font-semibold text-[#8C5835]">Portal Pengantin</p>
                <h1 class="mt-3 text-2xl font-semibold text-[#2B2520] tracking-tight">
                    Masuk ke Buku Kerja
                </h1>
                <p class="mt-2 text-xs text-[#6E665E] leading-relaxed">
                    Satu ruang kerja terpadu untuk pagu anggaran, catatan tugas adat, susunan acara hari-H, dan kwitansi vendor Anda.
                </p>
            </div>

            @if(config('services.google.client_id'))
                <a
                    href="{{ route('auth.google.redirect') }}"
                    class="inline-flex items-center justify-center gap-2.5 w-full rounded-lg bg-[#2B2520] px-4 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-[#1C1815] cursor-pointer"
                >
                    <x-ui.google-icon class="h-4 w-4 shrink-0" />
                    <span>Masuk dengan Akun Google</span>
                </a>
            @endif

            <form method="POST" action="{{ route('auth.google') }}" class="space-y-3 pt-4 border-t border-[#F0ECE4]">
                @csrf
                <input type="hidden" name="google_email" value="aurelia.reza@gmail.com">
                <input type="hidden" name="google_name" value="Aurelia Larasati & Reza Mahendra">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2.5 w-full rounded-lg {{ config('services.google.client_id') ? 'border border-[#E7E4DF] bg-white text-[#2B2520] hover:bg-[#F5F2EB]' : 'bg-[#2B2520] text-white hover:bg-[#1C1815]' }} px-4 py-2.5 text-sm font-semibold shadow-xs transition cursor-pointer"
                >
                    <x-ui.google-icon class="h-4 w-4 shrink-0" />
                    <span>Akses Demo Instan (Aurelia &amp; Reza)</span>
                </button>
            </form>

            <div class="relative flex items-center justify-center my-3">
                <div class="border-t border-[#E7E4DF] w-full"></div>
                <span class="bg-white px-2.5 text-[11px] font-medium text-[#6E665E] uppercase tracking-wider relative">atau buat akun pribadi baru</span>
            </div>

            <form method="POST" action="{{ route('auth.google') }}" class="space-y-3">
                @csrf
                <div>
                    <label for="google_email" class="mb-1 block text-xs font-medium text-[#2B2520]">Email Google Anda</label>
                    <input id="google_email" type="email" name="google_email" required placeholder="nama@gmail.com" class="editorial-input">
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 w-full rounded-lg border border-[#E7E4DF] bg-white px-4 py-2 text-xs font-semibold text-[#2B2520] hover:bg-[#F5F2EB] transition cursor-pointer"
                >
                    <span>Masuk &amp; Buka Buku Kerja Baru &rarr;</span>
                </button>
            </form>

            <div class="flex items-center justify-between text-xs text-[#6E665E] pt-2">
                <a href="{{ route('home') }}" class="hover:text-[#2B2520] transition">&larr; Kembali ke beranda</a>
                <span class="text-xs font-semibold text-[#2E5E3E]">Gratis</span>
            </div>
        </div>
    </div>
@endsection
