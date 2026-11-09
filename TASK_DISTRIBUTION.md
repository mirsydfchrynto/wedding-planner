# Pembagian Tugas Tim & Spesifikasi Peran (Enterprise Full-Stack Model)
## Atelier Vow Nusantara — Luxury Wedding Planner & Backoffice SaaS

Dokumen ini adalah **sumber kebenaran tunggal (*Single Source of Truth*)** pembagian kerja tim rekayasa perangkat lunak untuk aplikasi **Atelier Vow Nusantara**. Struktur tim disusun menggunakan model industri: **1 Lead & Admin Architect + 1 Lead Frontend Engineer (Web Utuh) + 2 Backend Core Engineers**.

---

## 1. Peta Peran Tim, Tanggung Jawab, & Git Branch

| No | Nama Anggota | Akun GitHub | Branch Git | Peran Utama (*Core Role*) | Cakupan Kerja & Modul | Status |
|:---|:---|:---|:---|:---|:---|:---|
| 1 | **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` / `main` | **Project Lead, Systems Architect & Admin Ops** | Arsitektur Multi-Tenancy, Konsol Admin Privat (`/admin`), Mesin Dokumen Cetak Master Guide A4 (`/print/master-guide`), Keamanan Auth, dan Kontainerisasi Docker | **100% Selesai & Teruji** |
| 2 | **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Lead Frontend Engineer & UI/UX Specialist** | Seluruh Antarmuka Web Utuh (Design System Vogue, Blade Views, Alpine.js, Responsivitas Mobile, Apple HIG & Refactoring UI Polish untuk 6 Modul Utama) | **100% Selesai & Teruji** |
| 3 | **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Senior Backend Engineer (Operations & Vendors)** | Data Layer & Logika Bisnis Modul Linimasa & Rundown Hari-H (`WeddingRundown`), Manajemen Kemitraan Vendor (`Vendor`), dan Arsitektur Multi-Termin (`VendorPayment`) | **100% Selesai & Teruji** |
| 4 | **Adinda Aulia Sabrina Arumsari** | `@adinda0112` | `dinda` | **Senior Backend Engineer (Guests & Workflows)** | Data Layer & Logika Bisnis Modul Buku Tamu Digital & RSVP (`WeddingGuest`), Workflow Checklist Tugas (`WeddingTask`), WhatsApp Link Generator, dan Otomasi Pengujian PHPUnit | **100% Selesai & Teruji** |

---

## 2. Rincian Job Desk Mendalam per Anggota Tim

### A. M. Irsyad Fachryanto (`branch: irsyad`) — LEAD & ADMIN ARCHITECT
- **Fokus Utama**: Menjaga integritas sistem, keamanan batas penyewa (*tenant boundaries*), operasional backoffice WO, dan kesiapan rilis produksi.
- **Tanggung Jawab Spesifik**:
  1. **Multi-Tenancy & Tenant Isolation**:
     - Membangun trait `BelongsToWeddingProfile` dengan Global Scope Eloquent agar seluruh query (vendor, budget, task, rundown, guest) terkunci secara otomatis pada profil pernikahan pasangan yang sedang login.
     - Mengamankan middleware `EnsureWeddingProfile` dan alur onboarding (`/onboarding`).
     - Mengamankan alur Google Sign-In dari celah eskalasi akun admin (`role = customer`).
  2. **Konsol Administrator & Lead WO (`/admin`)**:
     - Membangun konsol operasional tersembunyi dengan middleware `EnsureAdminAccess` (proteksi status `403 Forbidden` untuk non-admin).
     - Menyiapkan telemetri sistem: Eskalasi 1-klik tugas terlambat, verifikasi SLA kontrak vendor, dan **Matriks Kepatuhan Twelve-Factor SaaS (12/12 Faktor)**.
  3. **Mesin Cetak Dokumen Resmi Master Guide A4/PDF (`/print/master-guide`)**:
     - Merancang controller dan format cetak presisi 4 halaman A4 untuk panduan lapangan panitia tanpa ketergantungan koneksi internet.
  4. **DevOps & Kontainerisasi**:
     - `Dockerfile` multi-stage (PHP 8.4-FPM + Node 22 Vite Build + OPcache).
     - `docker-compose.yml` (App, PostgreSQL 16, Redis 7, Nginx Reverse Proxy).
     - Skrip deployment otomatis satu baris `./deploy.sh`.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/{AuthController,OnboardingController,PrintController,AdminController,DashboardController}.php`
  - `app/Models/Concerns/BelongsToWeddingProfile.php` & `app/Http/Middleware/EnsureAdminAccess.php`
  - `Dockerfile`, `docker-compose.yml`, `docker/nginx.conf`, `deploy.sh`

---

