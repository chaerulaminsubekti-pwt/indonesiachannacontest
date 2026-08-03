<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        return view('verifikasi.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'kode_verifikasi' => 'required|string',
        ]);

        $input = $request->kode_verifikasi;

        // Handle full URL (e.g. http://domain.com/verifikasi/ABC123)
        if (str_starts_with($input, 'http://') || str_starts_with($input, 'https://')) {
            $path = parse_url($input, PHP_URL_PATH);
            $segments = array_values(array_filter(explode('/', $path)));
            $input = end($segments) ?: $input;
        }

        // Search by both kode_verifikasi and nomor_sertifikat
        $certificate = Certificate::where(function ($query) use ($input) {
            $query->where('kode_verifikasi', $input)
                ->orWhere('nomor_sertifikat', $input);
        })
            ->with(['winner', 'winner.event', 'winner.class', 'winner.predikat'])
            ->first();

        if (! $certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi atau nomor sertifikat tidak ditemukan. Sertifikat tidak valid.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat Valid Resmi Dari Indonesia Channa Contest.',
            'data' => [
                'nomor_sertifikat' => $certificate->nomor_sertifikat,
                'kode_verifikasi' => $certificate->kode_verifikasi,
                'nama_pemenang' => $certificate->winner->nama_pemenang,
                'kelas' => $certificate->winner->class?->nama_kelas,
                'event' => $certificate->winner->event->nama_event,
                'tanggal_event' => Carbon::parse($certificate->winner->event->tanggal_mulai)->format('d M Y'),
                'venue' => $certificate->winner->event->venue,
                'wilayah_kota' => $certificate->winner->event->wilayah_kota,
                'tanggal_terbit' => $certificate->generated_at ? Carbon::parse($certificate->generated_at)->format('d M Y') : '-',
                'download_url' => route('sertifikat.download', $certificate),
            ],
        ]);
    }
}
