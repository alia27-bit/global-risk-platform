<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentimentDictionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'type',
    ];
}