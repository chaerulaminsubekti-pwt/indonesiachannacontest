# PLANNING — Sistem Pendaftaran Peserta Kontes (Internal, Mirror Google Sheets)

- **Tanggal:** 2026-08-03 (revisi 3 — sinkronisasi dengan kondisi real kode)
- **Status:** DIEKSEKUSI SEBAGIAN / IN-PROGRESS (terdapat gap & bug yang belum diperbaiki)
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
   **Nomor, Nama, Alamat, Nama Ikan, Kelas Team, No HP, Keterangan (Lunas), Fishin, Fishout**.
4. Saat event berjalan / registrasi ulang di venue:
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
   │     Kelas Ikan   : [dropdown = kelas yang dibuka penyelenggara]
   │     Kota Asal    : (isi)
   │     No WA        : (isi)
   ├─ klik "Daftar Sekarang"
   ├─ simpan pendaftaran → status = MENUNGGU VERIFIKASI
   ├─ Notifikasi pembayaran: nominal (harga kelas) + rekening (bank_accounts)
   └─ (lanjut) upload bukti → status = MENUNGGU VERIFIKASI
```

> Implementasi real memakai **2 langkah** (Data Diri → Pembayaran/Upload Bukti). Status langsung `menunggu_verifikasi` saat submit (tanpa langkah `menunggu_bayar` terpisah).

---

## 4. Alur Penyelenggara (Admin)

```
A. SEBELUM EVENT:
   1. Atur kelas yang dibuka (nama_kelas + harga_tiket) per event.
   2. Atur rekening pembayaran (bank_accounts).
   (opsional) atur biaya daftar.

B. VERIFIKASI PENDAFTARAN (online):
    - Lihat daftar pendaftaran + bukti pembayaran.
    - ACC  → status = LUNAS (muncul di Data Peserta publik) + auto no_urut.
    - Reject → status = rejected.

C. HARI EVENT (registrasi ulang di venue):
    - Buka data peserta (sempur/kelas).
    - Ceklis Fishin = ikan masuk.
    - Ceklis Fishout = ikan keluar.

D. Cetak sertifikat:
    - Data peserta LUNAS difungkan ke sistem sertifikat yang sudah ada.
