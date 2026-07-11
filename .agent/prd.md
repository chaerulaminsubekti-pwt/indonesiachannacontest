# PRD (Product Requirement Document)
# Portal Website & CMS Admin — ICC (Indonesia Channa Contest)

**Versi:** 1.0
**Tanggal:** 08 Juli 2026
**Pemilik Produk:** Pengurus ICC
**Tech Stack Utama:** Laravel 12 (v12.63.0), Livewire 3 + Alpine.js, Tailwind CSS, MySQL, Filament v4 (Admin Panel), DomPDF/Browsershot (Sertifikat), Laravel Notification + Queue (Email)

---

## 1. Ringkasan Produk

Portal ICC adalah website publik sekaligus sistem CMS yang berfungsi sebagai pusat informasi dan manajemen event kontes/perlombaan yang diselenggarakan oleh komunitas/penyelenggara di bawah naungan ICC. Website ini memiliki dua sisi utama:

1. **Portal Publik (Frontend)** — menampilkan informasi event, galeri, profil organisasi, regulasi, dan menerima pengajuan event dari calon penyelenggara. Terdapat **1 menu Login** di navbar untuk semua jenis akun (tidak ada halaman login terpisah).
2. **Panel Manajemen (CMS)** — terdiri dari:
   - **Super Admin ICC**: memiliki **akses penuh tanpa batas** ke seluruh sistem — mengelola konten master (home, gallery ICC, struktur organisasi, juri, regulasi), approval pengajuan event, approval testimoni, **pengaturan situs (logo, favicon, warna tema, dll.)**, serta manajemen akun/role.
   - **Panel Penyelenggara (Event Owner)**: mengelola event yang diajukan dan disetujui, meliputi data peserta, hasil juara, sertifikat elektronik, dan galeri event.
3. **Login Tunggal Berbasis Role**: satu form login yang sama digunakan oleh Super Admin maupun Penyelenggara. Setelah login berhasil, sistem otomatis mengarahkan (redirect) ke tampilan panel yang sesuai berdasarkan role akun tersebut.

---

## 2. Tujuan Bisnis

- Menyediakan media informasi resmi dan profesional untuk seluruh kegiatan event yang bernaung di ICC.
- Mempermudah penyelenggara mengajukan event secara online tanpa proses manual/administratif offline.
- Mengotomatiskan proses pembuatan sertifikat elektronik pemenang agar penyelenggara cukup input nama & kelas juara.
- Membangun kepercayaan publik melalui transparansi (regulasi, struktur organisasi, daftar juri resmi, testimoni penyelenggara).
- Menjadi basis data terpusat seluruh histori event, peserta, dan juara dari waktu ke waktu.

---

## 3. Definisi & Istilah

| Istilah | Keterangan |
|---|---|
| ICC | Induk organisasi/komunitas penyelenggara kontes |
| Penyelenggara | Akun pengelola event (organizer) hasil pengajuan yang disetujui |
| PIC / Penanggung Jawab | Kontak person resmi dari pihak penyelenggara |
| Kelas | Kategori lomba di dalam satu event (mis. kelas ikan A, B, C, dst) |
| Latber | Latihan Bersama — kategori event skala kecil |
| Sertifikat Elektronik | Dokumen PDF juara yang di-generate otomatis oleh sistem |
| CMS | Content Management System (panel admin) |

---

## 4. Tech Stack & Arsitektur

### 4.1 Backend
- **Framework:** Laravel 12 — versi yang digunakan: **v12.63.0** (PHP minimal 8.2, disarankan PHP 8.3/8.4 untuk performa terbaik).
- **Admin Panel:** Filament — gunakan **Filament v4** (kompatibel penuh dengan Laravel 12; Filament v3 juga masih bisa dipakai namun v4 lebih direkomendasikan untuk project baru di Laravel 12) untuk Super Admin — cepat dibangun, modern, mendukung Table Builder, Form Builder, Notifikasi, Widget Dashboard.
- **Panel Penyelenggara:** Custom Livewire 3 dashboard (agar UX bisa didesain sesuai brand ICC, terpisah dari tampilan Filament admin).
- **Database:** MySQL 8
- **Autentikasi:** Laravel Fortify/Breeze (Livewire stack) + Spatie `laravel-permission` untuk role & permission (Super Admin, Penyelenggara, Editor Konten) — mendukung skema **1 form login tunggal dengan redirect berbasis role** (lihat Section 6.1).
- **Queue & Job:** Laravel Queue (database/redis driver) untuk proses generate sertifikat massal & pengiriman email agar tidak membebani request utama.
- **Notifikasi Email:** Laravel Notification + Mailable, driver SMTP/Mailgun/SES.
- **Generate PDF Sertifikat:** `barryvdh/laravel-dompdf` atau `spatie/browsershot` (jika butuh desain sertifikat lebih kompleks dengan CSS custom + QR code verifikasi).
- **QR Code Verifikasi Sertifikat:** `simple-qrcode` — setiap sertifikat memiliki QR unik yang mengarah ke halaman verifikasi keaslian.
- **Storage File:** Laravel Filesystem (local/S3) untuk flyer event, galeri, PDF regulasi, foto struktur organisasi.
- **Image Processing:** `Intervention/Image` untuk resize & optimasi otomatis (thumbnail flyer, galeri, kompresi upload).
- **Search/Filter Event:** Query scope Eloquent berdasarkan tanggal (event lalu/berjalan/akan datang), bisa ditingkatkan dengan Laravel Scout jika data besar.

