<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'negara_id',
        'judul',
        'isi',
        'sumber',
        'url',
        'tanggal_terbit'
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }

    public function analisisSentimen()
    {
        return $this->hasOne(AnalisisSentimen::class);
    }
}