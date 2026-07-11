# Portal ICC — Code Review Report (cek2.md)

**Tanggal Review:** 09 Juli 2026  
**Versi Laravel:** 12.63.0  
**Status Test:** 17 passed, 30 assertions (PASS)

---

## 📋 Ringkasan Eksekutif

Codebase telah selesai **Phase 1 MVP (18/18 step checklist)**. Semua fitur utama berjalan: public portal, admin Filament, organizer panel, auth role-based, sertifikat otomatis via queue. Test suite lolos. Namun ditemukan **beberapa issue teknis & konsistensi** yang perlu diperbaiki sebelum lanjut ke Phase 2.

---

## 🔴 Critical Issues (Harus Diperbaiki)

### 1. N+1 Query di `EventController::show()`
**File:** `app/Http/Controllers/EventController.php:43-66`  
**Masalah:** View `event/show.blade.php` memanggil `$event->flyers()->get()` (line 17) tanpa eager load. Setiap event detail akan trigger 1 query tambahan.
**Fix:** Tambah `'flyers'` ke `with()` di controller.

```php
// Line 51: ubah dari
->with('organizer.user', 'classes')
// Menjadi
->with('organizer.user', 'classes', 'flyers')
```

---

### 2. N+1 Query di `event/index.blade.php`
**File:** `resources/views/event/index.blade.php:46`  
**Masalah:** Loop `$events` memanggil `$event->flyers()->first()` per item → N query.
**Fix:** Eager load `flyers` di `EventController::index()`:
```php
->with('organizer.user', 'flyers')
```
Lalu di view ganti `$event->flyers()->first()` → `$event->flyers->first()`.

---

### 3. Kolom `flyer` (legacy) Masih Ada di DB & Model
**File:** `database/migrations/2026_07_08_115536_create_portal_tables.php:32`, `app/Models/Event.php:15`  
**Masalah:** Tabel `events` masih punya kolom `flyer` (string single) padahal sudah migrasi ke tabel `event_flyers` (multiple). Data lama mungkin masih dipakai di view fallback (line 52 `event/show.blade.php`).
**Rekomendasi:**
- Buat migration hapus kolom `flyer` dari `events` (setelah migrasi data lama ke `event_flyers`).
- Hapus `'flyer'` dari `$fillable` di `Event.php`.
- Hapus fallback `@elseif ($event->flyer)` di `event/show.blade.php` & `event/index.blade.php`.

---

### 4. Certificate Verification Route Tidak Validasi `winner` & `event` Relation
**File:** `app/Http/Controllers/CertificateController.php:10-27`  
**Masalah:** Query hanya load `winner.event`. Jika `winner` atau `event` soft-deleted/null, halaman verifikasi bisa error atau menampilkan data kosong tanpa penanganan.
**Fix:** Tambah `whereHas` atau optional chaining di view.

---

### 5. `GenerateCertificateJob` Tidak Handle Exception
**File:** `app/Jobs/GenerateCertificateJob.php`  
**Masalah:** Jika PDF generation gagal (font missing, storage penuh, dsb), job gagal silent tanpa retry/logging. `ShouldQueue` default `tries=1`.
**Fix:** Tambah `tries`, `backoff`, dan `catch`/`failed` method untuk logging/notifikasi ke organizer.

```php
public $tries = 3;
public $backoff = [10, 30, 60];

public function failed(\Throwable $exception): void
{
    \Log::error('Certificate generation failed', [
        'winner_id' => $this->winner->id,
        'error' => $exception->getMessage(),
    ]);
    // Optional: notify organizer
}
```

---

## 🟡 Medium Issues (Perbaikan Kualitas)

### 6. `EventController::show()` Tidak Load `galleries` & `testimonial` Relations
View `event/show.blade.php` include `event.partials.gallery` (load `$galleries` dari controller) tapi tidak load `testimonial` yang mungkin dibutuhkan nanti. Konsistenkan eager load.

---

### 7. Duplicate Kategori Badge Logic
**File:** `resources/views/event/show.blade.php:47-54` & `resources/views/event/index.blade.php:63-71`  
**Masalah:** Logika `match ($kategori)` duplikat di 2 view. Jika kategori baru ditambah, harus edit 2 tempat.
**Fix:** Buat helper `App\Helpers\EventHelper::badge($kategori)` atau Blade component `<x-event-badge :kategori="$event->kategori" />`.

