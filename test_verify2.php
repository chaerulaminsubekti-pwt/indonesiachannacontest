<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;

$controller = new VerificationController();

// Test with existing nomor_sertifikat
$request = new Request(['kode_verifikasi' => 'ICC/7/1/20260709']);
$response = $controller->check($request);
$data = $response->getData(true);

echo "Test with nomor_sertifikat:\n";
echo "Success: " . ($data['success'] ? 'YES' : 'NO') . "\n";
echo "Message: " . $data['message'] . "\n";
if ($data['success']) {
    echo "Data: " . json_encode($data['data'], JSON_PRETTY_PRINT) . "\n";
}

// Test with kode_verifikasi
$cert = \App\Models\Certificate::where('nomor_sertifikat', 'ICC/7/1/20260709')->first();
if ($cert) {
    $request2 = new Request(['kode_verifikasi' => $cert->kode_verifikasi]);
    $response2 = $controller->check($request2);
    $data2 = $response2->getData(true);
    
    echo "\nTest with kode_verifikasi:\n";
    echo "Success: " . ($data2['success'] ? 'YES' : 'NO') . "\n";
    echo "Message: " . $data2['message'] . "\n";
}