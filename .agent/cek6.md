# Catatan Perubahan — Portal ICC (cek6.md)

## Tanggal: 10 Juli 2026

---

## Issue 1: Relokasi Grand Champion / Best / Most Entry ke Kelas

### Permintaan
Grand Champion Maruliodes, Grand Champion Mini Dwarf, Grand Champion Medium Dwarf, Best Single Fighter, Best Team, Best Team Support, Best Single Fighter Support, dan Most Entry dipindahkan dari **Predikat/Juara** menjadi **Kelas (EventClass)**.

### Perubahan

#### 1. `app/Models/EventClass.php`
- **Method baru `getDefaultKelasNames()`** — return array 8 nama kelas default (Grand Champion, Best, Most Entry)
- **Method baru `getDefaultPredikats()`** — return array predikat tetap (hanya Juara 1-5)
- **Method baru `ensureDefaultClassesExist($eventId)`** — auto-create kelas default jika belum ada untuk event tertentu
- **`booted()`** — sekarang pakai `getDefaultPredikats()` agar konsisten

#### 2. `app/Filament/Organizer/Resources/EventResource/RelationManagers/WinnersRelationManager.php`
- Hapus `getStandardPredikats()` dan `getSpecialPredikats()`
- Saat form dimuat → panggil `EventClass::ensureDefaultClassesExist()` biar kelas default otomatis tersedia
- Saat pilih kelas → jika belum punya predikat, auto-create Juara 1-5
- Predikat dropdown kini flat list (Juara 1-5) tanpa grouping

### Dampak
- ✅ Event baru → otomatis punya 8 kelas default + masing-masing 5 predikat (Juara 1-5)
- ✅ Event lama → kelas default & predikat otomatis terbuat saat pertama kali buka form Winners
- ✅ Semua kelas (lama & baru) pasti punya opsi predikat Juara 1-5

---

## Issue 2: Predikat/Juara Tidak Muncul untuk Kelas Tertentu (Bug Fix)

### Masalah
Saat pilih kelas "Yellow Progres" predikat muncul, tapi saat pilih kelas lain predikat kosong.

### Penyebab
`WinnerPredikat` hanya dibuat otomatis untuk kelas yang dibuat **setelah** fitur booted() diimplementasikan. Kelas yang sudah ada sebelumnya tidak punya data predikat.

### Perbaikan
Di `WinnersRelationManager::form()` → `winner_predikat_id` options:
- Cek jumlah predikat untuk kelas yang dipilih
- Jika 0 → auto-create default predikat (Juara 1-5)
- Ambil semua predikat dari DB

### Dampak
✅ Semua kelas apapun pasti punya opsi predikat saat dipilih di form.

---

## Issue 3: Predikat/Juara Opsional + Aksi Hapus Sertifikat

### Permintaan
1. Form Predikat/Juara tidak wajib diisi (opsional)
2. Tambah tombol **Hapus Sertifikat** di tabel Winners
3. Sertifikat tetap bisa digenerate tanpa predikat (fallback ke nama kelas)

### Perubahan

#### 1. `app/Filament/Organizer/Resources/EventResource/RelationManagers/WinnersRelationManager.php`
- **`winner_predikat_id`**: `->required()` dihapus, label jadi "Predikat / Juara (opsional)", placeholder "Kosongkan jika tidak perlu"
- **Action baru `deleteCertificate`**:
  - Icon: trash, color: danger (merah)
  - Hanya visible jika winner memiliki certificate
  - `requiresConfirmation()` — konfirmasi sebelum hapus
  - Hapus file PDF dari storage + hapus record certificate
  - Notifikasi sukses

#### 2. `resources/views/certificates/template.blade.php`
- Fallback predikat: `$winner->predikat?->nama_predikat ?? $winner->class?->nama_kelas ?? 'Juara'`
- Jika predikat tidak diisi, sertifikat akan menampilkan nama kelas sebagai predikat

### Alur Baru Input Juara
1. Pilih **Kelas** (Grand Champion Maruliodes / Mini Dwarf / Medium Dwarf / Best Single Fighter / Best Team / Best Team Support / Best Single Fighter Support / Most Entry)
2. **Predikat/Juara** — opsional, bisa dikosongkan
3. Isi **Nama Pemenang**
4. Simpan → Generate Sertifikat (tombol di tabel)

