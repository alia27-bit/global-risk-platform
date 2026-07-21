<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $table = 'news_cache';

    protected $fillable = [
        'country_id',
        'title',
        'content',
        'source',
        'url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function sentimentAnalysis()
    {
        return $this->hasOne(SentimentAnalysis::class);
    }
}