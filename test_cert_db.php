<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Certificate;

$cert = Certificate::where('nomor_sertifikat', 'ICC/7/5/20260709')->first();

if ($cert) {
    echo "Found certificate:\n";
    echo "  ID: {$cert->id}\n";
    echo "  Nomor: {$cert->nomor_sertifikat}\n";
    echo "  Kode: {$cert->kode_verifikasi}\n";
    echo "  Winner ID: {$cert->winner_id}\n";
    echo "  File: {$cert->file_path}\n";

    // Test relationships
    $cert->load(['winner', 'winner.event', 'winner.class', 'winner.predikat']);
    echo "\nRelationships:\n";
    echo '  Winner: '.($cert->winner ? $cert->winner->nama_pemenang : 'NULL')."\n";
    echo '  Event: '.($cert->winner && $cert->winner->event ? $cert->winner->event->nama_event : 'NULL')."\n";
    echo '  Class: '.($cert->winner && $cert->winner->class ? $cert->winner->class->nama_kelas : 'NULL')."\n";
    echo '  Predikat: '.($cert->winner && $cert->winner->predikat ? $cert->winner->predikat->nama_predikat : 'NULL')."\n";
} else {
    echo "Certificate not found\n";
}
