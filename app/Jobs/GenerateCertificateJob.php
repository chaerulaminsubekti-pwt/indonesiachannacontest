<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Models\SiteSetting;
use App\Models\Winner;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GenerateCertificateJob
{
    use Dispatchable, Queueable;

    public Winner $winner;

    public function __construct(Winner $winner)
    {
        $this->winner = $winner;
    }

    public function handle(): void
    {
        $winner = $this->winner;
        $event = $winner->event;
        $settings = SiteSetting::first();

        $kodeVerifikasi = strtoupper(Str::random(12));
        $nomorSertifikat = 'ICC/'.$event->id.'/'.$winner->id.'/'.date('Ymd');

        // Generate QR code as PNG file
        $qrDir = storage_path('app/public/qrcodes');
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }
        $qrFile = 'qr_'.$kodeVerifikasi.'.png';
        $qrPath = $qrDir.'/'.$qrFile;

        $qrOptions = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 8,
        ]);
        (new QRCode($qrOptions))->render(route('verifikasi', $kodeVerifikasi), $qrPath);

        $certificate = Certificate::create([
            'winner_id' => $winner->id,
            'nomor_sertifikat' => $nomorSertifikat,
            'kode_verifikasi' => $kodeVerifikasi,
            'generated_at' => now(),
        ]);

        $logoPath = public_path('images/icc-logo.png');
        $logoExists = file_exists($logoPath);

        // Convert Windows backslashes to forward slashes for DomPDF
        $qrPath = str_replace('\\', '/', $qrPath);
        $logoPath = str_replace('\\', '/', $logoPath);

        $pdf = Pdf::loadView('certificates.template', compact('winner', 'certificate', 'qrPath', 'settings', 'logoPath', 'logoExists'));
        $pdf->setPaper('a4', 'landscape');

        $filePath = 'certificates/'.$event->id.'/'.$winner->id.'.pdf';
        
        Storage::disk('public')->makeDirectory('certificates/'.$event->id);
        $pdf->save(Storage::disk('public')->path($filePath));

        $certificate->update(['file_path' => $filePath]);

        // Clean up temp QR file
        if (file_exists($qrPath)) {
            unlink($qrPath);
        }
    }
}