### Hapus Sertifikat
- Tombol **Hapus Sertifikat** (icon trash, merah) muncul di setiap baris yang sudah punya sertifikat
- Klik → konfirmasi → hapus file PDF + record → notifikasi

---

## Issue 4: Halaman Verifikasi Sertifikat

### Permintaan
Setiap nomor sertifikat yang digenerate bisa dicek di halaman verifikasi dan menampilkan keterangan "Sertifikat Valid Resmi Dari Indonesia Channa Contest".

### Perubahan

#### 1. `app/Http/Controllers/CertificateController.php` (verifikasi)
- Tambah eager load `winner.class` dan `winner.predikat` agar data kelas & predikat tampil

#### 2. `resources/views/certificates/verifikasi.blade.php` (halaman QR)
- **Desain ulang** — gradient green theme, ring, backdrop blur
- **Header**: "Sertifikat Valid — Resmi Dari Indonesia Channa Contest"
- **Detail lengkap**: Nama Pemenang, Predikat (fallback ke kelas), Kelas, Event, Tanggal, Venue, No Sertifikat, Tanggal Terbit
- **Download button** — shadow effect
- **Footer verification seal** — teks disclaimer
- **Halaman tidak valid** — tombol "Kembali ke Verifikasi"

#### 3. `resources/views/verifikasi/index.blade.php` (halaman search)
- Update teks valid: "Resmi Dari Indonesia Channa Contest"

#### 4. `app/Http/Controllers/VerificationController.php` (API check)
- Update message: "Sertifikat Valid Resmi Dari Indonesia Channa Contest."

---

## Issue 5: Verifikasi Sertifikat Stuck di "Memverifikasi kode..." (Bug Fix)

### Masalah
Setelah input nomor sertifikat, proses berhenti di "Memverifikasi kode..." tanpa hasil.

### Penyebab
`resources/views/layouts/public.blade.php` tidak memiliki `<meta name="csrf-token">`. JavaScript di `verifikasi/index.blade.php` membaca token via `document.querySelector('meta[name="csrf-token"]')` yang mengembalikan `null`, menyebabkan TypeError saat akses `.getAttribute('content')`. Fetch tidak pernah terpanggil.

### Perbaikan
**File:** `resources/views/layouts/public.blade.php`
- Tambah `<meta name="csrf-token" content="{{ csrf_token() }}">` di `<head>`

---

## Issue 6: Hilangkan Kolom Peringkat di Halaman Public & Verifikasi

### Permintaan
- Halaman public (detail event) → tabel winners → hilangkan kolom **Peringkat**
- Halaman verifikasi sertifikat (QR & modal) → hilangkan baris **Predikat/Peringkat**
- Modal verifikasi → header title dihapus, hanya tombol X (close)

### Perubahan

#### 1. `resources/views/event/partials/winners.blade.php`
- Hapus `<th>Peringkat</th>` dan `<td>` terkait
- Tabel sekarang: Nama Pemenang | Nomor Sertifikat | Sertifikat

#### 2. `resources/views/certificates/verifikasi.blade.php` (QR)
- Hapus baris "Predikat:" dari grid detail

#### 3. `resources/views/verifikasi/index.blade.php` (modal search)
- Modal header: hapus `<h3>` title, hanya tombol X (close)
- Hapus baris "Peringkat:" dari konten modal

#### 4. `app/Http/Controllers/VerificationController.php`
- Hapus field `peringkat` dari JSON response

---

## Issue 7: Redesain Sertifikat (PDF) — Style Sesuai Website

