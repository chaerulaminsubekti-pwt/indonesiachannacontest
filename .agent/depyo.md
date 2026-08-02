# Laporan Deploy Aplikasi INDONESIACHANNACONTEST

- **Tanggal penulisan:** 2026-08-03
- **Domain:** https://indonesiachannacontest.org
- **Hosting:** NiagaHosting (infrastruktur Hostinger, panel: hPanel)
- **Server SSH:** 46.202.138.42 | Port 65002 | User `u539054303`
- **Docroot asli:** `/home/u539054303/domains/indonesiachannacontest.org/public_html/`
- **Folder project:** `.../public_html/INDONESIACHANNACONTEST/`
- **Stack:** Laravel 11.x + Filament (admin & panel) + Livewire + Vite + MySQL/MariaDB 11.8

---

## 1. Ringkasan Status

Website telah **berhasil online** dan berfungsi penuh:

| Route | Status | Keterangan |
|-------|--------|------------|
| `/` (Home) | 200 | Halaman utama |
| `/event` | 200 | Daftar |
| `/event/{slug}` | 200 | Detail event (4 event valid) |
| `/gallery` | 200 | Galeri |
| `/login` | 200 | Login custom |
| `/daftar-juri` | 200 | Halaman juri |
| `/regulasi` | 200 | Regulasi |
| `/struktur-organisasi` | 200 | Struktur organisasi |
| `/pengajuan` | 200 | Form pengajuan |
| `/verifikasi-sertifikat` | 200 | Verifikasi |
| `/sitemap.xml` | 200 | SEO |
| `/robots.txt` | 200 | SEO |
| `/up` | 200 | Health check |
| `/admin` | 200 → redirect `/login` | Admin Filament |
| `/panel` | 200 → redirect `/login` | Panel penyelenggara |

**Total route:** 52 route (GET/POST), tercatat via `php artisan route:list`.

---

## 2. Permasalahan & Solusi Yang Dilakukan

### 2.1 Docroot domain salah
- Gejala: File yang di-upload di `~/public_html` tidak tampil.
- Akar masalah: Docroot web yang benar adalah `~/domains/indonesiachannacontest.org/public_html/`, bukan `~/public_html`.
- Solusi: Project disalin ke docroot domain yang benar.

### 2.2 `.htaccess` rewrite tidak berfungsi / looping
- Gejala: Root `403 Forbidden`, route lain `404`.
- Akar masalah: Rule rewrite memasukkan semua request (termasuk file asli) + direktori `/` ter-skip karena `-d`.
- Solusi: `.htaccess` memuat rule:
  ```apache
  <IfModule mod_rewrite.c>
      RewriteEngine On
      # Layani file asli jika ada di subfolder public
      RewriteCond %{REQUEST_URI} !^/INDONESIACHANNACONTEST/
      RewriteCond %{DOCUMENT_ROOT}/INDONESIACHANNACONTEST/public%{REQUEST_URI} -f
      RewriteRule ^(.*)$ INDONESIACHANNACONTEST/public/$1 [L]
      # Semua request lain -> front controller
      RewriteRule ^(.*)$ INDONESIACHANNACONTEST/public/index.php [L]
  </IfModule>
  ```

### 2.3 `proc_open` dan fungsi PHP dinonaktifkan
- Gejala: `composer install` error *"The Process class relies on proc_open"*.
- Akar masalah: `proc_open, system, exec, shell_exec, passthru, popen` ada di `disable_functions` server.
- Solusi: Di-handle sedapat mungkin; untuk beberapa command artisan disarankan diaktifkan via support. (Proses deploy tetap selesai.)

### 2.4 `symlink` dan `link` dinolokus
- Gejala: `php artisan storage:link` bisa jalan, tapi sebelumnya symlink menunjuk path salah.
- Akar: Files di-copy sebelum pemindahan folder docroot.
- Solusi: Re-recreate symlink setelah folder dipindah benar:
  ```bash
  cd <APP>
  rm -f public/storage
  php artisan storage:link
  ```
  Hasil symlink: `public/storage -> <docroot>/storage/app/public` ✓

### 2.5 Aset Vite (`public/build`) hilang
- Gejala: Semua halaman `500`; log berisi `Vite manifest not found` (manifest.json).
- Akar masalah: `/public/build` masuk `.gitignore` → tidak ter-copy.
- Solusi: Salin ulang `public/build/manifest.json` + 3 file aset:
  - `assets/app-4G2uDLMQ.js` (125 KB)
  - `assets/app-Cacu5y9W.css` (11 KB)
  - `assets/app-DNksafjo.css` (52 KB)

### 2.6 Gambar/dokumen upload tidak tampil
- Gejala: Gambar di halaman tidak muncul.
- Akar masalah: `storage/app/public` kosong di server (file besar tidak ter-copy bersama project).
- Solusi: Upload 60 file (52.5 MB) via `pscp` ke `storage/app/public/`:
  - `site`, `sliders`, `events/flyers`, `icc-galleries`, `event-galleries`, `documents`, `qrcodes`, `certificates`
  - Terverifikasi: semua path `/storage/...` → **200**

### 2.7 Konfigurasi Production
- Ubah `.env`: `APP_ENV=production`, `APP_DEBUG=false`.
- Jalankan: `config:cache`, `route:cache`, `view:cache`.
- DB tersambung 36 tabel (events, certificates, contacts, dst.).

---

## 3. Database