> **Catatan kompatibilitas versi:** Karena project menggunakan **Laravel 12.63.0**, pastikan seluruh package pendukung (Filament, Livewire, Spatie Permission, DomPDF/Browsershot, Intervention Image, simple-qrcode) dipasang pada rilis yang secara resmi mendukung Laravel 12 (cek `composer.json` masing-masing package sebelum instalasi, karena versi minor package terus update).

### 4.2 Frontend
- **Templating:** Blade + Livewire 3 (untuk komponen interaktif tanpa reload penuh: filter event, slider testimoni, form pengajuan multi-step).
- **Styling:** Tailwind CSS v3 + konfigurasi tema warna khas ICC (biru laut/tosca + aksen emas, cocok tema akuatik/kontes).
- **Animasi/Interaksi:** Alpine.js (dropdown, modal, tab), AOS (Animate on Scroll) untuk efek scroll modern, Swiper.js untuk slider banner & testimoni.
- **Icon:** Heroicons / Lucide.
- **Responsif:** Mobile-first, breakpoint Tailwind standar (sm/md/lg/xl).

### 4.3 Arsitektur Tingkat Tinggi

```
┌────────────────────────────┐
│        PORTAL PUBLIK        │  (Blade + Livewire, guest)
│ Home | Event | Gallery |    │
│ Struktur | Juri | Regulasi  │
└──────────────┬───────────────┘
               │
        ┌──────▼───────┐
        │   Laravel App │
        │  (Controllers,│
        │  Livewire,    │
        │  Policies)     │
        └──────┬────────┘
   ┌────────────┼─────────────┐
   │            │             │
┌──▼───┐   ┌────▼─────┐  ┌────▼─────┐
│MySQL │   │  Storage │  │  Queue   │
│  DB  │   │ (flyer,  │  │ (email,  │
│      │   │ galeri,  │  │  cert)   │
│      │   │ PDF)     │  │          │
└──────┘   └──────────┘  └──────────┘

Panel Terpisah (berdasarkan role & middleware):
 - /admin  → Filament (Super Admin ICC)
 - /panel  → Livewire Dashboard Penyelenggara
```

---

## 5. Roles & Hak Akses (RBAC)

| Role | Akses |
|---|---|
| **Super Admin ICC** | **Full akses tanpa batas** ke seluruh sistem: approval pengajuan event, kelola home, gallery ICC, struktur organisasi, juri, regulasi, approval testimoni, monitoring semua event & sertifikat, **kelola pengaturan situs (logo, favicon, nama website, warna tema, informasi kontak footer, dll.)**, kelola akun admin/editor lain, kelola seluruh role & permission. Super Admin pada dasarnya bisa mengubah/menghapus/menambah **apa pun** yang ada di sistem, termasuk konten yang biasanya dikelola role lain |
| **Editor Konten** *(opsional)* | Kelola konten Home, Gallery ICC, Regulasi, Struktur Organisasi, Juri (tanpa akses approval event, tanpa akses pengaturan situs/logo — dibatasi hanya Super Admin) |
| **Penyelenggara (Event Owner)** | Login setelah aktivasi email; hanya bisa kelola event miliknya sendiri: dashboard event, data peserta, input juara & generate sertifikat, upload galeri event |
| **PIC Tambahan** *(opsional roadmap)* | Sub-akun di bawah penyelenggara dengan akses terbatas (misal hanya input data peserta) |
| **Publik (Guest)** | Melihat seluruh halaman publik, submit form pengajuan event, submit form kontak (tanpa login) |

> Semua role di atas (Super Admin, Editor, Penyelenggara) **login melalui satu form login yang sama** di halaman utama (lihat Section 6.1). Tidak ada halaman/link login terpisah untuk masing-masing role.

---

## 6.1 Login Tunggal & Redirect Berbasis Role

Untuk menyederhanakan pengalaman pengguna, sistem **hanya menyediakan satu form login** yang bisa diakses siapa saja dari menu **"Login"** di navbar halaman utama — tidak dibedakan tampilannya untuk admin maupun penyelenggara.

