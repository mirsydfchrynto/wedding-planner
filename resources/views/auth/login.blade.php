@extends('layouts.app', ['title' => 'Login'])

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center py-10 px-2 sm:px-0">
        <div class="rounded-xl border border-[#E7E4DF] bg-white p-7 sm:p-9 shadow-sm space-y-6">
            <div>
                <p class="text-xs font-semibold text-[#8C5835]">Portal Pengantin</p>
                <h1 class="mt-3 text-2xl font-semibold text-[#2B2520] tracking-tight">
                    Login Pengantin
                </h1>
                <p class="mt-2 text-xs text-[#6E665E] leading-relaxed">
                    Satu ruang kerja terpadu untuk total anggaran, catatan tugas persiapan, dan kwitansi vendor Anda.
                </p>
            </div>

            <div class="space-y-4 pt-4 border-t border-[#F0ECE4]">
                <a
                    href="{{ route('auth.google.redirect') }}"
                    class="inline-flex items-center justify-center gap-2.5 w-full rounded-xl bg-[#2B2520] px-4 py-3 text-sm font-semibold text-white shadow-xs transition hover:bg-[#1C1815] cursor-pointer"
                >
                    <x-ui.google-icon class="h-4 w-4 shrink-0" />
                    <span>Login dengan Akun Google</span>
                </a>

                <p class="text-center text-[11px] text-[#6E665E] leading-relaxed">
                    Login aman menggunakan akun Google Anda. Pengguna baru otomatis dibuatkan buku kerja perencanaan pernikahan.
                </p>
                @if (config('services.google.demo_login'))
                    <form method="POST" action="{{ route('auth.google') }}" class="pt-1">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 w-full rounded-xl border border-[#D5CDBE] bg-[#F7F5F0] px-4 py-2.5 text-xs font-semibold text-[#5C5346] shadow-xs transition hover:bg-[#EFECE4] cursor-pointer"
                        >
                            <span>Masuk Cepat Mode Demo (Presentasi) &rarr;</span>
                        </button>
                    </form>
                @endif
            </div>

            <div class="flex items-center justify-between text-xs text-[#6E665E] pt-2">
                <a href="{{ route('home') }}" class="hover:text-[#2B2520] transition">&larr; Kembali ke beranda</a>
                <span class="text-xs font-semibold text-[#2E5E3E]">Gratis</span>
            </div>
        </div>
    </div>
@endsection
