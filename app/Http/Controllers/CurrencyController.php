<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\NilaiTukar;
use App\Services\Api\ExchangeRateService;

class CurrencyController extends Controller
{
    protected $service;

    public function __construct(ExchangeRateService $service)
    {
        $this->service = $service;
    }

    public function sync($id)
    {
        $country = Negara::findOrFail($id);

        $rate = $this->service->getRate($country->mata_uang);

        if (!$rate) {
            return back()->with('error', 'Data kurs tidak tersedia.');
        }

        NilaiTukar::updateOrCreate(
            [
                'negara_id' => $country->id
            ],
            [
                'mata_uang' => $country->mata_uang,
                'kurs' => $rate
            ]
        );

        return back()->with('success', 'Kurs berhasil diperbarui.');
    }
}