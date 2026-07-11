# Rencana Fitur Pendaftaran Peserta Event

## 1. Alur Sistem

```
[Registrasi Akun Peserta] 
        ↓ (auto active)
[Login Panel Peserta]
        ↓
[Lihat Daftar Event Available]
        ↓
[Klik Event → Isi Form Pendaftaran]
        ↓
[Tambah Multiple Ikan per Ajuan]
        ↓
[Simpan → Lihat Ringkasan + Total Biaya]
        ↓
[Bayar via Midtrans]
        ↓
[Pembayaran Diterima → Data Tampil di Halaman Public]
```

---

## 2. Database Changes

### 2.1. `users` — Tambah Role Peserta
Di migration `add_role_peserta_to_users_table.php`:
- Tidak perlu ubah kolom, role 'peserta' sudah bisa disimpan di kolom `role` yg sudah ada (string)
- Value baru: `'peserta'`

### 2.2. `participants` — Expand Kolom
Migration `expand_participants_for_registration_table.php`:
```php
Schema::table('participants', function (Blueprint $table) {
    $table->foreignId('user_id')->nullable()->after('id')
        ->constrained()->cascadeOnDelete();
    $table->string('nama_ikan')->nullable()->after('nama_peserta');
    $table->string('nama_team')->nullable()->after('nama_ikan');
    $table->text('alamat')->nullable()->after('nama_team');
    $table->string('no_wa')->nullable()->after('alamat');
    $table->string('status')->default('pending')->after('no_wa');
    // pending | paid | cancelled
    $table->decimal('biaya', 15, 2)->nullable()->after('status');
    $table->string('order_id')->nullable()->unique()->after('biaya');
    // Midtrans transaction ID
    $table->string('transaction_id')->nullable()->after('order_id');
    $table->timestamp('paid_at')->nullable()->after('transaction_id');
});
```

### 2.3. `event_classes` — Tambah Harga
Migration `add_biaya_to_event_classes_table.php`:
```php
Schema::table('event_classes', function (Blueprint $table) {
    $table->decimal('biaya_pendaftaran', 15, 2)->default(0)->after('nama_kelas');
});
```

### 2.4. New Table — `registration_orders` (Pengelompokan 1 Ajuan)
Migration `create_registration_orders_table.php`:
```php
Schema::create('registration_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->string('order_id')->unique(); // untuk Midtrans
    $table->decimal('total_biaya', 15, 2)->default(0);
    $table->string('status')->default('pending');
    // pending | settlement | expired | cancel
    $table->string('snap_token')->nullable(); // Midtrans Snap token
    $table->text('midtrans_response')->nullable(); // Raw response
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});
```

Migration `add_order_id_to_participants_table.php`:
```php
Schema::table('participants', function (Blueprint $table) {
    $table->foreignId('registration_order_id')->nullable()
        ->constrained('registration_orders')->cascadeOnDelete();
});
```

---

## 3. Model Changes

### 3.1. `User.php`
```php
public function canAccessPanel(Panel $panel): bool
{
    return match ($panel->getId()) {
        'admin' => $this->hasAnyRole(['super_admin', 'editor']),
        'panel' => $this->role === 'penyelenggara',
        'peserta' => $this->role === 'peserta',
        default => false,
    };
}
```

### 3.2. `EventClass.php`
Tambah `$fillable`: `'biaya_pendaftaran'`

### 3.3. `Participant.php`
```php
protected $fillable = [
    'user_id', 'event_id', 'event_class_id', 'registration_order_id',
    'nama_peserta', 'nama_ikan', 'nama_team', 'alamat', 'no_wa', 'no_urut',
    'status', 'biaya', 'order_id', 'transaction_id', 'paid_at',
];

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function order(): BelongsTo
{
    return $this->belongsTo(RegistrationOrder::class, 'registration_order_id');
}
```

