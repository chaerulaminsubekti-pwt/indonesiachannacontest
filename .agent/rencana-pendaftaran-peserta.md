# PLANNING — Sistem Pendaftaran Peserta Kontes (Internal, Mirror Google Sheets)

- **Tanggal:** 2026-08-03 (revisi 2)
- **Status:** PLANNING (belum dieksekusi)
- **Aplikasi:** indonesiachannacontest.org
- **Acuan template:** Google Sheets "Data Peserta Kutuk Meresahkan #5"

---

## 1. Konsep Inti

Menggantikan **Google Sheets** dengan sistem database internal yang **meniru persis template** spreadsheet peserta:

> Setiap **event** = 1 workbook. Setiap **kelas ikan** = 1 sheet/tab. Setiap **peserta** = 1 baris.

```
Event (Kutuk Meresahkan #5)
 ├─ Kelas: YELLOW PROGRES   → daftar peserta (baris)
 ├─ Kelas: YELLOW BEGINNER  → daftar peserta
 ├─ Kelas: RED PROGRES      → ...
 ├─ Kelas: DATA TEAM & SINGLE FIGHTER → daftar Team/SF
 └─ dst (kelas diinput oleh penyelenggara)
```

Kolom tiap peserta mengikuti template Google Sheets:
> **Nomor, Nama, Alamat, Nama Ikan, Team, No HP, Keterangan (Booking/Lunas), Fishin, Fishout**

---

## 2. Kebutuhan Fungsional (dikonfirmasi oleh pemilik)

1. **Admin/Penyelenggara menginput kelas yang dibuka** untuk event-nya (mis. YELLOW BEGINNER, AURANTI JUNIOR, LIMBATA BEGINNER, dll).
2. **Kelas yang diinput tersebut otomatis tersinkron** ke **form pendaftaran peserta** (dropdown "Kelas Ikan").
3. Di halaman **publik tab "Data Peserta"** muncul kolom:
   **Nomor, Nama, Alamat, Nama Ikan, Team, No HP, Keterangan (Lunas), Fishin, Fishout**.
4. Saat **event berjalan / registrasi ulang di venue**:
   - Penyelenggara buka **data peserta (sisi admin penyelenggara)**
   - **Ceklis Fishin** (ikan masuk) & **Fishout** (ikan keluar) per peserta.
5. **Data peserta ini digunakan untuk cetak sertifikat online** (download sertifikat per peserta yang LUNAS).

---

## 3. Alur User (Peserta — Pendaftaran)

```
[Peserta melihat event yang berjalan/ada]
   ├─ klik tombol "Daftar Kontes" (di bawah CP event)
   ├─ POPUP pendaftaran berisikan:
   │     Nama Pemilik : (isi)
   │     Team / SF    : (isi)
   │     Nama Ikan    : (isi)
   │     Jenis Ikan   : (isi)
   │     Kelas Ikan   : [dropdown = kelas yang dibuka penyelenggara]  (+ harga)
   │     Kota Asal    : (isi)
   │     No WA        : (isi)
   ├─ klik "Daftar Sekarang"
   ├─ simpan pendaftaran → status = MENUNGGU BAYAR
   ├─ Notifikasi pembayaran: nominal (harga kelas) + rekening (bank_accounts)
   └─ (lanjut) upload bukti → status = MENUNGGU VERIFIKASI
```

---

## 4. Alur Penyelenggara (Admin)

```
A. SEBELUM EVENT:
   1. Atur kelas yang dibuka (nama_kelas + harga_tiket) per event.
   2. Atur rekening pembayaran (bank_accounts).
   3. (opsional) atur biaya daftar.

B. VERIFIKASI PENDAFTARAN (online):
    - Lihat daftar pendaftaran + bukti pembayaran.
    - ACC  → status = LUNAS (muncul di Data Peserta publik) + auto no_urut.
    - Reject → status = rejected.

C. HARI EVENT (registrasi ulang di venue):
    - Buka data peserta (sempur/kelas).
    - Ceklis Fishin = ikan masuk.
    - Ceklis Fishout = ikan keluar.

D. Cetak sertifikat:
    - Data peserta LUNAS dipakai untuk generate & cetak sertifikat online.
```

---

## 5. Perubahan Database (Migration)

### 5.1 `event_classes`
| Kolom | Ket |
|-------|-----|
| `id` | PK |
| `event_id` | FK |
| `nama_kelas` | (mis. YELLOW PROGRES) |
| `harga_tiket` | decimal(10,2) nullable — diisi penyelenggara |

