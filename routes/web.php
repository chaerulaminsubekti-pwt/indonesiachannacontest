<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\VerificationController;
use App\Livewire\PengajuanEvent;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);
Route::get('event', [EventController::class, 'index'])->name('event.index');
Route::get('event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('pengajuan', PengajuanEvent::class)->name('pengajuan');
Route::get('struktur-organisasi', [StaticPageController::class, 'struktur'])->name('struktur');
Route::get('daftar-juri', [StaticPageController::class, 'juri'])->name('juri');
Route::get('regulasi', [StaticPageController::class, 'regulasi'])->name('regulasi');
Route::get('regulasi/{regulation}/download', [StaticPageController::class, 'download'])->name('regulasi.download');
Route::get('verifikasi/{kode}', [CertificateController::class, 'verifikasi'])->name('verifikasi');
Route::get('sertifikat/{certificate}/download', [CertificateController::class, 'download'])->name('sertifikat.download');

// Verification routes
Route::get('verifikasi-sertifikat', [VerificationController::class, 'index'])->name('verifikasi.index');
Route::post('verifikasi/check', [VerificationController::class, 'check'])->name('verifikasi.check');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('sitemap.xml', function () {
    $events = \App\Models\Event::whereIn('status', ['approved', 'berjalan', 'selesai'])->get();

    return response()->view('sitemap', compact('events'))->header('Content-Type', 'text/xml');
});

Route::get('robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    return response("User-agent: *
Allow: /
Disallow: /admin
Disallow: /panel
Disallow: /login

Sitemap: $sitemapUrl
")->header('Content-Type', 'text/plain');
});
