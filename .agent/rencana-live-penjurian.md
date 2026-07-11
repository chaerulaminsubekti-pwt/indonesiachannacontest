# Rencana Fitur Live Penjurian

## Deskripsi
Panel khusus Juri untuk input nilai peserta secara real-time per event. Hasil langsung tampil di leaderboard dan bisa difinalisasi oleh penyelenggara.

---

## 1. Database

### Tabel Baru

```sql
-- Tabel kelas peserta
CREATE TABLE classes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT NOT NULL,
    nama_kelas VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel peserta
CREATE TABLE participants (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT NOT NULL,
    class_id BIGINT NOT NULL,
    nama VARCHAR(255) NOT NULL,
    no_peserta VARCHAR(50) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel kriteria penilaian (per event/kelas)
CREATE TABLE judging_criteria (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT NOT NULL,
    nama_kriteria VARCHAR(255) NOT NULL,
    bobot DECIMAL(5,2) DEFAULT 1.00,
    max_nilai INT DEFAULT 100,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel penugasan juri ke kelas
CREATE TABLE judge_assignments (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    event_id BIGINT NOT NULL,
    class_id BIGINT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel nilai
CREATE TABLE scores (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    judge_assignment_id BIGINT NOT NULL,
    participant_id BIGINT NOT NULL,
    criteria_id BIGINT NOT NULL,
    nilai DECIMAL(8,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE (judge_assignment_id, participant_id, criteria_id)
);
```

### Modifikasi Tabel Event
```sql
ALTER TABLE events ADD COLUMN penjurian_status ENUM('draft','berjalan','selesai') DEFAULT 'draft';
```

### Models
- `models/Class.php` — relasi ke event
- `models/Participant.php` — relasi ke event & class
- `models/JudgingCriteria.php` — relasi ke event
- `models/JudgeAssignment.php` — relasi ke user, event, class
- `models/Score.php` — relasi ke assignment, participant, criteria

---

## 2. Panel / Halaman

### A. Organizer Panel (Pengaturan Penjurian)

| Halaman | Fungsi |
|---------|--------|
| Kelola Kelas | CRUD kelas peserta per event |
| Kelola Peserta | CRUD peserta + assign ke kelas |
| Kelola Kriteria | CRUD kriteria + bobot per event |
| Assign Juri | Pilih juri mana yg menilai kelas mana |
| Live Leaderboard | Lihat nilai real-time semua peserta |
| Finalisasi | Kunci penilaian → otomatis generate juara & sertifikat |

### B. Judge Panel (Input Nilai)

- Login khusus Juri (username/password dari user yang di-create admin)
- Tampilkan daftar kelas yang di-assign
- Per kelas: daftar peserta + form input nilai per kriteria
- Submit otomatis via AJAX / real-time

### C. Public View (Proyektor/Display)

- Halaman `/event/{slug}/leaderboard` — live score sorted
- Auto-refresh setiap 5 detik (polling) atau via WebSocket (Reverb)

---

## 3. Algoritma Perhitungan

```
Total Nilai Peserta = Σ (Nilai_Kriteria × Bobot_Kriteria)
Peringkat = sort by Total Nilai DESC

Jika ada multiple judge:
  Rata-rata = Σ Nilai_Judge / Jumlah_Judge
```

---

## 4. Real-time (Opsional — untuk leaderboard live)

**Menggunakan polling dulu (sederhana):**
- Halaman leaderboard refresh via `wire:poll.5s` (Livewire) atau `setInterval` JS tiap 5 detik

**Nanti upgrade ke WebSocket (Laravel Reverb):**
- Install `laravel/reverb`
- Broadcast event `ScoreUpdated` → push ke channel `leaderboard.{eventId}`
- Frontend listen pake `pusher-js` atau `laravel-echo`

---

## 5. Alur Lengkap

```
Admin/Organizer:
  1. Buat event → set status = disetujui
  2. Buka menu Penjurian → Kelola Kelas → tambah kelas
  3. Kelola Peserta → tambah peserta & assign kelas
  4. Kelola Kriteria → tentukan kriteria penilaian
  5. Assign Juri → pilih user juri untuk setiap kelas
  6. Buka penjurian → status penjurian = 'berjalan'

Juri (login ke panel juri):
  7. Lihat daftar kelas yang di-assign
  8. Klik kelas → lihat daftar peserta
  9. Input nilai per kriteria untuk setiap peserta
  10. Nilai otomatis tersimpan & kalkulasi ulang

Organizer (pantau):
  11. Buka Live Leaderboard → lihat peringkat real-time
  12. Jika semua nilai sudah masuk → klik Finalisasi
  13. Sistem generate juara & sertifikat otomatis

Public:
  14. Halaman leaderboard bisa ditampilkan di proyektor
```

---

## 6. Struktur File

```
app/
├── Filament/
│   └── Judge/                          # Panel Khusus Juri (PanelProvider baru)
│       ├── JudgePanelProvider.php
│       └── Pages/
│           ├── Dashboard.php
│           └── InputNilai.php
├── Filament/Organizer/
│   └── Resources/
│       └── EventResource/
│           └── RelationManagers/
│               ├── ClassesRelationManager.php
│               ├── ParticipantsRelationManager.php
│               ├── JudgingCriteriaRelationManager.php
│               └── JudgeAssignmentsRelationManager.php
├── Http/Livewire/
│   ├── JudgeScoreTable.php             # Form input nilai real-time
│   └── PublicLeaderboard.php           # Tampilan leaderboard publik
├── Models/
│   ├── Kelas.php
│   ├── Participant.php
│   ├── JudgingCriteria.php
│   ├── JudgeAssignment.php
│   └── Score.php
└── Services/
    └── ScoringService.php              # Hitung total & peringkat

resources/views/
├── livewire/
│   ├── judge-score-table.blade.php
│   └── public-leaderboard.blade.php
└── event/
    └── leaderboard.blade.php

routes/
└── web.php                             # Tambah route leaderboard publik
```

---

## 7. Tugas & Estimasi

| # | Task | Jam |
|---|------|-----|
| 1 | Buat migration + model (5 tabel) | 1 |
| 2 | Panel Organizer: Kelola Kelas, Peserta, Kriteria | 3 |
| 3 | Panel Organizer: Assign Juri | 1 |
| 4 | Panel Juri: JudgePanelProvider + auth middleware | 1 |
| 5 | Panel Juri: Dashboard + Input Nilai (Livewire) | 4 |
| 6 | Livewire leaderboard publik (polling) | 2 |
| 7 | Finalisasi + generate juara otomatis | 2 |
| 8 | ScoringService + perhitungan | 1 |
| 9 | Testing & debugging | 2 |
| **Total** | | **±17 jam** |