**Cara Kerja:**
1. Pengunjung klik menu **Login** di navbar → tampil modal/halaman form berisi: `Username/Email` + `Password` (tanpa pilihan "login sebagai...").
2. User submit form → sistem melakukan autentikasi standar Laravel (`Auth::attempt`).
3. Setelah autentikasi berhasil, sistem membaca kolom `role` pada tabel `users` dan melakukan **redirect otomatis**:

   | Role Terdeteksi | Diarahkan ke |
   |---|---|
   | `super_admin` | `/admin` (Filament Admin Panel — akses penuh) |
   | `editor` | `/admin` (Filament, dengan menu terbatas sesuai permission) |
   | `penyelenggara` | `/panel` (Dashboard Penyelenggara) |

4. Jika role tidak dikenali / akun nonaktif (`status = inactive`, misalnya belum aktivasi email) → user tetap di halaman login dengan pesan error yang sesuai (mis. "Akun belum diaktivasi, silakan cek email aktivasi Anda").
5. Middleware (`role:super_admin`, `role:penyelenggara`, dsb via Spatie Permission) tetap diterapkan di setiap route/panel agar user tidak bisa mengakses panel yang bukan haknya meski mencoba mengetik URL langsung.

**Implementasi Teknis (ringkas):**
- Gunakan 1 `LoginController`/Livewire `Login` component dengan 1 route `POST /login`.
- Setelah `Auth::attempt()` sukses, cek `auth()->user()->hasRole(...)` lalu `redirect()->intended()` sesuai mapping role di atas.
- Filament panel diatur agar hanya bisa diakses oleh user dengan role `super_admin`/`editor` (via `canAccessPanel()` di model `User`), sehingga akun penyelenggara tidak bisa masuk ke `/admin` meskipun tahu URL-nya, begitu juga sebaliknya.

---

## 6. Sitemap / Struktur Menu

```
Navbar Utama
├── Home
├── Event
│    └── [Detail Event] (halaman khusus per event)
│         ├── Dashboard (ringkasan event)
│         ├── Data Peserta
│         ├── Juara & Sertifikat (per kelas)
│         └── Gallery Event
├── Pengajuan Event
├── Gallery ICC
├── Struktur Organisasi ICC
├── Daftar Juri Aktif
├── Regulasi ICC
└── Login  (1 tombol/menu login di navbar, tampil untuk semua pengunjung)

Footer
├── Kontak Person Pengurus
├── Sosial Media ICC
└── Link cepat (Regulasi, Pengajuan Event)

Area setelah Login (1 pintu masuk, redirect otomatis sesuai role)
├── /login  → **1 halaman form login tunggal** (username/email + password) untuk SEMUA jenis akun
│              Sistem mengecek role user setelah autentikasi berhasil, lalu redirect otomatis:
│                - role = super_admin / editor  →  /admin  (Filament Admin Panel)
│                - role = penyelenggara          →  /panel  (Dashboard Penyelenggara)
├── /panel  (Dashboard Penyelenggara)
└── /admin  (Super Admin & Editor - Filament)
```

> **Catatan penting:** Tidak ada 2 form login terpisah. Baik Super Admin maupun Penyelenggara login lewat **satu form yang sama** di menu "Login" pada navbar utama. Tampilan panel yang muncul setelahnya (Filament untuk admin, Dashboard Livewire untuk penyelenggara) otomatis menyesuaikan berdasarkan **role** akun yang login — bukan dari 2 sistem login berbeda.

---

## 7. Spesifikasi Fitur Frontend (Per Halaman)

### 7.1 Home

| Section | Detail |
|---|---|
| **Hero Slider Banner** | Slider full-width (Swiper.js) berisi banner event unggulan/pengumuman, dikelola dari admin (upload gambar, judul, link tujuan, urutan tampil, jadwal tayang aktif/nonaktif) |
| **Sambutan Ketua ICC** | Section foto ketua + teks sambutan (rich text, dikelola admin via WYSIWYG editor) |
| **Event Manager** | Dua tab: **Event Berjalan** & **Event Akan Datang** — menampilkan card flyer, nama event, tanggal, lokasi, tombol "Lihat Detail". Data ditarik otomatis berdasarkan tanggal event terhadap tanggal hari ini |
| **Gallery Foto ICC** | Grid foto terbaru (limit 8–12 foto) dengan tombol "Lihat Semua" ke halaman Gallery ICC |
| **Testimoni Penyelenggara** | Slider testimoni (foto, nama penyelenggara, nama event, isi testimoni, rating opsional bintang) — hanya testimoni yang sudah disetujui admin yang tampil |
| **Kontak Person Pengurus ICC** | Card kontak (nama, jabatan, no. WA dengan link wa.me, email) |