```

---
## STATUS IMPLEMENTASI REAL (hasil audit kode, 2026-08-03)

> Berikut status aktual masing-masing bagian terhadap kode yang sudah ada. Tandai/semua hal di bawah perlu verifikasi karena mayoritas fitur klik **belum benar-benar fungsional**.

| # | Rencana (Section) | Status di Kode | Catatan |
|---|---|---|---|
| 2.1 | Input kelas penyelenggara | ✅ Ada | `EventClassesRelationManager` (Organizer) — bisa tambah/edit `nama_kelas` + `harga_tiket` |
| 2.2 | Kelas sinkron ke dropdown form | ✅ Ada | `DaftarKontes.blade` dropdown ambil dari `EventClass::where(event_id)` |
| 2.3 | Tab publik "Data Peserta" kolom lengkap | ⚠️ Belum terhubung | `DaftarPesertaPublik.php` + blade **sudah dibuat**, **TAPI `event/show.blade.php` masih memakai Google Sheets** (blok `google_sheet_url`), belum pakai `<livewire:daftar-peserta-publik>` |
| 2.3 | Kolom Alamat | ⚠️ Belum ada kolom | blade tampilkan `$peserta->alamat` tapi tidak ada field/migration `alamat` |
| 4.B | Verifikasi ACC → LUNAS + auto no_urut | ❌ Belum | `ParticipantsRelationManager` masih form lama (`nama_peserta`), tanpa action ACC/Reject, tanpa auto `no_urut` |
| 4.C | Fishin / Fishout (admin toggle) | ❌ Belum | Blade publik hanya **menampilkan** status fishin/fishout (readonly), admin belum punya toggle |
| 4.D | Cetak sertifikat dari peserta | ❌ Belum | Belum ada integrasi peserta LUNAS → sertifikat |

### Funan/Livewire yang sudah dibuat
- `app/Livewire/DaftarKontes.php` + `resources/views/livewire/daftar-kontes.blade.php` — modal 2-step lengkap.
- `app/Livewire/DaftarPesertaPublik.php` + `resources/views/livewire/daftar-peserta-publik.blade.php` — tabel publik (grup per kelas).
- Filament Organizer: `EventClassesRelationManager`, `BankAccountsRelationManager`, `ParticipantsRelationManager`, dalam `EventResource`.

---

## 6. GAP / KETIDAKSESUAIAN KODE (harus diperbaiki sebelum berfungsi)

Ditemukan saat audit — inilah masalah yang membuat fitur belum jalan dan **rentan error**:

1. **`app/Models/Participant.php` tidak lengkap.**
   - `$fillable` masih `['event_id', 'event_class_id', 'nama_peserta', 'no_urut']` → kolom baru (`nama_pemilik`, `team_sf`, `nama_ikan`, `kota_asal`, `no_hp`, `status`, `bukti_pembayaran`, `biaya`, `fishin`, `fishout`) TIDAK bisa diisi. `DaftarKontes::create([...])` akan **diam-diam membuang** field-field itu.
   - **Konstanta STATUS TIDAK ADA.** `Participant::STATUS_MENUNGGU_VERIFIKASI` (dipakai `DaftarKontes.php:116`) dan `Participant::STATUS_LUNAS` (dipakai `DaftarPesertaPublik.php:22`) tidak didefinisikan → **PHP Fatal Error**.

2. **Migration `participants` tidak lengkap / cacat.**
   - `2026_08_03_025038_add_columns_to_participants` hanya menambah `nama_pemilik`, `team_sf`, `no_hp`. **Belum ada** kolom: `nama_ikan`, `jenis_ikan`, `kota_asal`, `status`, `bukti_pembayaran`, `biaya`, `user_id`, `fishin`, `fishout`.
   - `2026_08_03_034029_add_dp_amount_to_participants` memakai `->after('biaya')` tetapi kolom `biaya` **belum pernah dibuat** → migration ini akan **gagal**.
   - Indeks kolom `event_class_id` boleh nullable; sesuai desain.

3. **Tabel `bank_accounts` TIDAK ada.**
   - Tidak ada migration yang membuat tabel `bank_accounts`, padahal model `BankAccount`, `BankAccountsRelationManager`, dan `DaftarKontes` (rekening) menggunakannya → **table missing**.

4. **Relasi `Event::bankAccounts()` tidak didefinisikan.**
   - `app/Models/Event.php` hanya punya `classes, participants, winners, galleries, judges, testimonial, flyers, cps`. Tidak ada `bankAccounts()`. Padahal `DaftarKontes.php:46` (`$this->event->bankAccounts()`) dan `BankAccountsRelationManager` (`relationship='bankAccounts'`) membutuhkannya → **method undefined**.

5. **`event/show.blade.php` belum mengarah ke komponen Livewire baru.**
   - Tab "Data Peserta" masih menampilkan blok Google Sheets (`google_sheet_url`) / pesan "Belum ada peserta" (show.step line 180–198).
   - Belum ada `<livewire:daftar-peserta-publik :event="..."/>` maupun tombol `<livewire:daftar-kontes :event="..."/>` di bawah CP.

6. **`EventClass` `$fillable` belum punya `harga_tiket`.**
   - Model `EventClass.php:11` hanya `['event_id', 'nama_kelas']` → menyimpan `harga_tiket` di form Filament **tidak akan persist** (kolom sudah ada di migration tapi tidak fillable).

7. **`ParticipantsRelationManager` belum sesuai rencana (Verifikasi // Fishin/Fishout).**
   - Masih pakai field lama (`nama_peserta`, `no_urut`), belum ada action **ACC → LUNAS + auto no_urut**, **Reject**, **Lihat Bukti**, **toggle Fishin/Fishout**, filter status/kelas, maupun tab registrasi venue.

8. **Notifikasi WA/email ke peserta** belum diimplementasikan (catatan rencana poin 6).

---

## 7. Rencana Perbaikan (next steps untuk menyetempel sesuai plan)

1. Perluas `participants` (migration baru): `user_id`, `nama_ikan`, `jenis_ikan`, `kota_asal`, `alamat`, `status`(enum), `bukti_pembayaran`, `biaya`, `fishin`, `fishout`. (Hati-hati urutan `biaya` sebelum `dp_amount` jika DP dipakai.)
2. Buat migration `bank_accounts`.
3. Update `Participant` model: `$fillable` lengkap + konstanta `STATUS_*`.
4. Tambah relasi `bankAccounts()` di `Event`.
5. Tambah `harga_tiket` ke `$fillable` `EventClass`.
6. Wiring `event/show.blade.php` → `daftar-peserta-publik` (tab Data Peserta) + tombol `daftar-kontes`.
7. Upgrade `ParticipantsRelationManager` (Organizer): ACC→LUNAS + auto no_urut (transaction + `lockForUpdate`), Reject, Lihat Bukti, toggle Fishin/Fishout, filter status/kelas.
8. Integrasi peserta LUNAS → cetak sertifikat.
9. Jalankan `predeploy-check.ps1` / pint + test + build setelah perbaikan.

---

## 8. Checklist Skenario (diperbarui — status real)

- [x] Komponen Livewire `DaftarKontes` & `DaftarPesertaPublik` dibuat (belum terhubung penuh).
- [x] Kolom kelas organisasi + harga di panel penyelenggara (Filament) dibuat.
- [ ] Migration `bank_accounts` ruang (belum).
- [ ] Kolom peserta lengkap (nama_ikan, alamat, status, bukti, biaya, fishin, fishout) — belum.
- [ ] Model `Participant` update + konstanta STATUS — belum.
- [ ] Link `event/show` → Data Peserta publik + tombol daftar — belum.
- [ ] ACC → LUNAS + auto no_urut per kelas — belum.
- [ ] Admin toggle Fishin/Fishout hari event — belum.
- [ ] Cetak sertifikat per peserta LUNAS — belum.
- [ ] Lint + test (`pint --test`, `composer run-script test`) hijau — belum diverifikasi.

---

**Dokumen ini = rencana + kondisi real.** Bagian yang sudah dieksekusi ditandai `✅`, yang belum selaras ditandai `⚠️`/`❌`, dan **GAP pada Seksi 6 harus diperbaiki dulu** sebelum fitur pendaftaran benar-benar berfungsi.

---

## 9. Catatan / Pertanyaan untuk Pemilik (dipertahankan)

1. Kelas = bebas diisi penyelenggara (memperhatikan pola sheet). OK.
2. **Pembayaran** manual transfer + validasi (tanpa gateway). OK.
3. "1 form 1 gang" — ikan kedua = pendaftaran baru (baris baru). OK.
4. **No HP di publik** = perlu izin data (privacy). Tetap tampil sesuai template Anda.
5. **Fishin/Fishout** hanya admin? blade publik menampilkan status tapi togglenya di admin — sesuai rencana.
6. **Notifikasi ke peserta**: WA (lanjutan) — belum diimplementasi.