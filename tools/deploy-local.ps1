# =====================================================
# DEPLOY - LANGKAH LOKAL (jalankan dari komputer Anda)
# Gunakan: powershell -ExecutionPolicy Bypass -File deploy-local.ps1
# =====================================================
$ErrorActionPreference = "Stop"
Set-Location "C:\xampp1\htdocs\Portal-ICC"

Write-Host "=== 1. Commit & push perubahan ke GitHub ===" -ForegroundColor Cyan
git add -A
git commit -m "deploy: update portal icc" | Out-Null
git push origin main

Write-Host "=== 2. Export database lokal (portal-icc) ===" -ForegroundColor Cyan
$mysqldump = "C:\xampp1\mysql\bin\mysqldump.exe"
$out = "database-backup-local.sql"
& $mysqldump -h 127.0.0.1 -P 3307 -u root portal-icc "--result-file=$out"
if (Test-Path $out) {
    $sz = (Get-Item $out).Length
    Write-Host ("OK DB: {0:N1} KB" -f ($sz/1KB)) -ForegroundColor Green
}

Write-Output ""
Write-Output "=== SELESAI (LANGKAH LOKAL) ==="
Write-Output "Langkah berikutnya di SERVER (SSH):"
Write-Output "1) Upload database-backup.sql ke server:"
Write-Output "   scp C:\xampp1\htdocs\Portal-ICC\database-backup.sql user@host:/home/user/"
Write-Output "2) Jalankan: bash ~/deploy.sh"