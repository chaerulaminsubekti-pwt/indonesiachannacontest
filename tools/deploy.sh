#!/usr/bin/env bash
# =====================================================
# DEPLOY - DIJALANKAN DI SERVER (via SSH)
# Penggunaan:
#   bash ~/deploy.sh
#
# ISI SESUAI ACCOUNT ANDA DI BAWAH:
# =====================================================
set -e

#############################################
#  KONFIGURASI (UBAH SESUAI AKUN ANDA)       #
#############################################
GIT_REPO="https://github.com/chaerulaminsubekti-pwt/indonesiachannacontest.git"
NGINX_OR_APACHE="apache"        # atau "nginx"
PHP_CMD="php"

APP_URL="https://indonesiachannacontest.org"
DB_HOST="localhost"
DB_PORT="3306"
DB_DATABASE="u539054303_icc2026"
DB_USERNAME="u539054303_chaerul"
DB_PASSWORD="A7xkcx232!@#"
BACKUP_FILE="database-backup-local.sql"

WWW_DIR="${WWW_DIR:-${HOME}/public_html}"

echo "=============================================="
echo " MENGONFIGURASI: ${WWW_DIR}"="..."
echo "=============================================="

cd "${WWW_DIR}"

echo "[1/8] Clone source dari GitHub..."
if [ ! -f artisan ]; then
    TMP_DIR="${WWW_DIR}/.clip_tmp"
    rm -rf "${TMP_DIR}"
    echo "   kloning ${GIT_REPO}"
    if ! git clone --branch main "${GIT_REPO}" "${TMP_DIR}" 2>/dev/null; then
        git clone "${GIT_REPO}" "${TMP_DIR}"
    fi
    # pindahkan isi repo ke public_html, termasuk file tersembunyi
    shopt -s dotglob
    mv "${TMP_DIR}"/* "${WWW_DIR}/"
    shopt -u dotglob
    rm -rf "${TMP_DIR}"
else
    echo "      artisan ditemukan => pull perubahan saja."
    git pull origin main
fi

echo "[2/8] Install dependency Laravel (vendor)..."
if ! command -v ${PHP_CMD} >/dev/null 2>&1; then
    echo "!! ${PHP_CMD} tidak ditemukan di PATH."; exit 1
fi
if command -v composer >/dev/null 2>&1; then
    composer install --no-dev --prefer-dist --optimize-autoloader
else
    echo "!! composer tidak ada di PATH, coba composer.phar..."
    [ -f composer.phar ] || curl -sS https://getcomposer.org/installer | php &&
    php composer.phar install --no-dev --prefer-dist --optimize-autoloader
fi

echo "[3/8] Membuat file .env..."
if [ ! -f .env ]; then
    cp .env.example .env
fi
# perbarui nilai penting di .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
sed -i "s|^SESSION_DRIVER=.*|SESSION_DRIVER=file|" .env
sed -i "s|^SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=true|" .env

echo "[4/8] Generate APP_KEY..."
php artisan key:generate --force

echo "[5/8] Import database (data lokal) jika ada..."
if [ -f "${HOME}/${BACKUP_FILE}" ]; then
    mysql -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" "-p${DB_PASSWORD}" "${DB_DATABASE}" < "${HOME}/${BACKUP_FILE}"
    echo "   import data lokal berhasil."
else
    echo "   (tidak menemukan ${BACKUP_FILE} di ${HOME}, lewati import)"
fi
# selalu jalankan migrate utk memastikan skema terbaru
php artisan migrate --force 2>/dev/null || echo "!! migrate gagal (cek log atau jalankan manual)"

echo "[6/8] Storage link + izin folder..."
php artisan storage:link
chmod -R 775 storage bootstrap/cache public/storage

echo "[7/8] Optimasi production (tanpa proc_open)..."
php artisan config:cache 2>/dev/null || echo "   (lewati config:cache)"
php artisan route:cache 2>/dev/null || echo "   (lewati route:cache)"
php artisan view:cache 2>/dev/null || echo "   (lewati view:cache)"

echo "[8/8] Tampilkan status..."
echo "=============================================="
echo " SELESAI! Buka: ${APP_URL}"
echo " Versi PHP:"; ${PHP_CMD} -v | head -1
echo "=============================================="
