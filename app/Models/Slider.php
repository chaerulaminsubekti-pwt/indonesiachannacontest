<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'judul', 'gambar', 'link', 'urutan',
        'status_aktif', 'tgl_mulai_tayang', 'tgl_selesai_tayang',
    ];
}
