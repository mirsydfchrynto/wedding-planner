# Referensi UI & Anti-Slop — 2026-11-10

Sumber: thread riset pengguna, 13 repositori. Katalog global: `Documents/MyBrain/50-AI-Agents/UI-AntiSlop-Reference-Thread-2026-11-10.md`.

## Stack

Laravel 13.40, Blade, Alpine 3.17, Tailwind 4, Vite 8. Tidak ada React/Next.

## Keputusan

| Referensi | Status | Alasan |
|---|---|---|
| `miqdadbadjuber/anti-slop` | **Adopsi checklist** | 40 aturan, 6 skill, Delivery Gate. MIT. |
| `heydamir/unslop` | **Adopsi metode audit** | Urutan 9 pass dan arah 5 baris. CC-BY-4.0, wajib kredit. |
| `superdesigndev/superdesign-skill` | Referensi | Pola design system sudah ada di `design-system/tatanikah/MASTER.md`. |
| `seropaski/lets-build` | Kandidat pola | Spec/plan/review disimpan di repo. Tidak perlu CLI. |
| `marvkr/better-design` | Referensi WCAG/token | Registry shadcn berbasis React, tidak dipakai. |
| `madebymustafa/design-taste` | Referensi | Genre skill tidak relevan untuk SaaS pernikahan. |
| `DavidHDev/canvas-ui` | Referensi visual | Lisensi NOASSERTION; cek sebelum copy. |
| `paper-design/shaders` | Referensi | Hanya untuk hero visual; bukan layar app. |
| `ui-layouts/uilayouts` | Referensi komponen | Port pola, bukan dependensi React. |
| `Hyperiux-Immersion-Labs/hyperiux-components` | Referensi | Next.js; cek lisensi per komponen. |
| `ibelick/motion-primitives` | Referensi gerak | Ambil prinsip easing/durasi, bukan paket React. |
| `ui-layouts/cursify` | **Tolak** | Tidak ada lisensi; cursor animation tidak relevan. |
| `akaieuan/trickle-UI-kit` | Referensi | Pure CSS bisa diport; jangan tambah React. |

## Aturan Proyek

- Baca `DESIGN.md` sebelum mengubah UI.
- Token dan anti-pattern detail dari `design-system/tatanikah/MASTER.md`.
- Jangan copy komponen eksternal mentah; dekonstruksi ke token Tailwind.
- Jangan tambah dependensi untuk efek yang bisa CSS.
- Jangan hapus angka, data, atau link saat merapikan copy.
- Kontras 4.5:1, focus ring, target 44px, input 16px, `prefers-reduced-motion`.
- Audit dengan screenshot; jalankan `npm run build` dan test terkait.

## Status Audit

Screenshot dan design system yang ada sudah menghindari pola keras: tanpa emoji ikon, tanpa gradient/glow, palet rendah kroma, angka tabular, dan motion `transform`/`opacity`. Ini bukan audit WCAG penuh; jalankan audit kontras dan keyboard sebelum publish.

## Tautan

- https://github.com/miqdadbadjuber/anti-slop
- https://github.com/heydamir/unslop
- https://github.com/superdesigndev/superdesign-skill
- https://github.com/seropaski/lets-build
- https://github.com/marvkr/better-design
- https://github.com/madebymustafa/design-taste
- https://github.com/DavidHDev/canvas-ui
- https://github.com/paper-design/shaders
- https://github.com/ui-layouts/uilayouts
- https://github.com/Hyperiux-Immersion-Labs/hyperiux-components
- https://github.com/ibelick/motion-primitives
- https://github.com/ui-layouts/cursify
- https://github.com/akaieuan/trickle-UI-kit
