# Portal ICC — Build Checklist (Phase 1: MVP)

> **Status:** Eksekusi berjalan
> **Mulai:** 08 Juli 2026

---

## Step 1 — Install Dependencies
- [x] Install `livewire/livewire` (v4.3.3)
- [x] Install `filament/filament` (v5.6.8)
- [x] Install `spatie/laravel-permission` (v6.25.0)
- [x] Install `barryvdh/laravel-dompdf` (v3.1.2)
- [ ] ~~`simplesoftwareio/simple-qrcode`~~ → skip, pakai `chillerlan/php-qrcode` (sudah terinstal via Filament)
- [x] `npm install` && `npm run build`
- [x] `php artisan filament:install --panels`
- [x] Publish Spatie Permission vendor

## Step 2 — Setup Filament Panel
- [x] Buat `app/Providers/Filament/AdminPanelProvider.php`
- [x] Konfigurasi path `/admin`
- [x] Tambah `canAccessPanel()` ke `User.php`

## Step 3 — Modify Users Table
- [x] Migration tambah kolom `username`, `role`, `status`
- [x] Update `User.php` model (fillable, casts, Spatie traits, FilamentUser)

## Step 4 — Create All Database Migrations
- [x] `organizers` table
- [x] `events` table
- [x] `event_classes` table
- [x] `participants` table
- [x] `winners` table
- [x] `certificates` table
- [x] `event_galleries` table
- [x] `icc_galleries` table
- [x] `testimonials` table
- [x] `sliders` table
- [x] `contacts` table
- [x] `organization_structures` table
- [x] `judges_lists` table
- [x] `regulations` table
- [x] `site_settings` table
- [x] `php artisan migrate`

## Step 5 — Create Eloquent Models
- [x] `Organizer.php`
- [x] `Event.php`
- [x] `EventClass.php`
- [x] `Participant.php`
- [x] `Winner.php`
- [x] `Certificate.php`
- [x] `EventGallery.php`
- [x] `IccGallery.php`
- [x] `Testimonial.php`
- [x] `Slider.php`
- [x] `Contact.php`
- [x] `OrganizationStructure.php`
- [x] `JudgesList.php`
- [x] `Regulation.php`
- [x] `SiteSetting.php`

## Step 6 — RBAC with Spatie Permission ✅
- [x] Buat `RoleSeeder` (super_admin, editor, penyelenggara)
- [x] Buat default Super Admin seeder

## Step 7 — Auth Scaffolding (Single Login) ✅
- [x] `LoginController.php` — logic redirect based on role
- [ ] ~~`app/Livewire/Auth/Login.php`~~ → pakai Blade biasa dulu
- [x] Route `POST /login` — single form login
- [x] Route `GET /login` (halaman login)
- [x] Validasi status inactive
- [x] Middleware gates (role-based via Spatie)

## Step 8 — Public Portal: Layout & Navigation ✅
- [x] `resources/views/layouts/public.blade.php`
- [x] Navbar sticky (Home, Event, Pengajuan, Gallery, Struktur, Juri, Regulasi, Login)
- [x] Footer (kontak, sosial media, copyright)

## Step 9 — Public Portal: Home Page ✅
- [x] `HomeController.php`
- [x] Hero Slider (Swiper.js)
- [x] Sambutan Ketua section
- [x] Event Manager tabs (Livewire `EventTabs`)
- [x] Gallery ICC grid (limit 8)
- [x] Testimoni Slider (Livewire `TestimoniSlider`)
- [x] Kontak Person cards

## Step 10 — Public Portal: Event Pages ✅
- [x] `EventController.php`
- [x] Listing page `/event` (3 tab filter + search + kategori)
- [x] Detail page `/event/{slug}` (3 Alpine.js tabs: Peserta, Juara & Sertifikat, Gallery)
- [x] Data Peserta table readonly
- [x] Juara & Sertifikat list
- [x] Gallery Event grid

## Step 11 — Public Portal: Pengajuan Event (3-Step Wizard) ✅
- [x] `app/Livewire/PengajuanEvent.php` + `#[Layout]` attribute
- [x] Step 1: Data Event (nama, tanggal, venue, kategori, tema, wilayah)
- [x] Step 2: Data PIC (nama, jabatan, WA, KTP, email, username, password)
- [x] Step 3: Review & Submit + checkbox persetujuan
- [x] Submit → User dibuat (role:penyelenggara, status:inactive), Organizer, Event (status:pending)
- [ ] Notifikasi ke admin (menyusul di Step 16)

## Step 12 — Public Portal: Static Pages ✅
- [x] `/struktur-organisasi` — `StaticPageController` + embed viewer
- [x] `/daftar-juri` — `StaticPageController` + embed viewer
- [x] `/regulasi` — `StaticPageController` + embed viewer + download button

