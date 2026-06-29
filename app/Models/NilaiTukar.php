<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTukar extends Model
{
    protected $table = 'nilai_tukar';

    protected $fillable = [
        'negara_id',
        'mata_uang',
        'kurs_ke_usd',
        'tanggal'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}