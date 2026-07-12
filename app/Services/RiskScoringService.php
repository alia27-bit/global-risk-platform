<?php

namespace App\Services;

class RiskScoringService
{

    public function calculate(

        float $weather,
        float $inflation,
        float $currency,
        float $news

    ){

        $score=

            ($weather*0.30)+
            ($inflation*0.20)+
            ($currency*0.10)+
            ($news*0.40);

        return round($score,2);

    }

    public function category($score)
    {

        if($score<30){
            return 'Low';
        }

        if($score<60){
            return 'Medium';
        }

        return 'High';

    }

}