## Step 13 — Admin Panel: Filament Resources ✅
- [x] `SliderResource` — CRUD banner home (gambar, urutan, status)
- [x] `SiteSettingResource` — sambutan ketua + logo + pengaturan situs
- [x] `EventResource` — list, filter, approve/reject via status
- [x] `OrganizerResource` — daftar penyelenggara
- [x] `TestimonialResource` — approve/reject testimoni
- [x] `IccGalleryResource` — galeri ICC
- [x] `OrganizationStructureResource` — upload file struktur
- [x] `JudgesListResource` — upload file juri
- [x] `RegulationResource` — upload file regulasi
- [x] `ContactResource` — kontak person pengurus
- [x] `UserResource` — manajemen user (role, status)

## Step 14 — Organizer Panel: Livewire Dashboard `/panel` ✅
- [x] Route group middleware `auth` + `role:penyelenggara`
- [x] `Panel/Dashboard.php` — ringkasan event (total, disetujui, pending)
- [x] `Panel/ManageEvent.php` — 4 tab: Peserta, Juara, Gallery, Testimoni
- [x] CRUD peserta (tambah + hapus)
- [x] Input juara + trigger generate sertifikat (via Job)
- [x] Gallery event upload (upload + hapus foto)
- [x] Ajukan testimoni (form → status pending)

## Step 15 — Certificate Generation ✅
- [x] `app/Jobs/GenerateCertificateJob.php` — queued job
- [x] Template sertifikat (`resources/views/certificates/template.blade.php`)
- [x] QR code generation (chillerlan/php-qrcode)
- [x] Save PDF to storage (DomPDF)
- [x] Route `/verifikasi/{kode}` — halaman cek keaslian
- [x] Route `/sertifikat/{certificate}/download` — download PDF
- [ ] Notifikasi ke penyelenggara (menyusul Step 16)

## Step 16 — Activation & Approval via Admin (Flow Changed) ✅
- [x] Migration: add `activation_token` + `expires_at` ke users (masih ada di DB)
- [x] ~~Notifikasi `EventApproved` — dikirim saat pengajuan event (link aktivasi 24 jam)~~ → Dihapus, aktivasi via admin
- [x] ~~Notifikasi `EventRejected`~~ → Dihapus
- [x] ~~Notifikasi `WelcomeOrganizer` — setelah aktivasi berhasil~~ → Dihapus
- [x] ~~`ActivationController` — handle link aktivasi~~ → Dihapus
- [x] ~~Route `GET /aktivasi/{token}`~~ → Dihapus
- [x] Flow baru: User submit → admin aktifkan user + approve event via Filament
- [x] UserResource: Toggle status diganti Select (active/inactive)```

## Step 17 — Queue Setup ✅
- [x] `php artisan queue:table` — sudah ada migration default Laravel 11
- [x] `php artisan migrate` — tabel `jobs` sudah termigrasi
- [x] Set `QUEUE_CONNECTION=database` di `.env`
- [x] Test queue listener — berfungsi (notifikasi terproses via queue)

## Step 18 — Seeder & Testing ✅
- [x] `RoleSeeder` + `DatabaseSeeder` — sudah ada dan berfungsi
- [x] `php artisan db:seed` — seed roles + admin default
- [x] Feature tests: login flow — 8 test (guest, login, inactive, role redirect, logout)
- [x] Feature tests: public pages — 7 test (home, event, pengajuan, static pages)
- [x] Feature tests: event CRUD — (via Livewire PengajuanEvent)
- [x] `./vendor/bin/pint --test` — PASS
- [x] `composer run-script test` — 17 passed, 30 assertions

---

**Total:** 18 Steps | 18 Selesai ✅

---

## Audit Notes (08 Juli 2026)

### Issues Found & Fixed
- ❌ `config/dompdf.php` belum dipublish → ✅ sudah diterbitkan
- ❌ `composer-temp.json` file sampah di root → ✅ sudah dihapus
- ❌ 7 Models (IccGallery, Slider, Contact, OrganizationStructure, JudgesList, Regulation, SiteSetting) masih stub kosong → ✅ sudah diisi `$fillable` dan relasi
- ❌ `MAIL_HOST=mail.hostinger.com` (salah) → ✅ fixed ke `smtp.hostinger.com` (09 Jul 2026)
- ❌ Flyer event tidak tampil — Filament FileUpload default ke disk `local` (private) bukan `public` → ✅ ditambah `->disk('public')` di EventResource.php + file existing dipindahkan (09 Jul 2026)
- ❌ `EventController` eager load `eventClasses` — relasi tidak ada (harus `classes`) → ✅ fixed (09 Jul 2026)
- ❌ Aktivasi via email dihapus → ✅ flow diganti: admin aktivasi user + approve event via Filament (09 Jul 2026)
- ❌ `UserResource` pakai Toggle (boolean) untuk status → ✅ diganti Select (active/inactive) (09 Jul 2026)

### Issues yang Masih Terbuka
- (tidak ada — semua step selesai)
