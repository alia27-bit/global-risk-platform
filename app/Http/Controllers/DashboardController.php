<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\SkorRisiko;
use App\Models\Berita;
use App\Models\LogApi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNegara = Negara::count();

        $risikoTinggi = SkorRisiko::where('kategori', 'High')->count();

        $risikoSedang = SkorRisiko::where('kategori', 'Medium')->count();

        $risikoRendah = SkorRisiko::where('kategori', 'Low')->count();

        $topRisiko = SkorRisiko::with('negara')
            ->orderByDesc('total_skor')
            ->take(10)
            ->get();

        $berita = Berita::latest()->take(8)->get();

        $logApi = LogApi::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalNegara',
            'risikoTinggi',
            'risikoSedang',
            'risikoRendah',
            'topRisiko',
            'berita',
            'logApi'
        ));
    }
}