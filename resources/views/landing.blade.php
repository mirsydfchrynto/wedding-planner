<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tatanikah — Buku Catatan &amp; Perencanaan Pernikahan Indonesia</title>
    <meta name="description" content="Rencana pernikahan yang jelas, dari batas anggaran sampai hari pernikahan. Gratis, dan tetap gratis.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=JetBrains+Mono:wght@400;600;800&family=Kalam:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-desk text-ink antialiased selection:bg-kunyit selection:text-ink">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:border-2 focus:border-ink focus:bg-soga focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-paper focus:shadow-[3px_3px_0px_#2B2118]">
        Lewati ke konten utama
    </a>

    {{-- Header Sederhana Neo-Brutalist --}}
    <header class="border-b-2 border-ink bg-paper-2 sticky top-0 z-30">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
            <a href="{{ route('home') }}" class="group flex items-baseline gap-2">
                <span class="font-display text-2xl font-black tracking-tight text-soga group-hover:text-soga-dark">Tatanikah</span>
                <span class="stamp !bg-kunyit hidden sm:inline-flex">Buku Catatan Digital</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="btn-neo !min-h-0 !py-1 !px-3.5 !text-xs font-bold">
                    Masuk Akun Demo
                </a>
            </div>
        </div>
    </header>

    <main id="konten" class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:py-12 space-y-10">

        {{-- SEKSI 1: HERO 3-DETIK (PUNCHY HEADLINE + MASKOT SVG ASISTEN) --}}
        <section class="bento-card bento-kunyit p-6 sm:p-10 relative overflow-hidden">
            <div class="tape"></div>
            <div class="flex flex-col md:flex-row items-center gap-8 justify-between">
                <div class="max-w-2xl space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="stamp !bg-paper font-black">Zero Cognitive Load</span>
                        <span class="text-xs font-black uppercase tracking-wider text-ink">Buku Panduan Pengantin Indonesia</span>
                    </div>

                    <h1 class="font-display text-4xl sm:text-6xl font-black leading-[1.05] text-ink">
                        Mau Nikah Tapi Bingung? Kami Urus Semuanya.
                    </h1>

                    <p class="text-xs sm:text-sm text-ink/90 leading-relaxed font-semibold">
                        Rencana pernikahan yang jelas, dari batas anggaran sampai hari pernikahan. Tatanikah hadir seperti buku catatan pernikahan fisik: pagu anggaran riset, kontrol tenggat mundur tugas, kwitansi vendor, dan susunan acara menit demi menit. Gratis, dan tetap gratis.
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ route('login') }}" class="btn-neo !bg-ink !text-paper !py-3 !px-6 !text-sm font-black shadow-[4px_4px_0px_#2B2118]">
                            Mulai Sekarang — Buka Buku Kerja &rarr;
                        </a>
                        <span class="text-xs font-bold text-ink">Demo tanpa daftar: masuk 1-klik dengan akun contoh.</span>
                    </div>
                </div>

                {{-- Maskot Asisten Kuncup Melati --}}
                <div class="shrink-0 text-center">
                    <div class="bento-card bento-paper p-4 inline-block">
                        <svg class="w-24 h-24 mx-auto" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="56" height="56" rx="2" fill="#FAF5EC" stroke="#2B2118" stroke-width="3"/>
                            <path d="M32 14C24 22 18 32 20 44C26 48 38 48 44 44C46 32 40 22 32 14Z" fill="#CDE7D8" stroke="#2B2118" stroke-width="2.5"/>
                            <circle cx="27" cy="34" r="2.5" fill="#2B2118"/>
                            <circle cx="37" cy="34" r="2.5" fill="#2B2118"/>
                            <path d="M29 39C31 41 33 41 35 39" stroke="#2B2118" stroke-width="2" stroke-linecap="round"/>
                            <path d="M32 8L32 14" stroke="#7A4425" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="32" cy="7" r="2.5" fill="#F6D365" stroke="#2B2118" stroke-width="1.5"/>
                        </svg>
                        <p class="hand font-bold text-ink text-sm mt-1">Mas Pandu</p>
                        <span class="stamp !bg-mint !text-[0.6rem] !py-0">Asisten Pribadi</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- SEKSI 2: BENTO GRID 4 KOTAK INTERAKTIF (COBA LANGSUNG DI LAYAR) --}}
        <section class="space-y-4">
            <div class="text-center">
                <span class="stamp !bg-mint">Coba Sendiri</span>
                <h2 class="font-display text-3xl font-extrabold text-ink mt-1">
                    Coba Langsung di Layar
                </h2>
                <p class="text-xs text-taupe font-semibold">Klik untuk mencoba simulasi anggaran, adat, dan checklist.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- BENTO A (Span 7 Kolom — Simulasi Interaktif Anggaran & Centang) --}}
                <div
                    class="bento-card bento-paper md:col-span-7 p-5 flex flex-col justify-between"
                    x-data="{
                        budget: 100000000,
                        task1: true,
                        task2: false,
                        task3: false,
                        formatRupiah(num) {
                            return 'Rp ' + Number(num).toLocaleString('id-ID');
                        }
                    }"
                >
                    <div>
                        <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                            <span class="font-display text-lg font-black text-ink">Simulasi Cepat Kas &amp; Tugas</span>
                            <span class="stamp !bg-kunyit">Live Demo</span>
                        </div>

                        {{-- Toggle Pilihan Budget --}}
                        <div class="mt-3">
                            <span class="text-[0.7rem] font-bold text-taupe block mb-1.5 uppercase tracking-wider">
                                Klik Pilihan Pagu Anggaran:
                            </span>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    @click="budget = 50000000"
                                    class="chip !min-h-0 !py-1 !px-2.5 !text-xs cursor-pointer font-bold"
                                    :class="budget === 50000000 ? 'chip-active' : ''"
                                >
                                    Rp 50 Juta
                                </button>
                                <button
                                    type="button"
                                    @click="budget = 100000000"
                                    class="chip !min-h-0 !py-1 !px-2.5 !text-xs cursor-pointer font-bold"
                                    :class="budget === 100000000 ? 'chip-active' : ''"
                                >
                                    Rp 100 Juta
                                </button>
                                <button
                                    type="button"
                                    @click="budget = 200000000"
                                    class="chip !min-h-0 !py-1 !px-2.5 !text-xs cursor-pointer font-bold"
                                    :class="budget === 200000000 ? 'chip-active' : ''"
                                >
                                    Rp 200 Juta
                                </button>
                            </div>
                        </div>

                        {{-- Auto-Split Proporsional Otomatis --}}
                        <div class="mt-3 bento-card bento-paper2 p-3 border-2 border-ink text-xs space-y-1">
                            <span class="text-[0.65rem] font-black uppercase text-soga block">Hasil Auto-Split Anggaran:</span>
                            <div class="flex justify-between font-bold">
                                <span>Katering Jamuan (30%):</span>
                                <span class="rupiah text-forest" x-text="formatRupiah(budget * 0.3)"></span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span>Venue Gedung (15%):</span>
                                <span class="rupiah text-soga" x-text="formatRupiah(budget * 0.15)"></span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span>Dekorasi &amp; Bunga (10%):</span>
                                <span class="rupiah text-ink" x-text="formatRupiah(budget * 0.10)"></span>
                            </div>
                        </div>

                        {{-- Simulasi 3 Centang Cepat --}}
                        <div class="mt-3 space-y-1.5 text-xs">
                            <span class="text-[0.68rem] font-bold text-taupe block uppercase tracking-wider">Coba Centang Kebutuhan:</span>
                            <label class="flex items-center gap-2 p-1.5 border-2 border-ink bg-paper cursor-pointer" :class="task1 ? 'checked-row' : ''">
                                <input type="checkbox" x-model="task1" class="h-4 w-4 accent-forest">
                                <span class="font-bold" :class="task1 ? 'line-through text-taupe' : 'text-ink'">Surat N1–N4 &amp; Pendaftaran KUA</span>
                            </label>
                            <label class="flex items-center gap-2 p-1.5 border-2 border-ink bg-paper cursor-pointer" :class="task2 ? 'checked-row' : ''">
                                <input type="checkbox" x-model="task2" class="h-4 w-4 accent-forest">
                                <span class="font-bold" :class="task2 ? 'line-through text-taupe' : 'text-ink'">Food Tasting Katering Bersama Orang Tua</span>
                            </label>
                            <label class="flex items-center gap-2 p-1.5 border-2 border-ink bg-paper cursor-pointer" :class="task3 ? 'checked-row' : ''">
                                <input type="checkbox" x-model="task3" class="h-4 w-4 accent-forest">
                                <span class="font-bold" :class="task3 ? 'line-through text-taupe' : 'text-ink'">Technical Meeting Seluruh Vendor di Venue</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- BENTO B (Span 5 Kolom — Mode Adat Nusantara Interaktif) --}}
                <div
                    class="bento-card bento-mint md:col-span-5 p-5 flex flex-col justify-between"
                    x-data="{
                        tradition: 'jawa',
                        data: {
                            'jawa': ['Siraman & Pembersihan Lahir Batin', 'Malam Midodareni', 'Upacara Panggih & Balangan Suruh', 'Sungkeman & Dulangan'],
                            'sunda': ['Ngeuyeuk Seureuh', 'Saweran & Meuleum Harupat', 'Huap Lingkung', 'Nincak Endog'],
                            'minang': ['Batimbang Tando', 'Malam Bainai', 'Akad & Manjapuik Marapulai', 'Basandiang di Rumah Gadang'],
                            'umum': ['Ijab Kabul / Pemberkatan Khidmat', 'Sesi Foto Keluarga Inti', 'Toast & Sambutan Mempelai', 'Gala Dinner & Resepsi']
                        }
                    }"
                >
                    <div>
                        <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                            <span class="font-display text-lg font-black text-ink">Tradisi Nusantara</span>
                            <span class="stamp !bg-paper">Adat &amp; Prosesi</span>
                        </div>

                        <div class="mt-3">
                            <span class="text-[0.68rem] font-bold text-taupe block mb-1 uppercase tracking-wider">Pilih Adat:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" @click="tradition = 'jawa'" class="chip !min-h-0 !py-1 !px-2 !text-xs font-bold" :class="tradition === 'jawa' ? 'chip-active' : ''">Jawa</button>
                                <button type="button" @click="tradition = 'sunda'" class="chip !min-h-0 !py-1 !px-2 !text-xs font-bold" :class="tradition === 'sunda' ? 'chip-active' : ''">Sunda</button>
                                <button type="button" @click="tradition = 'minang'" class="chip !min-h-0 !py-1 !px-2 !text-xs font-bold" :class="tradition === 'minang' ? 'chip-active' : ''">Minang</button>
                                <button type="button" @click="tradition = 'umum'" class="chip !min-h-0 !py-1 !px-2 !text-xs font-bold" :class="tradition === 'umum' ? 'chip-active' : ''">Umum / Modern</button>
                            </div>
                        </div>

                        {{-- Daftar Prosesi Terpilih --}}
                        <div class="mt-3 bento-card bento-paper p-3 border-2 border-ink space-y-1.5 text-xs">
                            <span class="text-[0.65rem] font-black uppercase text-soga block">Prosesi Otomatis Masuk Checklist:</span>
                            <template x-for="(item, idx) in data[tradition]" :key="idx">
                                <div class="flex items-center gap-2 font-bold text-ink">
                                    <span class="h-1.5 w-1.5 bg-soga shrink-0" aria-hidden="true"></span>
                                    <span x-text="item"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <p class="text-[0.68rem] text-taupe font-semibold mt-3 pt-2 border-t border-ink/30">
                        Memastikan tidak ada ritual sakral yang terlewatkan.
                    </p>
                </div>

                {{-- BENTO C (Span 6 Kolom — Kontrol Hemat Otomatis) --}}
                <div class="bento-card bento-terracotta md:col-span-6 p-5">
                    <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                        <span class="font-display text-lg font-black text-ink">Pengendali Hemat</span>
                        <span class="stamp !bg-paper !border-forest !text-forest">Anggaran Terkendali</span>
                    </div>

                    <div class="mt-3 space-y-2 text-xs">
                        <p class="font-bold text-ink">
                            Sistem mendeteksi jika ada penawaran vendor yang melampaui alokasi pos.
                        </p>
                        <div class="bento-card bento-paper p-3 border-2 border-ink space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold">Katering Deal: Rp 65 Juta</span>
                                <span class="stamp !bg-terracotta-light !border-wine !text-wine !py-0 !text-[0.6rem]">Lewat Rp 5 Jt</span>
                            </div>
                            <p class="text-[0.7rem] text-taupe font-semibold pt-1 border-t border-ink/20">
                                <x-ui.icon name="lightbulb" class="h-3.5 w-3.5 text-soga inline-block mr-1 align-text-bottom" /><strong>Saran Hemat Otomatis:</strong> Kurangi pos suvenir dari Rp 10 Jt ke Rp 5 Jt untuk menjaga total anggaran tetap aman.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BENTO D (Span 6 Kolom — Siap Cetak A4 & WhatsApp Keluarga) --}}
                <div class="bento-card bento-paper md:col-span-6 p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between border-b-2 border-ink pb-2">
                            <span class="font-display text-lg font-black text-ink">Kolaborasi Keluarga</span>
                            <span class="stamp !bg-kunyit">Mode Ramah Ortu</span>
                        </div>

                        <div class="mt-3 text-xs space-y-2">
                            <p class="font-bold text-ink">
                                Mudah dibagikan ke orang tua dan panitia keluarga tanpa mereka harus paham aplikasi rumit.
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-center font-bold">
                                <div class="bento-card bento-paper2 p-2 border-2 border-ink">
                                    <span class="flex items-center justify-center gap-1.5 text-sm"><x-ui.icon name="printer" class="h-4 w-4 text-soga" /> Format A4</span>
                                    <span class="text-[0.65rem] text-taupe block mt-0.5">Huruf besar, kontras tinggi</span>
                                </div>
                                <div class="bento-card bento-mint p-2 border-2 border-ink">
                                    <span class="flex items-center justify-center gap-1.5 text-sm"><x-ui.icon name="phone" class="h-4 w-4 text-forest" /> WA 1-Klik</span>
                                    <span class="text-[0.65rem] text-taupe block mt-0.5">Rangkuman siap kirim</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t-2 border-ink">
                        <a href="{{ route('login') }}" class="btn-neo-wa w-full !py-1.5 !text-xs text-center font-black">
                            Coba Bagikan ke WA &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </section>

        {{-- SEKSI 3: CTA PENUTUP --}}
        <section class="bento-card bento-paper2 p-8 sm:p-12 text-center border-2 border-ink relative">
            <div class="tape tape-right"></div>
            <span class="stamp !bg-kunyit font-black">Langkah Mudah</span>
            <h2 class="font-display text-3xl sm:text-5xl font-black text-ink mt-2">
                Persiapkan Pernikahan Anda dengan Tenang.
            </h2>
            <p class="text-xs sm:text-sm text-taupe font-semibold max-w-lg mx-auto mt-2">
                Tanpa biaya langganan, tanpa instalasi aplikasi berat. Buka langsung di browser ponsel Anda.
            </p>
            <div class="mt-6 flex justify-center">
                <a href="{{ route('login') }}" class="btn-neo !bg-ink !text-paper !py-3 !px-8 !text-base font-black shadow-[4px_4px_0px_#2B2118]">
                    Buka Buku Kerja Sekarang &rarr;
                </a>
            </div>
        </section>

    </main>

    <footer class="border-t-2 border-ink bg-paper-2 py-6 text-center text-xs text-taupe font-semibold">
        <p class="font-display text-base font-bold text-soga">Tatanikah</p>
        <p class="mt-0.5">Buku catatan &amp; tata laksana pernikahan tradisi Indonesia.</p>
    </footer>

    @stack('scripts')
</body>
</html>
