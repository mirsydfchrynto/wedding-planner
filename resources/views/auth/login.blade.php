@extends('layouts.app', ['title' => 'Masuk'])

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center py-10 px-2 sm:px-0">
        <div class="bento-card bento-paper p-6 sm:p-8 relative border-2 border-ink shadow-[4px_4px_0px_#2B2118]">
            <div class="tape"></div>
            
            <div>
                <span class="stamp !bg-kunyit font-black">Portal Pengantin</span>
                <h1 class="mt-3 font-display text-3xl font-black text-ink sm:text-4xl">Masuk ke Buku Kerja</h1>
                <p class="mt-2 text-xs font-semibold text-taupe leading-relaxed">
                    Satu akun untuk pagu anggaran, catatan tugas adat, susunan acara menit ke menit, dan kwitansi vendor Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('auth.google') }}" class="mt-6 pt-5 border-t-2 border-ink">
                @csrf
                <input type="hidden" name="google_email" value="aurelia.reza@gmail.com">
                <input type="hidden" name="google_name" value="Aurelia Larasati & Reza Mahendra">
                <button
                    type="submit"
                    class="btn-neo !bg-ink !text-paper !py-3 !px-4 !text-sm font-bold flex items-center justify-center gap-2.5 w-full cursor-pointer hover:!bg-soga shadow-[3px_3px_0px_#2B2118]"
                >
                    <x-ui.google-icon class="h-4 w-4 shrink-0" />
                    <span>Lanjutkan dengan Akun Google</span>
                </button>
            </form>

            <div class="mt-4 p-3 border-2 border-ink bg-paper-2 text-xs font-semibold text-taupe space-y-1">
                <p class="text-ink font-bold flex items-center gap-1.5">
                    <x-ui.icon name="check-circle" class="h-3.5 w-3.5 text-forest" />
                    <span>Akun Demo Otomatis</span>
                </p>
                <p>
                    Klik tombol di atas untuk langsung masuk ke ruang kerja contoh pernikahan Aurelia &amp; Reza tanpa perlu registrasi manual.
                </p>
            </div>

            <div class="mt-6 pt-4 border-t border-ink/30 flex items-center justify-between text-xs font-bold text-taupe">
                <a href="{{ route('home') }}" class="hover:text-ink underline underline-offset-4">&larr; Kembali ke sampul</a>
                <span class="stamp !bg-mint !py-0 !text-[0.65rem]">Gratis 100%</span>
            </div>
        </div>
    </div>
@endsection
