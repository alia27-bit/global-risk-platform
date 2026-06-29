<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negara extends Model
{
    protected $table = 'negara';

    protected $fillable = [
        'kode_negara',
        'nama_negara',
        'ibu_kota',
        'wilayah',
        'mata_uang',
        'kode_mata_uang',
        'bahasa',
        'populasi',
        'bendera'
    ];

    public function dataCuaca()
    {
        return $this->hasMany(DataCuaca::class);
    }

    public function indikatorEkonomi()
    {
        return $this->hasMany(IndikatorEkonomi::class);
    }

    public function nilaiTukar()
    {
        return $this->hasMany(NilaiTukar::class);
    }

    public function pelabuhan()
    {
        return $this->hasMany(Pelabuhan::class);
    }

    public function berita()
    {
        return $this->hasMany(Berita::class);
    }

    public function skorRisiko()
    {
        return $this->hasMany(SkorRisiko::class);
    }

    public function daftarPantauan()
    {
        return $this->hasMany(DaftarPantauan::class);
    }
}