### 7.2 Menu Event

- Halaman listing dengan 3 tab/filter otomatis berbasis tanggal sistem:
  - **Event Sudah Berjalan** (tanggal event < hari ini, status selesai)
  - **Event Berjalan** (tanggal mulai ≤ hari ini ≤ tanggal selesai)
  - **Event Akan Datang** (tanggal mulai > hari ini)
- Setiap card menampilkan flyer/foto, nama event, kategori (Latber/Mini Contest/Regional/Nasional), tanggal, kota/wilayah, nama penyelenggara.
- Filter tambahan: kategori event, wilayah kota, pencarian nama event.
- Klik event → membuka **halaman detail event** (route unik per event, misal `/event/{slug}`).

#### Halaman Detail Event (sub-menu di dalamnya)

| Sub-Menu | Isi & Fungsi |
|---|---|
| **Dashboard** | Ringkasan event: banner/flyer besar, deskripsi, tanggal & venue, jumlah peserta terdaftar, jumlah kelas lomba, status event, kontak PIC event |
| **Data Peserta** | Tabel peserta per kelas (nama peserta, kelas, nomor gantung/nomor urut) — publik bisa lihat readonly, penyelenggara bisa kelola dari panel |
| **Juara Setiap Kelas + Sertifikat** | List kelas → daftar juara 1/2/3/BB (Best of Best) dsb, lengkap tombol **Download/Lihat Sertifikat Elektronik** (PDF) per pemenang |
| **Gallery Event** | Galeri foto dokumentasi event tersebut (diupload oleh penyelenggara) |

### 7.3 Menu Pengajuan Event

Form publik (tanpa login) dengan 2 bagian data, dibuat sebagai **multi-step form (Livewire wizard)** agar tidak membebani user:

**Step 1 — Data Event**
- Nama Penyelenggara (organisasi/komunitas)
- Nama Event
- Tanggal Event (mulai – selesai)
- Alamat/Venue Event
- Kategori Event: Latber / Mini Contest / Regional / Nasional (select)
- Tema Event
- Wilayah/Kota

**Step 2 — Data Penanggung Jawab (PIC)**
- Nama
- Jabatan
- No. WhatsApp
- No. KTP
- E-mail
- Username (untuk login nanti)
- Password + Konfirmasi Password

**Step 3 — Review & Submit**
- Ringkasan data sebelum submit + checkbox persetujuan mengikuti Regulasi ICC (link ke halaman Regulasi).

**Alur setelah submit:**
1. Data masuk ke database dengan status `pending_review`.
2. Notifikasi masuk ke Super Admin (dashboard + email).
3. Admin review → **Approve** atau **Reject** (dengan catatan alasan bila ditolak).
4. Jika **Approve**:
   - Akun penyelenggara otomatis dibuat (status awal `inactive`).
   - Sistem mengirim **email aktivasi** (link unik/token, masa berlaku misal 24 jam) ke email yang didaftarkan.
   - Setelah klik link aktivasi → akun menjadi `active` dan bisa login ke `/panel`.
5. Jika **Reject**: email pemberitahuan alasan penolakan dikirim ke calon penyelenggara.

### 7.4 Menu Gallery ICC

- Galeri dokumentasi kegiatan pengurus ICC (bukan galeri per-event, tapi galeri umum organisasi).
- Ditampilkan grid dengan lightbox (klik untuk zoom).
- Bisa dikelompokkan per album/kegiatan (opsional: filter berdasarkan tahun/kegiatan).
- Dikelola penuh dari Admin CMS (upload multiple foto, judul album, deskripsi, tanggal).

### 7.5 Menu Struktur Organisasi ICC

- Menampilkan file (PDF atau gambar) struktur organisasi secara otomatis (embed viewer untuk PDF, atau tampil langsung jika format gambar).
- Admin cukup upload 1 file terbaru dari CMS, otomatis menggantikan versi lama di halaman publik.

### 7.6 Menu Daftar Juri Aktif

- Sama seperti struktur organisasi — auto-tampil dari file PDF/JPG yang diupload admin.
- (Opsional pengembangan lanjut: bisa dibuat versi terstruktur berupa data card juri berisi nama, spesialisasi, foto — namun sesuai kebutuhan awal cukup file viewer).

### 7.7 Menu Regulasi ICC

- Tampil otomatis dari file PDF/JPG yang diupload admin (embed viewer di halaman).
- Disertai tombol **Download** agar pengunjung bisa menyimpan file regulasi.

### 7.8 Testimoni (bagian dari data, ditampilkan di Home)

