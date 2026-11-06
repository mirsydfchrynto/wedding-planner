<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Enums\TimelinePhase;
use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\BudgetItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WeddingProfile;
use App\Models\WeddingTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
final class WeddingPlannerSeeder extends Seeder
{
    public function run(): void
    {
        WeddingTask::query()->delete();
        BudgetItem::query()->delete();
        Vendor::query()->delete();
        WeddingProfile::query()->delete();
        User::query()->delete();

        $customer = User::query()->create([
            'name' => 'Aurelia Larasati & Reza Mahendra',
            'email' => 'aurelia.reza@gmail.com',
            'password' => Hash::make('GoogleCustomer2026!'),
            'role' => 'customer',
            'google_id' => 'google-oauth-108291',
            'email_verified_at' => now(),
        ]);

        User::query()->create([
            'name' => 'M. Irsyad Fachryanto (Lead Admin)',
            'email' => 'irsyad@ateliervow.id',
            'password' => Hash::make('AtelierAdmin2026!'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $weddingDate = now()->addDays(138)->startOfDay();

        $profile = WeddingProfile::query()->create([
            'user_id' => $customer->id,
            'bride_name' => 'Aurelia Larasati',
            'groom_name' => 'Reza Mahendra',
            'wedding_date' => $weddingDate->toDateString(),
            'wedding_time' => '08:30 WIB (Akad) & 18:30 WIB (Resepsi)',
            'venue_name' => 'The Glasshouse at Plataran Menteng',
            'city' => 'Jakarta Pusat',
            'total_budget' => 350000000,
            'guest_target' => 450,
            'theme_concept' => 'Vogue Bridal Editorial — Warm Alabaster & Champagne Bronze',
        ]);

        WeddingProfile::useTenancy($profile->id);

        // 1. Seed 8 Curated Luxury Vendors
        $vendorPlataran = Vendor::query()->create([
            'name' => 'Plataran Menteng Heritage Venue',
            'category' => WeddingCategory::Venue,
            'contact_person' => 'Clara Wijaya (Senior Banquet Manager)',
            'phone' => '08119820450',
            'email' => 'events.menteng@plataran.com',
            'instagram' => '@plataran.menteng',
            'contract_amount' => 78000000,
            'paid_amount' => 78000000,
            'booking_status' => VendorBookingStatus::Paid,
            'payment_due_date' => $weddingDate->copy()->subDays(60)->toDateString(),
            'notes' => 'Sewa eksklusif Glasshouse & Courtyard 8 jam, termasuk ruang rias VIP & valet 30 mobil.',
        ]);

        $vendorDharmawangsa = Vendor::query()->create([
            'name' => 'Akasya Fine Indonesian Catering',
            'category' => WeddingCategory::Catering,
            'contact_person' => 'Chef Hendra Kusuma',
            'phone' => '081280991122',
            'email' => 'concierge@akasyacatering.id',
            'instagram' => '@akasyacatering',
            'contract_amount' => 112000000,
            'paid_amount' => 65000000,
            'booking_status' => VendorBookingStatus::DpPaid,
            'payment_due_date' => $weddingDate->copy()->subDays(30)->toDateString(),
            'notes' => '450 pax buffet heritage + 4 pondokan signature (Kambing Guling, Empal Gentong, Salmon En Croute).',
        ]);

        $vendorSaptodjojokartiko = Vendor::query()->create([
            'name' => 'Maison AdiWira Bridals & Bespoke Kebaya',
            'category' => WeddingCategory::Attire,
            'contact_person' => 'Nadine Prameswari',
            'phone' => '081377889900',
            'email' => 'atelier@adiwirabridals.com',
            'instagram' => '@adiwirabridals',
            'contract_amount' => 36000000,
            'paid_amount' => 20000000,
            'booking_status' => VendorBookingStatus::DpPaid,
            'payment_due_date' => $weddingDate->copy()->subDays(21)->toDateString(),
            'notes' => 'Kebaya Akad French Lace Alabaster + Beskap Champagne + Makeup Pengantin & 2 Ibu.',
        ]);

        $vendorLotusDecor = Vendor::query()->create([
            'name' => 'Atelier Botanica Floral & Scenography',
            'category' => WeddingCategory::Decoration,
            'contact_person' => 'Bramantyo Adi',
            'phone' => '081511223344',
            'email' => 'studio@atelierbotanica.id',
            'instagram' => '@atelierbotanica.id',
            'contract_amount' => 48000000,
            'paid_amount' => 24000000,
            'booking_status' => VendorBookingStatus::DpPaid,
            'payment_due_date' => $weddingDate->copy()->subDays(14)->toDateString(),
            'notes' => 'Instalasi pelaminan arsitektural Alabaster, meja akad kaca, dan bunga segar White Phalaenopsis.',
        ]);

        $vendorIluminen = Vendor::query()->create([
            'name' => 'Lumiere Editorial Stills & Cinema',
            'category' => WeddingCategory::Documentation,
            'contact_person' => 'Kevin Danuarta',
            'phone' => '081788990011',
            'email' => 'booking@lumierecinema.co',
            'instagram' => '@lumiere.stills',
            'contract_amount' => 26000000,
            'paid_amount' => 26000000,
            'booking_status' => VendorBookingStatus::Paid,
            'payment_due_date' => $weddingDate->copy()->subDays(45)->toDateString(),
            'notes' => '2 fotografer utama, 2 videografer sinematik, fine-art linen album, dan same-day edit reel.',
        ]);

        $vendorPapermint = Vendor::query()->create([
            'name' => 'Kertas & Tinta Letterpress Studio',
            'category' => WeddingCategory::Souvenir,
            'contact_person' => 'Saraswati Putri',
            'phone' => '081822334455',
            'email' => 'hello@kertastinta.id',
            'instagram' => '@kertastinta.studio',
            'contract_amount' => 17500000,
            'paid_amount' => 10000000,
            'booking_status' => VendorBookingStatus::Booked,
            'payment_due_date' => $weddingDate->copy()->subDays(25)->toDateString(),
            'notes' => '300 undangan blind-deboss cotton paper + 450 reed diffuser aroma White Tea & Cedar.',
        ]);

        $vendorOrchestra = Vendor::query()->create([
            'name' => 'Senandung Chamber Quartet & MC',
            'category' => WeddingCategory::Entertainment,
            'contact_person' => 'Dimas Prayogo',
            'phone' => '081944556677',
            'email' => 'show@senandungmusic.id',
            'instagram' => '@senandung.chamber',
            'contract_amount' => 14500000,
            'paid_amount' => 5000000,
            'booking_status' => VendorBookingStatus::Booked,
            'payment_due_date' => $weddingDate->copy()->subDays(10)->toDateString(),
            'notes' => 'String quartet akustik + grand piano + MC dwibahasa Indonesia/Inggris untuk Akad & Resepsi.',
        ]);

        $vendorWo = Vendor::query()->create([
            'name' => 'Vow & Co. Bespoke Wedding Organizer',
            'category' => WeddingCategory::Documents,
            'contact_person' => 'Tiara Maharani',
            'phone' => '081233445566',
            'email' => 'tiara@vowandco.id',
            'instagram' => '@vowandco.planner',
            'contract_amount' => 12000000,
            'paid_amount' => 0,
            'booking_status' => VendorBookingStatus::Contacted,
            'payment_due_date' => $weddingDate->copy()->subDays(40)->toDateString(),
            'notes' => 'Koordinasi 10 kru Hari-H, pengurusan berkas KUA Menteng, dan gladi bersih keluarga.',
        ]);

        // 2. Seed 12 Realistic Budget Items across all 8 categories
        $budgetRows = [
            [
                'category' => WeddingCategory::Venue,
                'title' => 'Sewa Eksklusif The Glasshouse & Courtyard Plataran',
                'estimated_cost' => 75000000,
                'actual_cost' => 78000000,
                'paid_amount' => 78000000,
                'vendor_id' => $vendorPlataran->id,
                'due_date' => $weddingDate->copy()->subDays(60)->toDateString(),
                'notes' => 'Penambahan 1 jam sewa ruang transit keluarga inti.',
            ],
            [
                'category' => WeddingCategory::Catering,
                'title' => 'Buffet Utama 450 Tamu & 4 Pondokan Signature',
                'estimated_cost' => 90000000,
                'actual_cost' => 86500000,
                'paid_amount' => 60000000,
                'vendor_id' => $vendorDharmawangsa->id,
                'due_date' => $weddingDate->copy()->subDays(30)->toDateString(),
                'notes' => 'Diskon early booking Rp 3.000.000 dari Akasya Catering.',
            ],
            [
                'category' => WeddingCategory::Catering,
                'title' => 'Specialty Coffee Bar & Artisanal Mocktail Corner',
                'estimated_cost' => 10000000,
                'actual_cost' => 10000000,
                'paid_amount' => 5000000,
                'vendor_id' => $vendorDharmawangsa->id,
                'due_date' => $weddingDate->copy()->subDays(20)->toDateString(),
                'notes' => '250 cup es kopi susu gula aren & Earl Grey botanical mocktail.',
            ],
            [
                'category' => WeddingCategory::Attire,
                'title' => 'Kebaya Akad French Lace, Beskap & Gaun Resepsi',
                'estimated_cost' => 28000000,
                'actual_cost' => 27500000,
                'paid_amount' => 15000000,
                'vendor_id' => $vendorSaptodjojokartiko->id,
                'due_date' => $weddingDate->copy()->subDays(21)->toDateString(),
                'notes' => 'Termasuk veil bordir tangan 3 meter & sepatu pengantin.',
            ],
            [
                'category' => WeddingCategory::Attire,
                'title' => 'MUA Pengantin, Hairdo & Seragam Keluarga Inti',
                'estimated_cost' => 9000000,
                'actual_cost' => 8500000,
                'paid_amount' => 5000000,
                'vendor_id' => $vendorSaptodjojokartiko->id,
                'due_date' => $weddingDate->copy()->subDays(18)->toDateString(),
                'notes' => 'Makeup trial sudah dijadwalkan bersama ibu kedua mempelai.',
            ],
            [
                'category' => WeddingCategory::Decoration,
                'title' => 'Scenography Pelaminan, Meja Akad & Fresh Phalaenopsis',
                'estimated_cost' => 45000000,
                'actual_cost' => 48000000,
                'paid_amount' => 24000000,
                'vendor_id' => $vendorLotusDecor->id,
                'due_date' => $weddingDate->copy()->subDays(14)->toDateString(),
                'notes' => 'Upgrade instalasi chandelier gantung dan lorong foyer.',
            ],
            [
                'category' => WeddingCategory::Documentation,
                'title' => 'Paket Foto & Sinematografi Editorial + Fine-Art Album',
                'estimated_cost' => 26000000,
                'actual_cost' => 26000000,
                'paid_amount' => 26000000,
                'vendor_id' => $vendorIluminen->id,
                'due_date' => $weddingDate->copy()->subDays(45)->toDateString(),
                'notes' => 'Lunas 100%, termasuk sesi pre-wedding studio.',
            ],
            [
                'category' => WeddingCategory::Souvenir,
                'title' => 'Undangan Cetak Letterpress & Microsite RSVP Digital',
                'estimated_cost' => 7500000,
                'actual_cost' => 7000000,
                'paid_amount' => 5000000,
                'vendor_id' => $vendorPapermint->id,
                'due_date' => $weddingDate->copy()->subDays(35)->toDateString(),
                'notes' => 'Wax seal monogram A&R warna champagne gold.',
            ],
            [
                'category' => WeddingCategory::Souvenir,
                'title' => '450 Botanical Reed Diffuser Souvenir Eksklusif',
                'estimated_cost' => 11000000,
                'actual_cost' => 10500000,
                'paid_amount' => 5000000,
                'vendor_id' => $vendorPapermint->id,
                'due_date' => $weddingDate->copy()->subDays(25)->toDateString(),
                'notes' => 'Kemasan hardbox linen krem dengan pita satin.',
            ],
            [
                'category' => WeddingCategory::Documents,
                'title' => 'Administrasi KUA Menteng, Mahar & Cincin Kawin',
                'estimated_cost' => 14000000,
                'actual_cost' => 13500000,
                'paid_amount' => 13500000,
                'vendor_id' => $vendorWo->id,
                'due_date' => $weddingDate->copy()->subDays(50)->toDateString(),
                'notes' => 'Cincin kawin palladium & gold 18K sudah selesai diukir.',
            ],
            [
                'category' => WeddingCategory::Documents,
                'title' => 'Jasa Wedding Organizer Full-Day & Koordinasi Kru',
                'estimated_cost' => 12000000,
                'actual_cost' => 12000000,
                'paid_amount' => 0,
                'vendor_id' => $vendorWo->id,
                'due_date' => $weddingDate->copy()->subDays(40)->toDateString(),
                'notes' => 'Menunggu finalisasi kontrak teknis minggu depan.',
            ],
            [
                'category' => WeddingCategory::Entertainment,
                'title' => 'Chamber String Quartet, Sound System & MC Dwibahasa',
                'estimated_cost' => 15000000,
                'actual_cost' => 14500000,
                'paid_amount' => 5000000,
                'vendor_id' => $vendorOrchestra->id,
                'due_date' => $weddingDate->copy()->subDays(10)->toDateString(),
                'notes' => 'Daftar 14 lagu prosesi kirab dan first dance sudah dikirim.',
            ],
        ];

        foreach ($budgetRows as $row) {
            BudgetItem::query()->create($row);
        }

        // 3. Seed 18 Checklist & Timeline Tasks across all 7 Timeline Phases
        $taskRows = [
            // H-12 Bulan
            [
                'title' => 'Pertemuan Keluarga Besar & Penetapan Tanggal Akad Nikah',
                'description' => 'Menyelaraskan tanggal baik bersama orang tua kedua mempelai dan menetapkan plafon anggaran Rp 350 Juta.',
                'category' => WeddingCategory::Documents,
                'phase' => TimelinePhase::Months12->value,
                'due_date' => now()->subDays(120)->toDateString(),
                'status' => TaskStatus::Done,
                'priority' => 'high',
                'assigned_to' => 'Pasangan & Keluarga',
                'is_milestone' => true,
                'vendor_id' => null,
                'completed_at' => now()->subDays(122),
            ],
            [
                'title' => 'Kunjungan Survey & Penguncian Kontrak Venue Plataran Menteng',
                'description' => 'Penandatanganan kontrak eksklusif The Glasshouse & pelunasan bertahap.',
                'category' => WeddingCategory::Venue,
                'phase' => TimelinePhase::Months12->value,
                'due_date' => now()->subDays(95)->toDateString(),
                'status' => TaskStatus::Done,
                'priority' => 'high',
                'assigned_to' => 'Aurelia & Reza',
                'is_milestone' => true,
                'vendor_id' => $vendorPlataran->id,
                'completed_at' => now()->subDays(96),
            ],
            // H-9 Bulan
            [
                'title' => 'Food Tasting Perdana & Booking Akasya Fine Catering',
                'description' => 'Uji rasa 8 menu buffet utama dan 4 pondokan bersama orang tua.',
                'category' => WeddingCategory::Catering,
                'phase' => TimelinePhase::Months9->value,
                'due_date' => now()->subDays(60)->toDateString(),
                'status' => TaskStatus::Done,
                'priority' => 'high',
                'assigned_to' => 'Aurelia & Keluarga',
                'is_milestone' => true,
                'vendor_id' => $vendorDharmawangsa->id,
                'completed_at' => now()->subDays(61),
            ],
            [
                'title' => 'Pengukuran Awal Kebaya Akad & Beskap di Maison AdiWira',
                'description' => 'Pemilihan kain French Lace Alabaster, siluet kebaya panjang, dan bordir kerah.',
                'category' => WeddingCategory::Attire,
                'phase' => TimelinePhase::Months9->value,
                'due_date' => now()->subDays(45)->toDateString(),
                'status' => TaskStatus::Done,
                'priority' => 'medium',
                'assigned_to' => 'Aurelia Larasati',
                'is_milestone' => false,
                'vendor_id' => $vendorSaptodjojokartiko->id,
                'completed_at' => now()->subDays(45),
            ],
            [
                'title' => 'Sesi Pre-Wedding Editorial & Penguncian Lumiere Stills',
                'description' => 'Pemotretan pre-wedding konsep monokrom & champagne untuk galeri foyer dan undangan.',
                'category' => WeddingCategory::Documentation,
                'phase' => TimelinePhase::Months9->value,
                'due_date' => now()->subDays(30)->toDateString(),
                'status' => TaskStatus::Done,
                'priority' => 'medium',
                'assigned_to' => 'Aurelia & Reza',
                'is_milestone' => true,
                'vendor_id' => $vendorIluminen->id,
                'completed_at' => now()->subDays(28),
            ],
            // H-6 Bulan
            [
                'title' => 'Pengurusan Berkas N1–N4 Kelurahan & Pendaftaran KUA Menteng',
                'description' => 'Melengkapi surat pengantar RT/RW, pasfoto latar biru, dan pendaftaran penghulu.',
                'category' => WeddingCategory::Documents,
                'phase' => TimelinePhase::Months6->value,
                'due_date' => now()->subDays(10)->toDateString(),
                'status' => TaskStatus::Done,
                'priority' => 'high',
                'assigned_to' => 'Reza Mahendra',
                'is_milestone' => true,
                'vendor_id' => $vendorWo->id,
                'completed_at' => now()->subDays(12),
            ],
            [
                'title' => 'Finalisasi 3D Moodboard & Layout Dekorasi Atelier Botanica',
                'description' => 'Persetujuan gambar kerja 3D pelaminan, posisi meja akad, dan titik lampu gantung.',
                'category' => WeddingCategory::Decoration,
                'phase' => TimelinePhase::Months6->value,
                'due_date' => now()->subDays(3)->toDateString(),
                'status' => TaskStatus::InProgress,
                'priority' => 'high',
                'assigned_to' => 'Aurelia & WO',
                'is_milestone' => true,
                'vendor_id' => $vendorLotusDecor->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Pengambilan Cincin Kawin & Kotak Mahar Kaca Brass',
                'description' => 'Verifikasi grafir nama dan ukuran cincin kawin kedua mempelai.',
                'category' => WeddingCategory::Documents,
                'phase' => TimelinePhase::Months6->value,
                'due_date' => now()->addDays(5)->toDateString(),
                'status' => TaskStatus::InProgress,
                'priority' => 'medium',
                'assigned_to' => 'Reza Mahendra',
                'is_milestone' => false,
                'vendor_id' => null,
                'completed_at' => null,
            ],
            // H-3 Bulan
            [
                'title' => 'Approval Cetak Dummy Undangan Letterpress & Wax Seal',
                'description' => 'Koreksi ejaan nama dan gelar seluruh orang tua sebelum naik cetak 300 eksemplar.',
                'category' => WeddingCategory::Souvenir,
                'phase' => TimelinePhase::Months3->value,
                'due_date' => now()->addDays(14)->toDateString(),
                'status' => TaskStatus::InProgress,
                'priority' => 'high',
                'assigned_to' => 'Aurelia Larasati',
                'is_milestone' => true,
                'vendor_id' => $vendorPapermint->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Pelunasan Produksi 450 Reed Diffuser Souvenir',
                'description' => 'Pengecekan kualitas botol kaca, kartu ucapan terima kasih, dan pita.',
                'category' => WeddingCategory::Souvenir,
                'phase' => TimelinePhase::Months3->value,
                'due_date' => now()->addDays(24)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'medium',
                'assigned_to' => 'Keluarga Mempelai',
                'is_milestone' => false,
                'vendor_id' => $vendorPapermint->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Fitting Busana Tahap II & Makeup Trial Bersama Ibu',
                'description' => 'Uji riasan flawless editorial dan penyesuaian lingkar pinggang kebaya akad.',
                'category' => WeddingCategory::Attire,
                'phase' => TimelinePhase::Months3->value,
                'due_date' => now()->addDays(35)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'Aurelia Larasati',
                'is_milestone' => true,
                'vendor_id' => $vendorSaptodjojokartiko->id,
                'completed_at' => null,
            ],
            // H-1 Bulan
            [
                'title' => 'Technical Meeting Seluruh Vendor di Plataran Menteng',
                'description' => 'Sinkronisasi rundown menit-ke-menit bersama WO, Venue, Catering, Dekorasi, Foto, dan Musik.',
                'category' => WeddingCategory::Venue,
                'phase' => TimelinePhase::Month1->value,
                'due_date' => $weddingDate->copy()->subDays(30)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'WO & Pasangan',
                'is_milestone' => true,
                'vendor_id' => $vendorWo->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Pelunasan Akhir Katering Akasya & Penguncian Jumlah Pax VIP',
                'description' => 'Konfirmasi jumlah tamu RSVP final dan pembagian meja VIP keluarga.',
                'category' => WeddingCategory::Catering,
                'phase' => TimelinePhase::Month1->value,
                'due_date' => $weddingDate->copy()->subDays(28)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'Reza Mahendra',
                'is_milestone' => true,
                'vendor_id' => $vendorDharmawangsa->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Distribusi Undangan Fisik VIP & Pengiriman RSVP WhatsApp',
                'description' => 'Pengiriman undangan cetak untuk tamu kehormatan dan broadcast undangan digital.',
                'category' => WeddingCategory::Souvenir,
                'phase' => TimelinePhase::Month1->value,
                'due_date' => $weddingDate->copy()->subDays(25)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'medium',
                'assigned_to' => 'Pasangan & Keluarga',
                'is_milestone' => false,
                'vendor_id' => null,
                'completed_at' => null,
            ],
            // H-7 Hari
            [
                'title' => 'Gladi Bersih Prosesi Akad Nikah & Serah Terima Cincin ke WO',
                'description' => 'Simulasi alur masuk pengantin, posisi saksi nikah, dan pengecekan mikrofon penghulu.',
                'category' => WeddingCategory::Documents,
                'phase' => TimelinePhase::Week1->value,
                'due_date' => $weddingDate->copy()->subDays(7)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'Pasangan & Saksi',
                'is_milestone' => true,
                'vendor_id' => $vendorWo->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Fitting Final & Serah Terima Busana ke Hotel Transit',
                'description' => 'Steam uap kebaya akad, beskap, dan kelengkapan aksesoris melati segar.',
                'category' => WeddingCategory::Attire,
                'phase' => TimelinePhase::Week1->value,
                'due_date' => $weddingDate->copy()->subDays(4)->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'Aurelia & WO',
                'is_milestone' => false,
                'vendor_id' => $vendorSaptodjojokartiko->id,
                'completed_at' => null,
            ],
            // Hari-H
            [
                'title' => 'Prosesi Sakral Akad Nikah (08:30 WIB) — The Glasshouse',
                'description' => 'Ijab Qabul, penandatanganan buku nikah, sungkeman kedua orang tua, dan sesi foto keluarga inti.',
                'category' => WeddingCategory::Venue,
                'phase' => TimelinePhase::WeddingDay->value,
                'due_date' => $weddingDate->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'Seluruh Tim & Keluarga',
                'is_milestone' => true,
                'vendor_id' => $vendorPlataran->id,
                'completed_at' => null,
            ],
            [
                'title' => 'Malam Resepsi & Jamuan Makan Malam Editorial (18:30 WIB)',
                'description' => 'Kirab pengantin diiringi string quartet, sambutan keluarga, toast, dan jamuan 450 tamu undangan.',
                'category' => WeddingCategory::Entertainment,
                'phase' => TimelinePhase::WeddingDay->value,
                'due_date' => $weddingDate->toDateString(),
                'status' => TaskStatus::Todo,
                'priority' => 'high',
                'assigned_to' => 'WO & Chamber Quartet',
                'is_milestone' => true,
                'vendor_id' => $vendorOrchestra->id,
                'completed_at' => null,
            ],
        ];

        foreach ($taskRows as $row) {
            WeddingTask::query()->create($row);
        }
        WeddingProfile::useTenancy(null);
    }
}
