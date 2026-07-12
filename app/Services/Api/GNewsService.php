<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class GNewsService
{

    protected string $url='https://gnews.io/api/v4/search';

    public function search($country)
    {

        $response=Http::get($this->url,[

            'q'=>$country,
            'lang'=>'en',
            'token'=>env('GNEWS_API_KEY')

        ]);

        if(!$response->successful()){
            return [];
        }

        return $response->json()['articles'];

    }

}