### Permintaan
Perbaiki tampilan sertifikat PDF agar sesuai tema website (merah #FF1A1A, hitam #0A0A0A, emas sebagai aksen) dan QR code bisa diverifikasi.

### Perubahan

#### `resources/views/certificates/template.blade.php`
Redesain total dengan:
- **Outer frame**: border hitam tebal (#0A0A0A)
- **Inner frame**: border merah (#FF1A1A) dengan background gradient putih-kemerahan
- **Red stripes**: garis merah di top & bottom
- **Corner decorations**: sudut merah di 4 pojok
- **Watermark ICC**: merah transparan
- **Typography**:
  - Nama organisasi: hitam, uppercase, bold
  - Nama event: merah
  - Judul "Sertifikat Penghargaan": hitam, letter-spacing, gold underline
  - Nama pemenang: 44px, hitam, bold, uppercase
- **Achievement badge**: gradient merah (#FF1A1A → #CC1515) dengan shadow
- **Body text**: teks deskripsi dengan highlight merah untuk nilai penting
- **Conditional text**:
  - Jika Juara 1-5: "Telah meraih Juara X pada Kelas Y"
  - Jika Grand Champion/Best dll: "Sebagai Grand Champion..." (tanpa kelas)
- **Officials**: seal hitam untuk Ketua, seal emas untuk Pembina
- **Footer**: QR code (kiri) + Signature section (kanan)
- **Nomor sertifikat**: center bottom
- **QR code**: mengarah ke `/verifikasi/{kode}` — bisa di-scan untuk verifikasi

#### `app/Jobs/GenerateCertificateJob.php`
- Tambah `$logoPath` dan `$logoExists` untuk handle logo via path absolut (DomPDF)
- QR code: ganti `OUTPUT_MARKUP_SVG` → `OUTPUT_IMAGE_PNG` + `imageBase64 => true` (DomPDF tidak support SVG)

---

## Issue 8: Tanda Tangan 1 Baris + Nama Penyelenggara di Sertifikat

### Permintaan
- Semua tanda tangan (Ketua ICC, Pembina ICC, Penyelenggara) dalam 1 baris di footer
- Tambah Nama Penyelenggara sesuai event

### Perubahan
`resources/views/certificates/template.blade.php`:
- Hapus section officials (seal cards) di tengah
- Footer: QR code (kiri) + 3 signature blocks (kanan) dalam 1 baris:
  - **Ketua ICC** — `$settings->nama_ketua`
  - **Pembina ICC** — `$settings->nama_pembina`
  - **Penyelenggara** — `$winner->event->organizer->user->name` fallback ke `nama_organisasi`
- Nomor sertifikat di center bottom

---

## Issue 9: QR Code Tidak Tampil & Tanda Tangan Hanya 1 (Bug Fix)

### Masalah
1. QR code tidak muncul — DomPDF tidak support `data:image/svg+xml;base64`
2. Tanda tangan hanya 1 yang terlihat — DomPDF tidak support CSS `display: flex`

### Perbaikan

#### `app/Jobs/GenerateCertificateJob.php`
- `OUTPUT_MARKUP_SVG` → `OUTPUT_IMAGE_PNG` + `imageBase64 => true`
- QR code sekarang berupa PNG data URI yang kompatibel dengan DomPDF

#### `resources/views/certificates/template.blade.php`
- Footer: `display: flex` → `<table>` layout (DomPDF support penuh)
- 4 kolom tabel: QR Code | Ketua ICC | Pembina ICC | Penyelenggara
- Hapus CSS `.detail-row` (tidak dipakai)

---

## Issue 10: Sertifikat Setengah Halaman & QR Code Tidak Muncul (Final Fix)

### Masalah
1. Sertifikat hanya memenuhi setengah halaman — konten tidak stretch ke full page
2. QR code tidak tampil — format data URI SVG/PNG tidak kompatibel DomPDF

### Perbaikan

#### `resources/views/certificates/template.blade.php`
- `html, body` set `height: 100%` + `box-sizing: border-box`
- `.out` dan `.in` pakai `height: 100%` + `box-sizing: border-box` agar stretch penuh
- Hapus `@page margin: 8mm` → pakai `body padding: 12px`
- Footer `position: absolute; bottom: 8px` di dalam `.in` (relative)
- Fallback QR: jika file tidak ada, tampilkan kotak kosong

#### `app/Jobs/GenerateCertificateJob.php`
- Simpan QR sebagai file PNG fisik (bukan base64)
- Konversi path Windows backslash → forward slash untuk DomPDF
- Hapus `isRemoteEnabled` (tidak perlu untuk local file)

