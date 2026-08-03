# LAPORAN KERJA — Sistem Pendaftaran Peserta Kontes (Peserta Daftar)

- **Terakhir diperbarui:** 2026-08-03
- **Fokus:** Pendaftaran peserta publik + kelola peserta di panel penyelenggara (Filament)
- **Aplikasi:** indonesiachannacontest.org (Laravel 12 + Filament v4 + Livewire 3 + Tailwind v4)
- **Acuan template:** Google Sheets "Data Peserta Kutuk Meresahkan #5"

> Dokumen ini = **peta kondisi project saat ini**. Sebelum mengembangkan fitur baru, baca dokumen ini agar langsung tahu sampai mana proses berjalan, file mana yang terlibat, dan apa saja kendala yang sudah ditemukan.

---

## 1. STATUS KESELURUHAN

- Sistem pendaftaran peserta **fungsional end-to-end**: pendaftaran publik (multi-ikan) → bukti transfer → verifikasi admin → LUNAS → tampil publik → toggle fishin/fishout → sertifikat.
- Panel penyelenggara tab "Data Peserta" lengkap: **statistik ringkas (dashboard-style)** + tabel + aksi (Edit, Catat DP, Terima/Lunas, Tolak, Generate Sertifikat, Lihat Bukti, Hapus).
- Data peserta di DB saat ini **kosong** (sengaja dihapus saat penyelarasan aturan status Booking).

---

## 2. ALUR YANG BERJALAN SAAT INI

### Publik — Pendaftaran (Livewire `DaftarKontes`)
1. Pengunjung klik **"Daftar Kontes"** pada halaman detail event (`event/show.blade.php:59` → `<livewire:daftar-kontes :event="$event" />`).
2. Pop-up 2 langkah:
   - **Step 1 — Data Diri:** Nama Pemilik, Team/SF, Kota Asal, No WhatsApp + daftar **ikan yang didaftarkan** (bisa >1; tiap baris = pilih Kelas Ikan + Nama Ikan; tombol "+ Tambah Ikan Lainnya" & "Hapus").
   - **Step 2 — Pembayaran:** kartu **Detail Pendaftaran** (per ikan: nama ikan + nama kelas + harga), **Total Bayar** (jumlah harga semua ikan), daftar rekening bank, upload **bukti transfer**, tombol Kirim.
3. Saat submit: **1 baris `Participant` per ikan** dibuat dalam 1 transaksi (`DB::transaction` + `lockForUpdate`), masing-masing:
   - `status = menunggu_verifikasi`
   - `no_urut` otomatis = `max(no_urut)` di kelas tsb + 1 (**berbasis per kelas**)
   - `biaya` = harga kelas tsb
   - `bukti_pembayaran` = path file yang sama untuk semua ikan
   - `nama_peserta` ikut diisi = `nama_pemilik`

### Publik — Tab "Data Peserta" (`DaftarPesertaPublik`)
- Dipasang di `event/show.blade.php:187` → `<livewire:daftar-peserta-publik :event="$event" />`.
- Menampilkan **semua peserta kecuali status `rejected`**, dikelompokkan per kelas.
- **Booking** (badge kuning) = semua yang belum `lunas`; **Lunas** (badge hijau) = status `lunas`.
- Jika `dp_amount > 0`, muncul teks kecil **"DP: Rp X.XXX"** di bawah badge.
- Kolom "Alamat" menampilkan **`kota_asal`** (bukan `alamat`).
- Urutan: peserta belum lunas dulu (urut `no_urut`), lalu peserta lunas (urut `no_urut`).
- Kolom: No (no_urut), Nama, Alamat(kota_asal), Nama Ikan, Team, No HP, Keterangan(status+DP), Fishin, Fishout.

