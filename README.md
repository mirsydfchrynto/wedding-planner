# Siap Nikah — Perencanaan Pernikahan Nusantara

Platform Software-as-a-Service (SaaS) manajemen perencanaan pernikahan Indonesia berbasis **Laravel 13 (PHP 8.3+)**, **Tailwind CSS v4**, **Alpine.js v3**, dan **Chart.js v4** dengan arahan visual **Apple Tactile Precision × Nusantara Batik Keraton** (*Kain Mori Alabaster `#FAF8F5` & Sogan Champagne Bronze `#735736`*).

---

## Arsitektur Akses & Workspace

1. **Akses Publik (`GET /`)**
   - Tamu melihat landing page pemasaran (`resources/views/landing.blade.php`): penjelasan modul, simulator anggaran reaktif, harga, dan FAQ. Tombol CTA mengarah ke `/login`.
   - Pengguna yang sudah masuk langsung dialihkan: berprofil ke `/dashboard`, akun baru tanpa profil ke `/onboarding`.
   - Isolasi data per akun: setiap pengantin hanya melihat anggaran, checklist, timeline, dan vendor miliknya sendiri (`WeddingProfile` global scope).
2. **Gerbang Login Google OAuth & Workspace Calon Pengantin (`GET /login` → `GET /dashboard`)**
   - Pelanggan masuk menggunakan **Akun Google** (`POST /auth/google`, default terisi `aurelia.reza@gmail.com`) untuk membuka 5 modul utama yang saling terhubung:
     - **Executive Dashboard (`GET /dashboard`)** — Hitung mundur waktu nyata (Hari, Jam, Menit), Indeks Kesiapan Hari-H, grafik Estimasi vs Aktual, **Tata Upacara Adat Jawa (Siraman, Midodareni, Akad, Panggih)**, dan **Kalkulator Neptu Weton Mempelai**.
    - **Budget Planner (`GET /budget`)** — Plafon anggaran induk, 29 pos biaya hasil riset pada 12 kategori (dokumen, venue, catering, dekorasi, busana, makeup, dokumentasi, hiburan, souvenir, transport, perhiasan, dana tak terduga), estimasi vs aktual, pelacakan pembayaran, dan indikator hemat/overbudget.
    - **Wedding Checklist (`GET /checklist`)** — 124 tugas yang dibagi ke 10 tahap persiapan, dengan filter kategori, status, PIC, dan centang selesai instan.
    - **Wedding Timeline (`GET /timeline`)** — Linimasa 10 tahap (Perencanaan sampai Hari-H) yang jatuh temponya disebar mengikuti sisa waktu menuju hari-H, plus rundown menit-demi-menit hari-H.
    - **Vendor Management (`GET /vendors`)** — Direktori mitra vendor pernikahan lengkap dengan kontak WhatsApp PIC, nilai kontrak, dan pelacakan DP/pelunasan per termin.
    - **Master Wedding Guide Print A4 / PDF (`GET /print/master-guide`)** — Lembar panduan siap cetak 4 halaman: Cover & Ringkasan Eksekutif, Rundown Menit-demi-Menit Hari-H, Direktori Kontak Vendor & Rekap Pembayaran, serta Lembar Persetujuan Panduan Kerja.
3. **Onboarding (`GET /onboarding`)**
   - Akun baru tanpa profil membuat profil pernikahan dulu; selesai itu 29 pos anggaran dan 124 tugas checklist dibuat otomatis lewat `BudgetAllocationService` dan `TimelineGeneratorService`, lalu workspace terbuka. Satu akun = satu profil pernikahan.

---

## Pembagian Tim & Cabang Git (*Branch*)

Setiap anggota tim bekerja pada *branch* dan berkas masing-masing sehingga bebas dari bentrokan kode (*zero merge conflicts*):

| Anggota Tim | Akun GitHub | Branch Git | Peran & Modul yang Dikerjakan | Berkas Utama (*File Ownership*) |
| :--- | :--- | :--- | :--- | :--- |
| **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` | **Lead Architecture, Google Auth, serta Modul #5: Executive Dashboard (`/dashboard`)** | `app/Http/Controllers/{Auth,Dashboard}Controller.php`<br>`resources/views/{auth,dashboard}/*`<br>`resources/views/layouts/app.blade.php` & `components/ui/*` |
| **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Modul #1: Budget Planner (`/budget`)** | `app/Http/Controllers/BudgetController.php`<br>`app/Http/Requests/*BudgetItemRequest.php`<br>`resources/views/budget/index.blade.php` |
| **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Modul #3: Wedding Timeline (`/timeline`) & Modul #4: Vendor Management (`/vendors`)** | `app/Http/Controllers/TimelineController.php`<br>`app/Http/Controllers/VendorController.php`<br>`resources/views/timeline/index.blade.php`<br>`resources/views/vendors/index.blade.php` |
| **Adinda Aulia Sabrina Arumsari** | `@adinda0112` | `dinda` | **Modul #2: Wedding Checklist (`/checklist`)** | `app/Http/Controllers/ChecklistController.php`<br>`resources/views/checklist/index.blade.php` |
---

## Panduan Instalasi Cepat

### Opsi A: Menjalankan via Docker Compose (Rekomendasi Produksi)
Cukup jalankan satu perintah menggunakan skrip otomatisasi:
```bash
./deploy.sh --seed
```
Aplikasi langsung aktif pada port `http://localhost:8080` lengkap dengan container PHP 8.4-FPM, PostgreSQL 16, Redis 7, dan Nginx Reverse Proxy.

### Opsi B: Menjalankan Lokal (PHP CLI & Composer)
```bash
# 1. Clone repositori & pindah ke branch kerja
git clone https://github.com/mirsydfchrynto/wedding-planner.git
cd wedding-planner
git checkout <nama-branch>

# 2. Instal dependensi, migrasi database, dan build asset
composer setup
php artisan migrate:fresh --seed

# 3. Jalankan server lokal
php artisan serve
```

### Akun Demo Bawaan (Otomatis dari Seeder)
- **Login Customer (Google Sign-In di `/login`):** `aurelia.reza@gmail.com` (langsung klik tombol **"Lanjutkan dengan Akun Google"**)

### Isolasi Data & Kunci Google Sign-In
- Semua tabel modul (`vendors`, `budget_items`, `wedding_tasks`) memiliki `wedding_profile_id`; record baru otomatis ditandai sesuai profil pengguna yang sedang masuk.
- `POST /auth/google` memakai **demo sign-in tanpa kata sandi** (membuat/masuk akun dari email). Kunci ini aktif hanya saat `GOOGLE_DEMO_LOGIN=true` (default non-production) dan mengembalikan `403` di produksi sampai Google OAuth asli dikonfigurasi.
