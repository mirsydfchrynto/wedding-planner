<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atelier Vow — Wedding Planner Gratis untuk Pernikahan Nusantara</title>
    <meta name="description" content="Wedding planner gratis: anggaran 29 pos riset, checklist 124 tugas, linimasa, vendor, dan cetak panduan A4. Tanpa langganan.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-alabaster text-espresso antialiased selection:bg-champagne/20 selection:text-espresso">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded focus:bg-espresso focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-alabaster">
        Lewati ke konten utama
    </a>

    <header class="sticky top-0 z-30 border-b border-stone-line bg-pearl/95 backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-6 px-6 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded bg-espresso text-sm font-bold text-alabaster">AV</span>
                <span class="text-base font-bold tracking-tight">Atelier Vow</span>
            </a>

            <div class="flex items-center gap-4">
                <a href="#fitur" class="hidden text-sm font-semibold text-taupe hover:text-espresso sm:inline">Fitur</a>
                <a href="#cara-kerja" class="hidden text-sm font-semibold text-taupe hover:text-espresso sm:inline">Cara kerja</a>
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-espresso px-4 py-2 text-sm font-semibold text-alabaster transition hover:bg-champagne"
                >
                    <span>Masuk</span>
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>
        </div>
    </header>

    <main id="konten">
        {{-- Hero --}}
        <section class="mx-auto max-w-6xl px-6 pt-14 pb-12 lg:px-8 lg:pt-20">
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full border border-stone-line bg-pearl px-3 py-1 text-xs font-semibold text-taupe">
                        <span class="h-1.5 w-1.5 rounded-full bg-sage"></span>
                        Gratis, tanpa langganan
                    </p>

                    <h1 class="mt-5 text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl">
                        Rencanakan pernikahan tanpa spreadsheet.
                    </h1>

                    <p class="mt-5 max-w-xl text-base leading-relaxed text-taupe sm:text-lg">
                        Atelier Vow menyusun anggaran, checklist, linimasa, dan vendor dalam satu alur.
                        Isinya mengikuti riset biaya pernikahan Indonesia, bukan tebakan.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-espresso px-6 py-3 text-sm font-semibold text-alabaster transition hover:bg-champagne"
                        >
                            <span>Mulai gratis</span>
                            <x-ui.icon name="arrow-right" class="h-4 w-4" />
                        </a>
                        <a
                            href="#fitur"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-stone-strong bg-pearl px-6 py-3 text-sm font-semibold text-espresso transition hover:bg-ivory"
                        >
                            Lihat isinya
                        </a>
                    </div>

                    <dl class="mt-10 grid max-w-lg grid-cols-3 gap-4 border-t border-stone-line pt-6">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-taupe">Pos anggaran</dt>
                            <dd class="mt-1 text-2xl font-bold tabular-nums">29</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-taupe">Tugas</dt>
                            <dd class="mt-1 text-2xl font-bold tabular-nums">124</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-taupe">Adat Nusantara</dt>
                            <dd class="mt-1 text-2xl font-bold tabular-nums">4</dd>
                        </div>
                    </dl>
                </div>

                {{-- Product preview --}}
                <div class="editorial-card overflow-hidden">
                    <div class="flex items-center justify-between border-b border-stone-line bg-ivory px-5 py-3">
                        <span class="text-xs font-bold uppercase tracking-wide text-taupe">Dashboard pengantin</span>
                        <span class="rounded-md bg-espresso px-2 py-0.5 text-xs font-bold text-alabaster tabular-nums">138 hari</span>
                    </div>

                    <div class="space-y-5 p-5">
                        <div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs font-semibold text-taupe">Plafon anggaran</span>
                                <span class="text-sm font-bold tabular-nums">Rp 150.000.000</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-ivory">
                                <div class="h-full w-[42%] rounded-full bg-champagne"></div>
                            </div>
                            <p class="mt-1.5 text-xs text-taupe">Terpakai Rp 63.000.000 · sisa Rp 87.000.000</p>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between rounded-lg border border-stone-line px-3 py-2">
                                <span class="text-sm font-medium">Paket catering</span>
                                <span class="text-sm font-semibold tabular-nums">Rp 30.000.000</span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg border border-stone-line px-3 py-2">
                                <span class="text-sm font-medium">Gedung/ballroom</span>
                                <span class="text-sm font-semibold tabular-nums">Rp 20.000.000</span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg border border-stone-line px-3 py-2">
                                <span class="text-sm font-medium">Dekorasi pelaminan</span>
                                <span class="text-sm font-semibold tabular-nums">Rp 5.000.000</span>
                            </div>
                        </div>

                        <div class="rounded-lg bg-sage-bg px-3 py-2.5">
                            <p class="text-xs font-semibold text-sage">Checklist fase berjalan</p>
                            <p class="mt-1 text-sm font-medium text-sage">6 dari 124 tugas selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="fitur" class="border-t border-stone-line bg-pearl py-16">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Enam hal yang biasanya bikin panik, sudah beres di sini.</h2>
                    <p class="mt-3 text-base text-taupe">
                        Semua modul saling terhubung dengan profil pernikahan yang sama.
                    </p>
                </div>

                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @php
                        $features = [
                            ['wallet', 'Anggaran realistis', '29 pos biaya hasil riset: sewa gedung, catering per pax, dekorasi, sampai dana darurat 5%.'],
                            ['check-circle', 'Checklist 124 tugas', 'Terbagi 10 tahap, jadwal menyesuaikan sisa waktu menuju hari-H, lengkap dengan prioritas.'],
                            ['building', 'Vendor & termin', 'Catat vendor, kontak PIC, rekening, serta pembayaran bertahap beserta statusnya.'],
                            ['calendar', 'Linimasa fleksibel', 'Fase dan jatuh tempo disebar dari hari ini sampai hari-H, plus rundown menit-demi-menit.'],
                            ['printer', 'Cetak panduan A4', 'Hasil rapi 4 halaman untuk dibagikan ke keluarga dan panitia, tanpa internet.'],
                            ['flower', 'Adat Nusantara', 'Tambahan tugas untuk adat Jawa, Sunda, Minang, atau Batak sesuai pilihan saat mendaftar.'],
                        ];
                    @endphp

                    @foreach($features as [$icon, $title, $body])
                        <article class="rounded-xl border border-stone-line bg-alabaster p-5">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-espresso text-alabaster">
                                <x-ui.icon :name="$icon" class="h-4 w-4" />
                            </span>
                            <h3 class="mt-4 text-base font-bold">{{ $title }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-taupe">{{ $body }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How it works --}}
        <section id="cara-kerja" class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Mulai dari tiga isian.</h2>
                <p class="mt-3 text-base text-taupe">
                    Anggaran dan checklist dibuat otomatis dari data itu. Semuanya masih bisa diubah kapan saja.
                </p>
            </div>

            <ol class="mt-10 grid gap-4 sm:grid-cols-3">
                @php
                    $steps = [
                        ['01', 'Isi profil pernikahan', 'Nama pasangan, tanggal akad/resepsi, kota, jumlah tamu, plafon anggaran, dan adat.'],
                        ['02', 'Dapat anggaran & checklist', 'Sistem menyusun 29 pos biaya dan 124 tugas dengan tenggat yang menyesuaikan tanggal acara.'],
                        ['03', 'Kerjakan sampai hari-H', 'Centang tugas, catat pembayaran vendor, pantau linimasa, lalu cetak panduan untuk panitia.'],
                    ];
                @endphp

                @foreach($steps as [$num, $title, $body])
                    <li class="rounded-xl border border-stone-line bg-pearl p-5">
                        <span class="text-sm font-bold text-champagne tabular-nums">{{ $num }}</span>
                        <h3 class="mt-2 text-base font-bold">{{ $title }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-taupe">{{ $body }}</p>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Free statement --}}
        <section class="border-y border-stone-line bg-espresso py-14 text-alabaster">
            <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 px-6 lg:flex-row lg:items-center lg:px-8">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Gratis, dan tetap gratis.</h2>
                    <p class="mt-2 text-sm leading-relaxed text-alabaster/80 sm:text-base">
                        Tidak ada paket berbayar, kartu kredit, atau masa uji coba. Satu akun Google, satu ruang kerja per pernikahan.
                    </p>
                </div>
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-alabaster px-6 py-3 text-sm font-semibold text-espresso transition hover:bg-ivory"
                >
                    <span>Buat ruang kerja</span>
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>
        </section>
    </main>

    <footer class="bg-alabaster py-10">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-4 px-6 text-sm text-taupe sm:flex-row sm:items-center lg:px-8">
            <p>Atelier Vow — wedding planner gratis untuk pernikahan Indonesia.</p>
            <div class="flex items-center gap-6 font-semibold">
                <a href="{{ route('login') }}" class="hover:text-espresso">Masuk</a>
                <a href="#fitur" class="hover:text-espresso">Fitur</a>
            </div>
        </div>
    </footer>
</body>
</html>