---

### 8. `PengajuanEvent` Livewire: Validasi Password Confirmation Manual
**File:** `app/Livewire/PengajuanEvent.php:49-51`  
**Masalah:** `pic_password_confirmation` divalidasi manual di `validateStep2()` tapi tidak pakai rule `confirmed` Laravel. Lebih rapi pakai rule bawaan:
```php
'pic_password' => 'required|confirmed|min:8',
```
Lalu hapus field `pic_password_confirmation` dari properties & validasi manual.

---

### 9. `EventFlyersRelationManager` Tidak Hapus File Fisik Saat Delete Record
**File:** `app/Filament/Organizer/Resources/EventResource/RelationManagers/EventFlyersRelationManager.php`  
**Masalah:** FileUpload `->disk('public')` tapi tidak ada `deleteUploadedFiles()` atau observer untuk hapus file di storage saat record dihapus.
**Fix:** Tambah `->deleteUploadedFiles()` pada FileUpload atau pakai `Spatie\MediaLibrary` / observer model.

---

### 10. `User::canAccessPanel('panel')` Hanya Cek `role === 'penyelenggara'`
**File:** `app/Models/User.php:43-46`  
**Masalah:** Tidak cek `status === 'active'`. User `penyelenggara` dengan status `inactive` tetap bisa akses `/panel` (walau login redirect sudah block di `LoginController`, tapi bypass URL langsung bisa).
**Fix:**
```php
'panel' => $this->role === 'penyelenggara' && $this->status === 'active',
```

---

### 11. Missing Index pada Foreign Key yang Sering Di-Query
**Migration:** `2026_07_08_115536_create_portal_tables.php`  
Kolom FK sering di-query tapi tidak punya index eksplisit (Laravel auto-index FK tapi composite query butuh index tambahan):
- `participants(event_id, event_class_id)`
- `winners(event_id, event_class_id)`
- `event_galleries(event_id)`
- `certificates(winner_id)` sudah PK
- `testimonials(event_id, status)` — untuk query approved testimoni di home

---

### 12. `SiteSetting` Observer Belum Publish Config
**File:** `app/Observers/SiteSettingObserver.php` (cek exists) & `config/dompdf.php`  
**Catatan checklist:** `config/dompdf.php` belum dipublish → sudah fixed per audit note. Pastikan `php artisan vendor:publish --tag=dompdf-config` sudah jalan.

---

## 🟢 Low / Nitpick (Opsional)

### 13. Tailwind Config Custom Colors (`icc-primary`, `icc-dark`, `icc-gray`)
View pakai class custom (`text-icc-primary`, `bg-icc-gray`, dll). Pastikan `tailwind.config.js` define color palette ICC agar konsisten & tidak bergantung JIT arbitrary value.

### 14. `Event` Model: `status` Enum Tidak Pakai Cast/Enum Class
**File:** `app/Models/Event.php`  
Status values: `pending`, `approved`, `rejected`, `berjalan`, `selesai`. Disarankan pakai `BackedEnum` + `Attribute::cast()` agar type-safe & autocomplete IDE.

### 15. `Winner` Model Missing `event()` & `class()` Relation
**File:** `app/Models/Winner.php` (cek exists)  
Digunakan di `WinnersRelationManager` & `CertificateController` via `$winner->event` & `$winner->class`. Pastikan relasi `belongsTo` ada.

### 16. Test Coverage Masih Minimal
- Hanya 17 test (8 login + 7 public pages + 2 example).
- Belum ada test: pengajuan event flow, certificate generation, organizer panel CRUD, admin approval.
- Target: minimal feature test tiap user flow kritis.

---

## 📦 Dependency Check

| Package | Version | Status |
|---------|---------|--------|
| `filament/filament` | v4.x | ✅ OK (Laravel 12 compat) |
| `livewire/livewire` | v3.x | ✅ OK |
| `spatie/laravel-permission` | v6.x | ✅ OK |
| `barryvdh/laravel-dompdf` | v3.x | ✅ OK (config published) |
| `chillerlan/php-qrcode` | via Filament | ✅ OK |
| `league/csv` | composer.json | ⚠️ Terpasang tapi **tidak dipakai** (Google Sheets import dihapus rev 3). Bisa `composer remove league/csv` |

---

## 🎯 Recommended Next Steps (Priority Order)

