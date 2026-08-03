<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Models\Participant;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateParticipantCertificateJob
{
    use Dispatchable, Queueable;

    public Participant $participant;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    public function handle(): void
    {
        $participant = $this->participant;
        $event = $participant->event;
        $settings = SiteSetting::first();

        $kodeVerifikasi = strtoupper(Str::random(12));
        $nomorSertifikat = 'ICC/'.$event->id.'/P'.$participant->id.'/'.date('Ymd');

        $qrDir = storage_path('app/public/qrcodes');
        if (! is_dir($qrDir)) {
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
            'participant_id' => $participant->id,
            'nomor_sertifikat' => $nomorSertifikat,
            'kode_verifikasi' => $kodeVerifikasi,
            'generated_at' => now(),
        ]);

        $logoPath = public_path('images/icc-logo.png');
        $logoExists = file_exists($logoPath);

        $qrPath = str_replace('\\', '/', $qrPath);
        $logoPath = str_replace('\\', '/', $logoPath);

        $pdf = Pdf::loadView('certificates.template-participant', compact('participant', 'certificate', 'qrPath', 'settings', 'logoPath', 'logoExists'));
        $pdf->setPaper('a4', 'landscape');

        $filePath = 'certificates/'.$event->id.'/participant-'.$participant->id.'.pdf';

        Storage::disk('public')->makeDirectory('certificates/'.$event->id);
        $pdf->save(Storage::disk('public')->path($filePath));

        $certificate->update(['file_path' => $filePath]);

        if (file_exists($qrPath)) {
            unlink($qrPath);
        }
    }
}
