<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Storage;

class PortService
{

    public function all()
    {

        $json=Storage::disk('local')->get('ports.json');

        return json_decode($json,true);

    }

}