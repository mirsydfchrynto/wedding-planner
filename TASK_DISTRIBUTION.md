# Pembagian Tugas Tim & Spesifikasi Peran (Enterprise Full-Stack Model)
## Atelier Vow Nusantara — Luxury Wedding Planner & Backoffice SaaS

Dokumen ini adalah **sumber kebenaran tunggal (*Single Source of Truth*)** pembagian kerja tim rekayasa perangkat lunak untuk aplikasi **Atelier Vow Nusantara**. Struktur tim dipimpin oleh **Irsyad sebagai Project Lead & PM Integrator**, didampingi **Adam di Frontend (Web Utuh)**, serta **Marsha & Dinda di Backend Core**.

---

## 1. Peta Peran Tim, Tanggung Jawab, & Git Branch

| No | Nama Anggota | Akun GitHub | Branch Git | Peran Utama (*Core Role*) | Cakupan Kerja & Modul | Status |
|:---|:---|:---|:---|:---|:---|:---|
| 1 | **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` / `main` | **Project Lead, Project Manager & System Integrator** | Memimpin proyek, integrasi seluruh modul, melengkapi kekurangan sistem, Cetak Master Guide (`/print/master-guide`), Keamanan Multi-Tenancy, dan Rilis Docker | **100% Selesai & Teruji** |
| 2 | **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Lead Frontend Engineer (Web Utuh UI/UX)** | Seluruh Antarmuka Pengguna Web Utuh (Design System Vogue, Blade Views, Alpine.js, Responsivitas Mobile, Apple HIG & Refactoring UI Polish untuk 6 Modul Utama) | **100% Selesai & Teruji** |
| 3 | **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Senior Backend Engineer (Financial & Vendors)** | Data Layer & Logika Bisnis Modul Budget Planner (`/budget`), Kalkulasi Deviasi, Smart Adat Allocator Logic, dan Arsitektur Multi-Termin Vendor (`VendorPayment`) | **100% Selesai & Teruji** |
| 4 | **Adinda Aulia Sabrina Arumsari** | `@adinda0112` | `dinda` | **Senior Backend Engineer (Guests & Workflows)** | Data Layer & Logika Bisnis Modul Buku Tamu Digital & RSVP (`WeddingGuest`), Workflow Checklist Tugas (`WeddingTask`), WhatsApp Link Generator, dan Otomasi Pengujian PHPUnit | **100% Selesai & Teruji** |

---

## 2. Rincian Job Desk Mendalam per Anggota Tim

### A. M. Irsyad Fachryanto (`branch: irsyad`) — PROJECT LEAD, PROJECT MANAGER & SYSTEM INTEGRATOR
- **Fokus Utama**: Memimpin arah teknis proyek, menyatukan seluruh pekerjaan tim (*system integration*), melengkapi setiap celah/kekurangan fitur, serta mengamankan arsitektur dan rilis server.
- **Tanggung Jawab Spesifik**:
  1. **Manajemen Proyek & Integrasi Sistem (*Merge Master*)**:
     - Mengatur branching strategy Git, me-review Pull Request dari branch `adam`, `marsha`, dan `dinda`, serta menggabungkannya ke `main` tanpa konflik kode (*zero merge conflicts*).
     - Menyatukan API contracts antara antarmuka Blade/Alpine dengan controller dan database.
     - Menyisir dan melengkapi fitur yang belum tuntas (*finishing & gap-filling*) pada seluruh modul agar aplikasi 100% siap pakai.
  2. **Konsol Administrator (`/admin`) — dihapus**:
     - Konsol admin beserta middleware `EnsureAdminAccess` dihapus pada commit `3f3a236` (simplifikasi fokus pelanggan).
  3. **Mesin Cetak Dokumen Resmi Master Guide A4/PDF (`/print/master-guide`)**:
     - Generator cetak presisi 4 halaman A4 (*Vogue Bridal Editorial*) untuk buku panduan lapangan panitia dan pengantin tanpa internet.
  4. **Arsitektur Multi-Tenancy & Keamanan**:
     - Trait `BelongsToWeddingProfile` dengan Global Scope Eloquent agar seluruh data pasangan terisolasi aman.
     - Gerbang onboarding otomatis (`/onboarding`) dan penanganan akun admin tanpa profil pernikahan pada alur Google OAuth.
  5. **DevOps & Kontainerisasi**:
     - `Dockerfile` multi-stage (PHP 8.4-FPM + Node 22 Vite Build + OPcache).
     - `docker-compose.yml` (App, PostgreSQL 16, Redis 7, Nginx Reverse Proxy).
     - Skrip deployment otomatis satu baris `./deploy.sh`.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/{AuthController,OnboardingController,PrintController,DashboardController}.php`
  - `app/Models/Concerns/BelongsToWeddingProfile.php`
  - `Dockerfile`, `docker-compose.yml`, `docker/nginx.conf`, `deploy.sh`, `TASK_DISTRIBUTION.md`

