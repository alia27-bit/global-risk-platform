<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (!in_array($locale, ['id', 'en'])) {
            abort(404);
        }

        session([
            'locale' => $locale
        ]);

        return back();
    }
}