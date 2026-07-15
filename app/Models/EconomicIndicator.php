<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EconomicIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'inflation',
        'gdp',
        'unemployment',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}