- Sumber data testimoni **hanya bisa diinput oleh penyelenggara** yang eventnya sudah berstatus selesai **dan** sudah melengkapi data juara + galeri dokumentasi (validasi sistem otomatis mengunci form testimoni sebelum syarat ini terpenuhi).
- Testimoni yang disubmit berstatus `pending` → harus disetujui Super Admin sebelum tampil di halaman publik.

---

## 8. Dashboard Panel Penyelenggara (`/panel`)

Setelah login, penyelenggara memiliki dashboard berisi:

1. **Ringkasan Event Saya** — daftar semua event yang pernah/sedang diajukan beserta statusnya (pending, disetujui, berjalan, selesai, ditolak).
2. **Kelola Event Aktif**, dengan sub-menu (mengikuti struktur halaman detail event publik, namun mode edit):
   - **Dashboard Event**: edit deskripsi, flyer, venue, tanggal.
   - **Data Peserta**: tambah/edit/hapus peserta, assign ke kelas, import via Excel (opsional, gunakan skill xlsx bila dibutuhkan).
   - **Input Juara & Generate Sertifikat**:
     - Pilih kelas lomba → input Nama Pemenang, Peringkat (Juara 1/2/3/Harapan/Best of Best) per kelas.
     - Setelah data juara diinput & disimpan, sistem **otomatis generate sertifikat PDF** menggunakan template resmi ICC (logo, nama event, nama pemenang, kelas, peringkat, tanda tangan ketua, QR code verifikasi).
     - Penyelenggara tinggal klik "Generate Sertifikat" — tidak perlu desain manual.
   - **Gallery Event**: upload foto dokumentasi event.
   - **Ajukan Testimoni**: form muncul aktif hanya setelah status event `selesai` dan data juara + galeri sudah lengkap.
3. **Profil Penyelenggara & PIC**: edit data kontak, ganti password.
4. **Ajukan Event Baru**: shortcut ke form pengajuan event (tanpa perlu isi ulang data penyelenggara/PIC bila sudah pernah terdaftar).

---

## 9. Admin CMS — Super Admin ICC (`/admin` — Filament)

### 9.1 Modul Utama

| Modul | Fungsi |
|---|---|
| **Dashboard** | Widget ringkasan: total event, event menunggu approval, total penyelenggara aktif, grafik jumlah event per bulan, testimoni pending |
| **Manajemen Slider Banner** | CRUD banner home (gambar, judul, link, urutan, status aktif, jadwal tayang) |
| **Sambutan Ketua** | Edit konten rich text + foto ketua |
| **Manajemen Pengajuan Event** | List pengajuan (pending/approved/rejected), detail data, tombol Approve/Reject + catatan |
| **Manajemen Event** | Monitoring seluruh event (termasuk yang dikelola penyelenggara), bisa override/edit jika diperlukan, ubah status event |
| **Manajemen Penyelenggara** | List akun penyelenggara, aktifkan/nonaktifkan akun, reset password, riwayat event |
| **Manajemen Kelas Lomba (Master)** | Kelola master data kelas (jika perlu standar kelas lintas event) |
| **Manajemen Sertifikat** | Lihat/monitoring seluruh sertifikat yang sudah di-generate, re-generate jika ada revisi data |
| **Gallery ICC** | CRUD album & foto galeri organisasi |
| **Struktur Organisasi** | Upload/replace file PDF/JPG |
| **Daftar Juri Aktif** | Upload/replace file PDF/JPG |
| **Regulasi ICC** | Upload/replace file PDF/JPG |
| **Testimoni** | Approve/reject testimoni yang masuk dari penyelenggara |
| **Kontak Person Pengurus** | CRUD data kontak (nama, jabatan, no. WA, email, foto) |
| **Pengaturan Situs (Site Settings)** *— khusus Super Admin* | Kelola **logo website** (logo header + favicon), nama website, tagline, warna tema (primary/secondary color picker), alamat kantor, media sosial (link Instagram/Facebook/YouTube/TikTok), email pengirim notifikasi, teks copyright footer. Perubahan di sini otomatis merefresh tampilan di seluruh halaman publik tanpa perlu ubah kode |
| **Manajemen User & Role** | Kelola akun admin/editor tambahan beserta permission (Spatie Permission); **hanya Super Admin** yang bisa membuat akun Super Admin/Editor baru atau mengubah role user manapun |
| **Log Aktivitas** | Audit trail (siapa approve/reject apa, kapan, termasuk siapa mengubah pengaturan situs) — opsional gunakan `spatie/laravel-activitylog` |

> **Prinsip akses Super Admin:** Modul "Pengaturan Situs" dan "Manajemen User & Role" **hanya bisa diakses Super Admin** — bahkan Editor Konten tidak memiliki izin ke sini. Selain dua modul tersebut, Super Admin juga otomatis memiliki akses **override/edit penuh** ke seluruh modul lain di tabel di atas (Editor Konten, sebaliknya, hanya memiliki akses ke sebagian modul sesuai permission yang diberikan).

