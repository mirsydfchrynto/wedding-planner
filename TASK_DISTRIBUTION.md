# Pembagian Tugas Tim & Panduan Kolaborasi Git — Atelier Vow Wedding Planner

Dokumen ini adalah **sumber kebenaran tunggal (*Single Source of Truth*)** pembagian kerja tim rekayasa perangkat lunak untuk aplikasi **Atelier Vow Nusantara — Luxury Wedding Planner & Backoffice SaaS**.

---

## 1. Peta Kepemilikan Modul & Branch Git

| No | Nama Lengkap | Akun GitHub | Branch Git | Modul & Tanggung Jawab Utama | Status Pengerjaan |
|:---|:---|:---|:---|:---|:---|
| 1 | **M. Irsyad Fachryanto** | `@mirsydfchrynto` | `irsyad` (Lead) | **Arsitektur Inti, Multi-Tenancy, Master Guide Print A4 (`/print/master-guide`), & Docker Deployment** | **100% SELESAI (Aktif di `main`)** |
| 2 | **Marsha Dwi Lucyana** | `@marshadwi` | `marsha` | **Modul #1: Budget Planner (`/budget`) & Multi-Termin Payment Breakdown** | *Siap Dikerjakan (Skema Siap)* |
| 3 | **Septiyan Adam Maulana** | `@adambyt3` | `adam` | **Modul #3: Timeline & Live Rundown Hari-H (`/timeline`), Modul #4: Vendor SLA (`/vendors`)** | *Siap Dikerjakan (Skema Siap)* |
| 4 | **Adinda Aulia Sabrina Arumsari** | `@adinda0112` | `dinda` | **Modul #2: Wedding Checklist (`/checklist`) & Modul Buku Tamu Digital / RSVP (`/guests`)** | *Siap Dikerjakan (Skema Siap)* |

---

## 2. Rincian Tugas & Panduan Implementasi per Anggota

### A. M. Irsyad Fachryanto (`branch: irsyad` & `main`) — LEAD ARCHITECT
- **Status**: **SELESAI & SUDAH DIRILIS DI `main` (v0.1.1)**
- **Fitur & Berkas yang Diselesaikan**:
  1. **Multi-Tenancy & Isolasi Data**:
     - `app/Models/Concerns/BelongsToWeddingProfile.php` (Global Scope tenant).
     - `app/Http/Middleware/EnsureWeddingProfile.php` (Gate onboarding otomatis).
     - `app/Http/Controllers/OnboardingController.php` & `resources/views/onboarding/*`.
     - `database/migrations/2026_11_06_000001_add_tenant_ownership_columns.php`.
     - `tests/Feature/TenancyTest.php` (5 test isolasi multi-tenant).
  2. **Fondasi Skema Bersama Tim**:
     - `database/migrations/2026_11_06_000002_create_rundowns_guests_and_payments_tables.php`.
     - Model `WeddingRundown`, `WeddingGuest`, `VendorPayment` (lengkap relasi ke `WeddingProfile` & `Vendor`).
     - Seeder `WeddingPlannerSeeder` diperbarui dengan data rundown hari-H, buku tamu, dan riwayat termin.
  3. **Cetak Master Guide Pernikahan A4 / PDF (`GET /print/master-guide`)**:
     - `app/Http/Controllers/PrintController.php`.
     - `resources/views/print/master-guide.blade.php` (Editorial layout, `@media print` 4 halaman: Ringkasan Eksekutif & Weton, Rundown Menit-demi-Menit, Kontak Darurat Vendor, Penataan Meja Tamu VIP).
     - Tombol *Buku Panduan & Cetak* di Dashboard terhubung langsung ke view ini.
  4. **Infrastruktur Produksi & Docker**:
     - `Dockerfile` multi-stage production PHP 8.4-FPM + Nginx.
     - `docker-compose.yml` (App, PostgreSQL 16, Redis 7, Nginx).
     - `docker/nginx.conf`, `.dockerignore`, dan skrip eksekusi satu baris `./deploy.sh`.

---

### B. Marsha Dwi Lucyana (`branch: marsha`) — BUDGET & FINANCIAL LEAD
- **Modul**: **Fitur #1: Budget Planner (`GET /budget`)**
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/BudgetController.php`
  - `resources/views/budget/index.blade.php`
  - `app/Models/BudgetItem.php` & `app/Models/VendorPayment.php`
- **Rincian Tugas yang Dikerjakan**:
  1. **Tampilan Riwayat Multi-Termin Pembayaran**:
     - Skema tabel `vendor_payments` sudah disediakan oleh Irsyad.
     - Tambahkan sub-panel / accordion di baris item anggaran vendor untuk menampilkan riwayat termin yang sudah dibayar (DP, Termin 1, Pelunasan) beserta tanggal bayar dan nomor referensi kwitansi (`receipt_ref`).
  2. **Smart Budget Allocator (Preset Adat Nusantara)**:
     - Tambahkan tombol / dropdown rekomendasi alokasi anggaran otomatis berdasarkan konsep:
       - **Adat Jawa Paes Ageng**: Venue 25%, Catering 35%, Busana & Paes 18%, Dekorasi 12%, Dokumentasi 6%, Lainnya 4%.
       - **Adat Sunda Siger**: Venue 22%, Catering 38%, Busana & Rias 16%, Dekorasi 14%, Dokumentasi 6%, Lainnya 4%.
       - **Modern Intimate**: Venue & Catering 50%, Dekorasi & Lighting 20%, Dokumentasi Editorial 15%, Entertainment 10%, Lainnya 5%.
  3. **Breakdown Biaya per Pax Tamu**:
     - Tampilkan kalkulasi real-time: `Biaya per Tamu = Plafon Total / Target Tamu` dan komparasi dengan `Realisasi Aktual / Target Tamu`.

---

### C. Septiyan Adam Maulana (`branch: adam`) — TIMELINE & VENDOR LEAD
- **Modul**: **Fitur #3: Wedding Timeline (`GET /timeline`)** & **Fitur #4: Vendor Management (`GET /vendors`)**
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/TimelineController.php` & `VendorController.php`
  - `resources/views/timeline/index.blade.php`
  - `resources/views/vendors/index.blade.php`
  - `app/Models/WeddingRundown.php` & `app/Models/Vendor.php`
