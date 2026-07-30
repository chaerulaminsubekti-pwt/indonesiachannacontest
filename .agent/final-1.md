# Laporan Perubahan — Sesi 8 (final-1.md)

## Permintaan 1: Posisi Deskripsi Event
Menampilkan deskripsi event di halaman public, diisi oleh admin/penyelenggara, letaknya di bawah judul event dan di atas keterangan tanggal & venue.

---

### Analisis Awal

| Komponen | Status Awal |
|----------|-------------|
| Kolom `deskripsi` di tabel `events` | ✅ Sudah ada (`text`, nullable) — migration `2026_07_08_115536_create_portal_tables.php:33` |
| Model `Event.php` `$fillable` | ✅ Sudah ada `'deskripsi'` |
| Form Admin `EventResource.php` | ✅ Sudah ada `Textarea::make('deskripsi')` di section Deskripsi |
| Form Organizer `EventResource.php` | ✅ Sudah ada `Textarea::make('deskripsi')` di section Deskripsi |
| Tampilan public `event/show.blade.php` | ⚠️ Sudah ada tapi posisi di **bawah** section Juri (kurang strategis) |

### Perubahan

#### File: `resources/views/event/show.blade.php`

**Sebelum:** Deskripsi ditampilkan di bagian paling bawah kolom kanan (setelah Juri), dengan label "Deskripsi" dan warna teks abu-abu.

**Sesudah:** Deskripsi dipindahkan ke posisi **antara judul event (`<h1>`)** dan **grid tanggal/venue**. Label "Deskripsi" dihapus (teks langsung muncul tanpa header). Warna teks: `text-icc-dark` (hitam).

**Struktur baru kolom kanan:**
```
1. Badge Kategori
2. Judul Event
3. Deskripsi Event ← (Baru)
4. Grid Info: Tanggal, Venue, Kota, Penyelenggara, Jumlah Kelas, Status
5. Contact Person (multiple)
6. Daftar Juri
```

---

## Permintaan 2: Multiple Contact Person per Event
CP tiap event bisa lebih dari 1, dengan nama CP, dikelola oleh role penyelenggara.

---

### Perubahan

#### 1. Migration Baru
**File:** `database/migrations/2026_07_30_100000_create_event_cps_table.php`

Tabel `event_cps`:
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint primary key | Auto increment |
| `event_id` | bigint FK → events | Cascade on delete |
| `nama` | string | Nama CP |
| `no_wa` | string(20) | Nomor WhatsApp |
| `timestamps` | | created_at, updated_at |

#### 2. Model Baru
**File:** `app/Models/EventCp.php`

- `$fillable`: `event_id`, `nama`, `no_wa`
- Relasi `event()`: BelongsTo ke Event

#### 3. Model Event — Relasi Baru
**File:** `app/Models/Event.php`

Tambah method:
```php
public function cps(): HasMany
{
    return $this->hasMany(EventCp::class);
}
```

#### 4. Relation Manager — EventCpsRelationManager
**File:** `app/Filament/Organizer/Resources/EventResource/RelationManagers/EventCpsRelationManager.php`

- Form: `nama` (required) + `no_wa` (required, tel, format 628xx)
- Table: Nama CP (searchable), No. WhatsApp (copyable), Tanggal Ditambahkan
- Actions: Tambah CP, Edit, Hapus

#### 5. Organizer EventResource — Update
**File:** `app/Filament/Organizer/Resources/EventResource.php`

- Hapus field `no_wa_cp` (single CP) dari form
- Ubah section "Data Peserta" hanya untuk Google Sheets
- Tambah `EventCpsRelationManager::class` ke `getRelations()` (setelah Flyers)

#### 6. Admin EventResource — Update
**File:** `app/Filament/Resources/Events/EventResource.php`

- Import dan tambah `EventCpsRelationManager::class` ke `getRelations()`

#### 7. Public View — Multiple CP
**File:** `resources/views/event/show.blade.php`

**Sebelum:** Tombol WA tunggal `$event->no_wa_cp`

**Sesudah:** Loop `$event->cps` menampilkan card per CP:
- Lingkaran hijau dengan icon WA
- Nama CP (bold)
- Nomor WA (abu-abu, kecil)
- Hover efek shadow + border hijau
- Klik → buka `https://wa.me/{no_wa}`

#### 8. Eager Load
**File:** `app/Http/Controllers/EventController.php`

Tambah `'cps'` ke `with()` di method `show()`.

---

