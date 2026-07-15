<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weather';

    protected $fillable = [
        'country_id',
        'temperature',
        'wind_speed',
        'weather_code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}