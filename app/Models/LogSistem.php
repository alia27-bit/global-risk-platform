<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSistem extends Model
{
    protected $table = 'log_sistem';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'alamat_ip',
        'perangkat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}