## Permintaan 3: Pop Up Flyer (Admin ICC)
Pop up flyer muncul saat website dibuka, dikelola admin ICC, ada tombol close.

---

### Perubahan

#### 1. Migration Baru
**File:** `database/migrations/2026_07_30_110000_add_popup_to_site_settings_table.php`

Tambah kolom ke `site_settings`:
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `popup_aktif` | boolean (default false) | Aktif/nonaktif popup |
| `popup_gambar` | string (nullable) | Path gambar flyer |

#### 2. Model SiteSetting
**File:** `app/Models/SiteSetting.php`

Tambah `popup_aktif`, `popup_gambar` ke `$fillable`.

#### 3. Admin Panel — Tab Pop Up Flyer
**File:** `app/Filament/Resources/SiteSettings/SiteSettingResource.php`

Tab baru "Pop Up Flyer" (icon: window) dengan:
- **Toggle** `popup_aktif` — Aktifkan Pop Up Flyer
- **FileUpload** `popup_gambar` — Upload gambar flyer (image, disk public, dir `site/popup`, max 5MB)

#### 4. Public Layout — Popup Component
**File:** `resources/views/layouts/public.blade.php`

Sebelum `@livewireScripts`, ditambahkan popup menggunakan Alpine.js:
- Overlay fullscreen: `bg-black/60 backdrop-blur-sm`
- Card putih rounded-2xl di tengah, max-width `sm` (384px)
- Tombol close: lingkaran `bg-black/50` di pojok kanan atas
- Klik di luar card → close (`@click.away`)
- Animasi fade-in scale (CSS `@keyframes`)
- `x-cloak` agar tidak flash sebelum Alpine load
- Hanya tampil jika `$settings->popup_aktif && $settings->popup_gambar`

---

## Verifikasi

| Item | Status |
|------|--------|
| Kolom `deskripsi` ada di DB | ✅ |
| Admin bisa isi deskripsi via form | ✅ |
| Organizer bisa isi deskripsi via form | ✅ |
| Public menampilkan deskripsi di bawah judul | ✅ |
| Public menampilkan deskripsi di atas tanggal/venue | ✅ |
| Jika deskripsi kosong, tidak tampil | ✅ |
| Tabel `event_cps` sudah termigrasi | ✅ |
| Model `EventCp` + relasi `cps()` | ✅ |
| Organizer bisa CRUD multiple CP via RelationManager | ✅ |
| Admin bisa lihat CP via RelationManager | ✅ |
| Public menampilkan semua CP dengan nama & WA | ✅ |
| Jika tidak ada CP, section tidak tampil | ✅ |
| `no_wa_cp` lama tetap ada (tidak dihapus) | ✅ |
| Migration popup sukses | ✅ |
| Admin bisa toggle aktif/nonaktif popup | ✅ |
| Admin bisa upload gambar flyer | ✅ |
| Popup muncul saat website dibuka (jika aktif) | ✅ |
| Tombol close berfungsi | ✅ |
| Klik luar card close popup | ✅ |
| Animasi fade-in | ✅ |
| `npm run build` sukses | ✅ |

---

## File yang Diubah/Dibuat

| File | Status |
|------|--------|
| `database/migrations/2026_07_30_100000_create_event_cps_table.php` | ➕ Baru |
| `database/migrations/2026_07_30_110000_add_popup_to_site_settings_table.php` | ➕ Baru |
| `app/Models/EventCp.php` | ➕ Baru |
| `app/Filament/Organizer/Resources/EventResource/RelationManagers/EventCpsRelationManager.php` | ➕ Baru |
| `app/Models/Event.php` | ✏️ Ditambah relasi `cps()` |
| `app/Models/SiteSetting.php` | ✏️ Ditambah `popup_aktif`, `popup_gambar` |
| `app/Filament/Organizer/Resources/EventResource.php` | ✏️ Hapus `no_wa_cp`, tambah `EventCpsRelationManager` |
| `app/Filament/Resources/Events/EventResource.php` | ✏️ Tambah `EventCpsRelationManager` |
| `app/Filament/Resources/SiteSettings/SiteSettingResource.php` | ✏️ Tambah tab Pop Up Flyer |
| `app/Http/Controllers/EventController.php` | ✏️ Tambah eager load `cps` |
| `resources/views/event/show.blade.php` | ✏️ Pindah deskripsi + multiple CP cards |
| `resources/views/layouts/public.blade.php` | ✏️ Tambah popup flyer Alpine.js |
