<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'base_currency',
        'target_currency',
        'exchange_rate',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}