<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Models\Country;
use App\Models\News;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->peran === 'admin') {
            $latestRiskIds = RiskScore::query()->selectRaw('MAX(id)')->groupBy('country_id');
            return view('dashboard.admin', [
                'countries' => Country::count(),
                'ports' => Port::count(),
                'newsCount' => News::count(),
                'users' => User::count(),
                'riskSummary' => RiskScore::whereIn('id', $latestRiskIds)->selectRaw('category, count(*) as total')->groupBy('category')->pluck('total', 'category'),
                'topRisks' => RiskScore::whereIn('id', $latestRiskIds)->with('country')->orderByDesc('total_score')->limit(8)->get(),
                'apiLogs' => ApiLog::latest()->limit(8)->get(),
            ]);
        }

        $watchlists = Watchlist::with('country.riskScore')
            ->where('user_id', $request->user()->id)
            ->latest()->limit(8)->get();

        return view('dashboard.user', [
            'watchlists' => $watchlists,
            'watchlistCount' => Watchlist::where('user_id', $request->user()->id)->count(),
            'countries' => Country::count(),
            'latestNews' => News::with(['country', 'sentimentAnalysis'])->latest('published_at')->limit(5)->get(),
            'riskChart' => $watchlists
                ->filter(fn ($item) => $item->country?->riskScore)
                ->map(fn ($item) => [
                    'country' => $item->country->name,
                    'score' => (float) $item->country->riskScore->total_score,
                    'category' => $item->country->riskScore->category,
                ])
                ->values(),
        ]);
    }
}
