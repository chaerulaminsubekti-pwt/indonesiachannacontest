# Production Deployment Checklist

Sebelum deploy ke production, pastikan semua item berikut sudah diperbaiki:

## .env — Environment Config
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://domain-anda.com`
- [ ] `LOG_LEVEL=warning` (atau `error`)

## Database
- [ ] Ganti `DB_PASSWORD` dengan password MySQL yang kuat
- [ ] Pastikan DB user hanya punya akses ke 1 database (bukan root)

## Session & Security
- [ ] `SESSION_ENCRYPT=true`
- [ ] `SESSION_SECURE_COOKIE=true` (cookie hanya via HTTPS)
- [ ] `SESSION_SAME_SITE=strict` (atau `lax`)
- [ ] Tambah middleware `throttle:5,1` di route `POST /login` (5 percobaan per menit)

## HTTPS
- [ ] Konfigurasi trusted proxy di `app/Http/Middleware/TrustProxies.php`
- [ ] Force HTTPS — set di `AppServiceProvider::boot()`: `$this->app['request']->server->set('HTTPS', 'on');` atau via Cloudflare/load balancer

## File & Directory
- [ ] `storage/` dan `bootstrap/cache/` writable by web server
- [ ] `.env` **tidak** ikut git (sudah di `.gitignore` ✅)
- [ ] Hapus file `test_login.php` jika ada

## Caching (untuk performa + security)
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan event:cache`

## Mail
- [ ] Ganti `MAIL_MAILER=log` ke SMTP nyata (sendgrid, mailgun, dsb)

## Queue (untuk sertifikat dll)
- [ ] Setup queue worker di production (pastikan `QUEUE_CONNECTION=database`)

## Extras
- [ ] Hapus atau proteksi rute debug/developer jika ada
- [ ] Pastikan `BCRYPT_ROUNDS=12` (minimal)
- [ ] Setup monitoring error (Sentry, Flare, dll)
