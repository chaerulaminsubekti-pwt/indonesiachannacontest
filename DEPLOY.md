# Panduan Deploy ke Hosting

## 1. Upload File
Upload seluruh folder project (kecuali `node_modules`, `.env`) ke hosting via FTP/File Manager.
> Atau gunakan Git: clone repo di server, lalu `composer install --no-dev`.

## 2. Setting `.env` di Server

Buat file `.env` baru (copy dari `.env.example` atau gunakan yang sudah ada, lalu sesuaikan):

```bash
# Ubah ini sesuai domain
APP_NAME=IndonesiaChannaContest
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-kamu.com

# Database (sesuai akun hosting)
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database

# Ubah ke secure di hosting
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Email (sudah diisi, sesuaikan jika perlu)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=admin@domain-kamu.com
MAIL_PASSWORD=password_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@domain-kamu.com
MAIL_FROM_NAME="ICC - Nama Website"
```

## 3. Generate Key (jika .env baru)
```bash
php artisan key:generate
```

## 4. Migrate Database
```bash
php artisan migrate --force
```

## 5. Storage Link
```bash
php artisan storage:link
```

## 6. Optimize (Wajib untuk Production)
```bash
php artisan optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache
php artisan event:cache
```

## 7. Hapus File Tidak Perlu di Public (jika ada)
```bash
# Hapus storage link lama jika perlu di recreate
rm public/storage
php artisan storage:link
```

## 8. Setting Izin Folder (Linux Hosting)
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage
```

## 9. Setting Web Server

### Untuk Apache (.htaccess sudah otomatis)
### Untuk Nginx, arahkan root ke `public/`:

```nginx
server {
    listen 80;
    server_name domain-kamu.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 10. Verifikasi
- ✅ Buka `https://domain-kamu.com` → Home muncul
- ✅ Buka `https://domain-kamu.com/admin` → Login Admin
- ✅ Buka `https://domain-kamu.com/panel` → Login Penyelenggara
- ✅ Cek `https://domain-kamu.com/sitemap.xml` → XML muncul
- ✅ Cek `https://domain-kamu.com/robots.txt` → muncul
- ✅ Cek favicon muncul di tab browser
- ✅ Cek download regulasi

## Catatan Penting
- Pastikan PHP versi 8.2+ dan extension: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD, Fileinfo
- Jika hosting tidak support `exec()` atau shell, beberapa command artisan harus dijalankan via terminal hosting atau minta bantuan support
- Jangan lupa set `APP_DEBUG=false` (sudah)
- File `.env` jangan pernah di-commit ke Git
- Sitemap & robots.txt sudah dinamis (tidak perlu buat manual)
- Favicon diambil dari Pengaturan Situs (admin panel)
