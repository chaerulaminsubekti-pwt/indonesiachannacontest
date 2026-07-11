<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IccGallery extends Model
{
    protected $fillable = ['judul_album', 'file_path', 'caption', 'tanggal'];
}
