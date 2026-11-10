@extends('layouts.app', ['title' => 'Masuk'])

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center py-12">
        <p class="text-sm font-semibold text-champagne">Atelier Vow</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-espresso">Masuk ke ruang kerja</h1>
        <p class="mt-3 text-sm leading-relaxed text-taupe">
            Satu akun untuk anggaran, daftar tugas, linimasa, dan vendor pernikahan Anda.
        </p>

        <form method="POST" action="{{ route('auth.google') }}" class="mt-8 border-t border-stone-line pt-8">
            @csrf
            <input type="hidden" name="google_email" value="aurelia.reza@gmail.com">
            <input type="hidden" name="google_name" value="Aurelia Larasati & Reza Mahendra">
            <button
                type="submit"
                class="flex w-full items-center justify-center gap-2.5 border border-stone-strong bg-pearl px-4 py-3 text-sm font-semibold text-espresso transition hover:border-espresso cursor-pointer"
            >
                <x-ui.google-icon class="h-4 w-4" />
                <span>Lanjutkan dengan akun Google</span>
            </button>
        </form>

        <p class="mt-6 text-xs text-taupe">
            Gratis, tanpa langganan. Data pernikahan hanya bisa dibuka oleh akun Anda.
        </p>

        <a href="{{ route('home') }}" class="mt-10 text-sm font-semibold text-taupe hover:text-espresso">&larr; Kembali ke beranda</a>
    </div>
@endsection
