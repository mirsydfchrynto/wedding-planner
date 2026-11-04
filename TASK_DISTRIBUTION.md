# Pembagian Tugas Tim & Panduan Kolaborasi Git — Wedding Planner

Dokumen ini merupakan acuan kerja resmi untuk 4 anggota tim pengembang **Atelier Vow — Wedding Planner** agar pembagian tugas seimbang, terukur, dan bebas dari konflik penggabungan kode (*zero merge conflicts*).

---

## 1. Peta Kepemilikan Modul & Branch Git

Setiap anggota memiliki **1 branch khusus** yang sudah diinisialisasi di GitHub (`origin`):

| No | Nama Lengkap | Branch Git | Modul & Tanggung Jawab Utama |
| :-: | :--- | :--- | :--- |
| 1 | **M. Irsyad Fachryanto** | `irsyad` | **Arsitektur Utama, UI Design System & Modul #5: Executive Dashboard** |
| 2 | **Marsha Dwi Lucyana** | `marsha` | **Modul #1: Budget Planner (Perencanaan & Realisasi Anggaran)** |
| 3 | **Septiyan Adam Maulana** | `adam` | **Modul #3: Wedding Timeline & Modul #4: Vendor Management** |
| 4 | **Adinda Aulia Sabrina Arumsari** | `dinda` | **Modul #2: Wedding Checklist (Manajemen Tugas Persiapan)** |

---

## 2. Rincian Tugas & Batas Berkas (*File Ownership*) per Anggota

### A. M. Irsyad Fachryanto (`branch: irsyad`)
- **Fokus Utama**: Arsitektur Frontend, Design System (*Vogue Bridal Editorial*), Layanan Agregasi Metrik, serta **Fitur #5: Dashboard Eksekutif & Pengaturan Profil Pasangan**.
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/DashboardController.php`
  - `app/Services/WeddingMetricsService.php`
  - `resources/views/dashboard/index.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/components/ui/*` (`stat-card`, `section-header`, `status-badge`, `progress-bar`, `modal`)
  - `resources/css/app.css` & `resources/js/app.js`
- **Daftar Tugas & Pengembangan**:
  1. Memelihara konsistensi token desain Tailwind v4 (`#FAF8F5` Alabaster, `#8C6D46` Champagne Bronze, tipografi `Cormorant Garamond` + `Plus Jakarta Sans`).
  2. Mengelola *Countdown* waktu nyata (Hari, Jam, Menit) menuju Hari-H pernikahan dan formulir pembaruan profil pasangan (`PATCH /profile`).
  3. Menyajikan grafik perbandingan Estimasi vs Aktual per kategori menggunakan Chart.js serta ringkasan kesiapan seluruh modul.

---

### B. Marsha Dwi Lucyana (`branch: marsha`)
- **Fokus Utama**: **Fitur #1: Budget Planner** (Perencanaan Anggaran, Pembagian Kategori, dan Pelacakan Realisasi Pengeluaran).
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/BudgetController.php`
  - `app/Http/Requests/StoreBudgetItemRequest.php`
  - `app/Http/Requests/UpdateBudgetItemRequest.php`
  - `resources/views/budget/index.blade.php`
  - `app/Models/BudgetItem.php`
- **Daftar Tugas & Pengembangan**:
  1. Mengelola plafon anggaran induk pernikahan (`PATCH /budget/cap`) dan perhitungan otomatis sisa anggaran.
  2. Mengelola fitur tambah, ubah, dan hapus komponen biaya (`POST /budget`, `PUT /budget/{budgetItem}`, `DELETE /budget/{budgetItem}`) pada 8 kategori pernikahan (*Venue*, *Catering*, *Busana*, *Dekorasi*, *Dokumentasi*, *Souvenir*, *Dokumen*, *Entertainment*).
  3. Menampilkan indikator selisih biaya (*Hemat* / *Overbudget*) dan progres serapan anggaran per kategori.

---

### C. Septiyan Adam Maulana (`branch: adam`)
- **Fokus Utama**: **Fitur #3: Wedding Timeline** (Linimasa Kronologis `H-12 Bulan` s/d `Hari-H`) dan **Fitur #4: Vendor Management** (Direktori Mitra & Pelacakan Kontrak/DP).
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/TimelineController.php`
  - `app/Http/Controllers/VendorController.php`
  - `resources/views/timeline/index.blade.php`
  - `resources/views/vendors/index.blade.php`
  - `app/Models/Vendor.php`
- **Daftar Tugas & Pengembangan**:
  1. Menampilkan peta linimasa kronologis per fase (`H-12 Bulan`, `H-9 Bulan`, `H-6 Bulan`, `H-3 Bulan`, `H-1 Bulan`, `H-7 Hari`, `Hari-H`), filter *Milestone Utama*, dan pembaruan status cepat (`PATCH /timeline/{weddingTask}/status`).
  2. Mengelola direktori vendor pernikahan (`POST /vendors`, `PUT /vendors/{vendor}`, `DELETE /vendors/{vendor}`), tautan langsung WhatsApp PIC vendor, serta pelacakan nilai kontrak vs DP/pelunasan (`PATCH /vendors/{vendor}/payment`).

---

### D. Adinda Aulia Sabrina Arumsari (`branch: dinda`)
- **Fokus Utama**: **Fitur #2: Wedding Checklist** (Daftar Tugas Persiapan Pernikahan, Filter Kategori & PIC, dan Status Penyelesaian).
- **Berkas yang Dikelola**:
  - `app/Http/Controllers/ChecklistController.php`
  - `resources/views/checklist/index.blade.php`
  - `app/Models/WeddingTask.php`
- **Daftar Tugas & Pengembangan**:
  1. Mengelola daftar checklist tugas persiapan pernikahan (`POST /checklist`, `PUT /checklist/{weddingTask}`, `DELETE /checklist/{weddingTask}`).
  2. Menyediakan fitur centang selesai satu-klik (`PATCH /checklist/{weddingTask}/toggle`) yang otomatis mencatat waktu `completed_at` dan memperbarui persentase progres kesiapan di Dashboard.
  3. Menyediakan penyaringan tugas berdasarkan kategori (`Dokumen`, `Venue`, `Catering`, `Busana`, dll.), status (`Belum Dimulai`, `Sedang Berjalan`, `Selesai`), dan penanggung jawab (`PIC`).

---

## 3. Alur Kerja Git Harian untuk Setiap Anggota

1. **Mulai Bekerja di Branch Sendiri**:
   ```bash
   git checkout <nama-branch-anda>
   git pull origin main
   ```
2. **Simpan Perubahan & Uji**:
   ```bash
   php artisan test
   npm run build
   git add .
   git commit -m "feat(<modul>): deskripsi singkat perubahan"
   git push origin <nama-branch-anda>
   ```
3. **Gabungkan ke `main` via Pull Request**:
   Buat *Pull Request* di GitHub dari branch Anda (`irsyad` / `marsha` / `adam` / `dinda`) menuju branch `main`.
