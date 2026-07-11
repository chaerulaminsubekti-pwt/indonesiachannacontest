# Rencana Integrasi Midtrans (Sandbox) untuk Pembayaran Pengajuan Event

## Persiapan
- [ ] Buat akun Midtrans (dashboard.midtrans.com) → ambil Server Key & Client Key (sandbox)
- [ ] Tambah env: `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION=false`

## Backend
- [ ] Install package: `composer require midtrans/midtrans-php`
- [ ] Buat migration tabel `payments` (id, order_id, event_id, user_id, gross_amount, status, transaction_id, payment_type, paid_at, created_at, updated_at)
- [ ] Buat model `Payment`
- [ ] Buat service class `MidtransService` (create transaction, handle notification)
- [ ] Buat controller/rute notifikasi webhook (Midtrans → POST ke `/api/payment/notification`)
- [ ] Update flow pengajuan: setelah submit form, redirect ke halaman checkout

## Frontend (User)
- [ ] Halaman checkout (ringkasan event + total biaya + tombol bayar)
- [ ] Integrasi Midtrans Snap (pop-up) — user pilih metode pembayaran
- [ ] Halaman sukses/gagal setelah pembayaran

## Admin
- [ ] Tampilkan status pembayaran di detail event (Lunas / Belum / Batal)
- [ ] Filter event by status pembayaran

## Flow
1. User isi form pengajuan event → submit
2. Event tersimpan dgn status `pending_payment`
3. Redirect ke halaman checkout
4. User klik Bayar → Snap pop-up muncul
5. User pilih metode & selesaikan pembayaran
6. Midtrans kirim notifikasi ke webhook server
7. Webhook update status payment & ubah event jadi `pending` / `draft` jika gagal
8. Admin bisa lihat status pembayaran
