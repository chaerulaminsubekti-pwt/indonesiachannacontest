# Audit Keamanan Portal-ICC — Pendaftaran Peserta (3 Agu 2026)

## Konteks
Audit menyusul fitur pendaftaran peserta (multi-ikan, DP, statistik, edit, hapus, upload bukti transfer).
Aplikasi Laravel 12 + Filament v4; `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY` valid (51 char).
Session: `SESSION_ENCRYPT=true`, `SESSION_DRIVER=file`.

## VERIFIED AMAN (sudah baik)
1. **Panel access role-based** — `app/Models/User.php:40` `canAccessPanel(Panel $panel)`:
   - `/admin` → `hasAnyRole(['super_admin','editor'])`
   - `/panel` → `role === 'penyelenggara'`
   - default → false (FilamentUser contract + `Authenticate` middleware => enforced).
2. **EventResource di-scope** ke organizer login (`organizer_id`) — organizer tidak bisa lihat event peserta lain.
3. **Event publik** hanya status `['approved','berjalan','selesai']` (`EventController`).
4. **Upload bukti transfer** — rule `image|max:4096`, nama file random (bukan user-controlled), disk `public`.
5. **CSRF** — login form `@csrf`; panel Filament via `VerifyCsrfToken`.
6. **Secrets** — hanya `.env.example` ke-track; `.gitignore` menutup `.env`, `.env.backup`, `.env.production`, `/storage/logs/*`.
7. **Password hashed** (cast `'hashed'`), `$hidden` password/remember_token.
8. **No debug tooling** — barryvdh = `laravel-dompdf` (sertifikat), bukan debugbar; tidak ada telescope.
9. **Output blade escaped** `{{ }}`.
10. `php artisan storage:link` sudah dibuat (public/storage ada).

## TEMUAN & REKOMENDASI
### Sedang
1. **Rate limiting pendaftaran** — `DaftarKontes` (submit) tanpa throttle => spam/flood tabel participants.
   - Fix: `throttle:10,1` pada route Livewire atau rate limiter per IP/email (nama/nomor HP).
2. **Nama file sertifikat** — `CertificateController::download` pakai `nama_penerima` mentah di filename.
   - Risiko header-injection rendah (Symfony menyaring CR/LF), tapi nama bisa aneh (slash/spasi).
   - Fix: sanitize — `Str::slug($name)` + fallback timestamp.

### Rendah
3. **Bukti transfer publik** — `/storage/bukti-pembayaran/...` (nama hash random, sulit ditebak) tapi tanpa proteksi akses.
   - Terima untuk kasus ini (admin butuh lihat); catat di dokumentasi.
4. **Cookie/session non-secure** — `SESSION_SECURE_COOKIE=false`, `APP_URL=http://127.0.0.1:8000`.
   - Di production HTTPS: `SESSION_SECURE_COOKIE=true`, `APP_URL=https://...`.

### Keputusan owner
5. **no_hp ditampilkan publik** di daftar peserta (selain nama) — sudah disetujui owner, dicatat saja.

## Verifikasi tambahan
- `php artisan storage:link` selesai.
- Tidak ada SQL injection (query pakai bindings/ORM).
- Tidak ada mass-assignment baru (DaftarKontes hanya menulis kolom terkontrol).
