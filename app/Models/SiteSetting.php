<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo_header', 'favicon', 'nama_website', 'tagline', 'meta_description',
        'warna_primary', 'warna_secondary', 'alamat',
        'link_instagram', 'link_facebook', 'link_youtube', 'link_tiktok',
        'email_pengirim_notifikasi', 'email_kontak', 'no_wa_kontak',
        'teks_copyright', 'teks_copyright_footer',
        'sambutan_ketua', 'foto_ketua', 'nama_ketua',
        'sambutan_pembina', 'foto_pembina', 'nama_pembina', 'jabatan_pembina',
        'updated_by',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
