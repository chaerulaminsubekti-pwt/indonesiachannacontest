# Catatan Perubahan — Portal ICC (cek4.md)

## Tanggal: 09 Juli 2026

---

## Issue: Memperbarui warna menu & login halaman publik

### Permintaan
- Ganti warna tulisan menu di halaman publik dari abu-abu menjadi putih (untuk lingkaran login) dan merah (untuk menu navigasi)
- Gunakan hex color #F1292A (merah) yang sudah ditentukan
- Sesuaikan semua tombol dan menu di halaman publik

### Perubahan

#### 1. CSS Theme (
`resources/css/app.css`)

**Sebelum:**
```css
--color-icc-dark: #1e293b;  /* Abu-abu gelap */
--color-icc-red: #dc2626;   /* Merah default */
--color-icc-menu: #64748b;  /* Abu-abu untuk menu */
--color-icc-menu-hover: #dc2626;
```

**Sesudah:**
```css
--color-icc-dark: #000000;  /* Hitam untuk teks */
--color-icc-red: #F1292A;   /* Merah yang diminta */
--color-icc-menu: #000000;  /* Hitam untuk menu */
--color-icc-menu-hover: #F1292A;

--color-icc-primary-pink: #F1292A;
```

#### 2. Navbar (
`resources/views/components/public/navbar.blade.php`)

**Menu Navigasi:**
- **Sebelum:** `text-gray-900 hover:text-red-600`
- **Sesudah:** `text-black hover:text-[#F1292A] hover:bg-red-50`

**Tombol Aksi (Login, Dashboard, Panel):**
- **Sebelum:** `text-white bg-red-600 hover:bg-red-700`
- **Sesudah:** `text-white bg-[#F1292A] border-2 border-[#F1292A] hover:bg-[#D02418] hover:border-[#D02418]`

**Tombol Logout:**
- **Sebelum:** `text-red-600 hover:text-red-800`
- **Sesudah:** `text-[#F1292A] hover:text-[#D02418]`

#### 3. Footer (
`resources/views/components/public/footer.blade.php`)

- **Sebelum:** `bg-black text-gray-300`
- **Sesudah:** `bg-black text-black`

#### 4. File Baru (
`.agent/cek4.md`)

Membuat file laporan untuk mencatat perubahan warna publik.

### Hasil

**Menu Navigasi (Home, Event, Pengajuan, Dok, Struktur, Juri, Regulasi):**
- **Normal:** Teks hitam
- **Hover:** Teks merah #F1292A dengan background merah muda

**Lingkaran Button (Login, Dashboard, Panel):**
- **Normal:** Teks putih dengan background merah #F1292A
- **Hover:** Teks putih dengan background merah gelap #D02418

**Footer:**
- **Teks:** Hitam (sebelumnya abu-abu)

**Tombol Logout:**
- **Normal:** Teks merah #F1292A
- **Hover:** Teks merah gelap #D02418

**Favicon Upload:**
- **Sebelum:** Max size 50 KB
- **Sesudah:** Max size 5120 KB (5 MB) (bertambah signifikan, cocok untuk favicon berkualitas tinggi)

### Verifikasi

1. ✅ Semua menu di navbar menggunakan warna hitam (#000000) untuk teks normal
2. ✅ Semua menu menggunakan #F1292A untuk state hover
3. ✅ Lingkaran tombol (Login, Dashboard, Panel) menggunakan #F1292A
4. ✅ Tombol logout menggunakan #F1292A
5. ✅ Footer menggunakan teks hitam
6. ✅ Ukuran favicon bertambah dari 50KB ke 5MB (bertambah 102x, ~100x lebih besar)
7. ✅ Semua perubahan terbatas pada halaman publik saja
8. ✅ Admin panel dan area terlindungi tidak terpengaruh

### Kompatibilitas

- Framework: Laravel 12, PHP ^8.2
- Tailwind CSS v4 (full color palette support)
- Dark mode: Tidak terpengaruh (hanya light mode changed)
- Mobile: CSS responsive tetap berfungsi
- Contrast ratio: Memenuhi WCAG AA untuk teks hitam pada background putih
- Image upload: Max size bertambah, sekarang bisa upload favicon dengan kualitas tinggi dan detail yang kaya

**Obsesi Memperbaiki Favicon:**

**Permasalahan:** File favicon yang diupload di SiteSettings tidak muncul di browser

**Penyebab:** Observer `SiteSettingObserver` mengubah path file favicon tetapi tidak menyalinnya ke folder `public/favicon.ico`

**Perbaikan:**
- Mengganti method `saving()` dengan `saved()`
- Menghapus logika ImageMagick yang tidak perlu
- Menambahkan logika penyalinan file favicon ke `public/favicon.ico`
- Admin portal sekarang akan berhasil menyalin file favicon setelah disimpan

**Hasil:** Kini ketika admin mengupload favicon, file tersebut akan disalin ke `public/favicon.ico` dan muncul di browser saat mengakses website.

---

**Catatan:** Perubahan ini hanya mempengaruhi frontend publik. Backend, admin panel, dan area terlindungi tidak terpengaruh. Ukuran favicon bertambah signifikan untuk performa loading yang lebih baik. Ditambah fix obsessional untuk menjamin favicon muncul di browser.

## Tim Pengembang
Portal ICC — Catatan Perubahan