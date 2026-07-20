<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ApiLog;
use App\Models\EconomicIndicator;
use App\Services\Api\WorldBankService;
use Throwable;

class EconomicIndicatorController extends Controller
{
    public function index()
    {
        $latestIndicatorIds = EconomicIndicator::query()
            ->selectRaw('MAX(id)')
            ->groupBy('country_id');

        $indicators = EconomicIndicator::query()
            ->whereIn('id', $latestIndicatorIds)
            ->with('country')
            ->orderByDesc('gdp')
            ->paginate(20);

        return view('economy.index', compact('indicators'));
    }

    public function show(Country $country)
    {
        $indicator = $country->economicIndicator;
        $history = $country->hasMany(EconomicIndicator::class)->latest()->limit(30)->get()->reverse()->values();

        return view(
            'economy.show',
            compact(
                'country',
                'indicator',
                'history'
            )
        );
    }

    public function sync(Country $country, WorldBankService $service)
    {
        try {
            $gdp = $service->latestValue($country->code, 'NY.GDP.MKTP.CD');
            $inflation = $service->latestValue($country->code, 'FP.CPI.TOTL.ZG');
            $unemployment = $service->latestValue($country->code, 'SL.UEM.TOTL.ZS');
            $exports = $service->latestValue($country->code, 'NE.EXP.GNFS.CD');
            $imports = $service->latestValue($country->code, 'NE.IMP.GNFS.CD');
            $population = $service->latestValue($country->code, 'SP.POP.TOTL');

            if ($gdp === null && $inflation === null && $unemployment === null) {
                ApiLog::create(['api_name' => 'World Bank', 'status_code' => 404, 'message' => "No indicators for {$country->code}"]);
                return back()->with('error', 'World Bank tidak memiliki indikator terbaru untuk negara ini.');
            }

            EconomicIndicator::create(['country_id' => $country->id] + compact('gdp', 'inflation', 'unemployment', 'exports', 'imports'));
            if ($population !== null) $country->update(['population' => (int) $population]);
            ApiLog::create(['api_name' => 'World Bank', 'status_code' => 200, 'message' => "Indicators synchronized for {$country->code}"]);
            return back()->with('success', 'GDP, inflasi, populasi, dan pengangguran berhasil diperbarui dari World Bank.');
        } catch (Throwable $exception) {
            ApiLog::create(['api_name' => 'World Bank', 'status_code' => 500, 'message' => $exception->getMessage()]);
            return back()->with('error', 'Koneksi World Bank gagal: '.$exception->getMessage());
        }
    }
}
