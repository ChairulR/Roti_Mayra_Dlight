<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners'; // nama tabel

    protected $fillable = [
        'judul',
        'gambar',    // path file gambar
    ];
}
