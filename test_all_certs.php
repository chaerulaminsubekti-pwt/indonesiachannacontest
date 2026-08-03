<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Certificate;

$certs = Certificate::all();
echo 'Total certificates: '.$certs->count()."\n\n";

foreach ($certs as $cert) {
    echo "ID: {$cert->id}\n";
    echo "  Nomor: {$cert->nomor_sertifikat}\n";
    echo "  Kode: {$cert->kode_verifikasi}\n";
    echo "  Winner ID: {$cert->winner_id}\n";
    echo "  File: {$cert->file_path}\n\n";
}