---

### B. Septiyan Adam Maulana (`branch: adam`) — LEAD FRONTEND ENGINEER (WEB UTUH)
- **Fokus Utama**: Berkolaborasi dengan Project Lead untuk memegang **seluruh tampilan web utuh (*full frontend*)** agar antarmuka tenang, mudah digunakan, bebas dari tumpukan teks (*decluttered*), responsif di layar smartphone, dan memiliki standar visual kelas atas (Apple HIG & Refactoring UI).
- **Tanggung Jawab Spesifik**:
  1. **Sistem Desain & Tipografi Visual (Apple HIG & Refactoring UI)**:
     - Mengelola token warna arsitektural di `resources/css/app.css` (Alabaster, Ivory, Pearl, Espresso, Champagne Bronze, Sage).
     - Menerapkan radius sudut Apple-style (`rounded-2xl` untuk kartu, `rounded-xl` untuk tombol/input).
     - Menghapus paragraf penjelasan berlebih (*decluttering*) pada seluruh header dan kartu KPI.
  2. **Pengembangan Seluruh Tampilan Modul (Blade Views)**:
    - **Landing Page SaaS** — dihapus pada commit `3f3a236`; `/` kini hanya mengalihkan ke login/dashboard/onboarding.
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
  - Seluruh direktori `resources/views/**/*` (selain admin dan print)
  - `resources/css/app.css` & `resources/js/app.js`

---

