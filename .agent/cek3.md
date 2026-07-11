# Catatan Perbaikan — Portal ICC (cek3.md)

## Tanggal: 09 Juli 2026

---

## Issue 1: Slider Image Tidak Tampil di Halaman Publik

### Masalah
Gambar slider yang diupload melalui Admin Panel (Filament) tidak muncul di halaman home/public. View menggunakan `Storage::url($slide->gambar)` tapi file disimpan di disk `local` (private) bukan `public`.

### Penyebab
File `app/Filament/Resources/Sliders/SliderResource.php` line 36:
```php
FileUpload::make('gambar')->image()->required()->directory('sliders'),
```
Tidak ada `->disk('public')`, sehingga default ke disk `local` (storage/app/private/sliders). File tidak accessible via URL `/storage/sliders/...`.

### Perbaikan

1. **File: `app/Filament/Resources/Sliders/SliderResource.php`** (line 36)
   - **Sebelum:**
     ```php
     FileUpload::make('gambar')->image()->required()->directory('sliders'),
     ```
   - **Sesudah:**
     ```php
     FileUpload::make('gambar')->image()->required()->directory('sliders')->disk('public'),
     ```

2. **Migrasi file existing:**
   - File lama di `storage/app/private/sliders/01KX3D627HNMRHYGXWXVQGRHW4.png` 
   - Dicopy ke `storage/app/public/sliders/01KX3D627HNMRHYGXWXVQGRHW4.png`

### Verifikasi
- Halaman home (`/`) sekarang menampilkan slider dengan URL gambar: `/storage/sliders/01KX3D627HNMRHYGXWXVQGRHW4.png`
- Test manual via `curl http://localhost:8000` konfirmasi HTML output berisi `<img src="/storage/sliders/...">`

---

## Issue 2: Tambah Sambutan Pembina Indonesia Channa Contest (Berdampingan dengan Sambutan Ketua)

### Request
Menambahkan section "Sambutan Pembina" yang terletak berdampingan (side-by-side) dengan "Sambutan Ketua" yang sudah ada. Tampilan responsif dan elegan.

### Perbaikan

1. **Migration:** Kolom sudah ada di tabel `site_settings` (nama_pembina, jabatan_pembina, sambutan_pembina, foto_pembina)

2. **Model: `app/Models/SiteSetting.php`** — Tambah field ke `$fillable`:
   ```php
   'sambutan_pembina', 'foto_pembina', 'nama_pembina', 'jabatan_pembina',
   ```

3. **Filament Resource: `app/Filament/Resources/SiteSettings/SiteSettingResource.php`** — Tambah Tab baru "Sambutan Pembina":
   - Tab icon: `heroicon-o-user-group`
   - Fields: Foto Pembina (avatar), Nama Pembina, Jabatan Pembina, Isi Sambutan (RichEditor)
   - Disk: `public` untuk file upload

4. **View: `resources/views/welcome.blade.php`** — Refactor section Sambutan:
   - Layout grid responsive: `grid-cols-1 sm:grid-cols-2 gap-8 lg:gap-12`
   - **Kiri:** Sambutan Ketua (card dengan gradient border, ring emas, hover effect)
   - **Kanan:** Sambutan Pembina (card mirip, beda icon/warna aksen)
   - Mobile: stack vertikal (1 kolom) <640px
   - Tablet ke atas: berdampingan (2 kolom) ≥640px
   - Elegan: gradient background subtle, border ring, hover shadow transition, avatar dengan ring emas

### Hasil
- Admin bisa input Sambutan Ketua & Pembina terpisah via Filament
- Halaman Home menampilkan keduanya berdampingan responsif
- Design konsisten dengan tema ICC (biru primary, emas accent)

---

## Issue 3: Slider Kedua Tidak Tampil + Perbaikan Slider (Auto-transition + Effects)

### Masalah
- Slider ke-2 diupload tapi tidak muncul di preview Filament maupun halaman public
- Slider tidak auto-transition dengan efek menarik
- **Duplicate navigation arrows** (2 tipe panah muncul bersamaan)
- Preview gambar di Filament admin tidak muncul setelah upload

### Penyebab & Perbaikan