### B. Marsha Dwi Lucyana (`branch: marsha`) — LEAD FRONTEND ENGINEER (WEB UTUH)
- **Fokus Utama**: Menemani Irsyad memegang **seluruh lapisan antarmuka (*frontend web utuh*)** agar aplikasi mudah digunakan, bebas dari tumpukan teks (*decluttered*), responsif di layar HP, dan memiliki estetika visual setara produk Apple / Vogue Editorial.
- **Tanggung Jawab Spesifik**:
  1. **Sistem Desain & Tipografi Visual (Apple HIG & Refactoring UI)**:
     - Mengelola token warna arsitektural di `resources/css/app.css` (Alabaster, Ivory, Pearl, Espresso, Champagne Bronze, Sage).
     - Menerapkan radius sudut Apple-style (`rounded-2xl` untuk kartu, `rounded-xl` untuk tombol/input).
     - Menghapus teks redundan/birokratis pada seluruh subjudul halaman dan kartu KPI.
  2. **Pengembangan Seluruh Tampilan Modul (Blade Views)**:
     - **Landing Page SaaS (`resources/views/saas/landing.blade.php`)**: Hero interaktif, simulasi adat, paket harga, dan FAQ.
     - **Dashboard Pengantin (`resources/views/dashboard/index.blade.php`)**: Kartu hitung mundur Hari-H, 4 kartu ringkasan KPI, grafik distribusi anggaran, dan kalkulator weton neptu.
     - **Budget Planner UI (`resources/views/budget/index.blade.php`)**: Widget *Smart Budget Allocator* untuk adat Jawa/Sunda/Modern, kartu alokasi, dan tabel rincian pos biaya.
     - **Checklist UI (`resources/views/checklist/index.blade.php`)**: Daftar tugas interaktif dengan checkbox instan, filter kategori pil, dan filter PIC.
     - **Timeline & Rundown UI (`resources/views/timeline/index.blade.php`)**: Segmented control switcher untuk linimasa bulanan dan tabel rundown menit-demi-menit Hari-H.
     - **Vendor Directory UI (`resources/views/vendors/index.blade.php`)**: Kartu vendor bersih, progress bar pelunasan, dan tombol WhatsApp PIC.
     - **Buku Tamu UI (`resources/views/guests/index.blade.php`)**: Filter kategori tamu, status RSVP, penataan meja, dan tombol aksi WhatsApp.
  3. **Komponen Bersama (*Shared UI Kit*)**:
     - `resources/views/components/ui/{stat-card,section-header,modal,status-badge,progress-bar,icon}.blade.php`.
     - Segmented control navigasi pada `resources/views/layouts/app.blade.php`.
- **Berkas yang Dikelola**:
  - Seluruh direktori `resources/views/**/*`
  - `resources/css/app.css` & `resources/js/app.js`

---

