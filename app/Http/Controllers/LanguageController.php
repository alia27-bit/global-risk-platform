<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Daftar bahasa yang didukung
     */
    private $supportedLanguages = private $supportedLanguages;

public function __construct()
{
    $this->supportedLanguages = array_keys(config('languages.supported'));
};

    public function switch($locale)
    {

        if(!in_array($locale,$this->supportedLanguages))
        {
            abort(404);
        }

        session([
            'locale'=>$locale
        ]);

        return redirect()->back();

    }

}