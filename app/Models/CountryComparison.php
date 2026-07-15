<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_a',
        'country_b',
    ];

    public function firstCountry()
    {
        return $this->belongsTo(Country::class, 'country_a');
    }

    public function secondCountry()
    {
        return $this->belongsTo(Country::class, 'country_b');
    }
}