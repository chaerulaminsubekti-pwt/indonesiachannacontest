<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Event;
use App\Models\IccGallery;
use App\Models\SiteSetting;
use App\Models\Slider;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (Schema::hasTable('sliders')) {
            $sliders = Slider::where('status_aktif', true)->orderBy('urutan')->get();
        } else {
            $sliders = collect();
        }

        $settings = Schema::hasTable('site_settings') ? SiteSetting::first() : null;

        $galleries = Schema::hasTable('icc_galleries') ? IccGallery::latest()->take(8)->get() : collect();

        $contacts = Schema::hasTable('contacts') ? Contact::all() : collect();

        $latestEvents = Schema::hasTable('events')
            ? Event::whereIn('status', ['approved', 'berjalan', 'selesai'])
                ->orderBy('tanggal_mulai', 'desc')
                ->take(6)
                ->get()
            : collect();

        return view('welcome', compact('sliders', 'settings', 'galleries', 'contacts', 'latestEvents'));
    }
}
