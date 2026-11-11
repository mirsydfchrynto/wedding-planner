<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VendorBookingStatus;
use App\Enums\WeddingCategory;
use App\Models\BudgetItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPayment;
use App\Models\WeddingProfile;
use App\Models\WeddingRundown;
use App\Models\WeddingTask;
use App\Services\BudgetAllocationService;
use App\Services\TimelineGeneratorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class WeddingPlannerSeeder extends Seeder
{
    public function run(): void
    {
        VendorPayment::query()->delete();
        WeddingRundown::query()->delete();
        WeddingTask::query()->delete();
        BudgetItem::query()->delete();
        Vendor::query()->delete();
        WeddingProfile::query()->delete();
        User::query()->delete();

        $customer = User::query()->create([
            'name' => 'Aurelia Larasati & Reza Mahendra',
            'email' => 'aurelia.reza@gmail.com',
            'password' => Hash::make('user'),
            'role' => 'customer',
            'google_id' => 'google-oauth-108291',
            'email_verified_at' => now(),
        ]);

        User::query()->create([
            'name' => 'Testing User Pengantin',
            'email' => 'user@tatanikah.id',
            'password' => Hash::make('user'),
            'role' => 'customer',
            'google_id' => 'google-oauth-108292',
            'email_verified_at' => now(),
        ]);

        User::query()->create([
            'name' => 'M. Irsyad Fachryanto (Lead Admin)',
            'email' => 'irsyad@tatanikah.id',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::query()->create([
            'name' => 'Administrator Tatanikah',
            'email' => 'admin@tatanikah.id',
            'password' => Hash::make('admin'),
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
            'notes' => 'Koordinasi 10 kru hari pernikahan, pengurusan berkas KUA Menteng, dan gladi bersih keluarga.',
        ]);

        // 2. Budget & checklist come from the research master data, exactly like onboarding:
        // 29 budget rows and 124 checklist tasks, due dates spread over the remaining time.
        app(BudgetAllocationService::class)->allocate($profile);
        app(TimelineGeneratorService::class)->generate($profile, 'jawa');

        // Attach the demo vendors to the first budget row of their category.
        $vendorByCategory = [
            WeddingCategory::Venue->value => $vendorPlataran,
            WeddingCategory::Catering->value => $vendorDharmawangsa,
            WeddingCategory::Attire->value => $vendorSaptodjojokartiko,
            WeddingCategory::Decoration->value => $vendorLotusDecor,
            WeddingCategory::Documentation->value => $vendorIluminen,
            WeddingCategory::Souvenir->value => $vendorPapermint,
            WeddingCategory::Entertainment->value => $vendorOrchestra,
            WeddingCategory::Planning->value => $vendorWo,
        ];

        foreach ($vendorByCategory as $category => $vendor) {
            BudgetItem::query()->where('category', $category)->orderBy('id')->first()?->update(['vendor_id' => $vendor->id]);
        }
        // 3. Seed Susunan Acara Hari Pernikahan (Minute-by-Minute Event Schedule)
        $rundownRows = [
            [
                'start_time' => '05:00',
                'end_time' => '07:30',
                'session_name' => 'Persiapan & Tata Rias',
                'activity' => 'Tata Rias & Hairdo Mempelai Wanita, Pria, dan Orang Tua',
                'location' => 'Ruang Rias VIP Glasshouse Plataran',
                'pic_name' => 'Anpa Suha & Tim MUA',
                'pic_phone' => '081234567891',
                'notes' => 'Sarapan pagi keluarga disiapkan WO di pantry VIP pk 06:00 WIB.',
                'sort_order' => 1,
            ],
            [
                'start_time' => '07:30',
                'end_time' => '08:00',
                'session_name' => 'Penyambutan',
                'activity' => 'Kedatangan Keluarga Besar, Saksi Nikah, dan Tamu Terbatas Akad',
                'location' => 'Foyer & Veranda Plataran Menteng',
                'pic_name' => 'Tim Penerima Tamu Keluarga CPW',
                'pic_phone' => '081298765432',
                'notes' => 'Pengecekan mas kawin dan kelengkapan buku nikah bersama petugas KUA.',
                'sort_order' => 2,
            ],
            [
                'start_time' => '08:00',
                'end_time' => '09:15',
                'session_name' => 'Ijab Qabul / Akad Nikah',
                'activity' => 'Prosesi Ijab Qabul, Khutbah Nikah, Penyerahan Mahar & Buku Nikah',
                'location' => 'Pendopo Utama Glasshouse Plataran',
                'pic_name' => 'Penghulu KUA Menteng & MC Bpk. Hartono',
                'pic_phone' => '081188229911',
                'notes' => 'Kamera Axioo standby multi-angle (livestream keluarga luar kota aktif).',
                'sort_order' => 3,
            ],
            [
                'start_time' => '09:15',
                'end_time' => '10:15',
                'session_name' => 'Upacara Adat Jawa Panggih',
                'activity' => 'Prosesi Balangan Gantal, Ngidak Endhog, Sinduran, Bobot Timbang, & Kacar Kucur',
                'location' => 'Pelaminan Utama Adat Jogja Paes Ageng',
                'pic_name' => 'Ibu Pemaes & Sanggar Suryo',
                'pic_phone' => '081377881122',
                'notes' => 'Gamelan live mengiringi gendhing Kodhok Ngorek & Ketawang Boyong.',
                'sort_order' => 4,
            ],
            [
                'start_time' => '10:15',
                'end_time' => '11:30',
                'session_name' => 'Sungkeman & Jamuan Siang',
                'activity' => 'Sungkeman Orang Tua, Foto Keluarga Inti, & Brunch Syukuran',
                'location' => 'Courtyard Dining Room Plataran',
                'pic_name' => 'Lead WO Irsyad Fachryanto',
                'pic_phone' => '081298811002',
                'notes' => 'Menu Akasya Fine Indonesian Catering disajikan prasmanan premium.',
                'sort_order' => 5,
            ],
            [
                'start_time' => '16:00',
                'end_time' => '18:00',
                'session_name' => 'Persiapan Resepsi Malam',
                'activity' => 'Touch-up Rias Pengantin, Ganti Busana Editorial Biyan, & Briefing Panitia Malam',
                'location' => 'Ruang Rias VIP',
                'pic_name' => 'Tim Stylist Biyan & WO',
                'pic_phone' => '081234567891',
                'notes' => 'Valet parking & sound check string quartet selesai sebelum pk 17:30 WIB.',
                'sort_order' => 6,
            ],
            [
                'start_time' => '18:30',
                'end_time' => '21:30',
                'session_name' => 'Gala Resepsi Pernikahan',
                'activity' => 'Kirab Pengantin Editorial, Toast, Jamuan 450 Tamu Undangan, & Live Entertainment',
                'location' => 'Grand Glasshouse Ballroom Plataran',
                'pic_name' => 'MC Dwi Andhika & Jakarta Chamber Orchestra',
                'pic_phone' => '081822334455',
                'notes' => 'Sesi foto VIP diawali pk 19:00 WIB, dilanjutkan ucapan selamat ramah tamah.',
                'sort_order' => 7,
            ],
        ];

        foreach ($rundownRows as $rundown) {
            WeddingRundown::query()->create($rundown);
        }

        // 6. Seed Riwayat Multi-Termin Pembayaran Vendor
        $paymentRows = [
            ['vendor_id' => $vendorPlataran->id, 'payment_stage' => 'DP 30%', 'amount' => 25000000, 'paid_date' => $weddingDate->copy()->subDays(180)->toDateString(), 'receipt_ref' => 'BCA-PLTR-001', 'notes' => 'Uang muka penguncian tanggal Glasshouse Plataran'],
            ['vendor_id' => $vendorPlataran->id, 'payment_stage' => 'Termin 1 (30%)', 'amount' => 25000000, 'paid_date' => $weddingDate->copy()->subDays(120)->toDateString(), 'receipt_ref' => 'BCA-PLTR-002', 'notes' => 'Termin kedua setelah konfirmasi layout venue'],
            ['vendor_id' => $vendorPlataran->id, 'payment_stage' => 'Pelunasan (40%)', 'amount' => 28000000, 'paid_date' => $weddingDate->copy()->subDays(60)->toDateString(), 'receipt_ref' => 'BCA-PLTR-003', 'notes' => 'Pelunasan 100% sewa venue & valet'],
            ['vendor_id' => $vendorDharmawangsa->id, 'payment_stage' => 'DP 35%', 'amount' => 35000000, 'paid_date' => $weddingDate->copy()->subDays(150)->toDateString(), 'receipt_ref' => 'MDR-CATER-101', 'notes' => 'DP catering 450 pax setelah food tasting'],
            ['vendor_id' => $vendorDharmawangsa->id, 'payment_stage' => 'Termin 1 (50%)', 'amount' => 50000000, 'paid_date' => $weddingDate->copy()->subDays(75)->toDateString(), 'receipt_ref' => 'MDR-CATER-102', 'notes' => 'Termin utama porsi stall live cooking'],
            ['vendor_id' => $vendorSaptodjojokartiko->id, 'payment_stage' => 'DP 35%', 'amount' => 20000000, 'paid_date' => $weddingDate->copy()->subDays(160)->toDateString(), 'receipt_ref' => 'BYN-BESPOKE-91', 'notes' => 'DP pembuatan busana couture & fitting awal'],
            ['vendor_id' => $vendorSaptodjojokartiko->id, 'payment_stage' => 'Pelunasan (65%)', 'amount' => 38000000, 'paid_date' => $weddingDate->copy()->subDays(50)->toDateString(), 'receipt_ref' => 'BYN-BESPOKE-92', 'notes' => 'Pelunasan setelah fitting final dan handover busana'],
            ['vendor_id' => $vendorLotusDecor->id, 'payment_stage' => 'DP 50%', 'amount' => 25000000, 'paid_date' => $weddingDate->copy()->subDays(140)->toDateString(), 'receipt_ref' => 'SRY-DEC-441', 'notes' => 'DP dekorasi pelaminan Paes Ageng & Glasshouse'],
            ['vendor_id' => $vendorLotusDecor->id, 'payment_stage' => 'Termin 1 (50%)', 'amount' => 25000000, 'paid_date' => $weddingDate->copy()->subDays(70)->toDateString(), 'receipt_ref' => 'SRY-DEC-442', 'notes' => 'Pelunasan dekorasi & instalasi bunga segar'],
            ['vendor_id' => $vendorIluminen->id, 'payment_stage' => 'DP 40%', 'amount' => 15000000, 'paid_date' => $weddingDate->copy()->subDays(170)->toDateString(), 'receipt_ref' => 'AXO-PHOTO-09', 'notes' => 'DP pemesanan tim fotografer & videografer Axioo'],
            ['vendor_id' => $vendorIluminen->id, 'payment_stage' => 'Pelunasan (60%)', 'amount' => 23000000, 'paid_date' => $weddingDate->copy()->subDays(45)->toDateString(), 'receipt_ref' => 'AXO-PHOTO-10', 'notes' => 'Pelunasan dokumentasi hari pernikahan & album teaser'],
        ];

        foreach ($paymentRows as $payment) {
            VendorPayment::query()->create($payment);
        }
        WeddingProfile::useTenancy(null);
    }
}