---

## 10. Modul Sertifikat Elektronik Otomatis

**Tujuan:** Penyelenggara cukup input *nama pemenang* + *kelas* → sertifikat otomatis jadi.

**Alur Teknis:**
1. Penyelenggara memilih Event → Kelas → input data juara (Nama, Peringkat).
2. Sistem menyimpan data ke tabel `winners`.
3. Job (`GenerateCertificateJob`) dijalankan (queue) untuk setiap juara:
   - Ambil template sertifikat (Blade view sebagai HTML) → berisi placeholder: nama event, nama pemenang, kelas, peringkat, tanggal, logo ICC, tanda tangan ketua, nomor sertifikat unik, QR code.
   - Render ke PDF (DomPDF/Browsershot).
   - Simpan file ke storage (`certificates/{event_id}/{winner_id}.pdf`) dan path disimpan di tabel `certificates`.
4. QR Code pada sertifikat mengarah ke halaman publik `/verifikasi/{kode_unik}` untuk mengecek keabsahan sertifikat (anti-pemalsuan).
5. Penyelenggara & peserta bisa **download langsung** dari halaman Detail Event → Juara & Sertifikat.
6. Jika penyelenggara melakukan **edit nama pemenang**, sistem otomatis re-generate ulang sertifikat (versi lama diarsipkan/ditimpa).

**Template Sertifikat** perlu didesain modern (landscape A4), memuat:
- Header logo ICC + nama event
- Judul "SERTIFIKAT PENGHARGAAN"
- Nama pemenang (font besar, elegan)
- Keterangan: "Telah meraih **[Peringkat]** pada Kelas **[Nama Kelas]**"
- Tanggal & lokasi event
- Tanda tangan Ketua ICC (gambar signature) + Cap digital
- Nomor sertifikat unik + QR Code verifikasi di pojok bawah

---

## 11. Modul Notifikasi Email & Aktivasi Akun

| Trigger | Penerima | Isi |
|---|---|---|
| Pengajuan event baru masuk | Super Admin | Notifikasi ada pengajuan baru + link review |
| Event disetujui | Calon Penyelenggara | Email aktivasi akun (link token) |
| Event ditolak | Calon Penyelenggara | Email alasan penolakan |
| Akun berhasil aktivasi | Penyelenggara | Email selamat datang + link login panel |
| Sertifikat berhasil digenerate | Penyelenggara (opsional) | Notifikasi sertifikat siap diunduh |
| Testimoni disetujui/ditolak | Penyelenggara | Notifikasi status testimoni |

Semua email menggunakan queue agar proses submit form tidak lambat menunggu SMTP.

---

## 12. Struktur Database (Ringkas / ERD Level Entitas)

```
users
 ├─ id, name, email, username, password, role, status(active/inactive), email_verified_at

organizers (1-1 dgn users, role=penyelenggara)
 ├─ id, user_id, nama_organisasi, jabatan_pic, no_wa, no_ktp

events
 ├─ id, organizer_id, nama_event, slug, tanggal_mulai, tanggal_selesai,
 │  venue, kategori(latber/mini/regional/nasional), tema, wilayah_kota,
 │  flyer, deskripsi, status(pending/approved/rejected/berjalan/selesai)

event_classes  (kelas lomba dalam suatu event)
 ├─ id, event_id, nama_kelas

participants
 ├─ id, event_id, event_class_id, nama_peserta, no_urut

winners
 ├─ id, event_id, event_class_id, nama_pemenang, peringkat

certificates
 ├─ id, winner_id, nomor_sertifikat, file_path, kode_verifikasi, generated_at

event_galleries
 ├─ id, event_id, file_path, caption

icc_galleries (galeri umum ICC, bukan per event)
 ├─ id, judul_album, file_path, caption, tanggal

testimonials
 ├─ id, event_id, organizer_id, isi_testimoni, rating, status(pending/approved/rejected)

sliders
 ├─ id, judul, gambar, link, urutan, status_aktif, tgl_mulai_tayang, tgl_selesai_tayang

contacts (kontak person pengurus)
 ├─ id, nama, jabatan, no_wa, email, foto

organization_structure / judges_list / regulations (masing2 tabel sederhana untuk file terbaru)
 ├─ id, file_path, tipe(pdf/jpg), updated_by, updated_at

site_settings (pengaturan global situs — hanya bisa diubah Super Admin)
 ├─ id, logo_header, favicon, nama_website, tagline, warna_primary, warna_secondary,
 │  alamat, link_instagram, link_facebook, link_youtube, link_tiktok,
 │  email_pengirim_notifikasi, teks_copyright_footer, updated_by, updated_at
```

---

## 13. User Flow

