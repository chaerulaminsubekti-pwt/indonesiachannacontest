<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Schema;

class CertificateController extends Controller
{
    public function verifikasi(string $kode)
    {
        $certificate = Schema::hasTable('certificates')
            ? Certificate::where('kode_verifikasi', $kode)
                ->with(['winner.event', 'winner.class', 'winner.predikat'])
                ->first()
            : null;

        if (! $certificate) {
            return view('certificates.verifikasi', [
                'valid' => false,
                'certificate' => null,
            ]);
        }

        return view('certificates.verifikasi', [
            'valid' => true,
            'certificate' => $certificate,
        ]);
    }

    public function download(Certificate $certificate)
    {
        $event = $certificate->winner?->event;

        if (! $event || ! in_array($event->status, ['approved', 'berjalan', 'selesai'])) {
            abort(404);
        }

        if (! $certificate->file_path || ! file_exists(storage_path('app/public/'.$certificate->file_path))) {
            abort(404);
        }

        return response()->download(
            storage_path('app/public/'.$certificate->file_path),
            'Sertifikat-'.$certificate->winner->nama_pemenang.'.pdf'
        );
    }
}
