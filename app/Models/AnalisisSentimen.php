<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisSentimen extends Model
{
    protected $table = 'analisis_sentimen';

    protected $fillable = [
        'berita_id',
        'skor_positif',
        'skor_negatif',
        'skor_netral',
        'hasil_sentimen'
    ];

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }
}