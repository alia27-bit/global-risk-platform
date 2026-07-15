<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Models\Country;
use App\Models\News;
use App\Models\RiskScore;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCountries = Country::count();

        $highRisk = RiskScore::where('category', 'High')->count();

        $mediumRisk = RiskScore::where('category', 'Medium')->count();

        $lowRisk = RiskScore::where('category', 'Low')->count();

        $topRisks = RiskScore::with('country')
            ->orderByDesc('total_score')
            ->take(10)
            ->get();

        $news = News::latest()
            ->take(8)
            ->get();

        $apiLogs = ApiLog::latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalCountries',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'topRisks',
            'news',
            'apiLogs'
        ));
    }
}