<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atelier Vow - Perencanaan Pernikahan Nusantara</title>
    <meta name="description" content="Perencanaan pernikahan Indonesia: 29 pos anggaran hasil riset, daftar 124 tugas, linimasa, vendor, dan panduan cetak A4. Gratis.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:bg-espresso focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-alabaster">
        Lewati ke konten utama
    </a>

    <header class="sticky top-0 z-30 border-b border-stone-line bg-alabaster/95 backdrop-blur-md">
        <div class="mx-auto flex max-w-5xl items-baseline justify-between gap-6 px-6 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-baseline gap-2.5">
                <span class="text-base font-bold tracking-tight">Atelier Vow</span>
                <span class="text-xs text-taupe">Nusantara</span>
            </a>

            <div class="flex items-baseline gap-6">
                <a href="#fitur" class="hidden text-sm font-semibold text-taupe hover:text-espresso sm:inline">Isi</a>
                <a href="#cara-kerja" class="hidden text-sm font-semibold text-taupe hover:text-espresso sm:inline">Cara kerja</a>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-espresso underline decoration-stone-strong underline-offset-4 hover:decoration-espresso">
                    Masuk
                </a>
            </div>
        </div>
    </header>

    <main id="konten" class="mx-auto max-w-5xl px-6 lg:px-8">
        {{-- Pembuka --}}
        <section class="border-b border-stone-line py-14 lg:py-20">
            <h1 class="max-w-3xl text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl">
                Rencana pernikahan yang jelas, dari plafon sampai hari-H.
            </h1>
            <p class="mt-6 max-w-2xl text-base leading-relaxed text-taupe sm:text-lg">
                Anggaran, daftar tugas, linimasa, dan vendor disusun dari riset biaya pernikahan Indonesia.
                Semua modul memakai satu profil pernikahan, jadi angkanya selalu sinkron.
            </p>

            <div class="mt-9 flex flex-wrap items-baseline gap-8">
                <a href="{{ route('login') }}" class="editorial-btn-primary">Mulai gratis</a>
                <a href="#fitur" class="text-sm font-semibold text-espresso underline decoration-stone-strong underline-offset-4 hover:decoration-espresso">
                    Lihat isinya
                </a>
            </div>

            <dl class="mt-12 grid max-w-xl grid-cols-3 gap-6 border-t border-stone-line pt-6">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-taupe">Pos anggaran</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums">29</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-taupe">Tugas</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums">124</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-taupe">Adat Nusantara</dt>
                    <dd class="mt-1 text-2xl font-bold tabular-nums">4</dd>
                </div>
            </dl>
        </section>

        {{-- Isi --}}
        <section id="fitur" class="border-b border-stone-line py-14">
            <h2 class="max-w-2xl text-2xl font-bold tracking-tight sm:text-3xl">Enam hal yang biasanya bikin panik.</h2>
            <dl class="mt-8 divide-y divide-stone-line/70">
                @php
                    $features = [
                        ['Anggaran realistis', '29 pos biaya hasil riset: sewa gedung, catering per pax, dekorasi, sampai dana darurat 5%.'],
                        ['Daftar 124 tugas', 'Sepuluh tahap persiapan, tenggat menyesuaikan sisa waktu menuju hari-H, dengan prioritas dan PIC.'],
                        ['Vendor & termin', 'Kontrak, kontak PIC, rekening, dan pembayaran bertahap dalam satu daftar.'],
                        ['Linimasa fleksibel', 'Fase dan tenggat tersebar dari hari ini sampai hari-H, plus rundown menit demi menit.'],
                        ['Panduan cetak A4', 'Ringkasan rapi untuk keluarga dan panitia, bisa dibaca tanpa internet.'],
                        ['Adat Nusantara', 'Tambahan tugas untuk adat Jawa, Sunda, Minang, atau Batak sesuai pilihan saat mulai.'],
                    ];
                @endphp

                @foreach($features as [$title, $body])
                    <div class="grid gap-1 py-4 sm:grid-cols-[minmax(0,14rem)_1fr] sm:gap-8">
                        <dt class="text-sm font-bold text-espresso">{{ $title }}</dt>
                        <dd class="text-sm leading-relaxed text-taupe">{{ $body }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        {{-- Cara kerja --}}
        <section id="cara-kerja" class="border-b border-stone-line py-14">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Mulai dari tiga isian.</h2>
            <p class="mt-3 max-w-2xl text-base text-taupe">
                Anggaran dan daftar tugas dibuat otomatis dari data itu, dan semuanya masih bisa diubah.
            </p>

            <ol class="mt-8 divide-y divide-stone-line/70">
                @php
                    $steps = [
                        ['Isi profil pernikahan', 'Nama pasangan, tanggal akad dan resepsi, kota, jumlah tamu, plafon anggaran, dan adat.'],
                        ['Terima anggaran & tugas', 'Sistem menyusun 29 pos biaya dan 124 tugas dengan tenggat yang menyesuaikan tanggal acara.'],
                        ['Kerjakan sampai hari-H', 'Centang tugas, catat pembayaran vendor, pantau linimasa, lalu cetak panduan untuk panitia.'],
                    ];
                @endphp

                @foreach($steps as [$title, $body])
                    <li class="grid gap-1 py-4 sm:grid-cols-[minmax(0,14rem)_1fr] sm:gap-8">
                        <p class="text-sm font-bold text-espresso">
                            <span class="mr-2 text-champagne tabular-nums">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            {{ $title }}
                        </p>
                        <p class="text-sm leading-relaxed text-taupe">{{ $body }}</p>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Penutup --}}
        <section class="py-14">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Gratis, dan tetap gratis.</h2>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-taupe">
                Tidak ada paket berbayar, kartu kredit, atau masa uji coba. Satu akun Google untuk satu ruang kerja pernikahan.
            </p>
            <div class="mt-7">
                <a href="{{ route('login') }}" class="editorial-btn-primary">Buat ruang kerja</a>
            </div>
        </section>
    </main>

    <footer class="border-t border-stone-line py-8">
        <div class="mx-auto flex max-w-5xl flex-col items-start justify-between gap-3 px-6 text-sm text-taupe sm:flex-row sm:items-baseline lg:px-8">
            <p>Atelier Vow - perencanaan pernikahan Indonesia.</p>
            <div class="flex items-center gap-6 font-semibold">
                <a href="{{ route('login') }}" class="hover:text-espresso">Masuk</a>
                <a href="#fitur" class="hover:text-espresso">Isi</a>
            </div>
        </div>
    </footer>
</body>
</html>
