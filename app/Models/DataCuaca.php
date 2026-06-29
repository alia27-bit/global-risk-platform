<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCuaca extends Model
{
    protected $table = 'data_cuaca';

    protected $fillable = [
        'negara_id',
        'suhu',
        'curah_hujan',
        'kecepatan_angin',
        'tingkat_badai',
        'waktu_pengambilan'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}