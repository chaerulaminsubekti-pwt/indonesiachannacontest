<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Winner;
use App\Models\EventClass;
use App\Models\WinnerPredikat;
use App\Models\Event;
use App\Models\SiteSetting;
use App\Jobs\GenerateCertificateJob;

// Create a new test winner with full test
$event = Event::first();
$eventClass = EventClass::where('event_id', $event->id)->first();
$predikat = WinnerPredikat::where('event_class_id', $eventClass->id)->where('nama_predikat', 'Juara 1')->first();

$newWinner = Winner::create([
    'event_id' => $event->id,
    'event_class_id' => $eventClass->id,
    'winner_predikat_id' => $predikat->id,
    'nama_pemenang' => 'Test Certificate Full ' . time(),
]);

echo "Created test winner: {$newWinner->nama_pemenang} (ID: {$newWinner->id})\n\n";

echo "Running GenerateCertificateJob...\n";
$job = new GenerateCertificateJob($newWinner);
$job->handle();

$newWinner->refresh();

if ($newWinner->certificate) {
    echo "SUCCESS! Certificate created:\n";
    echo "  Nomor: {$newWinner->certificate->nomor_sertifikat}\n";
    echo "  Kode: {$newWinner->certificate->kode_verifikasi}\n";
    echo "  File: {$newWinner->certificate->file_path}\n";
    
    // Check if file exists
    $filePath = storage_path('app/public/' . $newWinner->certificate->file_path);
    if (file_exists($filePath)) {
        echo "  File exists: YES\n";
        echo "  Size: " . filesize($filePath) . " bytes\n";
    } else {
        echo "  File exists: NO\n";
        echo "  Path: $filePath\n";
    }
    
    // Check if settings were passed - we can't easily test PDF content but we can verify the job runs
    echo "\nCertificate generation successful!\n";
} else {
    echo "FAILED: Certificate not created\n";
}

// Cleanup
$newWinner->certificate?->delete();
$newWinner->delete();
echo "\nTest winner cleaned up.\n";