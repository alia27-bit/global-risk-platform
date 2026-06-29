<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorEkonomi extends Model
{
    protected $table = 'indikator_ekonomi';

    protected $fillable = [
        'negara_id',
        'gdp',
        'inflasi',
        'ekspor',
        'impor',
        'tahun'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}