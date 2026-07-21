<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'latitude',
        'longitude',
        'port_type',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}