<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerbandinganNegara extends Model
{
    protected $table = 'perbandingan_negara';

    protected $fillable = [
        'negara_pertama_id',
        'negara_kedua_id',
        'skor_negara_pertama',
        'skor_negara_kedua'
    ];

    public function negaraPertama()
    {
        return $this->belongsTo(Negara::class,'negara_pertama_id');
    }

    public function negaraKedua()
    {
        return $this->belongsTo(Negara::class,'negara_kedua_id');
    }
}