### 3.4. — `RegistrationOrder.php` (Model Baru)
```php
class RegistrationOrder extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'order_id', 'total_biaya',
        'status', 'snap_token', 'midtrans_response', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'midtrans_response' => 'json',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'registration_order_id');
    }
}
```

---

## 4. Organizer Panel Changes

### 4.1. `EventResource` — Tambah Harga per Kelas
Di `EventClassesRelationManager` (ditambahkan ke organizer panel), form tambah field:
```php
TextInput::make('biaya_pendaftaran')
    ->label('Biaya Pendaftaran (Rp)')
    ->numeric()
    ->prefix('Rp')
    ->default(0)
```

### 4.2. EventResource — Aktifkan `ParticipantsRelationManager`
Di organizer's `EventResource.getRelations()`, tambah:
```php
ParticipantsRelationManager::class,
```
Dengan kolom: nama_peserta, kelas, status_pembayaran, dll.

---

## 5. Participant Panel (Filament Panel Baru)

### 5.1. Panel Provider — `PesertaPanelProvider.php`
```php
class PesertaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('peserta')
            ->path('peserta')
            ->homeUrl('/peserta')
            ->brandName('Peserta ICC')
            ->favicon($favicon)
            ->colors(['primary' => Color::Blue])
            ->discoverResources(...)
            ->discoverPages(...)
            ->middleware([...])
            ->authMiddleware([Authenticate::class]);
    }
}
```

### 5.2. Halaman Dashboard — Daftar Event Available
Halaman utama setelah login, menampilkan grid event yang:
- `status` = `approved` || `berjalan`
- `tanggal_selesai` >= today
- Belum memiliki participant untuk user yg login

Tombol **"Daftar"** → ke halaman form pendaftaran.

### 5.3. Halaman Form Pendaftaran
Route: `/peserta/events/{event}/register`

Form:
1. Event info (readonly, header)
2. **Multiple Entry Form:**
   - Nama Peserta (text)
   - Kelas Ikan (select dari event_classes, dengan harga)
   - Nama Ikan (text)
   - Nama Team / Single Fighter (text)
   - Alamat (textarea)
   - No. WhatsApp (tel)
3. Tombol **"Tambah Ikan +"** untuk entry tambahan
4. Tombol **"Simpan & Lanjutkan ke Pembayaran"**

### 5.4. Halaman Review & Pembayaran
Route: `/peserta/events/{event}/review`

Setelah saved:
- Tampilkan tabel semua participant yg diinput
- Total biaya = sum(biaya_pendaftaran dari setiap kelas)
- Tombol **"Bayar Sekarang Rp X.XXX"**
- Memanggil Midtrans Snap popup

### 5.5. Halaman Riwayat Pendaftaran
Route: `/peserta/registrations`

Tabel semua order / pendaftaran milik user:
- Event name
- Jumlah ikan
- Total biaya
- Status (Pending | Settlement | Expired)
- Tombol detail

---

## 6. Public Page — Data Peserta Terdaftar

### 6.1. Tampilan di Halaman Detail Event (`event/show.blade.php`)
Di tab (misal tab ke-4 "Peserta Terdaftar"):
```php
@if ($event->participants()->where('status', 'paid')->count())
    @foreach ($event->classes as $class)
        <h3>{{ $class->nama_kelas }}</h3>
        <ul>
            @foreach ($class->participants()->where('status', 'paid')->get() as $p)
                <li>{{ $p->nama_peserta }} - {{ $p->nama_team }} - {{ $p->nama_ikan }}</li>
            @endforeach
        </ul>
    @endforeach
@endif
```

---

## 7. Registration & Account Flow

### 7.1. Registrasi Akun Peserta
Halaman public: `/daftar-peserta`

Form:
- Nama Lengkap
- Email
- Username
- Password + Confirm
- No. WhatsApp

Submit → Create User dengan:
- `role = 'peserta'`
- `status = 'active'` (langsung aktif)
- Auto login → redirect ke `/peserta`

