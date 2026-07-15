<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentimentAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'positive',
        'negative',
        'neutral',
        'result',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}