### 13.0 Flow Login Tunggal (Semua Role)

```
Pengunjung klik menu "Login" di navbar Home
     │
     ▼
Muncul 1 Form Login (Username/Email + Password)
     │
     ▼
Submit → Auth::attempt()
     │
     ├── Gagal (password salah / akun tidak ditemukan) ──► Tampil pesan error, tetap di form login
     │
     ├── Akun ditemukan tapi status "inactive" (belum aktivasi email)
     │        └──► Pesan: "Akun belum aktif, silakan cek email aktivasi Anda"
     │
     └── Berhasil & akun aktif
              │
              ▼
        Sistem cek kolom `role` pada tabel users
              │
      ┌───────┴────────┐
      ▼                ▼
role = super_admin   role = penyelenggara
  / editor                  │
      │                     ▼
      ▼              Redirect ke /panel
Redirect ke /admin    (Dashboard Penyelenggara)
(Filament Panel)
```

### 13.1 Flow Pengajuan Event → Event Aktif

```
Calon Penyelenggara
     │
     ▼
Isi Form Pengajuan Event (3 step)
     │
     ▼
Submit → status: pending_review
     │
     ▼
Notifikasi masuk ke Super Admin
     │
     ├── Reject ──► Email alasan penolakan ke calon penyelenggara (selesai)
     │
     └── Approve
             │
             ▼
     Akun penyelenggara dibuat (status: inactive)
             │
             ▼
     Email aktivasi terkirim (link token, 24 jam)
             │
             ▼
     Penyelenggara klik link → akun jadi active
             │
             ▼
     Login ke /panel → kelola event (peserta, juara, sertifikat, galeri)
```

### 13.2 Flow Generate Sertifikat

```
Penyelenggara login → pilih Event → pilih Kelas
     │
     ▼
Input Nama Pemenang + Peringkat
     │
     ▼
Simpan → Job GenerateCertificateJob (queue)
     │
     ▼
PDF sertifikat dibuat + QR code unik
     │
     ▼
Tersimpan di storage & tabel certificates
     │
     ▼
Muncul tombol Download di halaman Juara & Sertifikat (publik & panel)
```

### 13.3 Flow Testimoni

```
Event berstatus "selesai"
   AND Data Juara lengkap
   AND Galeri Event lengkap
     │
     ▼
Form Testimoni terbuka otomatis di /panel
     │
     ▼
Penyelenggara isi testimoni → submit (status: pending)
     │
     ▼
Super Admin review di /admin
     │
     ├── Reject → tidak tampil publik
     └── Approve → tampil di slider Testimoni Home
```

### 13.4 Flow Publik Melihat Event

```
Pengunjung buka Menu "Event"
     │
     ▼
Sistem otomatis filter berdasarkan tanggal:
   - tanggal_selesai < hari ini      → tab "Sudah Berjalan"
   - tanggal_mulai <= hari ini <= tanggal_selesai → tab "Berjalan"
   - tanggal_mulai > hari ini        → tab "Akan Datang"
     │
     ▼
Klik salah satu event → /event/{slug}
     │
     ▼
Tampil sub-menu: Dashboard | Data Peserta | Juara & Sertifikat | Gallery Event
```

---

## 14. UI/UX & Design Guideline (Modern)

### 14.1 Konsep Visual
- Tema modern, bersih, dengan nuansa **biru laut/tosca** (identik komunitas akuatik/kontes) dipadu **emas/kuning keemasan** untuk elemen juara & sertifikat (kesan prestisius).
- Gaya card berbayang lembut (`shadow-lg`, `rounded-2xl`), banyak white-space, tipografi tegas untuk judul (`font-bold text-3xl/4xl`) dan tipografi santai untuk body text.
- Micro-interaction: hover scale pada card event, smooth transition tab (Livewire wire:transition / Alpine x-transition), efek scroll AOS pada section Home.
- Sertifikat & badge juara memakai elemen dekoratif (ribbon/medal icon) agar terasa formal dan berkelas.

### 14.2 Komponen UI Kunci
- Navbar sticky dengan mega-menu ringan untuk "Event" (dropdown 3 tab langsung dari navbar, opsional).
- Hero Slider full-width dengan overlay gradient agar teks tetap terbaca.
- Card Event: flyer (aspect-ratio 3:4 mirip poster), badge kategori berwarna beda per kategori (Latber=abu, Mini Contest=biru, Regional=hijau, Nasional=emas).
- Tab switcher (Sudah Berjalan/Berjalan/Akan Datang) menggunakan Livewire agar filter tanpa reload.
- Wizard form Pengajuan Event dengan progress bar step 1-2-3.
- Lightbox galeri (foto ICC & galeri event) dengan navigasi next/prev.
- Testimoni carousel dengan foto bulat + quote style.
- Viewer PDF inline (embed `<iframe>` atau `pdf.js`) untuk Struktur Organisasi, Daftar Juri, Regulasi — dengan tombol Download menonjol (khusus Regulasi).
- Sertifikat preview modal sebelum download (agar user bisa cek dulu tanpa unduh file).

