<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tatanikah — Buku Catatan &amp; Perencanaan Pernikahan Indonesia</title>
    <meta name="description" content="Rencana pernikahan yang jelas, dari batas anggaran sampai hari pernikahan. Gratis, dan tetap gratis.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#2B2520] antialiased selection:bg-[#F4ECE4] selection:text-[#2B2520]">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:border focus:border-[#E7E4DF] focus:bg-[#2B2520] focus:px-4 focus:py-2 focus:text-xs focus:font-semibold focus:text-white focus:rounded-md">
        Lewati ke konten utama
    </a>

    <header class="border-b border-[#E7E4DF] bg-[#FAF8F5]/90 backdrop-blur-md sticky top-0 z-30">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                <span class="text-xl font-bold tracking-tight text-[#2B2520] group-hover:text-[#8C5835] transition">Tatanikah</span>
                <span class="hidden rounded border border-[#E7E4DF] bg-[#F5F2EB] px-2 py-0.5 text-xs font-medium text-[#6E665E] sm:inline-flex">Buku Kerja Digital</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-[#2B2520] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1C1815]">
                    Login
                </a>
            </div>
        </div>
    </header>

    <main id="konten" class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:py-16 space-y-20">

        {{-- 1. HERO UTAMA DENGAN DIAGRAM ALUR VISUAL TERPADU --}}
        <section class="space-y-8">
            <div class="max-w-3xl space-y-4">
                <p class="text-xs font-semibold text-[#8C5835]">Buku Panduan Pengantin Indonesia</p>
                <h1 class="text-3xl sm:text-5xl font-semibold leading-[1.12] text-[#2B2520] tracking-tight">
                    Rencana Pernikahan Terorganisir, Tenang, dan Sederhana.
                </h1>
                <p class="text-sm sm:text-base text-[#6E665E] leading-relaxed max-w-2xl font-normal">
                    Rencana pernikahan yang jelas, dari batas anggaran sampai hari pernikahan. Tatanikah hadir sebagai buku kerja digital pengantin Indonesia: kelola total anggaran, kontrol tenggat mundur checklist, kwitansi pembayaran vendor, dan jadwal persiapan secara terpadu. Gratis, dan tetap gratis. Fleksibel untuk kapan pun Anda memulai, baik 6 bulan lagi, 1 bulan lagi, maupun minggu depan.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2B2520] px-5 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-[#1C1815]">
                        <span>Buka Buku Kerja</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <span class="text-xs text-[#6E665E]">Mulai susun rencana pernikahan Anda secara tenang dan terorganisir.</span>
                </div>
            </div>

            {{-- Visual Diagram: Peta Alur Terpadu Pernikahan --}}
            <div class="rounded-xl border border-[#E7E4DF] bg-white p-5 sm:p-6 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-[#F0ECE4] text-xs">
                    <span class="font-semibold text-[#2B2520]">Peta Koordinasi Satu Pintu</span>
                    <span class="text-[#6E665E]">Semua modul terhubung otomatis</span>
                </div>

                {{-- SVG Visual Flow Diagram --}}
                <div class="pt-4 overflow-x-auto">
                    <svg viewBox="0 0 760 110" class="w-full min-w-[680px] h-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Line connecting all stages -->
                        <line x1="80" y1="40" x2="680" y2="40" stroke="#E7E4DF" stroke-width="2" stroke-dasharray="4 4" />
                        <line x1="80" y1="40" x2="480" y2="40" stroke="#8C5835" stroke-width="2" />

                        <!-- Node 1: Target Anggaran -->
                        <circle cx="80" cy="40" r="16" fill="#FAF8F5" stroke="#8C5835" stroke-width="2" />
                        <text x="80" y="44" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="700" fill="#8C5835">1</text>
                        <text x="80" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="600" fill="#2B2520">Target Anggaran</text>
                        <text x="80" y="90" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" fill="#6E665E">Batas &amp; Alokasi Pos</text>
                        <!-- Node 2: Checklist & Adat -->
                        <circle cx="280" cy="40" r="16" fill="#FAF8F5" stroke="#8C5835" stroke-width="2" />
                        <text x="280" y="44" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="700" fill="#8C5835">2</text>
                        <text x="280" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="600" fill="#2B2520">Tugas &amp; Adat</text>
                        <text x="280" y="90" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" fill="#6E665E">Jadwal Hitung Mundur</text>

                        <!-- Node 3: Kwitansi Vendor -->
                        <circle cx="480" cy="40" r="16" fill="#FAF8F5" stroke="#8C5835" stroke-width="2" />
                        <text x="480" y="44" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="700" fill="#8C5835">3</text>
                        <text x="480" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="600" fill="#2B2520">Kwitansi Vendor</text>
                        <text x="480" y="90" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" fill="#6E665E">DP &amp; Pelunasan</text>

                        <!-- Node 4: Timeline & Kwitansi -->
                        <circle cx="680" cy="40" r="16" fill="#FAF8F5" stroke="#2E5E3E" stroke-width="2" />
                        <text x="680" y="44" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="700" fill="#2E5E3E">4</text>
                        <text x="680" y="74" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="600" fill="#2E5E3E">Timeline &amp; Kwitansi</text>
                        <text x="680" y="90" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="10" fill="#6E665E">Jadwal &amp; Pembayaran</text>
                    </svg>
                </div>
            </div>
        </section>

        {{-- 2. VISUAL ARSITEKTUR: CARA LAMA VS SATU PETA TATANIKAH --}}
        <section class="space-y-6">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-[#8C5835]">Perbandingan Visual</p>
                <h2 class="text-2xl sm:text-3xl font-semibold text-[#2B2520]">
                    Alur Kerja yang Menenangkan
                </h2>
                <p class="text-xs sm:text-sm text-[#6E665E]">Perbedaan nyata antara dokumen tercecer dengan sistem satu peta.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Visual Card: Cara Lama -->
                <div class="rounded-xl border border-[#F3D5D0] bg-[#FDF2F0]/40 p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#F3D5D0]">
                        <span class="text-xs font-semibold text-[#9E352B]">Cara Lama: Terfragmentasi</span>
                        <span class="text-[11px] text-[#9E352B]">Sering Terjadi Salah Paham</span>
                    </div>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#F3D5D0]">
                            <span class="text-[#9E352B] font-bold text-xs shrink-0">✕</span>
                            <span class="text-xs text-[#2B2520] font-medium">Spreadsheet Anggaran Manual</span>
                            <span class="text-[11px] text-[#9E352B] ml-auto shrink-0">Beda versi</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#F3D5D0]">
                            <span class="text-[#9E352B] font-bold text-xs shrink-0">✕</span>
                            <span class="text-xs text-[#2B2520] font-medium">Chat WhatsApp Tercecer</span>
                            <span class="text-[11px] text-[#9E352B] ml-auto shrink-0">Tenggelam</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#F3D5D0]">
                            <span class="text-[#9E352B] font-bold text-xs shrink-0">✕</span>
                            <span class="text-xs text-[#2B2520] font-medium">Kwitansi Kertas &amp; Bukti Transfer</span>
                            <span class="text-[11px] text-[#9E352B] ml-auto shrink-0">Hilang jejak</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#F3D5D0]">
                            <span class="text-[#9E352B] font-bold text-xs shrink-0">✕</span>
                            <span class="text-xs text-[#2B2520] font-medium">Susunan Acara Hari-H Lapangan</span>
                            <span class="text-[11px] text-[#9E352B] ml-auto shrink-0">Miskomunikasi</span>
                        </div>
                    </div>
                    <p class="text-xs text-[#6E665E] leading-relaxed pt-1">
                        Data keuangan di spreadsheet sering tidak cocok dengan riwayat transfer, rundown terdistribusi dalam banyak file chat berbeda.
                    </p>
                </div>

                <!-- Visual Card: Tatanikah -->
                <div class="rounded-xl border border-[#D3E6DA] bg-[#EAF4EE]/30 p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#D3E6DA]">
                        <span class="text-xs font-semibold text-[#2E5E3E]">Tatanikah: Satu Sumber Kebenaran</span>
                        <span class="text-[11px] text-[#2E5E3E]">Tersinkronisasi Otomatis</span>
                    </div>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#D3E6DA]">
                            <span class="text-[#2E5E3E] font-bold text-xs shrink-0">✓</span>
                            <span class="text-xs text-[#2B2520] font-medium">Buku Kas &amp; Total Anggaran</span>
                            <span class="text-[11px] text-[#2E5E3E] ml-auto shrink-0">Terkontrol</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#D3E6DA]">
                            <span class="text-[#2E5E3E] font-bold text-xs shrink-0">✓</span>
                            <span class="text-xs text-[#2B2520] font-medium">Catatan Tugas &amp; Adat</span>
                            <span class="text-[11px] text-[#2E5E3E] ml-auto shrink-0">Jadwal mundur</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#D3E6DA]">
                            <span class="text-[#2E5E3E] font-bold text-xs shrink-0">✓</span>
                            <span class="text-xs text-[#2B2520] font-medium">Kwitansi &amp; Termin Vendor</span>
                            <span class="text-[11px] text-[#2E5E3E] ml-auto shrink-0">Otomatis hitung sisa</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-white border border-[#D3E6DA]">
                            <span class="text-[#2E5E3E] font-bold text-xs shrink-0">✓</span>
                            <span class="text-xs text-[#2B2520] font-medium">Timeline &amp; Kwitansi Vendor</span>
                            <span class="text-[11px] text-[#2E5E3E] ml-auto shrink-0">Jadwal mundur</span>
                        </div>
                    </div>
                    <p class="text-xs text-[#6E665E] leading-relaxed pt-1">
                        Satu catatan terpusat: perubahan tanggal langsung memperbarui seluruh tenggat waktu, komitmen vendor, dan susunan acara resmi.
                    </p>
                </div>
        </section>

        {{-- 3. SIMULATOR INTERAKTIF DENGAN DIAGRAM PROPORSI VISUAL --}}
        <section
            class="space-y-6"
            x-data="{
                budget: 100000000,
                tradition: 'jawa',
                task1: true,
                task2: false,
                task3: false,
                data: {
                    'jawa': ['Siraman & Pembersihan Lahir Batin', 'Malam Midodareni', 'Upacara Panggih & Balangan Suruh', 'Sungkeman & Dulangan'],
                    'sunda': ['Ngeuyeuk Seureuh', 'Saweran & Meuleum Harupat', 'Huap Lingkung', 'Nincak Endog'],
                    'minang': ['Batimbang Tando', 'Malam Bainai', 'Akad & Manjapuik Marapulai', 'Basandiang di Rumah Gadang'],
                    'umum': ['Ijab Kabul / Pemberkatan Khidmat', 'Sesi Foto Keluarga Inti', 'Toast & Sambutan Mempelai', 'Gala Dinner & Resepsi']
                },
                formatRupiah(num) {
                    return 'Rp ' + Number(num).toLocaleString('id-ID');
                }
            }"
        >
            <div class="space-y-1">
                <p class="text-xs font-semibold text-[#2E5E3E]">Simulator Visual</p>
                <h2 class="text-2xl sm:text-3xl font-semibold text-[#2B2520]">
                    Kalkulasi Proporsional Otomatis
                </h2>
                <p class="text-xs sm:text-sm text-[#6E665E]">Pilih nominal anggaran dan lihat pembagian visual langsung di diagram bar.</p>
            </div>

            <div class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm space-y-6">
                <!-- Budget Pill Selector -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-[#F0ECE4]">
                    <div>
                        <span class="text-xs text-[#6E665E] block">Target Total Anggaran:</span>
                        <span class="text-2xl font-semibold text-[#2B2520] tabular-nums" x-text="formatRupiah(budget)"></span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            @click="budget = 50000000"
                            class="rounded-md border px-3 py-1.5 text-xs font-medium transition cursor-pointer"
                            :class="budget === 50000000 ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            Rp 50 Juta
                        </button>
                        <button
                            type="button"
                            @click="budget = 100000000"
                            class="rounded-md border px-3 py-1.5 text-xs font-medium transition cursor-pointer"
                            :class="budget === 100000000 ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            Rp 100 Juta
                        </button>
                        <button
                            type="button"
                            @click="budget = 200000000"
                            class="rounded-md border px-3 py-1.5 text-xs font-medium transition cursor-pointer"
                            :class="budget === 200000000 ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'"
                        >
                            Rp 200 Juta
                        </button>
                    </div>
                </div>

                {{-- Visual Stacked Proportion Bar Diagram --}}
                <div class="space-y-3">
                    <span class="text-xs font-medium text-[#2B2520] block">Diagram Alokasi Proporsional:</span>
                    <div class="h-6 w-full rounded-md overflow-hidden flex text-[10px] font-semibold text-white">
                        <div class="bg-[#2E5E3E] flex items-center justify-center transition-all duration-300" style="width: 30%" title="Katering 30%">30% Katering</div>
                        <div class="bg-[#8C5835] flex items-center justify-center transition-all duration-300" style="width: 15%" title="Gedung 15%">15% Gedung</div>
                        <div class="bg-[#A06414] flex items-center justify-center transition-all duration-300" style="width: 13%" title="Busana 13%">13% Busana</div>
                        <div class="bg-[#483725] flex items-center justify-center transition-all duration-300" style="width: 10%" title="Dekorasi 10%">10% Dekor</div>
                        <div class="bg-[#6E665E] flex items-center justify-center transition-all duration-300" style="width: 12%" title="Dokumentasi & Lainnya 12%">12% Media</div>
                        <div class="bg-[#D3E6DA] text-[#2E5E3E] flex items-center justify-center transition-all duration-300" style="width: 20%" title="Cadangan Aman 20%">20% Sisa Aman</div>
                    </div>

                    <!-- Clean Value Breakdown Legend -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2 text-xs">
                        <div>
                            <span class="flex items-center gap-1.5 text-[#6E665E]"><span class="h-2 w-2 rounded-full bg-[#2E5E3E]"></span> Katering (30%)</span>
                            <span class="text-sm font-semibold text-[#2B2520] tabular-nums" x-text="formatRupiah(budget * 0.3)"></span>
                        </div>
                        <div>
                            <span class="flex items-center gap-1.5 text-[#6E665E]"><span class="h-2 w-2 rounded-full bg-[#8C5835]"></span> Gedung/Venue (15%)</span>
                            <span class="text-sm font-semibold text-[#2B2520] tabular-nums" x-text="formatRupiah(budget * 0.15)"></span>
                        </div>
                        <div>
                            <span class="flex items-center gap-1.5 text-[#6E665E]"><span class="h-2 w-2 rounded-full bg-[#A06414]"></span> Busana &amp; Rias (13%)</span>
                            <span class="text-sm font-semibold text-[#2B2520] tabular-nums" x-text="formatRupiah(budget * 0.13)"></span>
                        </div>
                        <div>
                            <span class="flex items-center gap-1.5 text-[#2E5E3E]"><span class="h-2 w-2 rounded-full bg-[#D3E6DA]"></span> Cadangan Aman (20%)</span>
                            <span class="text-sm font-semibold text-[#2E5E3E] tabular-nums" x-text="formatRupiah(budget * 0.2)"></span>
                        </div>
                    </div>
                </div>

                {{-- Interactive Adat & Checklist Track --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#F0ECE4]">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-[#2B2520]">Pilihan Adat Nusantara:</span>
                            <span class="text-[#6E665E]">Prosesi Sakral Terjadwal</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5 text-xs">
                            <button type="button" @click="tradition = 'jawa'" class="rounded-md border px-2.5 py-1 text-xs font-medium transition cursor-pointer" :class="tradition === 'jawa' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">Jawa</button>
                            <button type="button" @click="tradition = 'sunda'" class="rounded-md border px-2.5 py-1 text-xs font-medium transition cursor-pointer" :class="tradition === 'sunda' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">Sunda</button>
                            <button type="button" @click="tradition = 'minang'" class="rounded-md border px-2.5 py-1 text-xs font-medium transition cursor-pointer" :class="tradition === 'minang' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">Minang</button>
                            <button type="button" @click="tradition = 'umum'" class="rounded-md border px-2.5 py-1 text-xs font-medium transition cursor-pointer" :class="tradition === 'umum' ? 'border-[#2B2520] bg-[#2B2520] text-white font-semibold' : 'border-[#E7E4DF] bg-white text-[#6E665E] hover:bg-[#F5F2EB]'">Umum</button>
                        </div>
                        <div class="rounded-lg border border-[#E7E4DF] bg-[#FAF8F5] p-3 text-xs space-y-1.5">
                            <template x-for="(item, idx) in data[tradition]" :key="idx">
                                <div class="flex items-center gap-2 text-[#2B2520]">
                                    <span class="text-[#8C5835] font-bold">•</span>
                                    <span x-text="item"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-[#2B2520]">Simulasi Checklist:</span>
                            <span class="text-[#6E665E]">Coba centang langsung</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-[#E7E4DF] bg-white transition hover:bg-[#FAF8F5] cursor-pointer" :class="task1 ? 'bg-[#F2F8F4] border-[#D3E6DA]' : ''">
                                <input type="checkbox" x-model="task1" class="h-4 w-4 rounded border-[#E7E4DF] text-[#2E5E3E] focus:ring-0">
                                <span class="font-medium" :class="task1 ? 'line-through text-[#6E665E]' : 'text-[#2B2520]'">Pengurusan Dokumen N1–N4 ke KUA</span>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-[#E7E4DF] bg-white transition hover:bg-[#FAF8F5] cursor-pointer" :class="task2 ? 'bg-[#F2F8F4] border-[#D3E6DA]' : ''">
                                <input type="checkbox" x-model="task2" class="h-4 w-4 rounded border-[#E7E4DF] text-[#2E5E3E] focus:ring-0">
                                <span class="font-medium" :class="task2 ? 'line-through text-[#6E665E]' : 'text-[#2B2520]'">Food Tasting Katering Bersama Keluarga Inti</span>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border border-[#E7E4DF] bg-white transition hover:bg-[#FAF8F5] cursor-pointer" :class="task3 ? 'bg-[#F2F8F4] border-[#D3E6DA]' : ''">
                                <input type="checkbox" x-model="task3" class="h-4 w-4 rounded border-[#E7E4DF] text-[#2E5E3E] focus:ring-0">
                                <span class="font-medium" :class="task3 ? 'line-through text-[#6E665E]' : 'text-[#2B2520]'">Technical Meeting Seluruh Vendor di Lokasi Acara</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. DIAGRAM ALUR 3 LANGKAH VISUAL (HOW IT WORKS) --}}
        <section class="space-y-6">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-[#8C5835]">Langkah Sederhana</p>
                <h2 class="text-2xl sm:text-3xl font-semibold text-[#2B2520]">
                    Alur Kerja dari Awal Sampai Selesai
                </h2>
                <p class="text-xs sm:text-sm text-[#6E665E]">Tidak perlu bingung memulai. Tiga fase mengalir secara runut.</p>
            </div>

            <div class="rounded-xl border border-[#E7E4DF] bg-white p-6 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-[#8C5835]">Langkah 01</span>
                        <h3 class="text-base font-semibold text-[#2B2520]">Tentukan Tanggal &amp; Anggaran</h3>
                        <p class="text-xs text-[#6E665E] leading-relaxed">
                            Masukkan perkiraan batas biaya. Sistem langsung mengunci target anggaran dan membagi batas aman pos katering, gedung, dan dekorasi.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-[#8C5835]">Langkah 02</span>
                        <h3 class="text-base font-semibold text-[#2B2520]">Pilih Tradisi &amp; Checklist</h3>
                        <p class="text-xs text-[#6E665E] leading-relaxed">
                            Pilih adat pernikahan. Seluruh daftar tugas disusun hitung mundur otomatis menuju hari-H lengkap dengan tanggal jatuh tempo.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-[#2E5E3E]">Langkah 03</span>
                        <h3 class="text-base font-semibold text-[#2B2520]">Kawal Vendor &amp; Pantau Timeline</h3>
                        <p class="text-xs text-[#6E665E] leading-relaxed">
                            Catat termin pembayaran vendor, pantau sisa saldo anggaran aman, dan kontrol seluruh linimasa persiapan hingga hari pernikahan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 5. PERTANYAAN UMUM (FAQ) --}}
        <section class="space-y-6">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-[#8C5835]">Pertanyaan Umum</p>
                <h2 class="text-2xl sm:text-3xl font-semibold text-[#2B2520]">
                    Hal yang Sering Ditanyakan
                </h2>
                <p class="text-xs sm:text-sm text-[#6E665E]">Jawaban lugas seputar penggunaan Tatanikah untuk calon mempelai.</p>
            </div>

            <div class="rounded-xl border border-[#E7E4DF] bg-white shadow-sm divide-y divide-[#F0ECE4] overflow-hidden">
                <div class="p-5 space-y-1">
                    <h3 class="text-sm font-semibold text-[#2B2520]">Apakah Tatanikah benar-benar gratis digunakan?</h3>
                    <p class="text-xs text-[#6E665E] leading-relaxed">
                        Ya. Tatanikah dirancang sebagai buku kerja digital untuk calon pengantin Indonesia. Anda dapat mencatat anggaran, checklist tugas, timeline persiapan, dan kwitansi vendor tanpa biaya langganan.
                    </p>
                </div>
                <div class="p-5 space-y-1">
                    <h3 class="text-sm font-semibold text-[#2B2520]">Apakah keluarga atau orang tua wajib membuat akun?</h3>
                    <p class="text-xs text-[#6E665E] leading-relaxed">
                        Tidak perlu. Cukup pengantin yang mengelola buku kerja untuk memantau persiapan bersama keluarga.
                    </p>
                </div>
                <div class="p-5 space-y-1">
                    <h3 class="text-sm font-semibold text-[#2B2520]">Bagaimana jika kami memakai jasa Wedding Organizer (WO)?</h3>
                    <p class="text-xs text-[#6E665E] leading-relaxed">
                        Tatanikah sangat cocok berdampingan dengan tim WO. Anda memiliki kontrol penuh atas anggaran dan kepastian linimasa persiapan.
                    </p>
                </div>
                <div class="p-5 space-y-1">
                    <h3 class="text-sm font-semibold text-[#2B2520]">Apakah data dapat diakses dari HP dan laptop?</h3>
                    <p class="text-xs text-[#6E665E] leading-relaxed">
                        Sangat bisa. Tatanikah didesain responsif untuk peramban ponsel maupun desktop sehingga Anda dapat memperbarui checklist dan anggaran di mana saja.
                    </p>
                </div>
            </div>
        </section>

        {{-- 6. PENUTUP & CALL TO ACTION --}}
        <section class="rounded-xl border border-[#E7E4DF] bg-white p-8 sm:p-12 text-center shadow-sm space-y-4">
            <h2 class="text-2xl sm:text-3xl font-semibold text-[#2B2520] tracking-tight">
                Mulai Perencanaan Pernikahan Anda
            </h2>
            <p class="text-xs sm:text-sm text-[#6E665E] max-w-lg mx-auto leading-relaxed">
                Tanpa biaya langganan, tanpa instalasi aplikasi rumit. Buka langsung di browser ponsel atau laptop Anda.
            </p>
            <div class="pt-1 flex justify-center">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2B2520] px-6 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-[#1C1815]">
                    <span>Buka Buku Kerja</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </section>

    </main>

    <footer class="border-t border-[#E7E4DF] bg-white py-8 text-center text-xs text-[#6E665E]">
        <p class="text-sm font-semibold text-[#2B2520]">Tatanikah</p>
        <p class="mt-0.5 text-xs text-[#6E665E]">Buku catatan dan tata laksana pernikahan tradisi Indonesia.</p>
    </footer>

    @stack('scripts')
</body>
</html>