1. **File upload sudah benar** (pakai `->disk('public')`), file tersimpan di `storage/app/public/sliders/` dan accessible via `/storage/sliders/...`

2. **Filament Admin Preview Fix** — `app/Filament/Resources/Sliders/SliderResource.php`:
   - FileUpload: tambah `->previewable(true)` agar preview muncul di form setelah upload
   - ImageColumn: tambah `->disk('public')->square()->height(60)` untuk preview di tabel

3. **View `welcome.blade.php`** — Upgrade Hero Slider:
   - Gunakan **standard Swiper classes** (`.swiper-button-next`, `.swiper-button-prev`) bukan custom class, sehingga Swiper tidak membuat duplicate arrows
   - Tambah navigation arrows (prev/next) + pagination dots
   - Ganti `effect: 'fade'` ke **`effect: 'slide'`** (lebih reliable dengan `loop: true`, menghindari glitch transisi first/last slide)
   - Autoplay 6 detik, loop infinite, speed 800ms, smooth slide transition

4. **`resources/js/app.js`** — Import Navigation module & CSS:
   ```js
   import { Autoplay, Pagination, Navigation } from 'swiper/modules';
   import 'swiper/css/navigation';
   ```

5. **`resources/css/app.css`** — Custom styling untuk navigation arrows:
   - Circular buttons dengan backdrop blur, border, hover effect
   - Positioned absolut di kiri/kanan tengah slider
   - Hidden di mobile (rely on swipe), visible di desktop
   - Pagination bullets styled dengan warna ICC gold untuk active

6. **Script init Swiper** — Konfigurasi final:
   ```js
   new Swiper('.hero-swiper', {
       modules: [Autoplay, Pagination, Navigation],
       loop: true,
       autoplay: { delay: 6000, disableOnInteraction: false },
       pagination: { clickable: true, el: '.hero-pagination' },
       navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
       effect: 'slide',
       speed: 800,
       slidesPerView: 1,
       spaceBetween: 0,
   });
   ```

### Hasil
- Kedua slider tampil & auto-slide dengan efek **slide smooth** yang reliable
- Navigation arrows (prev/next) **single, styled custom** — tidak duplicate
- Pagination dots clickable dengan warna ICC gold
- **Filament admin preview** gambar slider muncul di form (upload) dan tabel (list)

---

## Issue 4: Ubah Max File Size Foto Sambutan Ketua & Pembina

### Perbaikan
**File: `app/Filament/Resources/SiteSettings/SiteSettingResource.php`**

| Field | Sebelum | Sesudah |
|-------|---------|---------|
| `foto_ketua` | `maxSize(1024)` (1MB) | `maxSize(5120)` (5MB) |
| `foto_pembina` | `maxSize(1024)` (1MB) | `maxSize(5120)` (5MB) |

### Hasil
Admin bisa upload foto resolusi lebih tinggi (max 5MB) untuk sambutan ketua & pembina.

---

## Catatan Tambahan

File upload lain yang **sudah** pakai `->disk('public')` (per cek1.md fix):
- `EventResource.php` line 51: `flyer` field ✅
- `EventFlyersRelationManager.php` line 25: `file_path` field ✅
- `EventGalleriesRelationManager.php` line 25: `file_path` field ✅

File upload lain yang **perlu dicek** apakah sudah pakai `->disk('public')`:
- `SiteSettingResource.php` - logo_header, favicon, foto_ketua, **foto_pembina** ✅
- `IccGalleryResource.php` - file_path
- `ContactResource.php` - foto
- `OrganizationStructureResource.php` - file_path
- `JudgesListResource.php` - file_path
- `RegulationResource.php` - file_path

> **Rekomendasi:** Audit semua `FileUpload::make()` di Filament Resources untuk memastikan konsisten menggunakan `->disk('public')` agar file accessible via `Storage::url()` di public views.

sekarang saya ingin kamu merubah warna tulisan menu, menjadi hitam jangan abu-abu, tapi jika aktif kursor hover nya merah dop jangan hijau, sesuaikan semua tombol / menu yang ada di halaman public saja, kemudian untuk warna footer rubah menjadi warna hitam.
