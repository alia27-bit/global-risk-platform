<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    protected $table = 'pelabuhan';

    protected $fillable = [
        'negara_id',
        'nama_pelabuhan',
        'kota',
        'lintang',
        'bujur',
        'status_operasional'
    ];

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}