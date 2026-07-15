<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capital',
        'region',
        'subregion',
        'population',
        'latitude',
        'longitude',
        'currency',
        'currency_code',
        'flag',
    ];

    public function weather()
    {
        return $this->hasOne(Weather::class);
    }

    public function economicIndicator()
    {
        return $this->hasOne(EconomicIndicator::class);
    }

    public function exchangeRate()
    {
        return $this->hasOne(ExchangeRate::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function riskScore()
    {
        return $this->hasOne(RiskScore::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
}