- **Database:** `u539054303_icc2026`
- **DB User:** `u539054303_chaerul`
- **Tabel:** 36
- **Data:** 4 event, 10 user, dsb.
- Koneksi diverifikasi sukses.

### 3.1 User Admin
- **Email:** `admin@icc.com`
- **Password (di-reset):** `A7xkcx232!@#`
- **Role:** `super_admin` | status: `active`
- Login: `https://indonesiachannacontest.org/login`

---

## 5. Kredensial
> ⚠️ Password dibagikan di chat. Disarankan ganti setelah semua beres.

| Item | Nilai |
|------|-------|
| SSH Host | 46.202.138.42:65002 |
| SSH User | `user` |
| SSH Password | `A7xkcx232!@#` |
| DB Name | `u539054050533_icc2026` |
| DB User | `u539054303_chaerul` |
| Admin Email | `admin@icc.com` |
| Admin Password | `A7xkcx232!@#` |

---

## 5. Struktur Docroot Final

```
~/domains/indonesiachannacontest.org/public_html/
├── .htaccess                       # rewrite ke LP
├── database-backup-local.sql
├── deploy.sh
└── INDONESIACHANNACONTEST/        # App Laravel
    ├── artisan
    ├── .env
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── public/
    │   ├── build/                  # aset Vite
    │   ├── index.php
    │   └── storage -> ../storage/app/public
    ├── resources/
    ├── routes/
    ├── storage/
    │   ├── app/public/             # 60 file media (53MB)
    │   └── logs/
    └── vendor/
```

---

## 6. Catatan / Saran Selanjutnya

1. **Bersihkan log lama** `storage/logs/laravel.log` (965KB, berisi error Vite masa lalu):
   ```bash
   > path/storage/logs/laravel.log
   ```
2. **Purge CDN / hard refresh** jika data masih cache (Favicon suspect).
3. **Ganti password** SSH/DB/Admin setelah selesai karena pernah dibagi di chat.
4. **Disclaimer:** `proc_open` & beberapa fungsi tidak aktif di hosting; jika fitur tertentu (mis. subprocess di Filament) berjalan, hubungi support.
5. Untuk update selanjutnya gunakan `git clone`/`git pull` dari repo asli, atau upload ulang.

---

### File pendukung (di workspace lokal)
- `tools/deploy-local.ps1` — push & export DB lokal.
- `tools/deploy.sh` — skrip deploy di server.
- `database-backup-local.sql` — backup DB lokal.

---

## 7. Workflow Pengembangan & Update (WAJIB — kebijakan)

> **Aturan utama:** Semua perubahan sistem/update **HARUS di-test running di LOKAL terlebih dahulu**. **JANGAN** eksekusi langsung ke website online sebelum lolos uji.

### 7.1 Alur Standar Setiap Perubahan

```
1. KERJAKAN & UJI DI LOKAL
   ├─ Buat/perbaiki fitur di sourcelokal (C:\xampp1\htdocs\Portal-ICC)
   ├─ Jalankan: composer run-script dev   (server lokal + HMR)
   ├─ Uji manual semua halaman & fitur terdampak di http://localhost
   ├─ Lint + Test : ./vendor/bin/pint --test lalu composer run-script test
   └─ Build aset  : npm run build   (menghasilkan public/build/*)

2. SETELAH LOLOS → READY UNTUK PRODUCTION
   └─ Commit & push ke Git (update kode + assaringan ganti)

3. DEPLOY KE SERVER (hanya setelah lolos #1)
   ├─ Opsi A (git): SSH → git clone/git pull di docroot
   ├─ Opsi B (upload): upload ulang file yang berubah via File Manager / pscp
   ├─ Update database : php artisan migrate --force
   ├─ Storage link jika perlu : php artisan storage:link
   ├─ Cache : php artisan config:cache / route:cache / view:cache
   └─ Verifikasi : curl semua halaman kunci (200) + cek log (tanpa error baru)
```

### 7.2 Checklist "Siap Naik Production"
Berikut harus lulus **semuanya** sebelum deploy ke online:
- [ ] Pengujian manual di lokal berhasil (halaman tidak error, tidak 500)
- [ ] `composer run-script test` hijau (semua test pass)
- [ ] `./vendor/bin/pint --test` lolos (code style)
- [ ] `npm run build` sukses, aset `public/build` te-regen
- [ ] DB lokal dimigrate & data uji sesuai
- [ ] Deployment sudah di-commit ke Git
- [ ] Setelah deploy: verifikasi status HTTP 200 + log tidak bertumbuh error

### 7.3 Backup Sebelum Deploy (Opsional tapi disarankan)
Sebelum melakukan perubahan besar di server, lakukan backup:
```bash
# DB production
mysqldump -u user -p nama_db > backup_produksi_$(date +%Y%m%d).sql
# File media
tar -cvzf previous_media.tar.gz storage/app/public
```

### 7.4 Lingkungan Uji (Opsional: Server Staging)
Untuk fitur yang sangat berisiko, disarankan bikin lingkungan **staging** (subdomain terpisah, contoh `staging.indonesiachannacontest.org`) yang memakai DB sendiri, supaya benar-benar terisolasi dari website online.

### 7.5 Kredensial & Keamanan
- Jangan commit `.env`. Selalu buat di server.
- Jangan membagikan password di chat.
- Ganti password SSH/DB/admin secara berkala, apalagi setelah pernah dibagi.