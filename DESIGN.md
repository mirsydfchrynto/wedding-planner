# DESIGN.md — Tatanikah

Arah desain frontend Tatanikah: **Warm Minimalism × Modern Editorial × Clean SaaS**.
Sumber detail: `design-system/tatanikah/MASTER.md`.
Katalog referensi: `.ui-design/references-2026-11-10.md`.

## Product

Tatanikah adalah buku kerja perencanaan pernikahan digital Indonesia (*Buku Kerja Pernikahan*) untuk pasangan dan keluarga besar. Menyediakan pagu anggaran, catatan tugas adat, kwitansi pembayaran vendor, linimasa persiapan, dan dashboard kesiapan terpadu.

## Character

Tenang, terorganisir, hangat, dan modern editorial. Menggantikan elemen neo-brutalis kasar dengan garis halus 1px (hairlines), sudut membulat lembut (`rounded-2xl` dan `rounded-full`), tipografi berkarakter editorial, dan ruang bernapas yang lega.

## World

- **Canvas Ground**: Warm Alabaster (`#FAF8F5`).
- **Surface**: Pure White (`#FFFFFF`) untuk kartu lembar kerja dan dialog modal.
- **Surface Subtle**: Muted Oat (`#F5F2EB`) untuk sub-panel dan pill navigasi.
- **Hairlines**: 1px subtle border (`#E7E4DF`).
- **Typography Ink**: Primary Charcoal (`#2B2520`), Taupe (`#6E665E`), Placeholder (`#9E968D`).
- **Brand Accent**: Warm Sogan Terracotta (`#8C5835`) dan Forest Sage (`#2E5E3E`).

## Moments

- **Pagu Anggaran & Buku Kas**: Kartu metrik dengan auto-split proporsional, komitmen vendor, dan sisa dana aman dengan aritmatika yang konsisten.
- **Kwitansi Vendor**: Status lunas, uang muka (DP), dan jatuh tempo dengan angka tabular terformat Rupiah serta tautan WhatsApp 1-klik.
- **Wedding Timeline**: Monitoring jadwal persiapan kronologis dan tenggat waktu per tahapan menuju hari pernikahan dengan penanda tonggak (milestone).
- **Catatan Adat**: Prosesi sakral otomatis terisi sesuai tradisi (Jawa, Sunda, Minang, Nasional).

## Signature

- Angka mata uang Rupiah selalu berformat `.tabular-nums` atau font monospace tabular, rata kanan.
- Tombol aksi berbentuk pill (`rounded-full`) dengan efek tekan mikro Apple (`active:scale-[0.98]`).
- Tanpa hard offset shadows hitam, tanpa tape decals, tanpa rotasi kartun, tanpa 2px harsh outlines.

## Visual Direction

- **Palet**: Warm Minimalism (`--color-canvas: #FAF8F5`, `--color-surface: #FFFFFF`, `--color-border: #E7E4DF`, `--color-ink: #2B2520`, `--color-brand: #8C5835`, `--color-sage: #2E5E3E`).
- **Tipografi**: Judul menggunakan display serif `Cormorant Garamond`; antarmuka dan label menggunakan `Plus Jakarta Sans`; angka dan mata uang menggunakan `JetBrains Mono` / `.tabular-nums`.

## Layout

- Grid responsif dengan kartu `rounded-2xl` bergaris batas `1px solid #E7E4DF` dan bayangan lembut `0 1px 3px rgba(43,33,24,0.04)`.
- Desktop Header: Frosted sticky header (`backdrop-blur-md bg-[#FAF8F5]/90`) dengan navigasi pill terpadu.
- Mobile: Navigasi bawah frosted 5 kolom dengan target sentuh ≥44px, bebas overflow horizontal pada lebar layar minimum 390px.

## Anti-Slop Gates

- Tidak ada emoji sebagai ikon antarmuka; gunakan SVG Lucide (`<x-ui.icon>`). Emoji hanya diperbolehkan di dalam teks pesan ekspor WhatsApp keluarga.
- Tidak ada gradient text, glow spekulatif, decal selotip (tape), stempel miring berlebihan, atau outline hitam 2px tebal.
- Tidak ada angka atau metrik palsu.
- Aritmatika anggaran konsisten: Pagu Induk, Komitmen Vendor, dan Sisa Dana harus memiliki dasar hitung yang jelas.
- Kontras teks minimal 4.5:1; focus ring terlihat jelas.
