<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiskScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'weather_score',
        'economic_score',
        'news_score',
        'currency_score',
        'total_score',
        'category',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}