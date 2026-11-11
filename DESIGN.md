# DESIGN.md — Tatanikah

Arah desain untuk agent. Sumber detail: `design-system/tatanikah/MASTER.md`.
Katalog referensi: `.ui-design/references-2026-11-10.md`.

## Product

Tatanikah adalah ruang kerja perencanaan pernikahan Indonesia untuk pasangan dan wedding organizer. Isinya pagu anggaran, catatan tugas adat, kwitansi vendor, susunan acara, dan cetak panduan resmi.

## Character

Taktil, presisi, hangat berakar tradisi Nusantara. Seperti membuka buku catatan pernikahan fisik: kertas berserat krem, tinta cokelat tua, garis tegas, dan stempel penanda status.

## World

Meja kerja kayu (`#E7DBC7`), lembaran kertas krem (`#FAF5EC`, `#F3E8D3`), tinta pekat (`#2B2118`), dan aksen sogan batik Nusantara (`#7A4425`). Bukan korporat steril, bukan pula hiasan pesta berlebihan.

## Moments

- Pagu anggaran & buku kas: kartu bento dengan auto-split proporsional, komitmen vendor, dan sisa dana aman dengan aritmatika yang konsisten.
- Kwitansi vendor: status lunas, uang muka (DP), dan jatuh tempo dengan angka tabular terformat Rupiah.
- Rundown hari-H: pembagian sesi akad dan resepsi dengan garis waktu vertikal yang siap cetak A4.
- Catatan adat: prosesi sakral otomatis terisi sesuai tradisi (Jawa, Sunda, Minang, Modern).

## Signature

Angka Rupiah selalu `tabular-nums`, rata kanan, dengan garis batas tegas `2px #2B2118` dan bayangan offset keras `3px/4px`.

## Visual Direction

- Palet: Warm Heritage Neo-Brutalism & Sogan Nusantara (`--color-desk: #E7DBC7`, `--color-paper: #FAF5EC`, `--color-ink: #2B2118`, `--color-soga: #7A4425`, aksen `--color-kunyit: #F6D365`, `--color-mint: #CDE7D8`, `--color-forest: #3F5F46`). Nilai hex lengkap ada di `design-system/tatanikah/MASTER.md`.
- Tipografi: Judul menggunakan display serif `Cormorant Garamond`; antarmuka dan label menggunakan `Plus Jakarta Sans`; angka dan mata uang menggunakan `JetBrains Mono` / `.tabular-nums`; catatan informal menggunakan handwriting `Kalam`.

## Layout

- Grid responsif dengan kartu bento berkontur `2px solid #2B2118`.
- App: folder tab navigation di desktop, navigasi bawah satu baris 5 kolom di layar ponsel.
- Mobile: target sentuh minimal 44px, input minimal 16px, bebas overflow horizontal pada lebar layar minimum 390px.

## Anti-Slop Gates

Wajib sebelum publish:

- Tidak ada emoji sebagai ikon antarmuka; gunakan SVG Lucide (`<x-ui.icon>`). Emoji hanya diperbolehkan di dalam teks pesan ekspor WhatsApp keluarga.
- Tidak ada gradient text, glow, neon blob, atau bayangan buram spekulatif.
- Tidak ada angka, testimoni, atau metrik palsu.
- Tidak ada istilah internal scaffolding ("Bento A", "Zero-Effort UX") di dalam copy publik.
- Aritmatika anggaran harus konsisten: Pagu Induk, Komitmen Vendor, dan Sisa Dana harus memiliki dasar hitung yang jelas.
- Kontras teks minimal 4.5:1; focus ring terlihat.
- Audit visual dengan pengujian browser dan verifikasi screenshot berkala.

## Referensi

- `design-system/tatanikah/MASTER.md` — token, tipografi, motif ledger neo-brutalist, anti-pattern.
- `.ui-design/references-2026-11-10.md` — referensi arsitektural dan keputusan adopsi.