### 5.2 `participants` (perluas)
| Kolom | Ket |
|-------|-----|
| `id` | PK |
| `event_id` | FK |
| `event_class_id` | FK (kelas ikan) |
| `nama_pemilik` | string |
| `team_sf` | string nullable |
| `nama_ikan` | string |
| `jenis_ikan` | string nullable |
| `kota_asal` | string nullable |
| `no_hp` | string(20) nullable |
| `status` | enum: `menunggu_bayar`, `menunggu_verifikasi`, `lunas`, `rejected` |
| `bukti_pembayaran` | string nullable |
| `no_urut` | int nullable (auto saat lunas) |
| `fishin` | bool default false |
| `fishout` | bool default false |
| `keterangan` | string nullable (ops) |
| (data lama `nama_peserta`, `no_urut` diperiksa kompatibilitas saat eksekusi) | |

> `keterangan` di publik tampilkan "Lunas"/"Booking" — bisa derivasi dari `status`.

### 5.3 Tabel `bank_accounts`
| Kolom | Ket |
|-------|-----|
| `id` | PK |
| `event_id` | FK |
| `nama_bank` | string |
| `nomor_rekening` | string |
| `atas_nama` | string |
| `is_active` | bool |

---

## 6. Komponen yang Dibuat

### 6.1 Livewire `DaftarKontes` — modal pendaftaran
- `app/Livewire/DaftarKontes.php`
- `resources/views/livewire/daftar-kontes.blade.php`
- mount(Event), validasi, simpan, alur bukti, ambil data kelas dr event.

### 6.2 Livewire `DaftarPesertaPublik` — tab "Data Peserta"
- Menampilkan peserta **LUNAS** per kelas (dikelompakan nama kelas).
- Kolom: Nomor | Nama | Alamat | Nama Ikan | Team | No HP | Keterangan | Fishin | Fishout.

### 6.3 Blade `event/show.blade.php`
- Tombol "Daftar Kontes" bawah CP + modal.
- Ganti tab "Data Peserta" → `<livewire:daftar-peserta-publik :event="..."/>`.

---

## 7. Bagian Admin Penyelenggara (Filament Panel)

### 7.1 `EventResource`
- Tambah form untuk **mengatur kelas** (nama_kelas + harga) & **rekening** (bank_accounts).
- Hapus/sembunyikan `google_sheet_url` (karena peserta internal).

### 7.2 `ParticipantsRelationManager` (per event)
- Daftar peserta semua kelas, dengan kolom: No, Nama, Kelas, Team, Nama Ikan, No HP, Status, Bukti, Fishin, Fishout, No Urut.
- **Action ACC → LUNAS + auto no_urut**; **Reject**; **Lihat Bukti**.
- Filter by status/kelas.
- **Fitur registrasi ulang venue**: Tab khusus / tombol toggle **Fishin** & **Fishout** per baris (checkbox).

### 7.3 Cetak Sertifikat
- Data peserta LUNAS difungkan ke sistem sertifikat yang sudah ada (`Certificate` model, GenerateCertificateJob, template.blade) — generate by peserta.

---

## 8. Auto No Urut (per kelas)
Saat penegak set LUNAS:
```php
no_urut = MAX(no_urut) pada (event_id, event_class_id) + 1
```
Dalam `DB::transaction` + `lockForUpdate` di kelas tsb.

---

## 9. Migrasi & Deploy
1. Migration baru (participant kolom baru, harga_tiket, bank_accounts).
2. Uji lokal penuh (per `predeploy-check.ps1`: pint, test, npm build).
3. Baru deploy (backup + migrate --force).

---

## 10. Checklist Skenario
- [ ] Penyelenggara bisa input kelas + harga.
- [ ] Kelas sinkron ke dropdown form duftar.
- [ ] Peserta mendaftar (1 form 1 ikan), upload bukti.
- [ ] ACC → LUNAS × muncul Data Publik, urut noormor per kelas.
- [ ] Kolom publik: No, Nama, Alamat, Nama Ikan, Team, No HP, Keterangan, Fishin, Fishout.
- [ ] Hari event: admin cek advance Fishin/Fishout.
- [ ] Cetak sertifikat per peserta LUNAS.

---

## 11. Catatan / Pertanyaan untuk Pemilik
1. Kelas = bebas diisi penyelenggara (memanyukan pola sheet). OK.
2. **Pembayaran** manual transfer + validasi (tanpa gateway). OK.
3. "1 form 1 ikan" — ikan kedua = pendaftaran baru (baris baru). OK.
4. **No HP di publik** = perlu izin data (privacy). Tetap tampil sesuai template Anda.
5. **Fishin/Fishout** hanya admin? user berkata "di public" → tampilkan status (centang hijau/abu) tapi togglenya di admin. Konfirmasi.
6. **Notifikasi ke peserta**: kemungkinan WA (mulai-tingkat lanjutan).

---

**Dokumen ini = planning.** Belum ada code/eksekusi. Sampai Anda setuju, belum ada yang diubah.