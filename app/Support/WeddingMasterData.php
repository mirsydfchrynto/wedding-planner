<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Master data transcribed from the wedding research deliverables:
 * - Database_Anggaran_Wedding_Planner_Indonesia.xlsx (29 budget items)
 * - Master_Data_Checklist_Wedding_Planner.xlsx (124 tasks, 10 stages)
 *
 * Prices are indicative ranges from the research report, not vendor quotes.
 */
final class WeddingMasterData
{
    public const WEDDING_TRADITIONS = ['none', 'jawa', 'sunda', 'minang', 'batak'];

    /** @var array<int, array{id:string,category:string,title:string,min:int,max:int,unit:string,nature:string,per_guest:bool,notes:string}> */
    public const BUDGET_ITEMS = [
        ['id' => 'WP-001', 'category' => 'documents', 'title' => 'Pencatatan nikah di KUA', 'min' => 0, 'max' => 0, 'unit' => 'per pernikahan', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Akad di kantor KUA pada hari dan jam kerja'],
        ['id' => 'WP-002', 'category' => 'documents', 'title' => 'Pencatatan nikah di luar KUA/jam kerja', 'min' => 600000, 'max' => 600000, 'unit' => 'per pernikahan', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Layanan pencatatan nikah di luar kantor KUA atau di luar jam kerja'],
        ['id' => 'WP-003', 'category' => 'venue', 'title' => 'Gedung/ballroom', 'min' => 20000000, 'max' => 100000000, 'unit' => 'per acara', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Sewa tempat resepsi; kisaran contoh untuk acara sekitar 500 orang'],
        ['id' => 'WP-004', 'category' => 'venue', 'title' => 'Tenda outdoor', 'min' => 5000000, 'max' => 25000000, 'unit' => 'per acara', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Tenda dan perlengkapan dasar untuk resepsi luar ruang'],
        ['id' => 'WP-005', 'category' => 'catering', 'title' => 'Paket catering', 'min' => 30000, 'max' => 150000, 'unit' => 'per pax', 'nature' => 'Kondisional', 'per_guest' => true, 'notes' => 'Hidangan utama dan minuman; harga per tamu'],
        ['id' => 'WP-006', 'category' => 'catering', 'title' => 'Kue pengantin', 'min' => 1000000, 'max' => 5000000, 'unit' => 'per paket', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Kue dekoratif/konsumsi sesuai konsep'],
        ['id' => 'WP-007', 'category' => 'catering', 'title' => 'Snack/minuman/booth tambahan', 'min' => 5000000, 'max' => 20000000, 'unit' => 'per paket', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Coffee corner, snack, minuman khusus atau food stall'],
        ['id' => 'WP-008', 'category' => 'decoration', 'title' => 'Dekorasi pelaminan', 'min' => 5000000, 'max' => 30000000, 'unit' => 'per paket', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Backdrop, bunga, panggung dan dekorasi utama'],
        ['id' => 'WP-009', 'category' => 'decoration', 'title' => 'Dekorasi tambahan', 'min' => 1000000, 'max' => 10000000, 'unit' => 'per paket', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Dekorasi meja tamu, lighting, photobooth backdrop dan area tambahan'],
        ['id' => 'WP-010', 'category' => 'decoration', 'title' => 'Pondokan/traditional food stall', 'min' => 2000000, 'max' => 10000000, 'unit' => 'per paket (±100 tamu)', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Paket makanan pondokan/food stall tambahan'],
        ['id' => 'WP-011', 'category' => 'attire', 'title' => 'Gaun/kebaya pengantin', 'min' => 3000000, 'max' => 30000000, 'unit' => 'per busana', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Sewa atau pembelian busana pengantin wanita'],
        ['id' => 'WP-012', 'category' => 'attire', 'title' => 'Jas/setelan pengantin pria', 'min' => 2000000, 'max' => 15000000, 'unit' => 'per set', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Sewa atau pembelian jas dan aksesori'],
        ['id' => 'WP-013', 'category' => 'attire', 'title' => 'Busana adat/keluarga', 'min' => 2000000, 'max' => 15000000, 'unit' => 'per set/paket', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Busana adat pengantin atau keluarga sesuai tradisi'],
        ['id' => 'WP-014', 'category' => 'makeup', 'title' => 'Makeup dan hairdo pengantin', 'min' => 1500000, 'max' => 5000000, 'unit' => 'per paket', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Rias dan tata rambut pengantin; cek cakupan paket'],
        ['id' => 'WP-015', 'category' => 'documentation', 'title' => 'Fotografer', 'min' => 3000000, 'max' => 15000000, 'unit' => 'per acara', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Liputan foto akad dan/atau resepsi'],
        ['id' => 'WP-016', 'category' => 'documentation', 'title' => 'Videografer', 'min' => 3000000, 'max' => 20000000, 'unit' => 'per acara', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Liputan video, editing dan highlight'],
        ['id' => 'WP-017', 'category' => 'documentation', 'title' => 'Foto/video prewedding', 'min' => 1500000, 'max' => 5000000, 'unit' => 'per paket', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Sesi foto/video sebelum hari pernikahan'],
        ['id' => 'WP-018', 'category' => 'documentation', 'title' => 'Photobooth', 'min' => 2000000, 'max' => 8000000, 'unit' => 'per acara', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Sewa photobooth dan cetak foto/akses digital'],
        ['id' => 'WP-019', 'category' => 'entertainment', 'title' => 'MC', 'min' => 1000000, 'max' => 5000000, 'unit' => 'per acara', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Pembawa acara akad/resepsi'],
        ['id' => 'WP-020', 'category' => 'entertainment', 'title' => 'Band/musik', 'min' => 3000000, 'max' => 20000000, 'unit' => 'per acara', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Hiburan musik live'],
        ['id' => 'WP-021', 'category' => 'entertainment', 'title' => 'Tari/pertunjukan adat', 'min' => 2000000, 'max' => 15000000, 'unit' => 'per acara', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Tari tradisional, gamelan atau pertunjukan budaya'],
        ['id' => 'WP-022', 'category' => 'souvenir', 'title' => 'Souvenir', 'min' => 2000000, 'max' => 10000000, 'unit' => 'paket (±200 unit)', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Cendera mata untuk tamu; contoh paket 200 unit'],
        ['id' => 'WP-023', 'category' => 'souvenir', 'title' => 'Undangan cetak', 'min' => 1000000, 'max' => 5000000, 'unit' => 'paket (±200 unit)', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Undangan fisik; contoh paket 200 unit'],
        ['id' => 'WP-024', 'category' => 'souvenir', 'title' => 'Undangan digital', 'min' => 0, 'max' => 500000, 'unit' => 'per paket', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Undangan website atau tautan digital'],
        ['id' => 'WP-025', 'category' => 'transport', 'title' => 'Mobil pengantin', 'min' => 1000000, 'max' => 5000000, 'unit' => 'per unit/acara', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Sewa kendaraan pengantin dan/atau dekorasi mobil'],
        ['id' => 'WP-026', 'category' => 'transport', 'title' => 'Transportasi keluarga/tamu', 'min' => 1000000, 'max' => 5000000, 'unit' => 'per kendaraan', 'nature' => 'Opsional', 'per_guest' => false, 'notes' => 'Sewa kendaraan/elf/bus untuk keluarga atau tamu'],
        ['id' => 'WP-027', 'category' => 'jewelry', 'title' => 'Cincin kawin', 'min' => 500000, 'max' => 5000000, 'unit' => 'per pasang', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Cincin pasangan; harga sangat dipengaruhi bahan dan kadar'],
        ['id' => 'WP-028', 'category' => 'jewelry', 'title' => 'Mahar', 'min' => 500000, 'max' => 10000000, 'unit' => 'per set/nilai', 'nature' => 'Kondisional', 'per_guest' => false, 'notes' => 'Mahar sesuai kesepakatan pasangan dan ketentuan agama'],
        ['id' => 'WP-029', 'category' => 'contingency', 'title' => 'Dana tak terduga', 'min' => 0, 'max' => 0, 'unit' => '% dari subtotal', 'nature' => 'Disarankan', 'per_guest' => false, 'notes' => 'Cadangan untuk kenaikan harga, overtime, transportasi, cuaca dan kebutuhan mendadak'],
    ];

    /** @var array<string, array{phase:string,category:string,tasks:array<int, array{id:string,title:string,description:string,type:string}>}> */
    public const CHECKLIST_STAGES = [
        '1. Perencanaan Pernikahan' => [
            'phase' => 'Perencanaan',
            'category' => 'planning',
            'tasks' => [
                ['id' => 'WP-001', 'title' => 'Diskusikan rencana pernikahan bersama pasangan', 'description' => 'Menyamakan tujuan, harapan, dan gambaran umum acara yang diinginkan.', 'type' => 'Umum'],
                ['id' => 'WP-002', 'title' => 'Diskusikan rencana pernikahan dengan kedua keluarga', 'description' => 'Membicarakan dukungan keluarga, adat, bentuk acara, dan kebutuhan utama.', 'type' => 'Umum'],
                ['id' => 'WP-003', 'title' => 'Tentukan tanggal atau rentang tanggal pernikahan', 'description' => 'Memilih tanggal dengan mempertimbangkan kesiapan pasangan, keluarga, lokasi, dan petugas terkait.', 'type' => 'Umum'],
                ['id' => 'WP-004', 'title' => 'Tentukan konsep dan tema pernikahan', 'description' => 'Menentukan gaya acara, misalnya tradisional, modern, intimate, indoor, atau outdoor.', 'type' => 'Umum'],
                ['id' => 'WP-005', 'title' => 'Tentukan bentuk/rangkaian acara', 'description' => 'Menentukan apakah akan ada akad atau pemberkatan, resepsi, acara adat, atau rangkaian lain.', 'type' => 'Umum'],
                ['id' => 'WP-006', 'title' => 'Tentukan lokasi atau wilayah penyelenggaraan', 'description' => 'Menentukan kota/wilayah agar pencarian venue dan vendor lebih terarah.', 'type' => 'Umum'],
                ['id' => 'WP-007', 'title' => 'Perkirakan jumlah tamu undangan', 'description' => 'Membuat estimasi awal jumlah tamu dari kedua pihak keluarga dan teman.', 'type' => 'Umum'],
                ['id' => 'WP-008', 'title' => 'Susun anggaran keseluruhan', 'description' => 'Menentukan batas dana yang tersedia dan alokasi awal untuk setiap kebutuhan.', 'type' => 'Umum'],
                ['id' => 'WP-009', 'title' => 'Tentukan sumber pembiayaan', 'description' => 'Menyepakati kontribusi pasangan atau keluarga serta mekanisme pengelolaan dana.', 'type' => 'Umum'],
                ['id' => 'WP-010', 'title' => 'Siapkan dana cadangan', 'description' => 'Mengalokasikan dana untuk perubahan kebutuhan atau pengeluaran tak terduga.', 'type' => 'Disarankan'],
                ['id' => 'WP-011', 'title' => 'Buat daftar kebutuhan awal', 'description' => 'Mencatat kebutuhan utama agar proses pencarian dan pemesanan dapat dipantau.', 'type' => 'Umum'],
                ['id' => 'WP-012', 'title' => 'Tentukan pembagian tanggung jawab', 'description' => 'Menyepakati siapa yang menangani komunikasi, keputusan, pembayaran, dan koordinasi.', 'type' => 'Umum'],
                ['id' => 'WP-013', 'title' => 'Putuskan penggunaan Wedding Organizer', 'description' => 'Menilai apakah acara akan dikelola sendiri atau dibantu WO.', 'type' => 'Opsional'],
            ],
        ],
        '2. Administrasi dan Legalitas Pernikahan' => [
            'phase' => 'Administrasi',
            'category' => 'documents',
            'tasks' => [
                ['id' => 'WP-014', 'title' => 'Cari informasi persyaratan pernikahan', 'description' => 'Memeriksa persyaratan terbaru pada KUA, Dukcapil, atau lembaga keagamaan sesuai jenis pernikahan.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-015', 'title' => 'Siapkan KTP calon pengantin', 'description' => 'Menyiapkan identitas kedua calon pengantin sesuai persyaratan instansi.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-016', 'title' => 'Siapkan Kartu Keluarga', 'description' => 'Menyiapkan KK terbaru sesuai persyaratan administrasi.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-017', 'title' => 'Siapkan akta kelahiran atau dokumen identitas lain', 'description' => 'Menyiapkan dokumen yang diminta oleh instansi pencatat pernikahan.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-018', 'title' => 'Siapkan pas foto', 'description' => 'Menyiapkan pas foto dengan ukuran dan ketentuan latar sesuai persyaratan yang berlaku.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-019', 'title' => 'Urus surat pengantar atau formulir yang diperlukan', 'description' => 'Mengurus dokumen dari kelurahan/desa atau instansi terkait jika dipersyaratkan.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-020', 'title' => 'Siapkan dokumen tambahan sesuai kondisi', 'description' => 'Contohnya dokumen cerai, akta kematian pasangan terdahulu, izin orang tua/wali, atau dokumen lain jika relevan.', 'type' => 'Kondisional'],
                ['id' => 'WP-021', 'title' => 'Ikuti pemeriksaan atau verifikasi dokumen', 'description' => 'Memastikan berkas diterima dan tidak ada kekurangan atau ketidaksesuaian data.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-022', 'title' => 'Lakukan pendaftaran/pencatatan pernikahan', 'description' => 'Mendaftarkan rencana pernikahan melalui instansi yang berwenang sesuai prosedur.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-023', 'title' => 'Ikuti bimbingan atau pemeriksaan kesehatan jika dipersyaratkan', 'description' => 'Mengikuti layanan yang diwajibkan atau dijadwalkan oleh instansi terkait.', 'type' => 'Kondisional'],
                ['id' => 'WP-024', 'title' => 'Pastikan jadwal dan petugas pencatat/pejabat keagamaan', 'description' => 'Mengonfirmasi jadwal, tempat, dan pihak yang berwenang memimpin atau mencatat pernikahan.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-025', 'title' => 'Tentukan wali dan saksi jika relevan', 'description' => 'Memastikan wali dan saksi memenuhi ketentuan agama dan peraturan yang berlaku.', 'type' => 'Kondisional'],
            ],
        ],
        '3. Venue dan Lokasi Acara' => [
            'phase' => 'Venue',
            'category' => 'venue',
            'tasks' => [
                ['id' => 'WP-026', 'title' => 'Cari referensi lokasi pernikahan', 'description' => 'Mencari gedung, hotel, rumah, tempat ibadah, restoran, atau lokasi outdoor.', 'type' => 'Umum'],
                ['id' => 'WP-027', 'title' => 'Survei lokasi', 'description' => 'Mengunjungi lokasi untuk memeriksa kondisi ruang, akses, dan fasilitas.', 'type' => 'Disarankan'],
                ['id' => 'WP-028', 'title' => 'Bandingkan harga dan paket venue', 'description' => 'Membandingkan biaya sewa, fasilitas, durasi penggunaan, dan ketentuan tambahan.', 'type' => 'Disarankan'],
                ['id' => 'WP-029', 'title' => 'Periksa kapasitas dan tata letak venue', 'description' => 'Memastikan kapasitas sesuai estimasi tamu dan kebutuhan prosesi.', 'type' => 'Umum'],
                ['id' => 'WP-030', 'title' => 'Periksa fasilitas venue', 'description' => 'Memeriksa listrik, toilet, ruang rias, ruang keluarga, aksesibilitas, dan fasilitas lain.', 'type' => 'Disarankan'],
                ['id' => 'WP-031', 'title' => 'Periksa akses dan area parkir', 'description' => 'Memastikan lokasi mudah ditemukan dan memiliki pengaturan parkir yang memadai.', 'type' => 'Disarankan'],
                ['id' => 'WP-032', 'title' => 'Tentukan area akad/pemberkatan dan resepsi', 'description' => 'Menentukan area untuk setiap rangkaian acara sesuai kondisi venue.', 'type' => 'Kondisional'],
                ['id' => 'WP-033', 'title' => 'Pesan venue dan sepakati ketentuan', 'description' => 'Mengamankan tanggal serta memahami aturan penggunaan, pembatalan, dan biaya tambahan.', 'type' => 'Umum'],
                ['id' => 'WP-034', 'title' => 'Tandatangani kontrak dan bayar uang muka', 'description' => 'Menyimpan bukti pembayaran serta salinan kesepakatan.', 'type' => 'Umum'],
            ],
        ],
        '4. Pemilihan dan Pengelolaan Vendor' => [
            'phase' => 'Vendor',
            'category' => 'vendor',
            'tasks' => [
                ['id' => 'WP-035', 'title' => 'Cari dan bandingkan vendor', 'description' => 'Mengumpulkan rekomendasi, portofolio, harga, ulasan, dan ketersediaan vendor.', 'type' => 'Umum'],
                ['id' => 'WP-036', 'title' => 'Pilih Wedding Organizer', 'description' => 'Menentukan WO jika pasangan membutuhkan bantuan koordinasi dan pelaksanaan acara.', 'type' => 'Opsional'],
                ['id' => 'WP-037', 'title' => 'Pilih vendor dekorasi', 'description' => 'Menentukan dekorasi pelaminan, area acara, meja penerima tamu, dan elemen pendukung.', 'type' => 'Opsional'],
                ['id' => 'WP-038', 'title' => 'Pilih vendor katering', 'description' => 'Menentukan penyedia makanan dan minuman sesuai konsep serta anggaran.', 'type' => 'Umum'],
                ['id' => 'WP-039', 'title' => 'Tentukan menu dan lakukan food tasting', 'description' => 'Mencoba menu dan menyepakati pilihan makanan, minuman, serta kebutuhan khusus.', 'type' => 'Disarankan'],
                ['id' => 'WP-040', 'title' => 'Pilih Makeup Artist dan hair stylist', 'description' => 'Menentukan jasa rias dan tata rambut/hijab sesuai kebutuhan pengantin.', 'type' => 'Opsional'],
                ['id' => 'WP-041', 'title' => 'Pilih vendor fotografi', 'description' => 'Menentukan cakupan dokumentasi foto, durasi, jumlah fotografer, dan hasil akhir.', 'type' => 'Opsional'],
                ['id' => 'WP-042', 'title' => 'Pilih vendor videografi', 'description' => 'Menentukan dokumentasi video, durasi, dan format hasil jika dibutuhkan.', 'type' => 'Opsional'],
                ['id' => 'WP-043', 'title' => 'Pilih vendor busana atau penjahit', 'description' => 'Menentukan penyewaan, pembelian, atau pembuatan busana pengantin.', 'type' => 'Opsional'],
                ['id' => 'WP-044', 'title' => 'Pilih MC atau pembawa acara', 'description' => 'Menentukan pembawa acara untuk rangkaian yang membutuhkan MC.', 'type' => 'Opsional'],
                ['id' => 'WP-045', 'title' => 'Pilih vendor musik atau hiburan', 'description' => 'Menentukan musik, band, DJ, atau hiburan lain jika direncanakan.', 'type' => 'Opsional'],
                ['id' => 'WP-046', 'title' => 'Pilih vendor undangan', 'description' => 'Menentukan penyedia desain/cetak undangan jika tidak dibuat sendiri.', 'type' => 'Opsional'],
                ['id' => 'WP-047', 'title' => 'Pilih vendor souvenir', 'description' => 'Menentukan penyedia souvenir jika akan dibagikan kepada tamu.', 'type' => 'Opsional'],
                ['id' => 'WP-048', 'title' => 'Pilih vendor kue atau dessert', 'description' => 'Menentukan kue pernikahan atau dessert tambahan jika direncanakan.', 'type' => 'Opsional'],
                ['id' => 'WP-049', 'title' => 'Periksa portofolio dan kesepakatan layanan', 'description' => 'Memastikan hasil kerja, jumlah personel, waktu kerja, biaya, dan batas layanan tertulis.', 'type' => 'Disarankan'],
                ['id' => 'WP-050', 'title' => 'Tandatangani kontrak dan simpan bukti pembayaran', 'description' => 'Mendokumentasikan kesepakatan, termin pembayaran, pembatalan, dan kontak vendor.', 'type' => 'Umum'],
                ['id' => 'WP-051', 'title' => 'Simpan daftar kontak vendor', 'description' => 'Mencatat nama PIC, nomor kontak, layanan, dan status pemesanan.', 'type' => 'Umum'],
            ],
        ],
        '5. Busana dan Penampilan Pengantin' => [
            'phase' => 'Busana',
            'category' => 'attire',
            'tasks' => [
                ['id' => 'WP-052', 'title' => 'Tentukan konsep busana pengantin', 'description' => 'Menentukan model, warna, dan gaya busana sesuai tema serta kebutuhan prosesi.', 'type' => 'Umum'],
                ['id' => 'WP-053', 'title' => 'Siapkan busana pengantin wanita', 'description' => 'Menyewa, membeli, atau menjahit busana sesuai pilihan.', 'type' => 'Kondisional'],
                ['id' => 'WP-054', 'title' => 'Siapkan busana pengantin pria', 'description' => 'Menyewa, membeli, atau menjahit busana sesuai pilihan.', 'type' => 'Kondisional'],
                ['id' => 'WP-055', 'title' => 'Tentukan busana untuk setiap rangkaian acara', 'description' => 'Menentukan apakah busana akad/pemberkatan dan resepsi sama atau berbeda.', 'type' => 'Kondisional'],
                ['id' => 'WP-056', 'title' => 'Lakukan fitting busana', 'description' => 'Memastikan ukuran, kenyamanan, dan penyesuaian busana.', 'type' => 'Disarankan'],
                ['id' => 'WP-057', 'title' => 'Siapkan sepatu dan aksesori', 'description' => 'Menyiapkan alas kaki, perhiasan, kerudung, veil, atau aksesori lain.', 'type' => 'Kondisional'],
                ['id' => 'WP-058', 'title' => 'Tentukan konsep makeup dan rambut/hijab', 'description' => 'Menyepakati tampilan riasan dan tata rambut atau hijab.', 'type' => 'Opsional'],
                ['id' => 'WP-059', 'title' => 'Lakukan makeup trial', 'description' => 'Mencoba riasan terlebih dahulu jika layanan dan anggaran memungkinkan.', 'type' => 'Opsional'],
                ['id' => 'WP-060', 'title' => 'Siapkan busana keluarga inti', 'description' => 'Mengatur busana orang tua dan keluarga inti jika diperlukan.', 'type' => 'Opsional'],
                ['id' => 'WP-061', 'title' => 'Siapkan busana bridesmaid/groomsmen', 'description' => 'Menentukan dan mengoordinasikan pakaian pendamping jika ada.', 'type' => 'Opsional'],
                ['id' => 'WP-062', 'title' => 'Siapkan perlengkapan pribadi pengantin', 'description' => 'Menyiapkan pakaian ganti, alas kaki cadangan, alat rias, dan kebutuhan pribadi.', 'type' => 'Disarankan'],
            ],
        ],
        '6. Mahar, Cincin, dan Seserahan' => [
            'phase' => 'Mahar',
            'category' => 'jewelry',
            'tasks' => [
                ['id' => 'WP-063', 'title' => 'Tentukan bentuk dan isi mahar', 'description' => 'Menyepakati mahar sesuai ketentuan agama dan kesepakatan pasangan.', 'type' => 'Kondisional'],
                ['id' => 'WP-064', 'title' => 'Siapkan mahar', 'description' => 'Membeli atau menyiapkan mahar dan memastikan penyimpanannya aman.', 'type' => 'Kondisional'],
                ['id' => 'WP-065', 'title' => 'Tentukan desain dan ukuran cincin', 'description' => 'Memilih model serta memastikan ukuran cincin kedua pasangan.', 'type' => 'Opsional'],
                ['id' => 'WP-066', 'title' => 'Pesan atau beli cincin pernikahan', 'description' => 'Memesan cincin dan memeriksa hasil sebelum hari acara.', 'type' => 'Opsional'],
                ['id' => 'WP-067', 'title' => 'Tentukan apakah menggunakan seserahan', 'description' => 'Menyepakati kebutuhan seserahan berdasarkan tradisi dan kesepakatan keluarga.', 'type' => 'Kondisional'],
                ['id' => 'WP-068', 'title' => 'Buat daftar isi seserahan', 'description' => 'Mencatat barang yang akan disiapkan dan pihak yang bertanggung jawab.', 'type' => 'Kondisional'],
                ['id' => 'WP-069', 'title' => 'Beli dan kemas perlengkapan seserahan', 'description' => 'Menyiapkan barang serta wadah atau dekorasi seserahan jika digunakan.', 'type' => 'Kondisional'],
                ['id' => 'WP-070', 'title' => 'Tentukan PIC pembawa mahar dan cincin', 'description' => 'Menentukan orang yang bertanggung jawab membawa dan menyerahkan perlengkapan saat prosesi.', 'type' => 'Disarankan'],
            ],
        ],
        '7. Undangan dan Pengelolaan Tamu' => [
            'phase' => 'Undangan',
            'category' => 'souvenir',
            'tasks' => [
                ['id' => 'WP-071', 'title' => 'Buat daftar tamu dari kedua pihak', 'description' => 'Mencatat nama tamu dari keluarga, kerabat, teman, dan rekan kerja.', 'type' => 'Umum'],
                ['id' => 'WP-072', 'title' => 'Gabungkan dan periksa daftar tamu', 'description' => 'Menghindari data ganda serta memperkirakan jumlah undangan dan konsumsi.', 'type' => 'Disarankan'],
                ['id' => 'WP-073', 'title' => 'Tentukan desain dan informasi undangan', 'description' => 'Memastikan nama, tanggal, waktu, lokasi, dan informasi lain sudah benar.', 'type' => 'Umum'],
                ['id' => 'WP-074', 'title' => 'Buat undangan digital', 'description' => 'Membuat tautan atau file undangan digital jika dipilih.', 'type' => 'Opsional'],
                ['id' => 'WP-075', 'title' => 'Cetak undangan fisik', 'description' => 'Mencetak undangan untuk penerima yang membutuhkan undangan fisik.', 'type' => 'Opsional'],
                ['id' => 'WP-076', 'title' => 'Sebarkan undangan', 'description' => 'Mengirimkan undangan melalui kanal yang dipilih.', 'type' => 'Umum'],
                ['id' => 'WP-077', 'title' => 'Catat konfirmasi kehadiran (RSVP)', 'description' => 'Mencatat jawaban hadir/tidak hadir dan jumlah orang yang datang.', 'type' => 'Disarankan'],
                ['id' => 'WP-078', 'title' => 'Tindak lanjuti tamu yang belum merespons', 'description' => 'Menghubungi tamu tertentu untuk memperbarui perkiraan kehadiran.', 'type' => 'Opsional'],
                ['id' => 'WP-079', 'title' => 'Susun pengaturan tempat duduk atau meja', 'description' => 'Membuat denah atau pengelompokan tempat duduk jika diperlukan.', 'type' => 'Kondisional'],
                ['id' => 'WP-080', 'title' => 'Siapkan buku tamu dan penerima tamu', 'description' => 'Menentukan media pencatatan tamu serta petugas penyambut.', 'type' => 'Disarankan'],
                ['id' => 'WP-081', 'title' => 'Siapkan souvenir', 'description' => 'Menghitung jumlah dan menyiapkan pembagian souvenir jika digunakan.', 'type' => 'Opsional'],
                ['id' => 'WP-082', 'title' => 'Identifikasi tamu khusus atau kebutuhan aksesibilitas', 'description' => 'Mencatat tamu VIP, lansia, anak-anak, atau tamu dengan kebutuhan khusus.', 'type' => 'Kondisional'],
            ],
        ],
        '8. Rangkaian Acara dan Kebutuhan Pendukung' => [
            'phase' => 'Rangkaian',
            'category' => 'ceremony',
            'tasks' => [
                ['id' => 'WP-083', 'title' => 'Susun rangkaian akad/pemberkatan', 'description' => 'Menentukan urutan prosesi sesuai agama dan ketentuan yang berlaku.', 'type' => 'Kondisional'],
                ['id' => 'WP-084', 'title' => 'Susun rangkaian resepsi', 'description' => 'Menentukan urutan pembukaan, prosesi pengantin, sambutan, hiburan, dan penutupan.', 'type' => 'Kondisional'],
                ['id' => 'WP-085', 'title' => 'Tentukan rangkaian adat', 'description' => 'Menyepakati prosesi adat jika akan dilaksanakan.', 'type' => 'Kondisional'],
                ['id' => 'WP-086', 'title' => 'Susun rundown acara', 'description' => 'Mencatat urutan kegiatan, waktu, lokasi, dan penanggung jawab.', 'type' => 'Umum'],
                ['id' => 'WP-087', 'title' => 'Tentukan PIC setiap bagian', 'description' => 'Menetapkan koordinator untuk keluarga, vendor, tamu, dan perlengkapan.', 'type' => 'Disarankan'],
                ['id' => 'WP-088', 'title' => 'Siapkan teks sambutan atau doa', 'description' => 'Menyusun materi sambutan dan doa jika menjadi bagian acara.', 'type' => 'Kondisional'],
                ['id' => 'WP-089', 'title' => 'Siapkan transportasi pengantin dan keluarga', 'description' => 'Mengatur kendaraan, pengemudi, rute, dan waktu keberangkatan jika dibutuhkan.', 'type' => 'Kondisional'],
                ['id' => 'WP-090', 'title' => 'Atur penginapan tamu/keluarga luar kota', 'description' => 'Mencari dan mengatur akomodasi jika ada tamu yang membutuhkan.', 'type' => 'Kondisional'],
                ['id' => 'WP-091', 'title' => 'Siapkan konsumsi panitia/keluarga', 'description' => 'Memastikan kebutuhan makan dan minum petugas atau keluarga yang bertugas.', 'type' => 'Disarankan'],
                ['id' => 'WP-092', 'title' => 'Siapkan perlengkapan darurat pengantin', 'description' => 'Menyiapkan obat pribadi, plester, tisu, peniti, alat jahit kecil, dan kebutuhan darurat lain.', 'type' => 'Disarankan'],
                ['id' => 'WP-093', 'title' => 'Siapkan rencana cadangan', 'description' => 'Menentukan solusi untuk cuaca, keterlambatan, gangguan teknis, atau perubahan jumlah tamu.', 'type' => 'Disarankan'],
                ['id' => 'WP-094', 'title' => 'Siapkan kebutuhan parkir dan keamanan', 'description' => 'Mengatur petugas atau informasi parkir serta penyimpanan barang penting.', 'type' => 'Kondisional'],
            ],
        ],
        '9. Finalisasi dan Pemeriksaan Akhir' => [
            'phase' => 'Finalisasi',
            'category' => 'planning',
            'tasks' => [
                ['id' => 'WP-095', 'title' => 'Periksa kelengkapan dokumen pernikahan', 'description' => 'Memastikan dokumen, jadwal, dan konfirmasi instansi telah lengkap.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-096', 'title' => 'Konfirmasi ulang venue', 'description' => 'Memastikan tanggal, jam akses, fasilitas, dan aturan lokasi.', 'type' => 'Umum'],
                ['id' => 'WP-097', 'title' => 'Konfirmasi ulang seluruh vendor', 'description' => 'Memastikan jadwal kedatangan, layanan, PIC, dan kebutuhan teknis.', 'type' => 'Umum'],
                ['id' => 'WP-098', 'title' => 'Finalisasi jumlah tamu dan katering', 'description' => 'Memperbarui perkiraan tamu dan menyepakati jumlah porsi dengan katering.', 'type' => 'Disarankan'],
                ['id' => 'WP-099', 'title' => 'Periksa busana dan perlengkapan pengantin', 'description' => 'Memastikan busana, sepatu, aksesori, dan kebutuhan pribadi siap.', 'type' => 'Disarankan'],
                ['id' => 'WP-100', 'title' => 'Periksa cincin, mahar, dan seserahan', 'description' => 'Memastikan perlengkapan prosesi sudah tersedia dan disimpan dengan aman.', 'type' => 'Kondisional'],
                ['id' => 'WP-101', 'title' => 'Finalisasi rundown dan bagikan kepada PIC', 'description' => 'Memastikan versi rundown terbaru diterima pihak yang bertugas.', 'type' => 'Umum'],
                ['id' => 'WP-102', 'title' => 'Lakukan technical meeting', 'description' => 'Membahas alur, layout, waktu, listrik, sound system, dan koordinasi vendor.', 'type' => 'Disarankan'],
                ['id' => 'WP-103', 'title' => 'Lakukan gladi bersih jika diperlukan', 'description' => 'Berlatih prosesi dan perpindahan posisi untuk acara yang membutuhkan latihan.', 'type' => 'Opsional'],
                ['id' => 'WP-104', 'title' => 'Periksa pelunasan dan bukti transaksi', 'description' => 'Memastikan pembayaran sesuai kontrak dan menyimpan bukti.', 'type' => 'Umum'],
                ['id' => 'WP-105', 'title' => 'Siapkan daftar kontak penting', 'description' => 'Menyediakan kontak pasangan, keluarga, venue, WO, dan vendor.', 'type' => 'Disarankan'],
                ['id' => 'WP-106', 'title' => 'Pastikan seluruh PIC memahami tugas', 'description' => 'Mengonfirmasi tanggung jawab dan jalur komunikasi pada hari acara.', 'type' => 'Umum'],
                ['id' => 'WP-107', 'title' => 'Siapkan barang yang akan dibawa pada hari H', 'description' => 'Mengemas dokumen, cincin, mahar, pakaian ganti, dan barang pribadi.', 'type' => 'Disarankan'],
                ['id' => 'WP-108', 'title' => 'Atur waktu istirahat pengantin', 'description' => 'Menghindari aktivitas berlebihan dan memastikan pengantin cukup istirahat.', 'type' => 'Disarankan'],
            ],
        ],
        '10. Pelaksanaan Hari H' => [
            'phase' => 'Hari-H',
            'category' => 'ceremony',
            'tasks' => [
                ['id' => 'WP-109', 'title' => 'Lakukan persiapan pribadi dan makeup pengantin', 'description' => 'Pengantin menjalani rias, tata rambut/hijab, dan mengenakan busana.', 'type' => 'Umum'],
                ['id' => 'WP-110', 'title' => 'Pastikan pengantin dan keluarga tiba sesuai jadwal', 'description' => 'Mengatur kedatangan agar persiapan dan prosesi tidak terlambat.', 'type' => 'Umum'],
                ['id' => 'WP-111', 'title' => 'Periksa kesiapan venue dan dekorasi', 'description' => 'Memastikan area acara, meja, kursi, dekorasi, dan perlengkapan tersedia.', 'type' => 'Umum'],
                ['id' => 'WP-112', 'title' => 'Periksa kesiapan vendor dan peralatan teknis', 'description' => 'Memastikan vendor hadir dan sound system, listrik, serta alat pendukung berfungsi.', 'type' => 'Umum'],
                ['id' => 'WP-113', 'title' => 'Pastikan meja penerima tamu siap', 'description' => 'Memastikan buku tamu, daftar tamu, souvenir, dan petugas tersedia jika digunakan.', 'type' => 'Kondisional'],
                ['id' => 'WP-114', 'title' => 'Pastikan cincin, mahar, dan dokumen tersedia', 'description' => 'Memastikan perlengkapan prosesi berada pada PIC yang ditunjuk.', 'type' => 'Kondisional'],
                ['id' => 'WP-115', 'title' => 'Laksanakan akad nikah atau pemberkatan', 'description' => 'Melaksanakan prosesi sesuai agama dan ketentuan yang berlaku.', 'type' => 'Kondisional'],
                ['id' => 'WP-116', 'title' => 'Selesaikan penandatanganan dokumen pernikahan', 'description' => 'Menyelesaikan pencatatan atau dokumen sesuai prosedur instansi terkait.', 'type' => 'Wajib sesuai ketentuan'],
                ['id' => 'WP-117', 'title' => 'Laksanakan prosesi adat jika ada', 'description' => 'Melaksanakan rangkaian adat sesuai susunan acara.', 'type' => 'Kondisional'],
                ['id' => 'WP-118', 'title' => 'Laksanakan sesi foto pengantin dan keluarga', 'description' => 'Mengikuti sesi dokumentasi sesuai daftar atau arahan PIC.', 'type' => 'Opsional'],
                ['id' => 'WP-119', 'title' => 'Laksanakan resepsi jika ada', 'description' => 'Menjalankan rangkaian resepsi sesuai rundown.', 'type' => 'Kondisional'],
                ['id' => 'WP-120', 'title' => 'Koordinasikan pelayanan makanan dan minuman', 'description' => 'Memantau distribusi konsumsi serta kebutuhan tamu.', 'type' => 'Kondisional'],
                ['id' => 'WP-121', 'title' => 'Pantau jalannya rundown', 'description' => 'Memastikan kegiatan berjalan dan melakukan penyesuaian bila diperlukan.', 'type' => 'Umum'],
                ['id' => 'WP-122', 'title' => 'Pastikan tamu mendapatkan bantuan', 'description' => 'Mengarahkan tamu terkait lokasi, tempat duduk, konsumsi, dan kebutuhan lainnya.', 'type' => 'Disarankan'],
                ['id' => 'WP-123', 'title' => 'Amankan barang pribadi dan hadiah', 'description' => 'Menentukan orang yang bertanggung jawab atas barang penting dan hadiah.', 'type' => 'Disarankan'],
                ['id' => 'WP-124', 'title' => 'Pastikan penutupan dan serah terima lokasi', 'description' => 'Memastikan acara selesai, barang dikumpulkan, dan lokasi diserahkan sesuai ketentuan.', 'type' => 'Disarankan'],
            ],
        ],
    ];

    /** @var array<string, array<int, array{phase:string,title:string,priority:string}>> */
    public const ADAT_TASKS = [
        'jawa' => [
            ['phase' => 'Rangkaian', 'title' => 'Siraman (pembersihan lahir batin)', 'priority' => 'high'],
            ['phase' => 'Finalisasi', 'title' => 'Midodareni (malam sebelum akad)', 'priority' => 'high'],
            ['phase' => 'Hari-H', 'title' => 'Panggih (temu pengantin adat Jawa)', 'priority' => 'high'],
            ['phase' => 'Hari-H', 'title' => 'Sungkeman (mohon restu orang tua)', 'priority' => 'high'],
        ],
        'sunda' => [
            ['phase' => 'Rangkaian', 'title' => 'Ngeuyeuk Seureuh (wejangan hidup berumah tangga)', 'priority' => 'high'],
            ['phase' => 'Hari-H', 'title' => 'Saweran (tabur koin & beras kuning)', 'priority' => 'medium'],
            ['phase' => 'Hari-H', 'title' => 'Huap Lingkup & Pameuleum Harupat', 'priority' => 'high'],
        ],
        'minang' => [
            ['phase' => 'Rangkaian', 'title' => 'Batimbang Tando (tukar tanda pertunangan)', 'priority' => 'high'],
            ['phase' => 'Finalisasi', 'title' => 'Malam Bainai (menghias kuku dengan inai)', 'priority' => 'medium'],
            ['phase' => 'Hari-H', 'title' => 'Manjapuik Marapulai (penjemputan mempelai pria)', 'priority' => 'high'],
        ],
        'batak' => [
            ['phase' => 'Rangkaian', 'title' => 'Marhusip & Marhata Sinamot (rapat keluarga)', 'priority' => 'high'],
            ['phase' => 'Rangkaian', 'title' => 'Martumpol (ikrar pertunangan di gereja)', 'priority' => 'high'],
            ['phase' => 'Hari-H', 'title' => 'Ulaon Unjuk (pesta adat dengan pembagian Jambar)', 'priority' => 'high'],
        ],
    ];
}
