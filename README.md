# Atelier Vow — Wedding Planner Web Application

Sistem informasi manajemen perencanaan pernikahan terpadu berbasis **Laravel 13 (PHP 8.3+)**, **Tailwind CSS v4**, **Alpine.js v3**, dan **Chart.js v4** dengan arahan visual **Vogue Bridal Editorial** (*Warm Alabaster `#FAF8F5` & Burnished Champagne Bronze `#8C6D46`*).

Aplikasi ini mengintegrasikan 5 fitur utama dalam satu basis data terhubung:
1. **Budget Planner (`GET /budget`)** — Pengaturan plafon anggaran induk, pembagian biaya pada 8 kategori utama, perbandingan biaya estimasi vs aktual, pelacakan nominal terbayar, dan indikator penghematan/overbudget.
2. **Wedding Checklist (`GET /checklist`)** — Daftar tugas persiapan pernikahan dengan filter kategori, status (*To-Do*, *In Progress*, *Done*), penanggung jawab (*PIC*), prioritas, serta centang penyelesaian instan.
3. **Wedding Timeline (`GET /timeline`)** — Peta linimasa kronologis persiapan dari fase **H-12 Bulan**, **H-9 Bulan**, **H-6 Bulan**, **H-3 Bulan**, **H-1 Bulan**, **H-7 Hari**, hingga **Hari-H**, dilengkapi penanda milestone dan peringatan tenggat waktu.
4. **Vendor Management (`GET /vendors`)** — Direktori mitra vendor pernikahan lengkap dengan kontak WhatsApp langsung, nilai kontrak, nominal DP/pelunasan, serta status booking (*Riset*, *Negosiasi*, *Booked*, *DP Paid*, *Paid*).
5. **Executive Dashboard (`GET /`)** — Pusat kendali pasangan yang menampilkan hitung mundur waktu nyata (*Hari, Jam, Menit*) menuju Akad Nikah, 4 kartu indikator kinerja utama (*KPI*), grafik perbandingan estimasi vs realisasi anggaran per kategori, daftar tugas mendesak, dan jadwal pelunasan vendor.

---

## Pembagian Tim & Cabang Git (*Branch*)

Proyek ini dikembangkan secara kolaboratif oleh 4 anggota tim dengan pemisahan vertikal (*Vertical Feature Slices*) sehingga setiap anggota bekerja pada berkas *Controller* dan *Blade View* masing-masing tanpa bentrokan (*merge conflict*):

| Anggota Tim | Branch Git | Peran & Modul yang Dikerjakan | Berkas Utama (*File Ownership*) |
| :--- | :--- | :--- | :--- |
| **M. Irsyad Fachryanto** | `irsyad` | **Lead Frontend Architecture, Design System & Modul #5: Executive Dashboard** | `app/Http/Controllers/DashboardController.php`<br>`resources/views/dashboard/index.blade.php`<br>`resources/views/layouts/app.blade.php`<br>`resources/views/components/ui/*`<br>`resources/css/app.css` |
| **Marsha Dwi Lucyana** | `marsha` | **Modul #1: Budget Planner & Pengendalian Biaya** | `app/Http/Controllers/BudgetController.php`<br>`app/Http/Requests/*BudgetItemRequest.php`<br>`resources/views/budget/index.blade.php` |
| **Septiyan Adam Maulana** | `adam` | **Modul #3: Wedding Timeline & Modul #4: Vendor Management** | `app/Http/Controllers/TimelineController.php`<br>`app/Http/Controllers/VendorController.php`<br>`resources/views/timeline/index.blade.php`<br>`resources/views/vendors/index.blade.php` |
| **Adinda Aulia Sabrina Arumsari** | `dinda` | **Modul #2: Wedding Checklist & Manajemen Tugas** | `app/Http/Controllers/ChecklistController.php`<br>`resources/views/checklist/index.blade.php` |

> Lihat dokumen **[`TASK_DISTRIBUTION.md`](./TASK_DISTRIBUTION.md)** untuk rincian tugas pengembangan lanjutan per anggota dan panduan perintah Git.

---

## Panduan Instalasi Cepat (30 Detik)

### Prasyarat
- **PHP 8.3 atau 8.4** (dengan ekstensi `pdo_sqlite` atau `pdo_mysql`, `mbstring`, `xml`, `ctype`, `json`)
- **Composer 2.x**
- **Node.js 20+ & NPM**

### Langkah Menjalankan di Komputer Lokal

```bash
# 1. Clone repositori
git clone https://github.com/mirsydfchrynto/wedding-planner.git
cd wedding-planner

# 2. Pindah ke branch masing-masing (pilih sesuai nama: irsyad / marsha / adam / dinda)
git checkout <nama-branch>

# 3. Jalankan instalasi otomatis (Composer, .env, APP_KEY, Migrasi + Seeder, dan Build Vite)
composer setup
php artisan db:seed

# 4. Jalankan server lokal
php artisan serve
```

Buka **`http://127.0.0.1:8000`** pada peramban.

### Menggunakan MySQL (Laragon / XAMPP)
Secara bawaan aplikasi menggunakan **SQLite** (`database/database.sqlite`) agar langsung berjalan tanpa konfigurasi server database tambahan. Jika ingin menggunakan **MySQL**, ubah bagian database pada berkas `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wedding_planner
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:
```bash
php artisan migrate:fresh --seed
```

---

## Pengujian Otomatis (*Automated Testing*)

Untuk memastikan seluruh 5 modul berjalan baik sebelum melakukan *git push* atau membuat *Pull Request*:

```bash
php artisan test
```
