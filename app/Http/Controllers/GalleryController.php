<?php

namespace App\Http\Controllers;

use App\Models\IccGallery;
use Illuminate\Support\Facades\Schema;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Schema::hasTable('icc_galleries')
            ? IccGallery::latest()->paginate(24)
            : collect();

        return view('gallery.index', compact('galleries'));
    }
}