### C. Marsha Dwi Lucyana (`branch: marsha`) — SENIOR BACKEND (FINANCIAL & VENDORS)
- **Fokus Utama**: Membangun lapisan data (*backend data layer*), logika finansial, relasi Eloquent, dan arsitektur pembayaran vendor multi-termin.
- **Tanggung Jawab Spesifik**:
  1. **Logika Finansial & Budget Planner (`BudgetItem` & `BudgetController`)**:
     - Mengembangkan CRUD pos anggaran dengan kalkulasi deviasi real-time (`is_overbudget`, `remaining_balance`, `variance_amount`).
     - Menyusun logika *Smart Budget Allocator* untuk 3 preset adat Nusantara (Jawa Paes Ageng, Sunda Siger, dan Modern Intimate).
     - Menghitung rasio biaya per pax tamu undangan (`total_budget / guest_target`).
  2. **Arsitektur Multi-Termin Vendor (`Vendor` & `VendorPayment`)**:
     - Mengembangkan endpoint pencatatan termin pembayaran bertahap (`POST /vendors/{vendor}/payments`).
     - Logika pembaruan saldo otomatis: Setiap pembayaran termin dicatat, kolom `paid_amount` vendor bertambah, sisa pelunasan dihitung ulang, dan status beralih otomatis ke `Paid`.
     - Optimasi eager loading `with(['payments', 'budgetItems'])` untuk mencegah masalah N+1 query.
  3. **Validasi Transaksi (Form Requests)**:
     - `app/Http/Requests/{StoreBudgetItemRequest,UpdateBudgetItemRequest}.php`.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/BudgetController.php` & `VendorController.php`
  - `app/Models/BudgetItem.php`, `Vendor.php`, `VendorPayment.php`
  - `database/migrations/*budget_items*` & `*rundowns_guests_and_payments*`

---

### D. Adinda Aulia Sabrina Arumsari (`branch: dinda`) — SENIOR BACKEND (GUESTS & WORKFLOWS)
- **Fokus Utama**: Membangun lapisan data untuk Modul Buku Tamu Digital, konfirmasi kehadiran (*RSVP*), alur kerja checklist persiapan, serta pengujian otomatis (*automated test suite*).
- **Tanggung Jawab Spesifik**:
  1. **Modul Buku Tamu Digital & RSVP (`WeddingGuest`)**:
     - Membangun `GuestController` lengkap untuk penanganan data tamu undangan (Nama, Kategori VIP/Keluarga/Teman, Sesi Acara, Jumlah Pax, Status RSVP, No Meja).
     - Mengembangkan accessor WhatsApp URL pada model `WeddingGuest` (`$guest->whatsapp_url`) untuk memformat nomor telepon internasional `62...` dan mengenerate pesan konfirmasi otomatis.
     - Menyusun agregasi kapasitas: Total Tamu Terdata, Konfirmasi Hadir, Menunggu Konfirmasi, dan Berhalangan terhadap `guest_target`.
  2. **Workflow Checklist Persiapan (`WeddingTask`)**:
     - Mengembangkan endpoint toggle status tugas instan (`PATCH /checklist/{weddingTask}/toggle`).
     - Menyusun filter pencarian teks judul/catatan, filter prioritas (`high`, `medium`, `low`), dan filter penanggung jawab (`assigned_to`).
  3. **Otomasi Pengujian Backend (PHPUnit Test Suite)**:
     - Mengelola seluruh pengujian otomatis di `tests/Feature/WeddingPlannerModulesTest.php` dan `tests/Feature/TenancyTest.php`.
     - Memastikan seluruh 22 skenario pengujian fungsional dan isolasi tenant selalu lulus 100%.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/ChecklistController.php` & `GuestController.php`
  - `app/Models/WeddingGuest.php` & `WeddingTask.php`
  - `tests/Feature/{WeddingPlannerModulesTest,TenancyTest}.php`

---

## 3. Matriks Skill & Tool Developer Rekomendasi per Peran

Berikut skill terverifikasi dari ekosistem agen untuk masing-masing peran:

### A. Untuk Project Lead & PM Integrator (Irsyad)
- **`affaan-m/ecc@laravel-security`** (13.1K installs): Pengamanan hak akses, token sesi, dan pencegahan eskalasi hak akses.
- **`affaan-m/ecc@twelve-factor`**: Kepatuhan standar arsitektur cloud/SaaS.
- **`affaan-m/ecc@deployment-patterns`**: Pola orkestrasi kontainer dan optimasi produksi.
- *Perintah instalasi*: `npx skills add affaan-m/ecc@laravel-security`

### B. Untuk Lead Frontend Engineer (Adam)
- **`anthropics/skills@frontend-design`** (1.1M installs): Standar desain web modern, komposisi, dan tipografi.
- **`leonxlnx/taste-skill@design-taste-frontend`** (748K installs): Aturan anti-slop, penghapusan teks berlebih (*decluttering*), dan rasio visual premium.
- **`apple-ui-designer`**: Penerapan pedoman Apple Human Interface Guidelines (SF Pro aesthetic, radius halus, segmented control).
- **`refactoring-ui`**: Hierarki visual berbasis ukuran/bobot teks, kontras warna, dan skala spasi proporsional.
- *Perintah instalasi*: `npx skills add anthropics/skills@frontend-design`

### C. Untuk Senior Backend Financial (Marsha)
- **`jeffallan/claude-skills@laravel-specialist`** (23.8K installs): Standar arsitektur Laravel modern (PHP 8.3/8.4, Enum casts, Form Requests).
- **`affaan-m/ecc@laravel-patterns`** (12.6K installs): Pola service layer, query scopes, dan relasi Eloquent.
- **`affaan-m/ecc@api-design`**: Standarisasi RESTful API dan kontrak response.
- *Perintah instalasi*: `npx skills add jeffallan/claude-skills@laravel-specialist`

### D. Untuk Senior Backend Workflows & Testing (Dinda)
- **`affaan-m/ecc@laravel-tdd`** (8.5K installs): Pengembangan berbasis tes (Test-Driven Development) dan penulisan assertion HTTP.
- **`anthropics/skills@webapp-testing`** (200K installs): Verifikasi alur kerja form dan validasi data.
- **`affaan-m/ecc@error-handling`**: Penanganan fail-fast boundary validation dan pencegahan error query 500.
- *Perintah instalasi*: `npx skills add affaan-m/ecc@laravel-tdd`
