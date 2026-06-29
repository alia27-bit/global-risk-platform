<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkorRisiko extends Model
{
    protected $table = 'skor_risiko';

    protected $fillable = [
        'negara_id',
        'skor_cuaca',
        'skor_ekonomi',
        'skor_kurs',
        'skor_berita',
        'skor_pelabuhan',
        'skor_total',
        'kategori'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}