### Admin / Panel Penyelenggara — Tab "Peserta" (`ParticipantsRelationManager`)
- **Di atas tabel (statistik, gaya dashboard):** `ParticipantStatsOverview` (StatsOverviewWidget) — kartu **Total Peserta**, **Total Ikan**, **Team / Single Fighter**, + 1 kartu per kelas kontes berisi jumlahnya. Data diisi via `event_id`.
- **Tabel:** ID, No Urut, Nama, Kelas, Team/SF, Nama Ikan, No HP, DP (Rp), Status (badge berwarna), Fishin/Fishout (toggle inline), filter status & kelas.
- **Aksi per baris:**
  - **Edit** (pencil) — ubah semua data termasuk **upload/ubah bukti transfer** (FileUpload).
  - **Generate Sertifikat** — hanya untuk `lunas`; jika sudah ada sertifikat, peringatan.
  - **Catat DP** — input nominal DP; **hanya menyimpan `dp_amount`, TIDAK mengubah status**.
  - **Terima / Lunas** — verifikasi: status jadi `lunas` (no_urut dipertahankan dari pendaftaran).
  - **Tolak** — status `rejected`.
  - **Lihat Bukti** — modal preview bukti transfer (`participant-bukti.blade.php`).
  - **Hapus** (trash) — dengan konfirmasi.
- **Aksi header:** Tambah Peserta (manual) + Generate Sertifikat Massal (LUNAS yang belum punya sertifikat).

---

## 3. FILE-FILE KUNCI

| File | Peran |
|---|---|
| `app/Livewire/DaftarKontes.php` | Komponen Livewire pendaftaran publik (multi-ikan, DP/bukti). |
| `resources/views/livewire/daftar-kontes.blade.php` | Blade pop-up 2 langkah pendaftaran. |
| `app/Livewire/DaftarPesertaPublik.php` | Komponen Livewire tab Data Peserta publik. |
| `resources/views/livewire/daftar-peserta-publik.blade.php` | Blade tabel Data Peserta publik (Booking/Lunas + DP). |
| `app/Filament/Organizer/Resources/EventResource/RelationManagers/ParticipantsRelationManager.php` | Relation manager Peserta di panel penyelenggara. |
| `app/Filament/Organizer/Widgets/ParticipantStatsOverview.php` | Widget statistik ringkas di atas tabel peserta. |
| `app/Filament/Organizer/Resources/EventResource/RelationManagers/EventClassesRelationManager.php` | Kelola kelas kontes (nama_kelas + harga_tiket). |
| `app/Filament/Organizer/Resources/EventResource/RelationManagers/BankAccountsRelationManager.php` | Kelola rekening bank pembayaran. |
| `app/Models/Participant.php` | Model peserta (statuses(), fillable lengkap, relasi class/user/certificate). |
| `app/Models/EventClass.php` | Model kelas kontes (harga_tiket). |
| `app/Models/BankAccount.php` | Model rekening bank. |
| `app/Jobs/GenerateParticipantCertificateJob.php` | Job generate sertifikat per peserta. |
| `resources/views/filament/organizer/participant-bukti.blade.php` | Modal lihat bukti transfer. |
| `app/Filament/Organizer/Resources/EventResource.php` | Daftar relation manager (`ParticipantsRelationManager` & `WinnersRelationManager` terdaftar). |

---

## 4. SKEMA / STATUS DATA

Migration yang sudah jalan (`php artisan migrate:status` → semua Ran):
- `add_harga_tiket_to_event_classes_table` → `event_classes.harga_tiket`
- `add_columns_to_participants_table` → `nama_pemilik`, `team_sf`, `no_hp`
- `add_dp_amount_to_participants_table` → `participants.dp_amount`
- `add_participant_registration_columns_and_bank_accounts_table` → kolom `user_id`, `nama_pemilik`, `team_sf`, `nama_ikan`, `jenis_ikan`, `kota_asal`, `alamat`, `no_hp`, `status`, `bukti_pembayaran`, `biaya`, `fishin`, `fishout` + tabel `bank_accounts`
- `add_participant_certificates` → `certificates.participant_id`
- `make_nama_peserta_nullable`

Konstanta status `Participant`: `menunggu_bayar`, `menunggu_verifikasi`, `lunas`, `rejected`.

---

## 5. PERATURAN / ATURAN BISNIS (hasil konfirmasi pemilik)

