<?php

namespace App\Services;

use App\Models\KamusSentimen;

class SentimentService
{

    public function analyze($text)
    {

        $positive=KamusSentimen::where('jenis','positif')
            ->pluck('kata')
            ->toArray();

        $negative=KamusSentimen::where('jenis','negatif')
            ->pluck('kata')
            ->toArray();

        $words=preg_split('/\s+/',strtolower(strip_tags($text)));

        $positiveScore=0;
        $negativeScore=0;

        foreach($words as $word){

            if(in_array($word,$positive)){
                $positiveScore++;
            }

            if(in_array($word,$negative)){
                $negativeScore++;
            }

        }

        if($positiveScore>$negativeScore){

            return [
                'label'=>'Positive',
                'positive'=>$positiveScore,
                'negative'=>$negativeScore
            ];

        }

        if($negativeScore>$positiveScore){

            return [
                'label'=>'Negative',
                'positive'=>$positiveScore,
                'negative'=>$negativeScore
            ];

        }

        return [
            'label'=>'Neutral',
            'positive'=>$positiveScore,
            'negative'=>$negativeScore
        ];

    }

}