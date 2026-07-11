<?php

namespace App\Http\Controllers;

use App\Models\JudgesList;
use App\Models\OrganizationStructure;
use App\Models\Regulation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class StaticPageController extends Controller
{
    public function struktur()
    {
        $data = Schema::hasTable('organization_structures')
            ? OrganizationStructure::latest()->first()
            : null;

        return view('static.struktur-organisasi', compact('data'));
    }

    public function juri()
    {
        $data = Schema::hasTable('judges_lists')
            ? JudgesList::latest()->first()
            : null;

        return view('static.daftar-juri', compact('data'));
    }

    public function regulasi()
    {
        $data = Schema::hasTable('regulations')
            ? Regulation::latest()->get()
            : collect();

        return view('static.regulasi', compact('data'));
    }

    public function download(Regulation $regulation)
    {
        if (! $regulation->file_path || !Storage::disk('public')->exists($regulation->file_path)) {
            abort(404);
        }

        $extension = pathinfo($regulation->file_path, PATHINFO_EXTENSION);
        $filename = $regulation->nama;

        if (!str_ends_with($filename, '.' . $extension)) {
            $filename .= '.' . $extension;
        }

        return Storage::disk('public')->download($regulation->file_path, $filename);
    }
}
