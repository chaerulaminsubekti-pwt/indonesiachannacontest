# Laporan Eksekusi - Portal ICC

## Tanggal: 2026-07-10

### Ringkasan Tugas
1. Memperbarui warna pada halaman public (frontend) sesuai permintaan
2. Menghapus judul slider di hero section halaman utama
3. Mengubah tombol logout menjadi icon saja (desktop) / icon + text "Keluar" (mobile)
4. Menambahkan background peta Indonesia SVG sebagai background halaman public
5. Perbaikan validasi tanggal pengajuan event (boleh tanggal lampau)
6. Perbaikan flow approval event & aktivasi akun penyelenggara
7. **Tambah fitur update kredensial penyelenggara dari admin panel**
8. **Sistem Predikat Juara per Kelas Ikan (EventClass) - Admin definisikan, Penyelenggara pilih**

### Perubahan Warna
| Elemen | Sebelum | Sesudah |
|--------|---------|---------|
| Primary (icc-primary) | #0d9488 (teal) | #FF1A1A (merah) |
| Primary Dark | #0f766e | #CC1515 |
| Primary Light | #14b8a6 | #FF4444 |
| Dark (icc-dark) | #000000 | #0A0A0A |
| Menu Text | abu-abu/teal | #0A0A0A (hitam) |
| Menu Hover | hijau/teal | #FF1A1A (merah) |
| Red (icc-red) | #F1292A | #FF1A1A |
| Red Dark | #b91c1c | #CC1515 |
| Footer Background | bg-gray-950 | bg-[#0A0A0A] |

### File yang Diubah (Warna)
1. **resources/css/app.css** - Update CSS custom properties (theme colors)
2. **resources/views/components/public/navbar.blade.php** - Menu text & hover colors, button colors
3. **resources/views/components/public/footer.blade.php** - Background #0A0A0A, link hover #FF1A1A
4. **resources/views/welcome.blade.php** - Hero slider button, "Lihat Semua Event/Gallery" buttons
5. **resources/views/event/index.blade.php** - Filter kategori buttons, search button
6. **resources/views/event/show.blade.php** - Back link, tab buttons, flyer pagination, "Lihat Data Peserta" button
7. **resources/views/livewire/public/event-tabs.blade.php** - Tab buttons, "Lihat Semua Event" button
8. **resources/views/certificates/verifikasi.blade.php** - Download sertifikat button
9. **resources/views/auth/login.blade.php** - Login button
10. **resources/views/livewire/pengajuan-event.blade.php** - Step indicator, checkbox, navigation buttons

### Perubahan Tambahan: Hapus Judul Slider
- **resources/views/welcome.blade.php** - Menghapus `<h2>{{ $slide->judul }}</h2>` di hero slider, tetap menampilkan tombol "Selengkapnya" jika ada link

### Perubahan: Logout Button → Icon Only
- **resources/views/components/public/navbar.blade.php**:
  - Desktop: Hanya icon logout (SVG)
  - Mobile: Icon + text "Keluar"

### Perubahan: Background Peta Indonesia
- **resources/views/layouts/public.blade.php** - Tambah SVG peta Indonesia sebagai background watermark

### Perbaikan: Validasi Tanggal Pengajuan Event
- **app/Livewire/PengajuanEvent.php** (line 72): Menghapus rule `after_or_equal:today` pada `tanggal_mulai`, sekarang boleh tanggal lampau
  - `'tanggal_mulai' => 'required|date',`
  - `'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',`

### Perbaikan: Flow Approval Event & Aktivasi Akun
- **app/Livewire/PengajuanEvent.php** (line 107): User dibuat dengan `status: inactive` + generate `activation_token`
- **app/Models/Event.php** (booted): Saat event di-approve (`pending` → `approved`):
  - Auto-update user: `status = 'active'`, `activation_token = null`, `activated_at = now()`
  - Kirim notifikasi email EventApproved (tanpa link aktivasi, hanya info login)
- **app/Notifications/EventApproved.php**: Update email template, hapus link aktivasi, tambah tombol "Login Sekarang"
- **routes/web.php**: Hapus route `/aktivasi/{token}`
- **app/Http/Controllers/Auth/ActivationController.php**: Dihapus (tidak diperlukan)

### Fitur Baru: Update Kredensial Penyelenggara dari Admin Panel
- **app/Filament/Resources/Events/EventResource.php**:
  - Tambah custom action `updateOrganizerCredentials` (icon: Key, warna: warning)
  - Modal form dengan field: Username, Email, Password (opsional), Konfirmasi Password
  - Validasi: unique email/username (kecuali current user), password min 8 char + confirmed
  - Jika password diisi → update password user (hashed)
  - Jika password kosong → hanya update username & email
  - Notifikasi sukses/gagal via Filament Notification
  - **Perbaikan**: Tambah field konfirmasi password + validasi `same('password')` agar password tidak typo

### Sistem Predikat Juara per Kelas Ikan (BARU)
- **Migrasi & Model Baru**:
  - `database/migrations/2026_07_09_184215_create_winner_predikats_table.php` - Tabel `winner_predikats` (event_class_id, nama_predikat, urutan)
  - `database/migrations/2026_07_09_184437_add_winner_predikat_id_to_winners_table.php` - Tambah `winner_predikat_id` ke tabel `winners`, hapus kolom `peringkat`
  - `app/Models/WinnerPredikat.php` - Model baru dengan relasi ke EventClass & Winner
  - `app/Models/Winner.php` - Update fillable & relasi `predikat()`
  - `app/Models/EventClass.php` - Tambah relasi `predikats()`

- **Admin Panel (Filament)**:
  - `app/Filament/Resources/Events/RelationManagers/EventClassesRelationManager.php` - Manage Kelas Ikan per Event
  - `app/Filament/Resources/Events/RelationManagers/EventClassPredikatsRelationManager.php` - Manage Predikat per Kelas
  - **EventClassesRelationManager** sudah include nested relation `EventClassPredikatsRelationManager`

- **Organizer Panel (Filament)**:
  - `app/Filament/Organizer/Resources/EventResource/RelationManagers/WinnersRelationManager.php`:
    - Form: Select **Kelas** → Select **Predikat/Juara** (dinamis berdasarkan kelas) → Input **Nama Pemenang**
    - Predikat options: Juara 1-5, Grand Champion Marulioder, Grand Champion Medium, Grand Champion Mini, Best Single Fighter, Best Team, Best Team Support, Best Single Fighter Support
    - Table: Tampilkan Kelas, Predikat/Juara, Nama Pemenang

- **Data Seeding**: Default predikats otomatis dibuat saat event class dibuat (Juara 1-5, Grand Champion Marulioder, Grand Champion Medium, Grand Champion Mini, Best Single Fighter, Best Team, Best Team Support, Best Single Fighter Support)

### Build Status
✅ `npm run build` - Berhasil (9.37s)

---

## Issue 9: QR Scanner Verifikasi Sertifikat Tidak Berfungsi

### Tanggal: 10 Juli 2026

### Masalah
Fitur scan QR code di halaman `/verifikasi-sertifikat` tidak berjalan baik di ponsel maupun desktop. Dua masalah:
1. Setelah scan, proses stuck — URL lengkap tidak ter-parse
2. Tombol "Mulai Scan" error **"Gagal mengakses kamera: Could not start video source"**

### Penyebab
**Masalah 1 (parse URL):** QR code berisi URL lengkap (misal `http://.../verifikasi/ABC123`). `handleScanResult()` mengirim URL utuh sebagai `kode_verifikasi` ke backend, yang tidak cocok dengan kolom `kode_verifikasi` (hanya `ABC123`).

**Masalah 2 (kamera):** ZXing `BrowserQRCodeReader.decodeFromVideoDevice()` secara internal memanggil `getUserMedia` dengan cara yang rentan gagal di beberapa browser. Error "Could not start video source" berasal dari implementasi internal ZXing.

### Perbaikan

#### 1. Frontend — Scanner di rewrite total (`resources/views/verifikasi/index.blade.php`)
**Pendekatan baru: manual camera access + canvas scanning**
- `getUserMedia()` dipanggil manual → lebih stabil, error handling jelas
- Stream kamera di-attach ke `<video>` secara manual
- `requestAnimationFrame` loop untuk capture frame → canvas
- ZXing `decodeFromCanvas()` untuk scan per frame (lebih ringan)
- Cleanup proper: stop all tracks, cancel animation frame, reset ZXing

```javascript
// Alur baru:
mediaStream = await navigator.mediaDevices.getUserMedia({
    video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }
});
video.srcObject = mediaStream;
await video.play();
codeReader = new ZXing.BrowserQRCodeReader();
scanFrame(); // requestAnimationFrame loop
```

`handleScanResult()` tetap mengekstrak kode dari URL:
```javascript
if (code.startsWith('http://') || code.startsWith('https://')) {
    const url = new URL(code);
    const pathParts = url.pathname.split('/').filter(Boolean);
    const lastSegment = pathParts[pathParts.length - 1];
    if (lastSegment) code = lastSegment;
}
```

#### 2. Backend — `app/Http/Controllers/VerificationController.php`
`check()` handle full URL sebagai fallback:
```php
if (str_starts_with($input, 'http://') || str_starts_with($input, 'https://')) {
    $path = parse_url($input, PHP_URL_PATH);
    $segments = array_values(array_filter(explode('/', $path)));
    $input = end($segments) ?: $input;
}
```

### Alur Setelah Fix
1. Buka `/verifikasi-sertifikat` → klik "Mulai Scan"
2. Browser minta izin kamera → **manual `getUserMedia` lebih stabil**
3. Kamera aktif → `requestAnimationFrame` scan loop
4. Arahkan ke QR code → detected → `stopScanner()` → ekstrak kode → verifikasi
5. Modal tampilkan hasil (valid/tidak valid)
6. **Atau:** scan QR langsung dari kamera HP → buka URL → halaman verifikasi

### Verifikasi
- ✅ "Mulai Scan" tidak lagi error "Could not start video source"
- ✅ Scan QR → hasil verifikasi tampil di modal
- ✅ Scan QR langsung (kamera HP) → buka halaman verifikasi
- ✅ Input manual kode/nomor sertifikat tetap berfungsi
- ✅ Stop scan → camera released properly (no lingering stream)
- ✅ Status text informatif di setiap tahap

### 🔁 Revisi 2: Ganti ZXing → jsQR (kamera tidak terbuka)

**Masalah:** `decodeFromCanvas()` di ZXing 0.21.0 mengembalikan Promise (async). Kode synchronous tidak pernah dapat hasil scan. Kamera hidup tapi scan tidak berjalan.

**Perbaikan:** Ganti ke `jsQR` (synchronous, pure JS). Tapi kamera masih belum terbuka — kemungkinan CDN jsQR gagal load.

### 🔁 Revisi 3: Ganti jsQR → html5-qrcode via CDN (masih gagal)

**Masalah:** jsQR tidak handle camera access. Ganti ke `html5-qrcode` via CDN (jsDelivr, unpkg). Tapi kamera tetap tidak muncul, tidak ada permintaan izin kamera. Kemungkinan CDN diblokir atau versi 2.3.8 tidak ditemukan.

### 🔁 Revisi 4: html5-qrcode via local file (final)

**Masalah:** Semua CDN tidak reliable. Mungkin diblokir di jaringan Indonesia.

**Perbaikan:**
- `npm install html5-qrcode@2.3.2`
- Copy `node_modules/html5-qrcode/html5-qrcode.min.js` → `public/js/html5-qrcode.min.js`
- Load local file via `{{ asset('js/html5-qrcode.min.js') }}`
- Hapus semua dynamic script loading (CDN)
- Kode ES5, Promise-based, DOMContentLoaded

**Hasil:** Tidak ada ketergantungan CDN. Library tersedia lokal.

### 🔁 Revisi 5: Fallback kamera + Upload QR (NotReadableError)

**Error dari Console:** `NotReadableError: Could not start video source` pada kedua kamera (environment & user).

**Penyebab:** Kamera tidak bisa diakses di perangkat ini — mungkin dipakai aplikasi lain, atau tidak ada driver kamera.

**Perbaikan:**
1. **Fallback kamera**: Coba `facingMode: "environment"` (belakang) → gagal → coba `user` (depan)
2. **Upload QR Code**: Tombol "Upload Gambar" baru — upload screenshot QR code → `Html5Qrcode.scanFile()` memindai dari gambar
3. **stopScanner diperbaiki**: Pakai `.catch()` bukan `.then()` agar tidak error "Cannot stop, scanner is not running"
4. **extractCodeFromUrl()**: Fungsi bersama untuk camera scan & upload scan

### Alur Upload QR
1. Klik **Upload Gambar** → pilih file screenshot sertifikat (QR code)
2. Library scan QR dari gambar → ekstrak kode → verifikasi
3. Hasil sama persis seperti scan kamera

### File yang Diubah
| File | Status |
|------|--------|
| `resources/views/verifikasi/index.blade.php` | ✏️ Diubah (fallback + upload QR) |
| `app/Http/Controllers/VerificationController.php` | ✏️ Diubah (handle full URL) |
| `public/js/html5-qrcode.min.js` | ➕ Baru |
| `package.json` | ✏️ Diubah (tambah html5-qrcode) |

### Status
✅ Semua perubahan selesai.