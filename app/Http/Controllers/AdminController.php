<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\News;
use App\Models\Port;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'countries' => Country::count(),
            'news' => News::count(),
            'ports' => Port::count(),
            'users' => User::count(),
        ]);
    }
}