1. **Publik menampilkan peserta yang belum diverifikasi** dengan status otomatis **"Booking"**; yang sudah diverifikasi = **"Lunas"**.
2. **Kolom Alamat publik diambil dari `kota_asal`**.
3. **`no_urut` otomatis saat mendaftar** (bukan saat diverifikasi), berdasarkan urutan data yang sudah ada, **per kelas**.
4. **DP** dicatat oleh admin (aksi "Catat DP"), **tidak mengubah status** peserta, dan nominal DP tampil di publik + kolom DP (Rp) di admin.
5. **Satu pendaftaran bisa berisi lebih dari 1 ikan** (multi-kelas). Tiap ikan = 1 baris peserta, tapi `no_urut` per kelas.
6. Peserta dengan status `rejected` **tidak ditampilkan** di publik.

---

## 6. KENDALA / GOTCHA YANG SUDAH DITEMUKAN (PENTING!)

1. **Namespace Filament v4 berbeda dari v3:**
   - Gunakan `use Filament\Actions\Action;`, `CreateAction`, `EditAction`, `DeleteAction`, `BulkAction` — **JANGAN** `Filament\Tables\Actions\*` (kelasnya tidak ada di versi ini → `Class not found`).
   - Metode tabel = **`->recordActions([...])`** (bukan `->rowActions([...])` yang lama).
   - Modal width = `Filament\Support\Enums\Width` (`Width::Large`), **bukan** `Filament\Support\Enums\MaxWidth` (tidak ada).
2. **Menambah HTML di atas tabel relation manager:** jangan pakai `render()` override + `@include` (tabel hilang / `$component` undefined). Pakai override **`content(Schema $schema)`** dan sisipkan komponen sebelum `EmbeddedTable::make()`:
   - Statistik = `Livewire::make(ParticipantStatsOverview::class, ['event_id' => $this->getOwnerRecord()?->id])`.
3. **Ikon heroicons harus valid:** `heroicon-o-receipt-stack` **tidak ada** (error `SvgNotFound` bikin halaman gagal render). Yang valid contoh: `o-rectangle-stack`, `o-user-group`, `o-squares-2x2`, `o-trophy`, `o-banknotes`. Cek di `vendor/blade-ui-kit/blade-heroicons/resources/svg/o-*.svg`.
4. **CSS Tailwind v4 auto-content:** jika ada class baru tidak muncul, jalankan `npm run build` (public CSS di-build ulang). Contoh kasus: `bg-slate-800` tidak ter-generate → teks putih tak terlihat.
5. **`view:clear`** perlu dijalankan jika ada perubahan blade yang error tersimpan di kompilasi view cache (`storage/framework/views`).
6. **Tinker via PowerShell** rawan: quotes `"` terpotong → tulis script ke file temp lalu `Get-Content ... | php artisan tinker`.

---

## 7. IDE FITUR LANJUTAN (belum dikerjakan — untuk dikembangkan)

- [ ] **Notifikasi ke peserta** (WA/email) saat status berubah (menunggu verifikasi → Lunas / Ditolak).
- [ ] **Pilihan DP saat pendaftaran publik** (saat ini DP hanya dicatat admin; bisa tambah pilihan "Bayar DP" di form publik yang mengisi `dp_amount` dan `status` menjadi `menunggu_bayar`).
- [ ] **DP per pendaftaran** (saat ini `dp_amount` per baris/ikan; kalau mau sekali untuk semua ikan, perlu desain ulang).
- [ ] Integrasi pembayaran otomatis (gateway) — rencana `rencana midtrans.md` belum diterapkan.
- [ ] Pengecekan otomatis `harga_tiket` kosong → fallback (0) di form publik.
- [ ] Kolom "Jenis Ikan" (`jenis_ikan`) belum dipakai di form publik/admin.
- [ ] Predeploy: `./vendor/bin/pint --test`, `composer run-script test`, `npm run build`.

---

## 8. PERINTAH VERIFIKASI CEPAT

```bash
./vendor/bin/pint --test                       # style lint
composer run-script test                        # PHPUnit (17 test: Login, PublicPages, dsb.)
php artisan migrate:status                      # cek migration sudah Ran
npm run build                                   # build CSS/JS Tailwind + Vite
php artisan view:clear                          # bersihkan cache view bila blade error
```

> Catatan: tes yang ada saat ini hanya mencakup halaman publik & login. Belum ada tes khusus alur pendaftaran peserta / relation manager.
