# Tatanikah — SaaS Wedding Planner Web Application

Platform Software-as-a-Service (SaaS) manajemen perencanaan pernikahan Indonesia berbasis **Laravel 13 (PHP 8.3+)**, **Tailwind CSS v4**, **Alpine.js v3**, dan **Chart.js v4** dengan arahan visual **Apple Tactile Precision × Nusantara Batik Keraton** (*Kain Mori Alabaster `#FAF8F5` & Sogan Champagne Bronze `#735736`*).

---

## Arsitektur Akses & Workspace

1. **Akses Publik (`GET /`)**
   - Tamu melihat landing page pemasaran (`resources/views/landing.blade.php`): penjelasan modul, simulator anggaran reaktif, harga, dan FAQ. Tombol CTA mengarah ke `/login`.
   - Pengguna yang sudah masuk langsung dialihkan: berprofil ke `/dashboard`, akun baru tanpa profil ke `/onboarding`.
   - Isolasi data per akun: setiap pengantin hanya melihat anggaran, checklist, timeline, dan vendor miliknya sendiri (`WeddingProfile` global scope).
2. **Gerbang Login Google OAuth & Workspace Calon Pengantin (`GET /login` → `GET /dashboard`)**
   - Pelanggan masuk menggunakan **Akun Google** (`POST /auth/google`, default terisi `aurelia.reza@gmail.com`) untuk membuka 5 modul utama yang saling terhubung:
    - **Executive Dashboard (`GET /dashboard`)** — Hitung mundur waktu nyata (Hari, Jam), Indeks Kesiapan Pernikahan, ringkasan anggaran, tugas mendesak, dan progres tahapan.
    - **Budget Planner (`GET /budget`)** — Batas anggaran induk, 29 pos biaya hasil riset pada 12 kategori, estimasi vs aktual, pelacakan pembayaran, dan status hemat/overbudget.
    - **Wedding Checklist (`GET /checklist`)** — 124 tugas yang dibagi ke 10 tahap persiapan, dengan filter kategori, status, penanggung jawab, dan centang toggle status.
    - **Wedding Timeline (`GET /timeline`)** — Jadwal persiapan per tahapan kronologis menuju hari pernikahan dengan pemantauan progres dan penanda tonggak (milestone).
    - **Vendor Management (`GET /vendors`)** — Direktori mitra vendor lengkap dengan kontak WhatsApp penanggung jawab, nilai kontrak, serta pencatatan bukti pembayaran per termin.
3. **Onboarding (`GET /onboarding`)**
   - Akun baru tanpa profil membuat profil pernikahan dulu; selesai itu 29 pos anggaran dan 124 tugas persiapan dibuat otomatis lewat `BudgetAllocationService` dan `TimelineGeneratorService`, lalu workspace terbuka. Satu akun = satu profil pernikahan.

---

## Pembagian Tim & Cabang Git (*Branch*)

Setiap anggota tim bekerja pada *branch* dan berkas masing-masing sehingga bebas dari bentrokan kode (*zero merge conflicts*):

| Anggota Tim | Akun GitHub | Branch Git | Peran & Modul yang Dikerjakan | Berkas Utama (*File Ownership*) |
| :--- | :--- | :--- | :--- | :--- |
| **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` | **Lead Architecture, Google Auth, serta Modul #5: Executive Dashboard (`/dashboard`)** | `app/Http/Controllers/{Auth,Dashboard}Controller.php`<br>`resources/views/{auth,dashboard}/*`<br>`resources/views/layouts/app.blade.php` & `components/ui/*` |
| **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Modul #1: Budget Planner (`/budget`)** | `app/Http/Controllers/BudgetController.php`<br>`app/Http/Requests/*BudgetItemRequest.php`<br>`resources/views/budget/index.blade.php` |
| **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Modul #4: Vendor Management (`/vendors`) & Integrasi UI** | `app/Http/Controllers/VendorController.php`<br>`resources/views/vendors/index.blade.php`<br>`resources/views/vendors/form.blade.php` |
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

### Kredensial Akses Aplikasi (Siap Pakai)

1. **Pengguna / Calon Pengantin (Akses Utama)**
   - URL: `http://localhost:8000/login` (atau port `8080` pada Docker).
   - Metode: **Google Sign-In** (Otomatis live via Socialite jika kredensial aktif, atau klik tombol *Akses Demo Instan* untuk `aurelia.reza@gmail.com`).
   - Setiap akun Google baru otomatis diarahkan ke Wizard Onboarding Adaptif 3-Langkah (Mode Kilat $\le$ 45 hari atau Mode Terencana).

2. **Administrator Platform (Akses Rahasia / Tersembunyi)**
   - URL Langsung: `http://localhost:8000/admin/login` (Akses tidak dimunculkan di menu navigasi publik).
   - Email: `admin@tatanikah.id`
   - Password: `admin`
   - Dashboard Monitoring: `http://localhost:8000/admin` (Monitoring seluruh pasangan, total anggaran, vendor terdaftar, dan metrik sistem).

---

## Panduan Live Google OAuth & Redirect URI (Google Cloud Console)

Kredensial Google Client Secret dikonfigurasi melalui berkas `.env` lokal:
- **Client ID**: Diisi pada `GOOGLE_CLIENT_ID`
- **Client Secret**: Diisi pada `GOOGLE_CLIENT_SECRET`

### Pengaturan di Google Cloud Console:
1. Buka [Google Cloud Console Credentials](https://console.cloud.google.com/apis/credentials).
2. Pilih Project `gen-lang-client-0567503746` (atau OAuth 2.0 Client ID Anda).
3. Pada bagian **Authorized redirect URIs**, daftarkan URL berikut:
   - `http://localhost:8000/auth/google/callback`
   - `http://localhost:8080/auth/google/callback`
   - `http://127.0.0.1:8000/auth/google/callback`
   - `http://127.0.0.1:8080/auth/google/callback`
   - `https://[domain-publik-anda.com]/auth/google/callback` (saat live server)
4. Simpan perubahan. Pengguna kini dapat login langsung menggunakan akun Google riil mereka.
