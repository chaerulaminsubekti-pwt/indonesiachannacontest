# =====================================================
# CHECKLIST LOKAL — Wajib dijalankan SEBELUM deploy ke production
# Lokasi: C:\xampp1\htdocs\Portal-ICC\tools\predeploy-check.ps1
# Cara pakai: powershell -ExecutionPolicy Bypass -File tools\predeploy-check.ps1
#
# Sesuai kebijakan: semua perubahan HARUS lolos cek ini dulu,
# baru diizinkan di-eksekusi ke website online.
# =====================================================
$ErrorActionPreference = "Stop"
Set-Location "C:\xampp1\htdocs\Portal-ICC"

$WARN = @()
$FAIL = @()

Write-Host ""
Write-Host "==============================================" -ForegroundColor Cyan
Write-Host "  PRE-DEPLOY CHECK — INDONESIACHANNACONTEST" -ForegroundColor Cyan
Write-Host "==============================================" -ForegroundColor Cyan

# ---------- 1. Cek Git working tree ----------
Write-Host "`n[1/5] Cek status Git..." -ForegroundColor Yellow
$status = git status --porcelain
if ($status) {
    $WARN += "Ada perubahan belum di-commit (lihat di bawah). Pastikan sudah direview."
    Write-Host "  -> Ada perubahan yang BELUM di-commit:" -ForegroundColor Yellow
    $status | ForEach-Object { Write-Host "     $_" -ForegroundColor Yellow }
} else {
    Write-Host "  -> Working tree bersih." -ForegroundColor Green
}

# ---------- 2. Lint (Pint) ----------
Write-Host "`n[2/5] Lint kode dengan Laravel Pint..." -ForegroundColor Yellow
if (Test-Path ".\vendor\bin\pint.bat") {
    & ".\vendor\bin\pint.bat" --test
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  -> Pint lolos (code style sesuai)." -ForegroundColor Green
    } else {
        $FAIL += "Pint gagal: ada perbaikan style yang diperlukan."
        Write-Host "  -> Pint MENGELUARKAN error / butuh perbaikan." -ForegroundColor Red
    }
} else {
    $WARN += "pint.bat tidak ditemukan. Kemungkinan vendor tidak lengkap (coba: composer install)."
    Write-Host "  -> pint.bat tidak ada." -ForegroundColor Yellow
}

# ---------- 3. Test suite ----------
Write-Host "`n[3/5] Menjalankan test suite (PHPUnit)..." -ForegroundColor Yellow
& ".\vendor\bin\phpunit.bat"
if ($LASTEXITCODE -eq 0) {
    Write-Host "  -> Semua test PASS." -ForegroundColor Green
} else {
    $FAIL += "Satu atau lebih test gagal. Perbaiki sebelum deploy."
    Write-Host "  -> Ada test yang GAGAL." -ForegroundColor Red
}

# ---------- 4. Build aset Vite ----------
Write-Host "`n[4/5] Build aset Vite (npm run build)..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -eq 0) {
    Write-Host "  -> Build sukses; 'public/build' telah di-regen." -ForegroundColor Green
} else {
    $FAIL += "npm run build gagal. Aset produksi tidak terbentuk."
    Write-Host "  -> Build GAGAL." -ForegroundColor Red
}

# ---------- 5. Verifikasi aset build ada ----------
Write-Host "`n[5/5] Verifikasi file build..." -ForegroundColor Yellow
$manifest = "public\build\manifest.json"
if (Test-Path $manifest) {
    Write-Host "  -> manifest.json ADA." -ForegroundColor Green
} else {
    $FAIL += "public/build/manifest.json tidak ada. App tidak bisa load aset."
    Write-Host "  -> manifest.json TIDAK ada (bahaya!)." -ForegroundColor Red
}

# ---------- Ringkasan ----------
Write-Host ""
Write-Host "==============================================" -ForegroundColor Cyan
Write-Host " RINGKASAN HASIL" -ForegroundColor Cyan
Write-Host "==============================================" -ForegroundColor Cyan

if ($FAIL.Count -eq 0 -and $WARN.Count -eq 0) {
    Write-Host "  Hasil: SEMUA LULUS. AMAN untuk di-deploy ke production." -ForegroundColor Green
} elseif ($FAIL.Count -eq 0) {
    Write-Host "  Hasil: LULUS (tapi ada peringatan)." -ForegroundColor Yellow
    $WARN | ForEach-Object { Write-Host "  WAR: $_" -ForegroundColor Yellow }
} else {
    Write-Host "  Hasil: GAGAL. PERBAIKI DULU sebelum deploy ke online!" -ForegroundColor Red
    $FAIL | ForEach-Object { Write-Host "  FAIL: $_" -ForegroundColor Red }
}

Write-Host ""
If ($FAIL.Count -eq 0) {
    Write-Host "Siap untuk melanjutkan ke tahap deploy (git push / upload) production." -ForegroundColor Green
} else {
    Write-Host "ANDA BELUM BOLEH deploy. Selesaikan kegagalan di atas terlebih dahulu." -ForegroundColor Red
}
Write-Host ""