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
        'rainfall',
        'storm_risk',
        'observed_at',
    ];

    protected $casts = ['observed_at' => 'datetime'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
