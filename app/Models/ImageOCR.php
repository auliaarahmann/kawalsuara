<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageOCR extends Model
{
    protected $fillable = [
        'image_path',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'nomor_tps',
        'suara_paslon1',
        'suara_paslon12',
        'suara_paslon13',
    ];
}