### 7.2. Login
Login yang sudah ada di `/login`:
- Setelah login, cek role:
  - `super_admin`/`editor` → `/admin`
  - `penyelenggara` → `/panel`
  - `peserta` → `/peserta`

Di `LoginController`:
```php
return match ($user->role) {
    'peserta' => redirect()->route('peserta.dashboard'),
    'penyelenggara' => redirect('/panel'),
    default => redirect('/admin'),
};
```

---

## 8. Midtrans Integration

### 8.1. Composer
```bash
composer require midtrans/midtrans-php
```
Atau gunakan package: `sbahara/laravel-midtrans`

### 8.2. Config
`.env`:
```
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

### 8.3. Payment Flow
1. User klik "Bayar Sekarang"
2. Backend generate `order_id` (unique)
3. Backend hit Midtrans Snap API → dapat `snap_token`
4. Frontend tampilkan Snap popup
5. Midtrans callback (notification handler):
   - POST `/api/midtrans/callback`
   - Update `registration_orders.status` + `participants.status`
   - Update `paid_at`

### 8.4. Notification Handler
```php
Route::post('api/midtrans/callback', [MidtransController::class, 'callback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

---

## 9. Step-by-Step Implementasi

| Step | Task | Files |
|------|------|-------|
| 1 | Migration: add `biaya_pendaftaran` to `event_classes` | `database/migrations/` |
| 2 | Migration: create `registration_orders` table | `database/migrations/` |
| 3 | Migration: expand `participants` (new columns) | `database/migrations/` |
| 4 | Model: Update `EventClass`, `Participant`, buat `RegistrationOrder` | `app/Models/` |
| 5 | Middleware: `EnsureUserIsPeserta` | `app/Http/Middleware/` |
| 6 | Panel: `PesertaPanelProvider` + register di `config/app.php` | `app/Providers/Filament/` |
| 7 | Organizer: form `biaya_pendaftaran` di EventClassesRM | `app/Filament/Organizer/` |
| 8 | Public: Halaman registrasi akun peserta (Livewire) | `app/Livewire/` + view |
| 9 | Update LoginController untuk role peserta | `app/Http/Controllers/Auth/` |
| 10 | Halaman dashboard peserta (daftar event) | Filament page |
| 11 | Halaman form pendaftaran event (multi-entry) | Filament page / Livewire |
| 12 | Halaman review & pembayaran | Filament page / Livewire |
| 13 | Halaman riwayat pendaftaran | Filament page |
| 14 | Install Midtrans package + config | `composer.json`, `.env` |
| 15 | Midtrans controller + callback handler | `app/Http/Controllers/` |
| 16 | Update event show page: tab peserta terdaftar | `resources/views/event/show.blade.php` |

---

## 10. Diagram Database (Final)

```
users
  id (PK)
  name, email, username, password
  role (peserta | penyelenggara | super_admin | editor)
  status (active | inactive) ← peserta langsung active

registration_orders (baru)
  id (PK)
  user_id (FK → users)
  event_id (FK → events)
  order_id (unique) ← Midtrans
  total_biaya (decimal)
  status (pending | settlement | expired | cancel)
  snap_token
  midtrans_response (json)
  paid_at

participants (di-expand)
  id (PK)
  user_id (FK → users)
  event_id (FK → events)
  event_class_id (FK → event_classes)
  registration_order_id (FK → registration_orders) ← baru
  nama_peserta
  nama_ikan ← baru
  nama_team ← baru
  alamat ← baru
  no_wa ← baru
  no_urut
  status (pending | paid | cancelled) ← baru
  biaya ← baru
  order_id ← baru
  transaction_id ← baru
  paid_at ← baru

event_classes
  id (PK)
  event_id (FK → events)
  nama_kelas
  biaya_pendaftaran (decimal, default 0) ← baru
```
