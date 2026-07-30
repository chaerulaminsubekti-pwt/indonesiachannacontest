# Rencana Pendaftaran Peserta

## Alur Lengkap

```
                        ┌──────────────────┐
                        │  Event Tersedia   │
                        │  (Dashboard)      │
                        └────────┬─────────┘
                                 │ klik "Daftar"
                                 ▼
                        ┌──────────────────┐
                        │  Form Pendaftaran │
                        │  (1 form utuh)    │
                        │  - Nama, Alamat,  │
                        │    No WA          │
                        │  - Data Ikan      │
                        │    (multi-entry)  │
                        └────────┬─────────┘
                                 │ submit
                                 ▼
                        ┌──────────────────┐
                        │  Review + Bayar   │
                        │  - Detail order   │
                        │  - Total biaya    │
                        │  - Tombol Bayar   │
                        │    (Midtrans Snap)│
                        └────────┬─────────┘
                                 │ bayar sukses
                                 ▼
                        ┌──────────────────┐
                        │  Redirect ke      │
                        │  Riwayat / Sukses │
                        └────────┬─────────┘
                                 │ callback dari
                                 │ Midtrans
                                 ▼
                        ┌──────────────────┐
                        │  Status jadi      │
                        │  "lunas"          │
                        └──────────────────┘
```

## Komponen yang Diperlukan

### 1. Model Baru
- **`RegistrationOrder`** — mengelompokkan pendaftaran (1 peserta bisa daftar beberapa kelas/ikan dalam 1 event)
  - Fields: `user_id`, `event_id`, `order_id` (ICC-XXXX), `total_biaya`, `status` (pending/paid/cancelled), `midtrans_response` (JSON), `paid_at`

### 2. Modifikasi Model Existing
- **`Participant`** — tambah: `registration_order_id`, `biaya`
- **`EventClass`** — tambah: `biaya_pendaftaran` (decimal)

### 3. Panel Peserta (Filament)
- **Panel ID**: `peserta`, path: `/peserta`
- **Middleware**: `EnsureUserIsPeserta` — cek `role === 'peserta'`
- **Pages**:
  - `Dashboard` — daftar event approved/berjalan (pakai `x-event-card` yang dimodifikasi + tombol Daftar)
  - `RiwayatPendaftaran` — tabel order history
  - `ReviewPembayaran` — detail order + Snap button

### 4. Livewire Component
- **`DaftarPeserta`** — form registrasi publik (untuk user baru), auto-create akun + login
- **`Peserta\PendaftaranEvent`** — form daftar event untuk peserta yang sudah login (1 form: data peserta + data ikan multi-entry)

### 5. Payment (Midtrans)
- **Controller**: `PaymentController` — `snapToken()`, `success()`, `callback()`
- **Config**: `config/midtrans.php`
- **Route**: `POST /api/midtrans/callback` (tanpa CSRF)

### 6. Routing
| Route | Fungsi |
|-------|--------|
| `/daftar-peserta` | Registrasi publik |
| `peserta/daftar/{event}` | Form pendaftaran (login required) |
| `peserta/review/{order}` | Review + bayar |
| `peserta/riwayat` | Riwayat order |
| `POST /api/midtrans/callback` | Webhook Midtrans |

## Kartu Event di Dashboard Peserta

`x-event-card` perlu dimodifikasi agar bisa dipakai di dua konteks:
- **Publik**: link ke `event.show` (seperti sekarang)
- **Peserta**: link ke `event.show` + tombol "Daftar" di bagian bawah

Cara: tambahkan prop `showDaftar` (boolean) di event-card.blade.php. Jika true, render tombol Daftar di dalam card dan ubah onclick pakai `event.stopPropagation()`.

## Step Implementasi

1. Database: migration `biaya_pendaftaran` di event_classes
2. Database: migration `registration_orders` table
3. Database: migration expand participants (add `registration_order_id`, `biaya`)
4. Model: `RegistrationOrder`, update `EventClass`, `Participant`
5. Model: `User.canAccessPanel()` tambah peserta
6. Middleware: `EnsureUserIsPeserta`
7. Provider: `PesertaPanelProvider`
8. Admin: `EventClassesRelationManager` field biaya_pendaftaran
9. Modifikasi `x-event-card` — tambah prop `showDaftar`
10. Filament: `Dashboard` page (daftar event + tombol Daftar)
11. Livewire: `DaftarPeserta` (registrasi publik)
12. Livewire: `Peserta\PendaftaranEvent` (form daftar)
13. Filament: `ReviewPembayaran` page (Snap)
14. Filament: `RiwayatPendaftaran` page (tabel)
15. Routes: web + midtrans callback
16. Controller: `PaymentController`
17. Config: `midtrans.php`
18. `.env`: Midtrans keys
19. Layout: livewire layout `layouts/app.blade.php`
