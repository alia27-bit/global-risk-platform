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
        'alpha2',
        'capital',
        'region',
        'subregion',
        'population',
        'latitude',
        'longitude',
        'currency',
        'currency_code',
        'languages',
        'flag',
    ];

    protected function casts(): array
    {
        return ['languages' => 'array'];
    }

    public function weather()
    {
        return $this->hasOne(Weather::class)->latestOfMany();
    }

    public function economicIndicator()
    {
        return $this->hasOne(EconomicIndicator::class)->latestOfMany();
    }

    public function exchangeRate()
    {
        return $this->hasOne(ExchangeRate::class)->latestOfMany();
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
        return $this->hasOne(RiskScore::class)->latestOfMany();
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
}