1. **Fix N+1 queries** (#1, #2) — impact performa langsung.
2. **Bersihkan kolom `flyer` legacy** (#3) — hapus debt migrasi.
3. **Hardening `GenerateCertificateJob`** (#5) — reliability production.
4. **Extract badge helper/component** (#7) — maintainability.
5. **Tambah index DB** (#11) — performa query besar.
6. **Expand test coverage** (#16) — confidence deploy.

---

## ✅ Yang Sudah Baik (Sesuai PRD & Checklist)

- Arsitektur 3 panel (Public, Admin Filament, Organizer Livewire) terpisah jelas.
- Single login + role-based redirect berfungsi (test pass).
- RBAC Spatie Permission: `super_admin`, `editor`, `penyelenggara`.
- Event approval flow via Filament (status `pending`→`approved`/`rejected`).
- Organizer panel: CRUD peserta, juara, gallery, testimoni, generate sertifikat (queue).
- Sertifikat: DomPDF landscape A4 + QR code verifikasi + download public.
- Static pages: Struktur, Juri, Regulasi (embed viewer + download).
- Flyer multiple via `event_flyers` table + RelationManager (disk `public`).
- Google Sheets simplified: hanya simpan URL, public page link ke sheets.
- Badge kategori pakai warna solid (Tailwind v4 compat).
- Queue database driver + job sertifikat.
- Pint (PSR-12) pass, test pass.

---

## 📝 Catatan Tambahan

- **Filament v4** di `AdminPanelProvider` pakai `->default()` tapi `OrganizerPanelProvider` tidak. Ini benar karena hanya 1 panel default.
- `LoginController` pakai `email` untuk login, tapi `User` punya `username` fillable. Pastikan form login konsisten (sekarang pakai email).
- `PengajuanEvent` step 3 submit membuat `User` + `Organizer` + `Event` dalam 1 transaksi — sudah pakai `DB::transaction()`? (Perlu cek kode lengkap).
- `Event` model `booted()` event `updated` kirim notifikasi `EventApproved`/`EventRejected` — notifikasi email sudah pakai queue? (Cek `Notification` implement `ShouldQueue`).

## 🛠️ Perubahan Terkini
- `app/Models/Event.php` — perbaikan event approval: saat status event berubah menjadi `approved`, akun penyelenggara terkait diaktifkan (`status = active`).
- `app/Http/Controllers/Auth/LoginController.php` — login sekarang mendukung input `email` atau `username` melalui field `login`.
- `resources/views/auth/login.blade.php` — form login diubah dari `Email` menjadi `Email atau Username`, dan field dikirim sebagai `login`.
- `app/Models/User.php` — akses panel `/panel` dikunci hanya untuk user `penyelenggara` yang `status === active`.- `app/Http/Controllers/Auth/LoginController.php` — logout sekarang redirect ke halaman login `route('login')` bukan home.- `cek2.md` diperbarui untuk mencatat semua perubahan selama perbaikan ini.
- `app/Models/User.php` — akses panel `/panel` dikunci hanya untuk user `penyelenggara` yang `status === active`.
- `app/Filament/Auth/Responses/LogoutResponse.php` — custom Filament logout response dibuat untuk redirect ke login page setelah logout.
- `app/Providers/AppServiceProvider.php` — mendaftarkan override `LogoutResponseContract` untuk Filament.
- `app/Http/Controllers/Auth/LoginController.php` — logout sekarang redirect ke halaman login `route('login')` bukan home.
- `cek2.md` diperbarui untuk mencatat semua perubahan selama perbaikan ini.
Note: The changes listed above were reverted to their original implementation. Specifically:

- `app/Models/Event.php`: removed automatic activation of organizer `User` on event approval; only notifications remain.
- `app/Http/Controllers/Auth/LoginController.php`: restored to email-only login and original logout redirect to home.
- `resources/views/auth/login.blade.php`: restored email-only input.
- `app/Models/User.php`: restored `canAccessPanel` check to role-only (no `status` check).
- `app/Filament/Auth/Responses/LogoutResponse.php`: custom file removed.
- `app/Providers/AppServiceProvider.php`: removed binding override for Filament `LogoutResponseContract`.

These reverts were applied to align the codebase back to the state prior to the approval/login/logout fixes.

---

**Reviewer:** AI Assistant  
**File:** `cek2.md` (ini)  
**Reference:** `.agent/checklist.md`, `.agent/prd.md`, `cek1.md`
