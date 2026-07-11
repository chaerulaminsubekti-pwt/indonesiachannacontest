# Catatan Perubahan — Portal ICC

## 1. Flyer Preview (RelationManager + Public)

### Masalah
- Preview flyer di tabel RelationManager tidak tampil
- Halaman public event tidak menampilkan flyers dari tabel `event_flyers`

### Penyebab
- `config/filesystems.php` — URL disk `public` pakai `APP_URL` (`http://localhost`) sehingga menghasilkan URL absolut `http://localhost/storage/...`. Saat app diakses via port berbeda (misal `:8000`), browser gagal memuat gambar.
- View `resources/views/event/show.blade.php` masih pakai field lama `$event->flyer` (kolom tunggal tabel `events`). Setelah migrasi ke tabel `event_flyers`, data tidak terbaca.

### Fix
- **`config/filesystems.php` baris 44**: `url` diubah dari `rtrim(env('APP_URL', 'http://localhost'), '/').'/storage'` menjadi `'/storage'` (relatif).
- **`resources/views/event/show.blade.php`**: Ganti `$event->flyer` dengan `$event->flyers()->get()` + Alpine.js carousel (dot navigasi jika >1 flyer).

---

## 2. Google Sheets — Data Peserta (3 kali revisi)

### 🔁 Revisi 1: Import Service
- **File baru**: `app/Services/GoogleSheetImportService.php`
- **File baru**: `database/migrations/2026_07_09_094428_add_google_sheet_url_to_events_table.php`
- **Modifikasi**: `app/Models/Event.php` — tambah `google_sheet_url` ke `$fillable`
- **Modifikasi**: `app/Filament/Organizer/Resources/EventResource/RelationManagers/ParticipantsRelationManager.php`
  - Tambah action "Import dari Google Sheets" di headerActions
  - Tambah `->content()` dengan ringkasan kelas + total
- **Modifikasi**: `resources/views/event/partials/participants.blade.php` — tambah grid ringkasan per Kelas Ikan
- **Error**: `Class "Filament\Tables\Actions\Action" not found` — fix namespace ke `Filament\Actions\Action`

### 🔁 Revisi 2: Ubah Aturan Kolom + Cache Fix
- **Modifikasi**: `app/Services/GoogleSheetImportService.php`
  - Kolom detection: "Nama Ikan" (primary) + "Nama" (primary) + "No" (primary)
  - Error message update
- **Modifikasi**: `app/Http/Controllers/EventController.php`
  - Fetch dari Google Sheets langsung di public page
  - Cache 10 menit, kosong → jangan di-cache
  - SSL bypass + error logging

### 🔁 Revisi 3: Simplifikasi (Link Only)
- **Hapus**: `app/Services/GoogleSheetImportService.php`
- **Hapus**: `app/Services/GoogleSheetService.php`
- **Hapus**: `resources/views/event/partials/participants.blade.php`
- **Hapus**: `resources/views/filament/organizer/participants-summary.blade.php`
- **Modifikasi**: `app/Filament/Organizer/Resources/EventResource.php`
  - Hapus `ParticipantsRelationManager` dari `getRelations()`
- **Modifikasi**: `app/Filament/Organizer/Resources/EventResource.php`
  - Tambah field `google_sheet_url` (Link Google Sheets) di form utama
- **Modifikasi**: `app/Http/Controllers/EventController.php`
  - Hapus semua logic fetch Google Sheets
  - Hapus query participants (tidak dipakai)
  - Bersihkan import yang tidak terpakai
- **Modifikasi**: `resources/views/event/show.blade.php`
  - Tab Data Peserta: jika `google_sheet_url` terisi → tombol "Lihat Data Peserta"
  - Jika tidak → "Belum ada data peserta"

### Alur Akhir (Google Sheets)
1. Penyelenggara buka edit event → isi field **"Link Google Sheets (Data Peserta)"**
2. Sheet harus public, kolom: **No**, **Nama**, **Nama Ikan**
3. Halaman public → tab **Data Peserta** → tombol "Lihat Data Peserta" → buka link Google Sheets

---

## 3. Badge Kategori (Public Event)

### Masalah
Badge kategori di halaman public event tidak terlihat (putih/tulisan tidak terbaca).

### Penyebab
- **Tailwind v4** mengganti `bg-gradient-to-r` menjadi `bg-linear-to-r`. Kelas `bg-gradient-to-r` tidak dikenal, jadi background tidak terpasang.
- Warna gradient kurang kontras.

### Fix
- **`resources/views/event/show.blade.php`**: Ganti gradient dengan warna solid:
  - `bg-amber-600` text-white → Nasional (icon: ⭐)
  - `bg-emerald-600` text-white → Regional (icon: 🌐)
  - `bg-violet-600` text-white → Mini Contest (icon: ▶️)
  - `bg-sky-600` text-white → Latber (icon: ℹ️)
- Tambah `match()` untuk deteksi kategori (case-insensitive via `strtolower()`)

---

## Ringkasan File yang Berubah/Dibuat

| File | Status |
|---|---|
| `config/filesystems.php` | ✏️ Diubah |
| `resources/views/event/show.blade.php` | ✏️ Diubah |
| `app/Models/Event.php` | ✏️ Diubah (fillable) |
| `app/Http/Controllers/EventController.php` | ✏️ Diubah |
| `app/Filament/Organizer/Resources/EventResource.php` | ✏️ Diubah |
| `database/migrations/2026_07_09_094428_add_google_sheet_url_to_events_table.php` | ➕ Baru |
| `database/migrations/2026_07_08_115536_create_portal_tables.php` | (tidak diubah) |
| `app/Services/GoogleSheetImportService.php` | ❌ Dihapus |
| `app/Services/GoogleSheetService.php` | ❌ Dihapus |
| `resources/views/event/partials/participants.blade.php` | ❌ Dihapus |
| `resources/views/filament/organizer/participants-summary.blade.php` | ❌ Dihapus |
| `app/Filament/Organizer/Resources/EventResource/RelationManagers/ParticipantsRelationManager.php` | ✏️ Dikembalikan ke asal (simple table) |
| `composer.json` | ✏️ Ditambah `league/csv` (tidak dipakai akhirnya) |

---

## Command Berguna

```bash
./vendor/bin/pint                          # Laravel Pint (code style)
./vendor/bin/pint --test                   # Cek tanpa fix
composer run-script test                   # Clear config + php artisan test
php artisan migrate                        # Jalankan migration
php artisan tinker                         # Interactive shell
```