### 14.3 Aksesibilitas & Performa
- Lazy-load gambar & galeri (native `loading="lazy"` + Intervention Image untuk kompresi otomatis saat upload).
- Kontras warna sesuai WCAG AA minimal.
- Skeleton loading pada saat Livewire fetch data event/testimoni.

---

## 15. Non-Functional Requirements

| Aspek | Requirement |
|---|---|
| **Keamanan** | Validasi input ketat (Form Request Laravel), hash password (bcrypt/argon2), proteksi CSRF bawaan Laravel, rate limiting login & submit form, sanitasi upload file (whitelist ekstensi pdf/jpg/png, max size), token aktivasi & reset password expired |
| **Performa** | Caching halaman publik (Laravel Cache untuk Home, Gallery), pagination di semua listing, queue untuk proses berat (email, generate PDF) |
| **Skalabilitas** | Storage bisa dipindah ke S3/Cloud tanpa ubah kode (Laravel Filesystem abstraction) |
| **SEO** | Meta title/description dinamis per halaman event, sitemap.xml otomatis, URL slug friendly (`/event/nama-event-2026`) |
| **Backup** | Backup database & storage berkala (`spatie/laravel-backup`) |
| **Monitoring Error** | Integrasi Laravel Log/Telescope (dev) atau Sentry (production) |
| **Responsif** | Full mobile-friendly, khususnya form pengajuan event & dashboard penyelenggara sering diakses via HP |

---

## 16. Roadmap Pengembangan (Fase MVP → Full)

**Fase 1 — MVP (Prioritas Utama)**
- Autentikasi & role (Super Admin, Penyelenggara)
- Menu Home (slider, sambutan, event manager, kontak)
- Menu Event + Detail Event (dashboard, data peserta, juara+sertifikat, galeri)
- Menu Pengajuan Event + alur approval + aktivasi email
- Panel Penyelenggara dasar (kelola event, peserta, juara, generate sertifikat)
- Admin CMS dasar (Filament: approval event, kelola master file/PDF, kelola slider)

**Fase 2 — Penyempurnaan**
- Menu Gallery ICC, Struktur Organisasi, Daftar Juri, Regulasi (viewer + download)
- Modul Testimoni lengkap (validasi syarat otomatis + approval)
- QR Code verifikasi sertifikat + halaman verifikasi publik
- Notifikasi email menyeluruh + log aktivitas admin

**Fase 3 — Peningkatan (Opsional)**
- Import peserta via Excel
- Sub-akun PIC tambahan per penyelenggara
- Statistik & laporan (jumlah peserta per kategori/wilayah per tahun)
- Integrasi WhatsApp Gateway untuk notifikasi otomatis ke PIC
- Multi-bahasa (ID/EN) jika event berskala internasional

---

## 17. Kriteria Keberhasilan (Success Metrics)

- Waktu proses pengajuan event dari submit hingga akun aktif ≤ 1x24 jam (tergantung kecepatan review admin).
- 100% sertifikat juara dapat digenerate otomatis tanpa desain manual oleh penyelenggara.
- Waktu generate sertifikat per peserta < 5 detik (via queue, tidak blocking).
- Semua halaman publik (Home, Event, Gallery, Regulasi) dapat diakses tanpa login.
- Tidak ada testimoni tampil di publik tanpa melalui proses approval admin.

---

## 18. Lampiran — Ringkasan Struktur Halaman Detail Event

```
/event/{slug}
 ├── Tab: Dashboard
 │     - Flyer besar, deskripsi event, tanggal, venue, kategori, status
 │     - Statistik singkat (jumlah kelas, jumlah peserta)
 │
 ├── Tab: Data Peserta
 │     - Tabel per kelas: No Urut | Nama Peserta | Kelas
 │
 ├── Tab: Juara & Sertifikat
 │     - Group per kelas → list peringkat 1/2/3/dst
 │     - Tombol "Lihat Sertifikat" (preview modal) & "Download"
 │
 └── Tab: Gallery Event
       - Grid foto dokumentasi event (lightbox)
```

---

**Catatan Implementasi:** Dokumen ini merupakan acuan awal (PRD) yang dapat digunakan langsung sebagai dasar pembuatan *database migration*, *wireframe/UI mockup*, dan *task breakdown* development di Laravel. Struktur modul sudah dipisah jelas antara **Publik**, **Panel Penyelenggara**, dan **Admin CMS** agar tim developer dapat membagi pengerjaan secara paralel.
