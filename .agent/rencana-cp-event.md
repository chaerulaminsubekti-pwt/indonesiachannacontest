# Rencana: Tombol Daftar & WhatsApp di Halaman Event

## Ringkasan
Setiap event bisa punya nomor WhatsApp Contact Person (CP) sendiri yang diisi oleh penyelenggara. Di halaman public, tampil tombol "Daftar Sekarang" dan tombol WhatsApp yang mengarah ke nomor CP event tersebut.

---

## 1. Database

### Modifikasi Tabel `events`
Tambah 1 kolom baru:

```sql
ALTER TABLE events ADD COLUMN no_wa_cp VARCHAR(20) NULL AFTER google_sheet_url;
```

### Model `Event.php`
```php
protected $fillable = [
    ..., 'google_sheet_url', 'no_wa_cp',
];
```

---

## 2. Organizer Panel

### EventResource Form — tambah field di section yang sudah ada
- Section: **Informasi Event** atau buat section baru **Kontak Pendaftaran**
- Field: `TextInput::make('no_wa_cp')->label('CP Pendaftaran (WhatsApp)')->tel()->maxLength(20)`

**Lokasi:** `app/Filament/Organizer/Resources/EventResource.php`

---

## 3. Halaman Public Event Detail

### Lokasi: `resources/views/event/show.blade.php`

Setelah grid info event (tanggal, venue, dll) dan sebelum divider, tambah 2 tombol:

**Jika event memiliki `no_wa_cp`:**
```html
<div class="flex flex-wrap gap-3 mt-6">
    <a href="https://wa.me/{{ $event->no_wa_cp }}" target="_blank"
       class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition shadow-lg">
        <svg class="w-5 h-5" ...> <!-- icon WA --> </svg>
        Daftar Sekarang
    </a>
    <a href="https://wa.me/{{ $event->no_wa_cp }}" target="_blank"
       class="inline-flex items-center gap-2 px-6 py-3 border border-green-500 text-green-600 rounded-xl font-semibold hover:bg-green-50 transition shadow-sm">
        <svg class="w-5 h-5" ...> <!-- icon WA --> </svg>
        Hubungi CP
    </a>
</div>
```

**Jika `no_wa_cp` kosong, tombol tidak ditampilkan.**

### Posisi dalam Layout
Letakkan tombol setelah grid info (tanggal, venue, penyelenggara, dll) dan sebelum bagian Juri.

---

## 4. File yang Diubah

| File | Perubahan |
|------|-----------|
| `app/Models/Event.php` | Tambah `no_wa_cp` ke `$fillable` |
| `app/Filament/Organizer/Resources/EventResource.php` | Tambah field `no_wa_cp` di form |
| `resources/views/event/show.blade.php` | Tambah 2 tombol (Daftar & WhatsApp) |
| Database migration (baru) | `add_no_wa_cp_to_events_table` |

---

## 5. Catatan
- Nomor WA diisi manual oleh penyelenggara per event (bukan ambil dari profil organizer)
- Format nomor: `62812xxxxxxx` (kode negara tanpa `+` atau `0`)
- Tombol "Daftar Sekarang" dan "Hubungi CP" sama-sama buka WhatsApp
- Bisa ditambah 1 kolom `link_daftar` jika nanti ingin pakai Google Form / link eksternal
