<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarPantauan extends Model
{
    protected $table = 'daftar_pantauan';

    protected $fillable = [
        'user_id',
        'negara_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function negara()
    {
        return $this->belongsTo(Negara::class);
    }
}