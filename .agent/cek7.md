# Laporan Pekerjaan - Sesi 7

## Fitur: Tombol Daftar & WhatsApp di Halaman Event

### Yang dikerjakan:

| # | Item | File | Status |
|---|------|------|--------|
| 1 | Migration tabel `events` kolom `no_wa_cp` | `database/migrations/2026_07_11_031032_add_no_wa_cp_to_events_table.php` | ✅ |
| 2 | Menjalankan migrate | `php artisan migrate --force` | ✅ |
| 3 | Update `$fillable` model Event | `app/Models/Event.php` | ✅ |
| 4 | Field `no_wa_cp` di form Event Organizer (section Data Peserta, 2 kolom) | `app/Filament/Organizer/Resources/EventResource.php` | ✅ |
| 5 | Tombol "Daftar Sekarang" (solid hijau) + "Hubungi CP" (outline hijau) di halaman public event | `resources/views/event/show.blade.php` | ✅ |

### Detail:
- **Field CP Pendaftaran**: input `tel`, format `62812xxxxxxx`, ditempatkan di section **Data Peserta** (bersebelahan dengan Google Sheets URL)
- **Tombol di public**: muncul hanya jika `no_wa_cp` tidak kosong
  - Tombol "Daftar Sekarang" → background hijau solid, teks putih, ikon WA
  - Tombol "Hubungi CP" → border hijau, teks hijau, ikon WA
  - Keduanya buka `https://wa.me/{nomor}` di tab baru

### Ringkasan
| Item | Status |
|------|--------|
| Migration + model | ✅ Selesai |
| Form panel penyelenggara | ✅ Selesai |
| Tombol public | ✅ Selesai |
