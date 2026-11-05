# Atelier Vow Nusantara — SaaS Wedding Planner Web Application

Platform Software-as-a-Service (SaaS) manajemen perencanaan pernikahan Indonesia berbasis **Laravel 13 (PHP 8.3+)**, **Tailwind CSS v4**, **Alpine.js v3**, dan **Chart.js v4** dengan arahan visual **Apple Tactile Precision × Nusantara Batik Keraton** (*Kain Mori Alabaster `#FAF8F5` & Sogan Champagne Bronze `#735736`*).

---

## Arsitektur 3 Lapisan Utama

1. **Web Utama Publik — Branding, Marketing & SaaS Landing Page (`GET /`)**
   - Halaman utama ketika pengunjung membuka aplikasi: menampilkan nilai jual SaaS, pratinjau ruang kerja interaktif 3D (`spatialTilt`), **Simulasi Interaktif Anggaran & Adat Nusantara** (`100`–`1.000` tamu), 3 paket berlangganan (*Akad Intimate*, *Keraton Royal*, *Atelier Enterprise WO*), dan strategi kampanye pemasaran.
2. **Gerbang Login Google OAuth & Workspace Calon Pengantin (`GET /login` → `GET /dashboard`)**
   - Pelanggan masuk menggunakan **Akun Google** (`POST /auth/google`, default terisi `aurelia.reza@gmail.com`) untuk membuka 5 modul utama yang saling terhubung:
     - **Executive Dashboard (`GET /dashboard`)** — Hitung mundur waktu nyata (Hari, Jam, Menit), Indeks Kesiapan Hari-H, grafik Estimasi vs Aktual, **Tata Upacara Adat Jawa (Siraman, Midodareni, Akad, Panggih)**, dan **Kalkulator Neptu Weton Mempelai**.
     - **Budget Planner (`GET /budget`)** — Plafon anggaran induk, alokasi 8 kategori, estimasi vs aktual, pelacakan pembayaran, dan indikator hemat/overbudget.
     - **Wedding Checklist (`GET /checklist`)** — Daftar tugas persiapan dengan filter kategori, status, PIC, dan centang selesai instan.
     - **Wedding Timeline (`GET /timeline`)** — Peta linimasa kronologis dari fase **H-12 Bulan** hingga **Hari-H**.
     - **Vendor Management (`GET /vendors`)** — Direktori mitra vendor pernikahan lengkap dengan kontak WhatsApp PIC, nilai kontrak, dan pelacakan DP/pelunasan.
3. **Konsol Admin & Operasional WO yang Tersembunyi & Privat (`GET /admin`)**
   - **Tersembunyi sepenuhnya** dari pengunjung publik maupun pelanggan biasa (`EnsureAdminAccess` middleware — mengembalikan `403 Forbidden` jika diakses oleh akun non-admin).
   - Diakses melalui menu **Otorisasi Privat Administrator & Lead WO** di halaman `/login` menggunakan kredensial Admin (`irsyad@ateliervow.id` / `AtelierAdmin2026!`).
   - Menyediakan eskalasi 1-klik untuk tugas yang terlambat (`overdue`), audit pos anggaran berisiko tinggi, verifikasi SLA kontrak vendor, dan **Matriks Kepatuhan Twelve-Factor SaaS (12/12)**.

---

## Pembagian Tim & Cabang Git (*Branch*)

Setiap anggota tim bekerja pada *branch* dan berkas masing-masing sehingga bebas dari bentrokan kode (*zero merge conflicts*):

| Anggota Tim | Akun GitHub | Branch Git | Peran & Modul yang Dikerjakan | Berkas Utama (*File Ownership*) |
| :--- | :--- | :--- | :--- | :--- |
| **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` | **Lead Architecture, SaaS Landing Page (`/`), Google Auth & Private Admin (`/admin`), serta Modul #5: Executive Dashboard (`/dashboard`)** | `app/Http/Controllers/{Saas,Auth,Admin,Dashboard}Controller.php`<br>`app/Http/Middleware/EnsureAdminAccess.php`<br>`resources/views/{saas,auth,admin,dashboard}/*`<br>`resources/views/layouts/app.blade.php` & `components/ui/*` |
| **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Modul #1: Budget Planner (`/budget`)** | `app/Http/Controllers/BudgetController.php`<br>`app/Http/Requests/*BudgetItemRequest.php`<br>`resources/views/budget/index.blade.php` |
| **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Modul #3: Wedding Timeline (`/timeline`) & Modul #4: Vendor Management (`/vendors`)** | `app/Http/Controllers/TimelineController.php`<br>`app/Http/Controllers/VendorController.php`<br>`resources/views/timeline/index.blade.php`<br>`resources/views/vendors/index.blade.php` |
| **Adinda Aulia Sabrina Arumsari** | `@adinda0112` | `dinda` | **Modul #2: Wedding Checklist (`/checklist`)** | `app/Http/Controllers/ChecklistController.php`<br>`resources/views/checklist/index.blade.php` |
---

## Panduan Instalasi Cepat (30 Detik)

```bash
# 1. Clone repositori
git clone https://github.com/mirsydfchrynto/wedding-planner.git
cd wedding-planner

# 2. Pindah ke branch masing-masing (irsyad / marsha / adam / dinda)
git checkout <nama-branch>

# 3. Jalankan instalasi otomatis (Composer, .env, APP_KEY, Migrasi + Seeder, dan Build Vite)
composer setup
php artisan migrate:fresh --seed

# 4. Jalankan server lokal
php artisan serve
```

### Akun Demo Bawaan (Otomatis dari Seeder)
- **Login Customer (Google Sign-In di `/login`):** `aurelia.reza@gmail.com` (langsung klik tombol **"Lanjutkan dengan Akun Google"**)
- **Login Admin Privat (Klik "Otorisasi Privat Administrator & Lead WO" di `/login`):**
  - Email: `irsyad@ateliervow.id`
  - Kunci Otorisasi: `AtelierAdmin2026!`
