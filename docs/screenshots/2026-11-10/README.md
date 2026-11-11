# Dokumentasi Tampilan Tatanikah

Diambil 10 November 2026 dari `http://127.0.0.1:8095` memakai data seeder lokal.

- Desktop: 1440 x 1000, full page.
- Mobile: 390 x 844, full page.
- Checklist dan Susunan Acara dipecah tambahan per 1.100 px pada viewport desktop 1440 x 1200 agar teks terbaca.
- Akun: `aurelia.reza@gmail.com`; profil demo 12 Desember 2026, H-32.
- Data: 29 pos anggaran, 128 tugas (124 tugas riset + 4 tugas adat), 8 vendor.
- Total: 66 PNG, 17 MB.

## Halaman utama

| Halaman | Route / state | Desktop | Mobile |
|---|---|---|---|
| Landing publik | `/` | [desktop](desktop/01-landing.png) | [mobile](mobile/01-landing.png) |
| Login | `/login` | [desktop](desktop/02-login.png) | [mobile](mobile/02-login.png) |
| Onboarding langkah 1: Pasangan | `/onboarding` | [desktop](desktop/03-onboarding-step-1.png) | [mobile](mobile/03-onboarding-step-1.png) |
| Onboarding langkah 2: Tempat & Acara | `/onboarding` | [desktop](desktop/03b-onboarding-step-2.png) | [mobile](mobile/03b-onboarding-step-2.png) |
| Onboarding langkah 3: Anggaran | `/onboarding` | [desktop](desktop/03c-onboarding-step-3.png) | [mobile](mobile/03c-onboarding-step-3.png) |
| Dashboard | `/dashboard` | [desktop](desktop/04-dashboard.png) | [mobile](mobile/04-dashboard.png) |
| Menu navigasi mobile terbuka | `/dashboard` | - | [mobile](mobile/04b-dashboard-menu.png) |
| Edit profil pernikahan | `/profile/edit` | [desktop](desktop/05-profile-edit.png) | [mobile](mobile/05-profile-edit.png) |
| Daftar anggaran | `/budget` | [desktop](desktop/06-budget-index.png) | [mobile](mobile/06-budget-index.png) |
| Tambah pos anggaran | `/budget/create` | [desktop](desktop/07-budget-create.png) | [mobile](mobile/07-budget-create.png) |
| Edit pos anggaran | `/budget/1/edit` | [desktop](desktop/08-budget-edit.png) | [mobile](mobile/08-budget-edit.png) |
| Daftar tugas | `/checklist` | [desktop](desktop/09-checklist-index.png) | [mobile](mobile/09-checklist-index.png) |
| Tambah tugas | `/checklist/create` | [desktop](desktop/10-checklist-create.png) | [mobile](mobile/10-checklist-create.png) |
| Edit tugas | `/checklist/1/edit` | [desktop](desktop/11-checklist-edit.png) | [mobile](mobile/11-checklist-edit.png) |
| Susunan acara | `/rundown` | [desktop](desktop/12-rundown-index.png) | [mobile](mobile/12-rundown-index.png) |
| Jadwalkan agenda | `/rundown/create` | [desktop](desktop/13-rundown-create.png) | [mobile](mobile/13-rundown-create.png) |
| Tambah sesi acara | `/rundown/sessions/create` | [desktop](desktop/14-rundown-session-create.png) | [mobile](mobile/14-rundown-session-create.png) |
| Daftar vendor | `/vendors` | [desktop](desktop/15-vendors-index.png) | [mobile](mobile/15-vendors-index.png) |
| Tambah vendor | `/vendors/create` | [desktop](desktop/16-vendors-create.png) | [mobile](mobile/16-vendors-create.png) |
| Edit vendor | `/vendors/1/edit` | [desktop](desktop/17-vendors-edit.png) | [mobile](mobile/17-vendors-edit.png) |
| Catat termin pembayaran | `/vendors/1/payments/create` | [desktop](desktop/18-vendor-payment-create.png) | [mobile](mobile/18-vendor-payment-create.png) |
| Buku panduan A4 | `/print/master-guide` | [desktop](desktop/19-print-master-guide.png) | [mobile](mobile/19-print-master-guide.png) |

## Checklist per bagian

[01](desktop/segments/09-checklist-index-01.png) ·
[02](desktop/segments/09-checklist-index-02.png) ·
[03](desktop/segments/09-checklist-index-03.png) ·
[04](desktop/segments/09-checklist-index-04.png) ·
[05](desktop/segments/09-checklist-index-05.png) ·
[06](desktop/segments/09-checklist-index-06.png) ·
[07](desktop/segments/09-checklist-index-07.png) ·
[08](desktop/segments/09-checklist-index-08.png) ·
[09](desktop/segments/09-checklist-index-09.png) ·
[10](desktop/segments/09-checklist-index-10.png) ·
[11](desktop/segments/09-checklist-index-11.png) ·
[12](desktop/segments/09-checklist-index-12.png)

## Susunan acara per bagian

[01](desktop/segments/12-rundown-index-01.png) ·
[02](desktop/segments/12-rundown-index-02.png) ·
[03](desktop/segments/12-rundown-index-03.png) ·
[04](desktop/segments/12-rundown-index-04.png) ·
[05](desktop/segments/12-rundown-index-05.png) ·
[06](desktop/segments/12-rundown-index-06.png) ·
[07](desktop/segments/12-rundown-index-07.png) ·
[08](desktop/segments/12-rundown-index-08.png) ·
[09](desktop/segments/12-rundown-index-09.png) ·
[10](desktop/segments/12-rundown-index-10.png) ·
[11](desktop/segments/12-rundown-index-11.png)

## Catatan

- Halaman dinamis memakai record pertama hasil seeder: anggaran `1`, tugas `1`, vendor `1`.
- Checklist dan Susunan Acara sangat panjang; gambar full page tetap disimpan, tetapi versi segmen desktop yang dipakai untuk membaca detail.
- Panduan A4 diambil sebagai tampilan layar, bukan dialog cetak browser.
- User sementara untuk menangkap onboarding sudah dihapus; data demo tidak berubah.