### C. Septiyan Adam Maulana (`branch: adam`) — SENIOR BACKEND (OPERATIONS & VENDORS)
- **Fokus Utama**: Membangun lapisan data (*backend data layer*), relasi Eloquent, validasi transaksi, dan logika operasional untuk Modul Linimasa, Rundown Hari-H, dan Mitra Vendor.
- **Tanggung Jawab Spesifik**:
  1. **Modul Rundown Hari-H & Linimasa (`WeddingRundown` & `WeddingTask`)**:
     - Mengembangkan logika penyimpanan agenda rundown menit-demi-menit (`POST /timeline/rundowns`).
     - Membangun logika pembaruan status tugas linimasa (`PATCH /timeline/{weddingTask}/status`).
     - Menyusun filter kronologis per fase persiapan (H-12 Bulan s/d Hari-H).
  2. **Modul Vendor & Arsitektur Multi-Termin (`Vendor` & `VendorPayment`)**:
     - Mengembangkan CRUD vendor dengan validasi nomor telepon dan kategori `WeddingCategory`.
     - Membangun endpoint pencatatan termin pembayaran bertahap (`POST /vendors/{vendor}/payments`).
     - Menyusun logika otomatisasi: Setiap pembayaran termin dicatat, kolom `paid_amount` vendor bertambah, sisa pelunasan dihitung ulang, dan status booking otomatis beralih (`DpPaid` $\rightarrow$ `Paid`).
  3. **Optimasi Query & Eager Loading**:
     - Mencegah N+1 query dengan eager loading terstruktur: `with(['payments', 'budgetItems', 'tasks'])`.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/TimelineController.php` & `VendorController.php`
  - `app/Models/WeddingRundown.php`, `Vendor.php`, `VendorPayment.php`
  - `database/migrations/*create_vendors*` & `*create_rundowns_guests_and_payments*`

---

### D. Adinda Aulia Sabrina Arumsari (`branch: dinda`) — SENIOR BACKEND (GUESTS & WORKFLOWS)
- **Fokus Utama**: Membangun lapisan data untuk Modul Buku Tamu Digital, konfirmasi kehadiran (*RSVP*), alur kerja checklist persiapan, serta pengujian otomatis (*automated test suite*).
- **Tanggung Jawab Spesifik**:
  1. **Modul Buku Tamu Digital & RSVP (`WeddingGuest`)**:
     - Membangun `GuestController` lengkap untuk penanganan data tamu undangan (Nama, Kategori VIP/Keluarga/Teman, Jumlah Pax, Sesi Acara, Status RSVP, No Meja).
     - Mengembangkan accessor WhatsApp URL pada model `WeddingGuest` (`$guest->whatsapp_url`) untuk memformat nomor telepon internasional `62...` dan mengenerate pesan konfirmasi otomatis.
     - Menyusun kalkulasi agregasi kapasitas: Total Tamu Terdata, Konfirmasi Hadir, Menunggu Konfirmasi, dan Berhalangan terhadap `guest_target`.
  2. **Workflow Checklist Persiapan (`WeddingTask`)**:
     - Mengembangkan endpoint toggle status tugas instan (`PATCH /checklist/{weddingTask}/toggle`).
     - Menyusun filter pencarian teks judul/catatan, filter prioritas (`high`, `medium`, `low`), dan filter penanggung jawab (`assigned_to`).
  3. **Otomasi Pengujian Backend (PHPUnit Test Suite)**:
     - Menjaga cakupan pengujian di `tests/Feature/WeddingPlannerModulesTest.php` dan `tests/Feature/TenancyTest.php`.
     - Memastikan 22 test skenario fungsional dan isolasi multi-tenancy selalu lulus 100%.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/ChecklistController.php` & `GuestController.php`
  - `app/Models/WeddingGuest.php` & `WeddingTask.php`
  - `tests/Feature/{WeddingPlannerModulesTest,TenancyTest}.php`

---

## 3. Matriks Skill & Tool Developer Rekomendasi per Peran

Untuk mempercepat pengerjaan dan menjaga standar kualitas industri (*best practices*), berikut skill agen dan perkakas yang disarankan untuk masing-masing peran:

### A. Untuk Lead & Admin Architect (Irsyad)
- **`affaan-m/ecc@laravel-security`** (13.1K installs): Audit keamanan hak akses, pencegahan bypass auth, dan hardening backend.
- **`affaan-m/ecc@twelve-factor`**: Kepatuhan standar arsitektur cloud/SaaS (konfigurasi env, stateless process, port binding).
- **`affaan-m/ecc@deployment-patterns`**: Pola CI/CD, optimasi kontainer Docker, dan kesiapan rilis produksi.
- *Perintah instalasi*: `npx skills add affaan-m/ecc@laravel-security`

### B. Untuk Lead Frontend Engineer (Marsha)
- **`anthropics/skills@frontend-design`** (1.1M installs): Fondasi desain frontend modern, tipografi, dan komposisi ruang.
- **`leonxlnx/taste-skill@design-taste-frontend`** (748K installs): Aturan anti-slop, penghapusan teks berlebih, palet warna elegan, dan rasio visual premium.
- **`apple-ui-designer`**: Penerapan pedoman Apple Human Interface Guidelines (SF Pro aesthetic, radius halus, segmented control).
- **`refactoring-ui`**: Hierarki visual melalui bobot & ukuran teks, kontras warna, dan skala spasi proporsional.
- *Perintah instalasi*: `npx skills add anthropics/skills@frontend-design`

### C. Untuk Backend Operations Engineer (Adam)
- **`affaan-m/ecc@laravel-patterns`** (12.6K installs): Pola arsitektur controller, service layer, Eloquent scopes, dan query optimization.
- **`jeffallan/claude-skills@laravel-specialist`** (23.8K installs): Panduan spesifik ekosistem Laravel modern (PHP 8.3/8.4, Enum casts, Form Requests).
- **`affaan-m/ecc@api-design`**: Standarisasi penamaan endpoint, HTTP verb (POST/PUT/PATCH/DELETE), dan response contract.
- *Perintah instalasi*: `npx skills add affaan-m/ecc@laravel-patterns`

### D. Untuk Backend Workflow & QA Engineer (Dinda)
- **`affaan-m/ecc@laravel-tdd`** (8.5K installs): Pengembangan berbasis tes (Test-Driven Development), pembuatan fixture, dan assertion HTTP status.
- **`anthropics/skills@webapp-testing`** (200K installs): Verifikasi end-to-end, visual regression testing, dan pengujian form input.
- **`affaan-m/ecc@error-handling`**: Penanganan boundary validation, fail-fast rules, dan pencegahan error query 500.
- *Perintah instalasi*: `npx skills add affaan-m/ecc@laravel-tdd`

---

## 4. Alur Kolaborasi Git Harian

1. **Sinkronisasi Kode**:
   ```bash
   git checkout <branch-anda>
   git pull origin main
   php artisan migrate:fresh --seed
   npm run build
   ```
2. **Pengujian Sebelum Kirim**:
   ```bash
   php artisan test
   ./vendor/bin/pint --test
   ```
3. **Commit & Push**:
   ```bash
   git add .
   git commit -m "feat(<nama-fitur>): penjelasan singkat pekerjaan"
   git push origin <branch-anda>
   ```