- **Rincian Tugas yang Dikerjakan**:
  1. **Tab Baru: Rundown Acara Hari-H (Minute-by-Minute Live Rundown)**:
     - Skema tabel `wedding_rundowns` dan data seeder sudah disediakan oleh Irsyad.
     - Buat tab switcher di `/timeline`:
       - Tab 1: **Timeline Persiapan Bulanan (H-12 bln s.d. Hari-H)** (sudah ada).
       - Tab 2: **Rundown Acara Hari-H (Jadwal Menit-demi-Menit)** (fitur baru).
     - Di Tab 2, tampilkan tabel/kartu interaktif: Jam Mulai, Jam Selesai, Sesi Acara, Aktivitas, Lokasi, Nama PIC, No Telepon PIC (klik langsung ke WhatsApp atau telepon), dan Catatan Khusus.
     - Sediakan modal tambah/edit item rundown (`POST /timeline/rundowns`).
  2. **Form Catat Bukti Termin Pembayaran Vendor**:
     - Di halaman `/vendors`, perbarui form pembayaran agar mendukung pencatatan riwayat termin pembayaran ke tabel `vendor_payments` (bukan hanya menimpa angka `paid_amount`).
     - Setiap kali pembayaran baru dicatat: update `paid_amount` vendor secara otomatis.

---

### D. Adinda Aulia Sabrina Arumsari (`branch: dinda`) — CHECKLIST & GUEST OPS LEAD
- **Modul**: **Fitur #2: Checklist Persiapan (`GET /checklist`)** & **Fitur Tambahan: Buku Tamu Digital & RSVP (`GET /guests`)**
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/ChecklistController.php`
  - `resources/views/checklist/index.blade.php`
  - `app/Http/Controllers/GuestController.php` (baru)
  - `resources/views/guests/index.blade.php` (baru)
  - `app/Models/WeddingTask.php` & `app/Models/WeddingGuest.php`
- **Rincian Tugas yang Dikerjakan**:
  1. **Modul Buku Tamu Digital & Manajemen RSVP (`/guests`)**:
     - Skema tabel `wedding_guests` dan model `WeddingGuest` sudah dibuat oleh Irsyad.
     - Buat `GuestController` dan view `resources/views/guests/index.blade.php`.
     - Tampilkan tabel daftar tamu: Nama, Kategori (VIP / Keluarga CPW / Keluarga CPP / Teman), Jumlah Pax, Sesi Acara (Akad / Resepsi Malam), Nomor Meja, Status RSVP (Pending / Confirmed / Declined).
     - Tombol cepat kirim undangan/konfirmasi kehadiran via WhatsApp menggunakan accessor `$guest->whatsapp_url`.
     - Ringkasan kuota tamu: Total Tamu Terdata, Tamu Terkonfirmasi Hadir, dan Sisa Kuota terhadap `guest_target`.
  2. **Filter PIC Penanggung Jawab pada Checklist**:
     - Di halaman `/checklist`, tambahkan filter dropdown berdasarkan `assigned_to` (Calon Pengantin, Orang Tua, Lead WO, Vendor).
     - Tampilkan badge prioritas tinggi (Merah) dengan visual yang lebih menonjol.

---

## 3. Alur Kerja Git Harian Setiap Anggota Tim

Agar kode tidak bentrok (*conflict-free collaboration*):

1. **Pindah ke Branch Sendiri & Tarik Kode Terbaru dari `main`**:
   ```bash
   git checkout <branch-anda>   # contoh: git checkout marsha
   git pull origin main         # mengambil migrasi, model, dan fitur dari Irsyad
   ```
2. **Jalankan Migrasi Database Lokal**:
   ```bash
   php artisan migrate --seed
   ```
3. **Kembangkan Fitur Sesuai Batas Berkas Masing-masing**.
4. **Jalankan Pengujian & Format Kode Sebelum Push**:
   ```bash
   php artisan test
   npm run build
   ./vendor/bin/pint --test
   ```
5. **Commit dan Push ke Branch Pribadi**:
   ```bash
   git add .
   git commit -m "feat(<nama-fitur>): penjelasan singkat perubahan"
   git push origin <branch-anda>
   ```
6. **Buka Pull Request (PR) ke `main` di GitHub**.
