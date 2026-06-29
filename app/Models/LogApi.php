<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogApi extends Model
{
    protected $table = 'log_api';

    protected $fillable = [
        'nama_api',
        'endpoint',
        'status_kode',
        'durasi_ms',
        'pesan'
    ];
}