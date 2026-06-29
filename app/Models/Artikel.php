<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $table = 'artikel';

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}