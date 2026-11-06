# Pembagian Tugas Tim & Panduan Kolaborasi Git — Atelier Vow Wedding Planner

Dokumen ini menjadi acuan kerja resmi untuk 4 anggota tim pengembang **Atelier Vow Nusantara** agar pembagian tugas seimbang, terukur, dan bebas dari *merge conflict*.

---

## 1. Alur Sistem (Public SaaS Landing → Google Login Customer → Private Admin)

1. **Web Utama Publik (`GET /`)**: Menampilkan **SaaS Brand Landing Page & Marketing Campaign** (simulasi anggaran & adat Nusantara, perbandingan 3 paket harga SaaS, dan tombol *Masuk dengan Google*).
2. **Gerbang Autentikasi (`GET /login`)**:
   - **Customer (Calon Pengantin)**: Klik **"Lanjutkan dengan Akun Google"** (`aurelia.reza@gmail.com`) untuk masuk ke workspace utama (`/dashboard`, `/budget`, `/checklist`, `/timeline`, `/vendors`).
   - **Administrator & Lead WO (Privat & Tersembunyi)**: Klik tautan bawah **"Otorisasi Privat Administrator & Lead WO"** lalu masukkan email `irsyad@ateliervow.id` dan kunci `AtelierAdmin2026!` untuk membuka `/admin`. Pelanggan biasa tidak dapat melihat maupun mengakses `/admin` (`403 Forbidden`).
3. **Onboarding Pengantin Baru (`GET /onboarding`)**: Akun yang baru masuk dan belum punya profil pernikahan otomatis diarahkan ke form onboarding. Setelah profil dibuat, seluruh data modul terkunci pada profil tersebut (`wedding_profile_id`), sehingga tidak ada kebocoran data antar akun.

---

## 2. Peta Kepemilikan Modul & Branch Git

| No | Nama Lengkap | Akun GitHub | Branch Git | Modul & Tanggung Jawab Utama |
| :-: | :--- | :--- | :--- | :--- |
| 1 | **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` | **Arsitektur Utama, SaaS Landing Page (`/`), Google Auth (`/login`), Private Admin Console (`/admin`), & Modul #5: Executive Dashboard (`/dashboard`)** |
| 2 | **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Modul #1: Budget Planner (`/budget` — Perencanaan & Realisasi Anggaran)** |
| 3 | **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Modul #3: Wedding Timeline (`/timeline`) & Modul #4: Vendor Management (`/vendors`)** |
| 4 | **Adinda Aulia Sabrina Arumsari** | `@adinda0112` | `dinda` | **Modul #2: Wedding Checklist (`/checklist` — Manajemen Tugas Persiapan)** |
---

## 3. Rincian Tugas & Batas Berkas (*File Ownership*) per Anggota

### A. M. Irsyad Fachryanto (`branch: irsyad`)
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/{SaasController,AuthController,AdminController,DashboardController}.php`
  - `app/Http/Middleware/EnsureAdminAccess.php`
  - `app/Services/WeddingMetricsService.php`
  - `app/Models/Concerns/BelongsToWeddingProfile.php`, `app/Http/Controllers/OnboardingController.php`, `app/Http/Middleware/EnsureWeddingProfile.php`, `database/migrations/2026_11_06_000001_add_tenant_ownership_columns.php`, `resources/views/onboarding/*`, `tests/Feature/TenancyTest.php`
  - `resources/views/{saas,auth,admin,dashboard}/*`
  - `resources/views/layouts/app.blade.php` & `resources/views/components/ui/*`
  - `resources/css/app.css` & `resources/js/app.js`

### B. Marsha Dwi Lucyana (`branch: marsha`)
- **Fokus Utama**: **Fitur #1: Budget Planner (`GET /budget`)**.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/BudgetController.php`
  - `app/Http/Requests/StoreBudgetItemRequest.php` & `UpdateBudgetItemRequest.php`
  - `resources/views/budget/index.blade.php`
  - `app/Models/BudgetItem.php`

### C. Septiyan Adam Maulana (`branch: adam`)
- **Fokus Utama**: **Fitur #3: Wedding Timeline (`GET /timeline`)** & **Fitur #4: Vendor Management (`GET /vendors`)**.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/TimelineController.php`
  - `app/Http/Controllers/VendorController.php`
  - `resources/views/timeline/index.blade.php`
  - `resources/views/vendors/index.blade.php`
  - `app/Models/Vendor.php`

### D. Adinda Aulia Sabrina Arumsari (`branch: dinda`)
- **Fokus Utama**: **Fitur #2: Wedding Checklist (`GET /checklist`)**.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/ChecklistController.php`
  - `resources/views/checklist/index.blade.php`
  - `app/Models/WeddingTask.php`

---

## 4. Alur Kerja Git Harian untuk Setiap Anggota

1. **Pindah ke Branch Sendiri & Tarik Update Terbaru**:
   ```bash
   git checkout <nama-branch-anda>
   git pull origin main
   ```
2. **Simpan Perubahan, Uji, & Push ke Branch Sendiri**:
   ```bash
   php artisan test
   npm run build
   git add .
   git commit -m "feat(<modul>): deskripsi singkat perubahan"
   git push origin <nama-branch-anda>
   ```
3. **Gabungkan ke `main` via Pull Request di GitHub**.
