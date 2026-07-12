<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\Berita;
use App\Models\Pelabuhan;
use App\Models\SkorRisiko;
use App\Models\NilaiTukar;

class ApiController extends Controller
{
    public function countries()
    {
        return response()->json(
            Negara::all()
        );
    }

    public function risk()
    {
        return response()->json(
            SkorRisiko::with('negara')->get()
        );
    }

    public function news()
    {
        return response()->json(
            Berita::latest()->get()
        );
    }

    public function ports()
    {
        return response()->json(
            Pelabuhan::all()
        );
    }

    public function currency()
    {
        return response()->json(
            NilaiTukar::all()
        );
    }
}