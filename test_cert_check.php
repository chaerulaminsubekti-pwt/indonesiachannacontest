<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Winner;
use App\Models\Event;

$event = Event::first();

if ($event) {
    echo "Event: {$event->nama_event} (ID: {$event->id})\n\n";
    
    $winners = \App\Models\Winner::where('event_id', $event->id)
        ->with(['class', 'certificate'])
        ->orderBy('event_class_id')
        ->orderBy('peringkat')
        ->get()
        ->groupBy(function ($w) {
            return $w->class ? $w->class->nama_kelas : 'Tanpa Kelas';
        });
    
    echo "Winners count: {$winners->count()}\n";
    
    foreach ($winners as $kelas => $winnerList) {
        echo "\nKelas: $kelas\n";
        foreach ($winnerList as $w) {
            $cert = $w->certificate;
            $className = $w->class ? $w->class->nama_kelas : 'N/A';
            $certInfo = $cert ? 'EXISTS (file: ' . ($cert->file_path ?? 'none') . ')' : 'NONE';
            echo "  - {$w->nama_pemenang} | Class: {$className} | Cert: {$certInfo}\n";
        }
    }
} else {
    echo "No events found\n";
}